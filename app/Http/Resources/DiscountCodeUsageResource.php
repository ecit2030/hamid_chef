<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountCodeUsageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount_code_id' => $this->discount_code_id,
            'user_id' => $this->user_id,
            'booking_id' => $this->booking_id,
            'original_amount' => $this->original_amount,
            'discount_amount' => $this->discount_amount,
            'final_amount' => $this->final_amount,
            'used_at' => $this->used_at?->format('Y-m-d H:i:s'),

            // Computed
            'discount_percentage' => $this->discount_percentage,
            'savings' => $this->savings,

            // Relationships
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'phone_number' => $this->user->phone_number,
                ];
            }),
            'booking' => $this->whenLoaded('booking', function () {
                return [
                    'id' => $this->booking->id,
                    'date' => $this->booking->date?->format('Y-m-d'),
                    'booking_status' => $this->booking->booking_status,
                ];
            }),
        ];
    }
}
