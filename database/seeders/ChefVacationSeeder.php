<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefVacation;
use Carbon\Carbon;

class ChefVacationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة إجازات الطهاة...');

        $chefs = Chef::all();

        foreach ($chefs as $chef) {
            // Add vacation
            ChefVacation::firstOrCreate([
                'chef_id' => $chef->id,
                'date' => Carbon::now()->addDays(rand(5, 15))->format('Y-m-d'),
            ], [
                'note' => 'إجازة شخصية',
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ تم إضافة الإجازات بنجاح');
    }
}
