<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('discount_code');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('discount_codes', 'code')->ignore($id)
            ],
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'كود الخصم مطلوب',
            'code.unique' => 'كود الخصم موجود مسبقاً',
            'type.required' => 'نوع الخصم مطلوب',
            'type.in' => 'نوع الخصم يجب أن يكون نسبة مئوية أو مبلغ ثابت',
            'value.required' => 'قيمة الخصم مطلوبة',
            'value.min' => 'قيمة الخصم يجب أن تكون أكبر من أو تساوي صفر',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'per_user_limit.required' => 'عدد الاستخدامات لكل مستخدم مطلوب',
            'per_user_limit.min' => 'عدد الاستخدامات لكل مستخدم يجب أن يكون على الأقل 1',
        ];
    }
}
