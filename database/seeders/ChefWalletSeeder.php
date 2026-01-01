<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefWallet;

class ChefWalletSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة محافظ الطهاة...');

        $chefs = Chef::all();

        foreach ($chefs as $chef) {
            ChefWallet::firstOrCreate(
                ['chef_id' => $chef->id],
                [
                    'balance' => rand(500, 5000),
                ]
            );
        }

        $this->command->info('✅ تم إضافة المحافظ بنجاح');
    }
}
