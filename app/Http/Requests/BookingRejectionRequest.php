<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRejectionRequest extends FormRequest
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
            'rejection_reason' => [
                'required',
                'string',
                'min:1',
                'max:500',
                function ($attribute, $value, $fail) {
                    // Check if the string contains only whitespace
                    if (trim($value) === '') {
                        $fail(__('validation.rejection_reason_whitespace'));
                    }
                },
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rejection_reason.required' => __('validation.rejection_reason_required'),
            'rejection_reason.string' => __('validation.rejection_reason_string'),
            'rejection_reason.min' => __('validation.rejection_reason_min'),
            'rejection_reason.max' => __('validation.rejection_reason_max'),
        ];
    }
}
