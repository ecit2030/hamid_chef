<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChefService;
use App\Models\ChefServiceEquipment;

class ChefServiceEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة معدات خدمات الطهاة...');

        $services = ChefService::all();
        $equipment = ['موقد غاز', 'أواني طبخ', 'سكاكين احترافية', 'خلاط كهربائي', 'فرن'];

        foreach ($services as $service) {
            $selectedEquipment = array_rand(array_flip($equipment), rand(2, 4));
            
            foreach ((array)$selectedEquipment as $eq) {
                ChefServiceEquipment::firstOrCreate([
                    'chef_service_id' => $service->id,
                    'name' => $eq,
                ], [
                    'is_included' => (bool)rand(0, 1),
                ]);
            }
        }

        $this->command->info('✅ تم إضافة معدات الخدمات بنجاح');
    }
}
