<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefWithdrawalRequest;
use App\Models\WithdrawalMethod;

class ChefWithdrawalRequestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة طلبات سحب الطهاة...');

        $chefs = Chef::all();

        foreach ($chefs as $chef) {
            $withdrawalMethod = WithdrawalMethod::inRandomOrder()->first();
            if ($withdrawalMethod) {
                ChefWithdrawalRequest::create([
                    'chef_id' => $chef->id,
                    'withdrawal_method_id' => $withdrawalMethod->id,
                    'amount' => rand(200, 800),
                    'status' => ['pending', 'processing', 'paid'][rand(0, 2)],
                    'payment_details' => json_encode(['iban' => 'SA' . rand(1000000000000000, 9999999999999999)]),
                ]);
            }
        }

        $this->command->info('✅ تم إضافة طلبات السحب بنجاح');
    }
}
