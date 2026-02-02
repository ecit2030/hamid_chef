<?php

namespace Database\Factories;

use App\Models\TermsAndConditions;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermsAndConditionsFactory extends Factory
{
    protected $model = TermsAndConditions::class;

    public function definition(): array
    {
        return [
            'title_ar' => 'الشروط والأحكام',
            'title_en' => 'Terms and Conditions',
            'content_ar' => 'محتوى الشروط والأحكام بالعربي. هذا نص تجريبي للشروط والأحكام.',
            'content_en' => 'Terms and Conditions content in English. This is a sample text for terms and conditions.',
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function version(string $version): static
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }
}
