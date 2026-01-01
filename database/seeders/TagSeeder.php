<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة الوسوم...');

        $tags = [
            ['name' => 'حلال', 'slug' => 'halal'],
            ['name' => 'نباتي', 'slug' => 'vegetarian'],
            ['name' => 'خالي من الجلوتين', 'slug' => 'gluten-free'],
            ['name' => 'حار', 'slug' => 'spicy'],
            ['name' => 'للأطفال', 'slug' => 'kids-friendly'],
            ['name' => 'مناسبات', 'slug' => 'occasions'],
            ['name' => 'حفلات', 'slug' => 'parties'],
            ['name' => 'عزائم', 'slug' => 'gatherings'],
            ['name' => 'رمضان', 'slug' => 'ramadan'],
            ['name' => 'أعياد', 'slug' => 'holidays'],
            ['name' => 'سريع التحضير', 'slug' => 'quick-prep'],
            ['name' => 'طازج', 'slug' => 'fresh'],
            ['name' => 'عضوي', 'slug' => 'organic'],
            ['name' => 'تقليدي', 'slug' => 'traditional'],
            ['name' => 'عصري', 'slug' => 'modern'],
            ['name' => 'اقتصادي', 'slug' => 'budget-friendly'],
            ['name' => 'فاخر', 'slug' => 'luxury'],
            ['name' => 'للعائلات', 'slug' => 'family-friendly'],
            ['name' => 'رومانسي', 'slug' => 'romantic'],
            ['name' => 'صحي', 'slug' => 'healthy'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => $tag['slug']],
                ['name' => $tag['name'], 'is_active' => true]
            );
        }

        $this->command->info('✅ تم إضافة الوسوم بنجاح');
    }
}
