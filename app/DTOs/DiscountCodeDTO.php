<?php

namespace App\DTOs;

use App\Models\DiscountCode;

class DiscountCodeDTO extends BaseDTO
{
    public $id;
    public $code;
    public $description;
    public $type;
    public $value;
    public $min_order_amount;
    public $max_discount_amount;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $usage_count;
    public $per_user_limit;
    public $is_active;
    public $created_by;
    public $updated_by;
    public $created_at;
    public $updated_at;

    // Computed attributes
    public $usage_percentage;
    public $remaining_usages;
    public $status;
    public $is_expired;
    public $is_upcoming;

    public function __construct(
        $id,
        $code,
        $description,
        $type,
        $value,
        $min_order_amount,
        $max_discount_amount,
        $start_date,
        $end_date,
        $usage_limit,
        $usage_count,
        $per_user_limit,
        $is_active,
        $created_by = null,
        $updated_by = null,
        $created_at = null,
        $updated_at = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->type = $type;
        $this->value = $value;
        $this->min_order_amount = $min_order_amount;
        $this->max_discount_amount = $max_discount_amount;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->usage_limit = $usage_limit;
        $this->usage_count = $usage_count;
        $this->per_user_limit = $per_user_limit;
        $this->is_active = (bool) $is_active;
        $this->created_by = $created_by;
        $this->updated_by = $updated_by;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function fromModel(DiscountCode $discountCode): self
    {
        $dto = new self(
            $discountCode->id,
            $discountCode->code,
            $discountCode->description,
            $discountCode->type,
            $discountCode->value,
            $discountCode->min_order_amount,
            $discountCode->max_discount_amount,
            $discountCode->start_date?->toDateTimeString(),
            $discountCode->end_date?->toDateTimeString(),
            $discountCode->usage_limit,
            $discountCode->usage_count,
            $discountCode->per_user_limit,
            $discountCode->is_active,
            $discountCode->created_by,
            $discountCode->updated_by,
            $discountCode->created_at?->toDateTimeString(),
            $discountCode->updated_at?->toDateTimeString()
        );

        // Add computed attributes
        $dto->usage_percentage = $discountCode->usage_percentage;
        $dto->remaining_usages = $discountCode->remaining_usages;
        $dto->status = $discountCode->status;
        $dto->is_expired = $discountCode->is_expired;
        $dto->is_upcoming = $discountCode->is_upcoming;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_amount' => $this->min_order_amount,
            'max_discount_amount' => $this->max_discount_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'usage_limit' => $this->usage_limit,
            'usage_count' => $this->usage_count,
            'per_user_limit' => $this->per_user_limit,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'usage_percentage' => $this->usage_percentage,
            'remaining_usages' => $this->remaining_usages,
            'status' => $this->status,
            'is_expired' => $this->is_expired,
            'is_upcoming' => $this->is_upcoming,
        ];
    }
}
