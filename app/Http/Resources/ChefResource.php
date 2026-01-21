<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'bio' => $this->bio,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'banner' => $this->banner ? asset('storage/' . $this->banner) : null,
            'address' => $this->address,
            'governorate_id' => $this->governorate_id,
            'district_id' => $this->district_id,
            'area_id' => $this->area_id,
            'base_hourly_rate' => $this->base_hourly_rate,
            'status' => $this->status,
            'rating_avg' => $this->rating_avg,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Include relationships if loaded
            'governorate' => $this->whenLoaded('governorate'),
            'district' => $this->whenLoaded('district'),
            'area' => $this->whenLoaded('area'),
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
        ];
    }
}
