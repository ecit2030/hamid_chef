<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChefProfileRequest extends FormRequest
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
            // Chef-specific fields
            'name' => ['sometimes', 'string', 'max:255'],
            'short_description' => ['sometimes', 'string', 'max:500'],
            'long_description' => ['sometimes', 'string', 'max:2000'],
            'bio' => ['sometimes', 'string', 'max:1000'],
            'email' => ['sometimes', 'string', 'email', 'max:255'],
            'phone' => ['sometimes', 'string', 'regex:/^[0-9+\-\s()]+$/', 'max:20'],
            'address' => ['sometimes', 'string', 'max:500'],
            'governorate_id' => ['sometimes', 'integer', 'exists:governorates,id'],
            'district_id' => ['sometimes', 'integer', 'exists:districts,id'],
            'area_id' => ['sometimes', 'integer', 'exists:areas,id'],
            'base_hourly_rate' => ['sometimes', 'numeric', 'min:0', 'max:9999.99'],
            'avatar' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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
            'name.string' => __('validation.string', ['attribute' => __('validation.attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.name'), 'max' => 255]),
            'short_description.string' => __('validation.string', ['attribute' => __('validation.attributes.short_description')]),
            'short_description.max' => __('validation.max.string', ['attribute' => __('validation.attributes.short_description'), 'max' => 500]),
            'long_description.string' => __('validation.string', ['attribute' => __('validation.attributes.long_description')]),
            'long_description.max' => __('validation.max.string', ['attribute' => __('validation.attributes.long_description'), 'max' => 2000]),
            'bio.string' => __('validation.string', ['attribute' => __('validation.attributes.bio')]),
            'bio.max' => __('validation.max.string', ['attribute' => __('validation.attributes.bio'), 'max' => 1000]),
            'email.string' => __('validation.string', ['attribute' => __('validation.attributes.email')]),
            'email.email' => __('validation.email', ['attribute' => __('validation.attributes.email')]),
            'email.max' => __('validation.max.string', ['attribute' => __('validation.attributes.email'), 'max' => 255]),
            'phone.string' => __('validation.string', ['attribute' => __('validation.attributes.phone')]),
            'phone.regex' => __('validation.regex', ['attribute' => __('validation.attributes.phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('validation.attributes.phone'), 'max' => 20]),
            'address.string' => __('validation.string', ['attribute' => __('validation.attributes.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('validation.attributes.address'), 'max' => 500]),
            'governorate_id.integer' => __('validation.integer', ['attribute' => __('validation.attributes.governorate_id')]),
            'governorate_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.governorate_id')]),
            'district_id.integer' => __('validation.integer', ['attribute' => __('validation.attributes.district_id')]),
            'district_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.district_id')]),
            'area_id.integer' => __('validation.integer', ['attribute' => __('validation.attributes.area_id')]),
            'area_id.exists' => __('validation.exists', ['attribute' => __('validation.attributes.area_id')]),
            'base_hourly_rate.numeric' => __('validation.numeric', ['attribute' => __('validation.attributes.base_hourly_rate')]),
            'base_hourly_rate.min' => __('validation.min.numeric', ['attribute' => __('validation.attributes.base_hourly_rate'), 'min' => 0]),
            'base_hourly_rate.max' => __('validation.max.numeric', ['attribute' => __('validation.attributes.base_hourly_rate'), 'max' => 9999.99]),
        ];
    }
}
