<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة فئات الطعام...');

        $categories = [
            ['name' => 'المطبخ السعودي', 'slug' => 'saudi-cuisine'],
            ['name' => 'المطبخ الخليجي', 'slug' => 'gulf-cuisine'],
            ['name' => 'المطبخ الشامي', 'slug' => 'levantine-cuisine'],
            ['name' => 'المطبخ المصري', 'slug' => 'egyptian-cuisine'],
            ['name' => 'المطبخ الهندي', 'slug' => 'indian-cuisine'],
            ['name' => 'المطبخ الإيطالي', 'slug' => 'italian-cuisine'],
            ['name' => 'المشويات', 'slug' => 'grills'],
            ['name' => 'الحلويات', 'slug' => 'desserts'],
            ['name' => 'المأكولات البحرية', 'slug' => 'seafood'],
            ['name' => 'الأكل الصحي', 'slug' => 'healthy-food'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'is_active' => true]
            );
        }

        $this->command->info('✅ تم إضافة الفئات بنجاح');
    }
}
