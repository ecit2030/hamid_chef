<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTermsAndConditionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'content_ar' => 'sometimes|required|string',
            'content_en' => 'sometimes|required|string',
            'version' => 'sometimes|required|string|max:20',
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
