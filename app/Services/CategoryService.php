<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\DTOs\CategoryDTO;
use App\Services\CategoryImageService;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    protected CategoryRepository $categories;
    protected SVGIconService $svgIconService;
    protected CategoryImageService $categoryImageService;

    public function __construct(CategoryRepository $categories, SVGIconService $svgIconService, CategoryImageService $categoryImageService)
    {
        $this->categories = $categories;
        $this->svgIconService = $svgIconService;
        $this->categoryImageService = $categoryImageService;
    }

    public function all(array $with = [])
    {
        return $this->categories->all($with);
    }

    public function paginate(int $perPage = 15, array $with = [])
    {
        return $this->categories->paginate($perPage, $with);
    }

    /**
     * Expose an Eloquent query builder for controllers that need to apply
     * additional constraints or filters before pagination.
     */
    public function query(?array $with = null): Builder
    {
        return $this->categories->query($with);
    }

    public function find($id, array $with = [])
    {
        return $this->categories->findOrFail($id, $with);
    }

    public function create(array $attributes)
    {
        // Ensure slug is generated from name when creating
        if (empty($attributes['slug']) && ! empty($attributes['name'])) {
            $attributes['slug'] = $this->makeUniqueSlug($attributes['name']);
        }

        return $this->categories->create($attributes);
    }

    public function update($id, array $attributes)
    {
        // Generate slug from name on update if name provided
        if (! empty($attributes['name'])) {
            $attributes['slug'] = $this->makeUniqueSlug($attributes['name'], $id);
        }

        return $this->categories->update($id, $attributes);
    }

    /**
     * Generate a unique slug for the given name.
     * If $ignoreId is provided, ignore that record when checking uniqueness (useful on update).
     */
    protected function makeUniqueSlug(string $name, $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Category::where('slug', $slug)->when($ignoreId, function ($q) use ($ignoreId) {
            $q->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $base.'-'.++$i;
        }

        return $slug;
    }

    public function delete($id)
    {
        return $this->categories->delete($id);
    }

    public function activate($id)
    {
        return $this->categories->activate($id);
    }

    public function deactivate($id)
    {
        return $this->categories->deactivate($id);
    }

    /**
     * Upload an icon/image for a category (supports SVG, PNG, JPEG, WebP, GIF)
     */
    public function uploadIcon(int $categoryId, UploadedFile $iconFile): CategoryDTO
    {
        $category = $this->categories->findOrFail($categoryId);

        // حذف الأيقونة/الصورة القديمة إن وجدت
        if ($category->icon_path) {
            $this->deleteIconFile($category->icon_path);
        }

        // رفع الملف الجديد (SVG أو صورة)
        $iconPath = CategoryImageService::isImageFile($iconFile)
            ? $this->categoryImageService->uploadImage($iconFile, $categoryId)
            : $this->svgIconService->uploadIcon($iconFile, $categoryId);

        // تحديث القسم
        $updatedCategory = $this->categories->update($categoryId, [
            'icon_path' => $iconPath,
        ]);

        return CategoryDTO::fromModel($updatedCategory);
    }

    /**
     * Delete icon/image file (handles both SVG and raster images)
     */
    private function deleteIconFile(string $path): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $isImage = in_array($extension, ['png', 'jpeg', 'jpg', 'webp', 'gif']);

        if ($isImage) {
            $this->categoryImageService->deleteImage($path);
        } else {
            $this->svgIconService->deleteIcon($path);
        }
    }

    /**
     * Remove icon from a category
     */
    public function removeIcon(int $categoryId): CategoryDTO
    {
        $category = $this->categories->findOrFail($categoryId);

        if ($category->icon_path) {
            $this->deleteIconFile($category->icon_path);

            $updatedCategory = $this->categories->update($categoryId, [
                'icon_path' => null,
            ]);

            return CategoryDTO::fromModel($updatedCategory);
        }

        return CategoryDTO::fromModel($category);
    }

    /**
     * Delete category and its associated icon
     */
    public function deleteWithIcon($id)
    {
        $category = $this->categories->findOrFail($id);

        if ($category->icon_path) {
            $this->deleteIconFile($category->icon_path);
        }

        return $this->categories->delete($id);
    }
}
