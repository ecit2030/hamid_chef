<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTermsAndConditionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'version' => 'required|string|max:20',
            'is_active' => 'nullable|boolean',
            'effective_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title_ar.required' => 'العنوان بالعربية مطلوب',
            'title_en.required' => 'العنوان بالإنجليزية مطلوب',
            'content_ar.required' => 'المحتوى بالعربية مطلوب',
            'content_en.required' => 'المحتوى بالإنجليزية مطلوب',
            'version.required' => 'رقم الإصدار مطلوب',
        ];
    }
}
