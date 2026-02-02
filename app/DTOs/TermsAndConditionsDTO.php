<?php

namespace App\DTOs;

use App\Models\TermsAndConditions;

class TermsAndConditionsDTO extends BaseDTO
{
    public function __construct(
        public int $id,
        public string $title_ar,
        public string $title_en,
        public string $content_ar,
        public string $content_en,
        public string $version,
        public bool $is_active,
        public ?string $effective_date,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}

    public static function fromModel(TermsAndConditions $terms): self
    {
        return new self(
            id: $terms->id,
            title_ar: $terms->title_ar,
            title_en: $terms->title_en,
            content_ar: $terms->content_ar,
            content_en: $terms->content_en,
            version: $terms->version,
            is_active: $terms->is_active,
            effective_date: $terms->effective_date?->toIso8601String(),
            created_at: $terms->created_at?->toIso8601String(),
            updated_at: $terms->updated_at?->toIso8601String(),
        );
    }

    /**
     * Convert to array for API response
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'content_ar' => $this->content_ar,
            'content_en' => $this->content_en,
            'version' => $this->version,
            'is_active' => $this->is_active,
            'effective_date' => $this->effective_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Convert to localized array (for mobile app)
     */
    public function toLocalizedArray(string $locale = 'ar'): array
    {
        return [
            'id' => $this->id,
            'title' => $locale === 'ar' ? $this->title_ar : $this->title_en,
            'content' => $locale === 'ar' ? $this->content_ar : $this->content_en,
            'version' => $this->version,
            'effective_date' => $this->effective_date,
        ];
    }
}
