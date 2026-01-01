<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WithdrawalMethod;

class WithdrawalMethodSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة طرق السحب...');

        $methods = [
            ['name' => 'تحويل بنكي', 'description' => 'تحويل مباشر إلى حسابك البنكي في السعودية', 'is_active' => true],
            ['name' => 'STC Pay', 'description' => 'تحويل فوري عبر محفظة STC Pay', 'is_active' => true],
            ['name' => 'Apple Pay', 'description' => 'تحويل عبر Apple Pay', 'is_active' => true],
        ];

        foreach ($methods as $method) {
            WithdrawalMethod::firstOrCreate(['name' => $method['name']], $method);
        }

        $this->command->info('✅ تم إضافة طرق السحب بنجاح');
    }
}
