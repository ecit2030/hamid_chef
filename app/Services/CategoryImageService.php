<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CategoryImageService
{
    private const MAX_FILE_SIZE = 2048000; // 2MB
    private const ALLOWED_MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/webp',
        'image/gif',
    ];
    private const ALLOWED_EXTENSIONS = ['png', 'jpeg', 'jpg', 'webp', 'gif'];
    private const STORAGE_PATH = 'category-icons';

    /**
     * Upload an image file for a category
     */
    public function uploadImage(UploadedFile $file, int $categoryId): string
    {
        $this->validateImageFile($file);

        $filename = $this->generateUniqueFilename($categoryId, $file);
        $path = $file->storeAs(self::STORAGE_PATH, $filename, 'public');

        return $path;
    }

    /**
     * Delete an image file
     */
    public function deleteImage(string $imagePath): bool
    {
        try {
            return Storage::disk('public')->delete($imagePath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Validate image file
     */
    private function validateImageFile(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw ValidationException::withMessages([
                'icon' => 'حجم الملف كبير جداً. الحد الأقصى المسموح هو 2MB',
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw ValidationException::withMessages([
                'icon' => 'نوع الملف غير مدعوم. الصيغ المسموحة: PNG, JPEG, JPG, WebP, GIF',
            ]);
        }

        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            throw ValidationException::withMessages([
                'icon' => 'نوع الملف غير صالح',
            ]);
        }
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(int $categoryId, UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = time();
        $random = substr(md5(uniqid()), 0, 8);

        return "category_{$categoryId}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Check if file is an image (not SVG)
     */
    public static function isImageFile(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, self::ALLOWED_EXTENSIONS);
    }
}
