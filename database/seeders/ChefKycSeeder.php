<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\Kyc;

class ChefKycSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة KYC للطهاة...');

        $chefs = Chef::with('user')->get();

        foreach ($chefs as $chef) {
            if ($chef->user) {
                Kyc::firstOrCreate(
                    ['user_id' => $chef->user->id],
                    [
                        'full_name' => $chef->name,
                        'gender' => ['male', 'female'][rand(0, 1)],
                        'date_of_birth' => now()->subYears(rand(25, 50)),
                        'address' => 'عنوان الشيف',
                        'document_type' => 'id_card',
                        'document_scan_copy' => 'kyc/documents/sample.pdf',
                        'status' => 'approved',
                        'is_verified' => true,
                        'verified_at' => now(),
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ تم إضافة KYC بنجاح');
    }
}
