<?php

namespace App\DTOs;

use App\Models\DiscountCodeUsage;

class DiscountCodeUsageDTO extends BaseDTO
{
    public $id;
    public $discount_code_id;
    public $user_id;
    public $booking_id;
    public $original_amount;
    public $discount_amount;
    public $final_amount;
    public $used_at;

    // Computed
    public $discount_percentage;
    public $savings;

    public function __construct(
        $id,
        $discount_code_id,
        $user_id,
        $booking_id,
        $original_amount,
        $discount_amount,
        $final_amount,
        $used_at
    ) {
        $this->id = $id;
        $this->discount_code_id = $discount_code_id;
        $this->user_id = $user_id;
        $this->booking_id = $booking_id;
        $this->original_amount = $original_amount;
        $this->discount_amount = $discount_amount;
        $this->final_amount = $final_amount;
        $this->used_at = $used_at;
    }

    public static function fromModel(DiscountCodeUsage $usage): self
    {
        $dto = new self(
            $usage->id,
            $usage->discount_code_id,
            $usage->user_id,
            $usage->booking_id,
            $usage->original_amount,
            $usage->discount_amount,
            $usage->final_amount,
            $usage->used_at?->toDateTimeString()
        );

        $dto->discount_percentage = $usage->discount_percentage;
        $dto->savings = $usage->savings;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'discount_code_id' => $this->discount_code_id,
            'user_id' => $this->user_id,
            'booking_id' => $this->booking_id,
            'original_amount' => $this->original_amount,
            'discount_amount' => $this->discount_amount,
            'final_amount' => $this->final_amount,
            'used_at' => $this->used_at,
            'discount_percentage' => $this->discount_percentage,
            'savings' => $this->savings,
        ];
    }
}
