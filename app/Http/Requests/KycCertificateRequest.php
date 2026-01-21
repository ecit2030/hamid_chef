<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycCertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'certificate_type' => [
                'required',
                'string',
                'in:identity_document,health_certificate,professional_certificate',
            ],
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB in kilobytes
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'certificate_type.required' => __('validation.required', ['attribute' => __('validation.attributes.certificate_type')]),
            'certificate_type.string' => __('validation.string', ['attribute' => __('validation.attributes.certificate_type')]),
            'certificate_type.in' => __('validation.in', ['attribute' => __('validation.attributes.certificate_type')]),
            'file.required' => __('validation.required', ['attribute' => __('validation.attributes.file')]),
            'file.file' => __('validation.file', ['attribute' => __('validation.attributes.file')]),
            'file.mimes' => __('validation.mimes', ['attribute' => __('validation.attributes.file'), 'values' => 'jpg, jpeg, png, pdf']),
            'file.max' => __('validation.max.file', ['attribute' => __('validation.attributes.file'), 'max' => '5MB']),
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'certificate_type' => __('validation.attributes.certificate_type'),
            'file' => __('validation.attributes.file'),
        ];
    }
}
