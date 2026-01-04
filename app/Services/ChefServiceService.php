<?php

namespace App\Services;

use App\Repositories\ChefServiceRepository;
use App\Services\ChefServiceImageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ValidationException;
use Exception;

class ChefServiceService
{
    protected ChefServiceRepository $services;
    protected ChefServiceImageService $imageService;

    public function __construct(ChefServiceRepository $services, ChefServiceImageService $imageService)
    {
        $this->services = $services;
        $this->imageService = $imageService;
    }

    /**
     * Query عام (لو احتجته في حالات خاصة)
     */
    public function query(?array $with = null): Builder
    {
        return \App\Models\ChefService::query();
    }

    /**
     * تستخدم في لوحة التحكم أو أي مكان عام
     * - $with = null  => يستعمل defaultWith في ChefServiceRepository
     * - $with = []    => بدون علاقات
     * - $with = ['..']=> علاقات مخصصة
     */
    public function all(?array $with = null)
    {
        return $this->services->all($with);
    }

    public function paginate(int $perPage = 15, ?array $with = null)
    {
        return $this->services->paginate($perPage, $with);
    }

    public function find(int|string $id, ?array $with = null)
    {
        return $this->services->findOrFail($id, $with);
    }

    /**
     * إنشاء خدمة جديدة
     */
    public function create(array $attributes)
    {
        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract tags, images, and equipment before creating service
        $tags = $attributes['tags'] ?? [];
        $serviceImages = $attributes['service_images'] ?? [];
        $equipment = $attributes['equipment'] ?? [];
        unset($attributes['tags'], $attributes['service_images'], $attributes['equipment']);

        DB::beginTransaction();
        
        try {
            $service = $this->services->create($attributes);

            // Sync tags if provided
            if (!empty($tags)) {
                $this->syncServiceTags($service, $tags);
            }

            // Create images if provided
            if (!empty($serviceImages)) {
                $this->imageService->createMultiple($service->id, $serviceImages);
            }

            // Create equipment if provided
            if (!empty($equipment)) {
                $this->createServiceEquipment($service, $equipment);
            }

            DB::commit();
            // Reload with default relations so DTOs include tags/images immediately
            return $this->services->findOrFail($service->id);
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث بالـ id (مناسب للـ Admin)
     */
    public function update(int|string $id, array $attributes)
    {
        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract tags, images, and equipment data before updating service
        $tags = $attributes['tags'] ?? null;
        $serviceImages = $attributes['service_images'] ?? null;
        $deleteImageIds = $attributes['delete_service_image_ids'] ?? [];
        $equipment = $attributes['equipment'] ?? null;
        unset($attributes['tags'], $attributes['service_images'], $attributes['delete_service_image_ids'], $attributes['equipment']);

        DB::beginTransaction();
        
        try {
            $service = $this->services->update($id, $attributes);

            // Sync tags if provided
            if ($tags !== null) {
                $this->syncServiceTags($service, $tags);
            }

            // Update images if provided
            if ($serviceImages !== null || !empty($deleteImageIds)) {
                $this->imageService->updateGallery($service->id, $serviceImages ?? [], $deleteImageIds);
            }

            // Update equipment if provided
            if ($equipment !== null) {
                $this->updateServiceEquipment($service, $equipment);
            }

            DB::commit();
            // Reload with default relations so DTOs include tags/images immediately
            return $this->services->findOrFail($service->id);
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث Model جاهز (مناسب للـ API بعد find + Policy)
     */
    public function updateModel(Model $service, array $attributes)
    {
        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract tags, images, and equipment data before updating service
        $tags = $attributes['tags'] ?? null;
        $serviceImages = $attributes['service_images'] ?? null;
        $deleteImageIds = $attributes['delete_service_image_ids'] ?? [];
        $equipment = $attributes['equipment'] ?? null;
        unset($attributes['tags'], $attributes['service_images'], $attributes['delete_service_image_ids'], $attributes['equipment']);

        DB::beginTransaction();
        
        try {
            $updatedService = $this->services->updateModel($service, $attributes);

            // Sync tags if provided
            if ($tags !== null) {
                $this->syncServiceTags($updatedService, $tags);
            }

            // Update images if provided
            if ($serviceImages !== null || !empty($deleteImageIds)) {
                $this->imageService->updateGallery($updatedService->id, $serviceImages ?? [], $deleteImageIds);
            }

            // Update equipment if provided
            if ($equipment !== null) {
                $this->updateServiceEquipment($updatedService, $equipment);
            }

            DB::commit();
            // Reload with default relations so DTOs include tags/images immediately
            return $this->services->findOrFail($updatedService->id);
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int|string $id): bool
    {
        return $this->services->delete($id);
    }

    public function activate(int|string $id)
    {
        return $this->services->activate($id);
    }

    public function deactivate(int|string $id)
    {
        return $this->services->deactivate($id);
    }

    /**
     * 🔹 API: Query لخدمات طاهي معيّن (index مع فلاتر)
     * - يرجع Builder عشان تقدر تطبق CanFilter و باقي الفلاتر
     * - يستفيد من defaultWith في ChefServiceRepository لما $with = null
     */
    public function getQueryForChef(int $chefId, ?array $with = null): Builder
    {
        $query = \App\Models\ChefService::where('chef_id', $chefId);
        
        if ($with !== null) {
            $query->with($with);
        }
        
        return $query;
    }

    /**
     * 🔹 API: جلب خدمة مملوكة لطاهي معيّن (show / update / delete / activate / deactivate)
     */
    public function findForChef(int|string $id, int $chefId, ?array $with = null)
    {
        $query = \App\Models\ChefService::where('id', $id)->where('chef_id', $chefId);
        
        if ($with !== null) {
            $query->with($with);
        }
        
        return $query->firstOrFail();
    }

    /**
     * 🔹 API: جلب الخدمات النشطة فقط (للعرض العام)
     */
    public function getActiveServices(?array $with = null): Builder
    {
        $query = \App\Models\ChefService::where('is_active', true);
        
        if ($with !== null) {
            $query->with($with);
        }
        
        return $query;
    }

    /**
     * 🔹 API: البحث في الخدمات حسب العلامة
     */
    public function getServicesByTag(int $tagId, ?array $with = null): Builder
    {
        $query = \App\Models\ChefService::whereHas('tags', function ($query) use ($tagId) {
                $query->where('tag_id', $tagId)->where('chef_service_tags.is_active', true);
            })
            ->where('is_active', true);
        
        if ($with !== null) {
            $query->with($with);
        }
        
        return $query;
    }

    /**
     * 🔹 API: البحث في الخدمات حسب نوع الخدمة
     */
    public function getServicesByType(string $serviceType, ?array $with = null): Builder
    {
        $query = \App\Models\ChefService::where('service_type', $serviceType)
            ->where('is_active', true);
        
        if ($with !== null) {
            $query->with($with);
        }
        
        return $query;
    }

    /**
     * Normalize file-related attributes before passing to repository.
     * - remove keys that are present but null/empty to allow DB defaults
     * - convert frontend `/storage/...` public URLs back to relative storage path
     * - keep UploadedFile instances so BaseRepository handles storing them
     */
    protected function normalizeFileAttributes(array $attributes): array
    {
        // Only handle file keys that exist on the chef_services table
        $fileKeys = ['feature_image']; // Add file keys if any exist in the future

        foreach ($fileKeys as $key) {
            if (!array_key_exists($key, $attributes)) {
                continue;
            }

            $value = $attributes[$key];

            // If it's an UploadedFile, leave it for BaseRepository to handle
            if ($value instanceof UploadedFile) {
                continue;
            }

            // If explicitly null or empty string, remove the key so DB default applies
            if ($value === null || $value === '') {
                unset($attributes[$key]);
                continue;
            }

            // If frontend passed full public path like '/storage/services/..', convert to 'services/...'
            if (is_string($value) && str_starts_with($value, '/storage/')) {
                $attributes[$key] = ltrim(substr($value, strlen('/storage/')), '/');
            }
        }

        return $attributes;
    }

    /**
     * مزامنة علامات الخدمة
     * 
     * @param Model $service
     * @param array $tagIds
     * @return void
     */
    protected function syncServiceTags(Model $service, array $tagIds): void
    {
        // Prepare sync data with additional pivot data
        $syncData = [];
        foreach ($tagIds as $tagId) {
            $syncData[$tagId] = [
                'is_active' => true,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $service->tags()->sync($syncData);
    }

    /**
     * إضافة علامة للخدمة
     * 
     * @param int|string $serviceId
     * @param int $tagId
     * @return void
     */
    public function addTag(int|string $serviceId, int $tagId): void
    {
        $service = $this->find($serviceId);
        
        if (!$service->tags()->where('tag_id', $tagId)->exists()) {
            $service->tags()->attach($tagId, [
                'is_active' => true,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * إزالة علامة من الخدمة
     * 
     * @param int|string $serviceId
     * @param int $tagId
     * @return void
     */
    public function removeTag(int|string $serviceId, int $tagId): void
    {
        $service = $this->find($serviceId);
        $service->tags()->detach($tagId);
    }

    /**
     * تفعيل/إلغاء تفعيل علامة للخدمة
     * 
     * @param int|string $serviceId
     * @param int $tagId
     * @param bool $isActive
     * @return void
     */
    public function toggleTagStatus(int|string $serviceId, int $tagId, bool $isActive): void
    {
        $service = $this->find($serviceId);
        
        $service->tags()->updateExistingPivot($tagId, [
            'is_active' => $isActive,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);
    }

    /**
     * إنشاء أدوات الخدمة
     * 
     * @param Model $service
     * @param array $equipmentData
     * @return void
     */
    protected function createServiceEquipment(Model $service, array $equipmentData): void
    {
        foreach ($equipmentData as $equipment) {
            if (empty($equipment['name'])) {
                continue; // Skip empty equipment
            }

            $service->equipment()->create([
                'name' => $equipment['name'],
                'is_included' => $equipment['is_included'] ?? true,
            ]);
        }
    }

    /**
     * تحديث أدوات الخدمة
     * 
     * @param Model $service
     * @param array $equipmentData
     * @return void
     */
    protected function updateServiceEquipment(Model $service, array $equipmentData): void
    {
        // Get existing equipment IDs
        $existingIds = $service->equipment()->pluck('id')->toArray();
        $updatedIds = [];

        foreach ($equipmentData as $equipment) {
            if (empty($equipment['name'])) {
                continue; // Skip empty equipment
            }

            if (isset($equipment['id']) && in_array($equipment['id'], $existingIds)) {
                // Update existing equipment
                $service->equipment()->where('id', $equipment['id'])->update([
                    'name' => $equipment['name'],
                    'is_included' => $equipment['is_included'] ?? true,
                ]);
                $updatedIds[] = $equipment['id'];
            } else {
                // Create new equipment
                $newEquipment = $service->equipment()->create([
                    'name' => $equipment['name'],
                    'is_included' => $equipment['is_included'] ?? true,
                ]);
                $updatedIds[] = $newEquipment->id;
            }
        }

        // Delete equipment that are no longer in the list
        $toDelete = array_diff($existingIds, $updatedIds);
        if (!empty($toDelete)) {
            $service->equipment()->whereIn('id', $toDelete)->delete();
        }
    }
}