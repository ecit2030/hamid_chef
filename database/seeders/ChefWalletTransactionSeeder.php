<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefWalletTransaction;

class ChefWalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة معاملات محافظ الطهاة...');

        $chefs = Chef::all();
        $transactionTypes = ['credit', 'debit'];

        foreach ($chefs as $chef) {
            for ($i = 0; $i < rand(3, 6); $i++) {
                ChefWalletTransaction::create([
                    'chef_id' => $chef->id,
                    'type' => $transactionTypes[array_rand($transactionTypes)],
                    'amount' => rand(100, 1000),
                    'balance' => rand(1000, 5000),
                    'description' => 'معاملة مالية',
                ]);
            }
        }

        $this->command->info('✅ تم إضافة معاملات المحافظ بنجاح');
    }
}
