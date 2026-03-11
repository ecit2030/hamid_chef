<?php

namespace App\Http\Requests;

use App\Rules\UniqueChefForUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\ValidationException as AppValidationException;

class StoreChefRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isAdmin = $this->routeIs('admin.*');

        return [
            'user_id' => array_filter([
                $isAdmin ? 'required' : 'nullable',
                'exists:users,id',
                new UniqueChefForUser($this->input('user_id')),
            ]),
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'long_description' => 'nullable|string|max:2000',
            'email' => 'nullable|email|unique:chefs,email',
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => 'nullable|string|max:1000',
            'base_hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'governorate_id' => 'required|exists:governorates,id',
            'district_id' => 'required|exists:districts,id',
            'area_id' => 'required|exists:areas,id',
            'logo' => 'nullable|image|max:4096',
            'banner' => 'nullable|image|max:4096',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|max:5120', // 5MB per image
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ];
    }

    /**
     * For Inertia: redirect back with errors so the form can display them.
     * For API: return JSON via AppValidationException.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->header('X-Inertia')) {
            throw (new \Illuminate\Validation\ValidationException($validator))
                ->redirectTo($this->getRedirectUrl());
        }
        throw AppValidationException::withMessages($validator->errors()->toArray());
    }
}
