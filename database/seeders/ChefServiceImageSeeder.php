<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChefService;
use App\Models\ChefServiceImage;

class ChefServiceImageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة صور خدمات الطهاة...');

        $services = ChefService::all();

        foreach ($services as $service) {
            for ($i = 1; $i <= rand(2, 4); $i++) {
                ChefServiceImage::firstOrCreate([
                    'chef_service_id' => $service->id,
                    'image' => 'services/service_' . $service->id . '_image_' . $i . '.jpg',
                ], [
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ تم إضافة صور الخدمات بنجاح');
    }
}
