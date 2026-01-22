<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'phone_number' => [
                'sometimes',
                'string',
                'regex:/^[0-9+\-\s()]+$/',
                'max:20',
                Rule::unique('users', 'phone_number')->ignore($userId),
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'address' => ['sometimes', 'string', 'max:500'],
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
            'first_name.string' => __('validation.string', ['attribute' => __('validation.attributes.first_name')]),
            'first_name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.first_name'), 'max' => 255]),
            'last_name.string' => __('validation.string', ['attribute' => __('validation.attributes.last_name')]),
            'last_name.max' => __('validation.max.string', ['attribute' => __('validation.attributes.last_name'), 'max' => 255]),
            'phone_number.string' => __('validation.string', ['attribute' => __('validation.attributes.phone_number')]),
            'phone_number.regex' => __('validation.regex', ['attribute' => __('validation.attributes.phone_number')]),
            'phone_number.max' => __('validation.max.string', ['attribute' => __('validation.attributes.phone_number'), 'max' => 20]),
            'phone_number.unique' => __('validation.unique', ['attribute' => __('validation.attributes.phone_number')]),
            'email.string' => __('validation.string', ['attribute' => __('validation.attributes.email')]),
            'email.email' => __('validation.email', ['attribute' => __('validation.attributes.email')]),
            'email.max' => __('validation.max.string', ['attribute' => __('validation.attributes.email'), 'max' => 255]),
            'email.unique' => __('validation.unique', ['attribute' => __('validation.attributes.email')]),
            'address.string' => __('validation.string', ['attribute' => __('validation.attributes.address')]),
            'address.max' => __('validation.max.string', ['attribute' => __('validation.attributes.address'), 'max' => 500]),
        ];
    }
}
