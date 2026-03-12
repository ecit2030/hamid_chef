<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadCategoryIconRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'icon' => [
                'required',
                'file',
                'mimes:svg,png,jpeg,jpg,webp,gif',
                'max:2048', // 2MB for images
                function ($attribute, $value, $fail) {
                    if ($value instanceof UploadedFile) {
                        $ext = strtolower($value->getClientOriginalExtension());
                        if ($ext === 'svg') {
                            $this->validateSVGContent($value, $fail);
                        }
                        // For PNG/JPEG/etc: max 2MB is already in rule
                        if (in_array($ext, ['png', 'jpeg', 'jpg', 'webp', 'gif']) && $value->getSize() > 2048000) {
                            $fail('حجم الملف كبير جداً. الحد الأقصى للمصورات هو 2MB');
                        }
                    }
                },
            ],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'icon.required' => 'يجب اختيار ملف الصورة/الأيقونة',
            'icon.file' => 'يجب أن يكون الملف المرفوع ملف صالح',
            'icon.mimes' => 'الصيغ المسموحة: SVG, PNG, JPEG, JPG, WebP, GIF',
            'icon.max' => 'حجم الملف كبير جداً. الحد الأقصى: 2MB للصور، 100KB لملفات SVG',
        ];
    }

    private function validateSVGContent(UploadedFile $file, $fail): void
    {
        if ($file->getSize() > 102400) {
            $fail('حجم ملف SVG كبير جداً. الحد الأقصى هو 100KB');
            return;
        }

        try {
            $content = $file->getContent();
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($content);

            if ($xml === false) {
                $fail('ملف SVG غير صالح أو تالف');
                return;
            }

            $dangerousPatterns = [
                '/<script[^>]*>.*?<\/script>/is',
                '/javascript:/i',
                '/on\w+\s*=/i',
                '/<iframe[^>]*>/i',
                '/<object[^>]*>/i',
                '/<embed[^>]*>/i',
                '/<link[^>]*>/i',
                '/<meta[^>]*>/i',
            ];

            foreach ($dangerousPatterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $fail('ملف SVG يحتوي على محتوى غير آمن');
                    return;
                }
            }

            if (!preg_match('/<svg[^>]*>/i', $content)) {
                $fail('الملف لا يحتوي على عنصر SVG صالح');
            }
        } catch (\Exception $e) {
            $fail('حدث خطأ أثناء التحقق من الملف');
        }
    }
}
