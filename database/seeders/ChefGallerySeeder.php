<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefGallery;

class ChefGallerySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة معرض صور الطهاة...');

        $chefs = Chef::all();

        foreach ($chefs as $chef) {
            for ($i = 1; $i <= rand(3, 6); $i++) {
                ChefGallery::firstOrCreate([
                    'chef_id' => $chef->id,
                    'image' => 'chefs/gallery/chef_' . $chef->id . '_image_' . $i . '.jpg',
                ], [
                    'caption' => 'صورة من أعمال الشيف',
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ تم إضافة معرض الصور بنجاح');
    }
}
