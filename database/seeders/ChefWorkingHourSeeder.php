<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefWorkingHour;

class ChefWorkingHourSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة ساعات عمل الطهاة...');

        $chefs = Chef::all();

        foreach ($chefs as $chef) {
            // Add working hours (Sunday - Thursday)
            for ($day = 0; $day <= 4; $day++) {
                // Morning shift
                ChefWorkingHour::firstOrCreate([
                    'chef_id' => $chef->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                ], [
                    'end_time' => '14:00',
                    'is_active' => true,
                ]);
                
                // Evening shift
                ChefWorkingHour::firstOrCreate([
                    'chef_id' => $chef->id,
                    'day_of_week' => $day,
                    'start_time' => '17:00',
                ], [
                    'end_time' => '22:00',
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ تم إضافة ساعات العمل بنجاح');
    }
}
