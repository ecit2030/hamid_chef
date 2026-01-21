<?php

namespace App\Services;

use App\Repositories\ChefRepository;
use App\Services\ChefGalleryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Exceptions\ValidationException;
use Exception;

class ChefService
{
    protected ChefRepository $chefs;
    protected ChefGalleryService $galleryService;

    public function __construct(ChefRepository $chefs, ChefGalleryService $galleryService)
    {
        $this->chefs = $chefs;
        $this->galleryService = $galleryService;
    }

    /**
     * Query عام (لو احتجته في حالات خاصة)
     */
    public function query(?array $with = null): Builder
    {
        return $this->chefs->query($with);
    }

    /**
     * تستخدم في لوحة التحكم أو أي مكان عام
     * - $with = null  => يستعمل defaultWith في ChefRepository
     * - $with = []    => بدون علاقات
     * - $with = ['..']=> علاقات مخصصة
     */
    public function all(?array $with = null)
    {
        return $this->chefs->all($with);
    }

    public function paginate(int $perPage = 15, ?array $with = null)
    {
        return $this->chefs->paginate($perPage, $with);
    }

    public function find(int|string $id, ?array $with = null)
    {
        return $this->chefs->findOrFail($id, $with);
    }

    /**
     * إنشاء طاهي جديد
     * - في الـ API: يربط الطاهي بالمستخدم الحالي تلقائيًا إذا لم يُرسل user_id
     * - في لوحة التحكم: يمكن تمرير user_id من الفورم
     */
    public function create(array $attributes)
    {
        if (empty($attributes['user_id']) && Auth::check()) {
            $attributes['user_id'] = Auth::id();
        }

        // Proactive validation to prevent duplicate chef profiles
        if (!empty($attributes['user_id'])) {
            $this->checkUserHasChef($attributes['user_id']);
        }

        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract categories and gallery images before creating chef
        $categories = $attributes['categories'] ?? [];
        $galleryImages = $attributes['gallery_images'] ?? [];
        unset($attributes['categories'], $attributes['gallery_images']);

        DB::beginTransaction();

        try {
            $chef = $this->chefs->create($attributes);

            // Sync categories if provided
            if (!empty($categories)) {
                $this->syncChefCategories($chef, $categories);
            }

            // Create gallery images if provided
            if (!empty($galleryImages)) {
                $this->galleryService->createMultiple($chef->id, $galleryImages);
            }

            DB::commit();
            return $chef;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if user already has a chef profile
     *
     * @param int $userId
     * @throws \App\Exceptions\ValidationException
     */
    public function checkUserHasChef(int $userId): void
    {
        if ($this->existsForUser($userId)) {
            throw ValidationException::withMessages([
                'user_id' => ['المستخدم لديه بالفعل ملف طاهي']
            ]);
        }
    }

    /**
     * Handle database exceptions during chef creation
     *
     * @param QueryException $e
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleChefCreationDatabaseException(QueryException $e, Request $request)
    {


        // Check for duplicate entry constraint violation
        if ($e->getCode() == 23000 && $this->isDuplicateChefError($e)) {
            // Transform to ValidationException for consistent response format
            $validationException = ValidationException::withMessages([
                'user_id' => ['المستخدم لديه بالفعل ملف طاهي']
            ]);

            return $validationException->render($request);
        }

        // For other database errors, re-throw
        throw $e;
    }

    /**
     * Check if the QueryException is a duplicate chef profile error
     *
     * @param QueryException $e
     * @return bool
     */
    public function isDuplicateChefError(QueryException $e): bool
    {
        $message = $e->getMessage();

        return str_contains($message, 'Duplicate entry') &&
               str_contains($message, 'chefs_user_id_unique');
    }

    /**
     * تحديث بالـ id (مناسب للـ Admin)
     */
    public function update(int|string $id, array $attributes)
    {
        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract categories and gallery data before updating chef
        $categories = $attributes['categories'] ?? null;
        $galleryImages = $attributes['gallery_images'] ?? null;
        $deleteGalleryIds = $attributes['delete_gallery_ids'] ?? [];
        unset($attributes['categories'], $attributes['gallery_images'], $attributes['delete_gallery_ids']);

        DB::beginTransaction();

        try {
            $chef = $this->chefs->update($id, $attributes);

            // Sync categories if provided
            if ($categories !== null) {
                $this->syncChefCategories($chef, $categories);
            }

            // Update gallery if provided
            if ($galleryImages !== null || !empty($deleteGalleryIds)) {
                $this->galleryService->updateGallery($chef->id, $galleryImages ?? [], $deleteGalleryIds);
            }

            DB::commit();
            return $chef;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث Model جاهز (مناسب للـ API بعد find + Policy)
     */
    public function updateModel(Model $chef, array $attributes)
    {
        $attributes = $this->normalizeFileAttributes($attributes);

        // Extract categories and gallery data before updating chef
        $categories = $attributes['categories'] ?? null;
        $galleryImages = $attributes['gallery_images'] ?? null;
        $deleteGalleryIds = $attributes['delete_gallery_ids'] ?? [];
        unset($attributes['categories'], $attributes['gallery_images'], $attributes['delete_gallery_ids']);

        DB::beginTransaction();

        try {
            $updatedChef = $this->chefs->updateModel($chef, $attributes);

            // Sync categories if provided
            if ($categories !== null) {
                $this->syncChefCategories($updatedChef, $categories);
            }

            // Update gallery if provided
            if ($galleryImages !== null || !empty($deleteGalleryIds)) {
                $this->galleryService->updateGallery($updatedChef->id, $galleryImages ?? [], $deleteGalleryIds);
            }

            DB::commit();
            return $updatedChef;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int|string $id): bool
    {
        return $this->chefs->delete($id);
    }

    public function activate(int|string $id)
    {
        return $this->chefs->activate($id);
    }

    public function deactivate(int|string $id)
    {
        return $this->chefs->deactivate($id);
    }

    /**
     * 🔹 API: Query لطهاة مستخدم معيّن (index مع فلاتر)
     * - يرجع Builder عشان تقدر تطبق CanFilter و باقي الفلاتر
     * - يستفيد من defaultWith في ChefRepository لما $with = null
     */
    public function getQueryForUser(int $userId, ?array $with = null): Builder
    {
        return $this->chefs->query($with)->where('user_id', $userId);
    }

    /**
     * 🔹 API: جلب طاهي مملوك لمستخدم معيّن (show / update / delete / activate / deactivate)
     */
    public function findForUser(int|string $id, int $userId, ?array $with = null)
    {
        return $this->chefs->query($with)->where('id', $id)->where('user_id', $userId)->firstOrFail();
    }

    /**
     * 🔹 API: جلب ملف الطاهي للمستخدم الحالي (للطاهي نفسه)
     */
    public function findForCurrentUser(int $userId, ?array $with = null)
    {
        return $this->chefs->query($with)->where('user_id', $userId)->firstOrFail();
    }

    /**
     * 🔹 API: تحقق من وجود ملف طاهي للمستخدم الحالي
     */
    public function existsForUser(int $userId): bool
    {
        return $this->chefs->query()->where('user_id', $userId)->exists();
    }

    /**
     * 🔹 API: جلب الطهاة النشطين فقط (للعرض العام)
     */
    public function getActiveChefs(?array $with = null): Builder
    {
        return $this->chefs->query($with)->where('is_active', true);
    }

    /**
     * 🔹 API: البحث في الطهاة حسب المنطقة
     */
    public function getChefsByArea(int $areaId, ?array $with = null): Builder
    {
        return $this->chefs->query($with)->where('area_id', $areaId)->where('is_active', true);
    }

    /**
     * 🔹 API: البحث في الطهاة حسب المديرية
     */
    public function getChefsByDistrict(int $districtId, ?array $with = null): Builder
    {
        return $this->chefs->query($with)->where('district_id', $districtId)->where('is_active', true);
    }

    /**
     * 🔹 API: البحث في الطهاة حسب المحافظة
     */
    public function getChefsByGovernorate(int $governorateId, ?array $with = null): Builder
    {
        return $this->chefs->query($with)->where('governorate_id', $governorateId)->where('is_active', true);
    }

    /**
     * جلب الطهاة بحسب القسم (category/cuisine)
     *
     * @param int $categoryId
     * @param array|null $with
     * @return Builder
     */
    public function getChefsByCategory(int $categoryId, ?array $with = null): Builder
    {
        return $this->chefs->queryByCategory($categoryId, $with)->where('is_active', true);
    }

    /**
     * Normalize file-related attributes before passing to repository.
     * - remove keys that are present but null/empty to allow DB defaults
     * - convert frontend `/storage/...` public URLs back to relative storage path
     * - keep UploadedFile instances so BaseRepository handles storing them
     */
    protected function normalizeFileAttributes(array $attributes): array
    {
        // Only handle file keys that exist on the chefs table
        $fileKeys = ['logo', 'banner'];

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

            // If frontend passed full public path like '/storage/chefs/..', convert to 'chefs/...'
            if (is_string($value) && str_starts_with($value, '/storage/')) {
                $attributes[$key] = ltrim(substr($value, strlen('/storage/')), '/');
            }
        }

        return $attributes;
    }

    /**
     * مزامنة أقسام الطاهي
     *
     * @param Model $chef
     * @param array $categoryIds
     * @return void
     */
    protected function syncChefCategories(Model $chef, array $categoryIds): void
    {
        // Prepare sync data with additional pivot data
        $syncData = [];
        foreach ($categoryIds as $categoryId) {
            $syncData[$categoryId] = [
                'is_active' => true,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $chef->categories()->sync($syncData);
    }

    /**
     * إضافة قسم للطاهي
     *
     * @param int|string $chefId
     * @param int $categoryId
     * @return void
     */
    public function addCategory(int|string $chefId, int $categoryId): void
    {
        $chef = $this->find($chefId);

        if (!$chef->categories()->where('cuisine_id', $categoryId)->exists()) {
            $chef->categories()->attach($categoryId, [
                'is_active' => true,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * إزالة قسم من الطاهي
     *
     * @param int|string $chefId
     * @param int $categoryId
     * @return void
     */
    public function removeCategory(int|string $chefId, int $categoryId): void
    {
        $chef = $this->find($chefId);
        $chef->categories()->detach($categoryId);
    }

    /**
     * تفعيل/إلغاء تفعيل قسم للطاهي
     *
     * @param int|string $chefId
     * @param int $categoryId
     * @param bool $isActive
     * @return void
     */
    public function toggleCategoryStatus(int|string $chefId, int $categoryId, bool $isActive): void
    {
        $chef = $this->find($chefId);

        $chef->categories()->updateExistingPivot($categoryId, [
            'is_active' => $isActive,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Update chef profile (for API)
     * Updates both user and chef tables in a transaction
     *
     * @param \App\Models\Chef $chef
     * @param array $data
     * @return \App\Models\Chef
     */
    public function updateChefProfile($chef, array $data)
    {
        DB::beginTransaction();

        try {
            // Normalize file attributes
            $data = $this->normalizeFileAttributes($data);

            // Handle logo upload if present
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                $data['logo'] = $this->chefs->uploadFile($data['logo'], 'chefs/logos', true);

                // Delete old logo if exists
                if ($chef->logo) {
                    $this->chefs->deleteFile($chef->logo, true);
                }
            }

            // Handle banner upload if present
            if (isset($data['banner']) && $data['banner'] instanceof UploadedFile) {
                $data['banner'] = $this->chefs->uploadFile($data['banner'], 'chefs/banners', true);

                // Delete old banner if exists
                if ($chef->banner) {
                    $this->chefs->deleteFile($chef->banner, true);
                }
            }

            // Update chef record
            $chef = $this->chefs->update($chef->id, $data);

            DB::commit();
            return $chef;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
