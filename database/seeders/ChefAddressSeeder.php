<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\Address;
use App\Models\Area;

class ChefAddressSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة عناوين الطهاة...');

        $chefs = Chef::with('user')->get();

        foreach ($chefs as $chef) {
            $area = Area::inRandomOrder()->first();
            if ($area && $chef->user) {
                Address::firstOrCreate(
                    ['user_id' => $chef->user->id, 'is_default' => true],
                    [
                        'area_id' => $area->id,
                        'address' => 'عنوان الشيف',
                        'street' => 'شارع الأمير سلطان',
                        'building_number' => rand(1, 500),
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ تم إضافة العناوين بنجاح');
    }
}
