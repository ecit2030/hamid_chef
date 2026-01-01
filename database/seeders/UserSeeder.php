<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Address;
use App\Models\Area;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة المستخدمين...');

        $customers = [
            ['first_name' => 'فهد', 'last_name' => 'الدوسري', 'email' => 'fahad@example.com', 'phone' => '0501234567'],
            ['first_name' => 'نورة', 'last_name' => 'العنزي', 'email' => 'noura@example.com', 'phone' => '0551234567'],
            ['first_name' => 'سلطان', 'last_name' => 'الشمري', 'email' => 'sultan@example.com', 'phone' => '0561234567'],
            ['first_name' => 'هند', 'last_name' => 'القحطاني', 'email' => 'hind@example.com', 'phone' => '0571234567'],
            ['first_name' => 'خالد', 'last_name' => 'الغامدي', 'email' => 'khaled@example.com', 'phone' => '0581234567'],
            ['first_name' => 'ريم', 'last_name' => 'الحربي', 'email' => 'reem@example.com', 'phone' => '0591234567'],
            ['first_name' => 'عبدالرحمن', 'last_name' => 'المطيري', 'email' => 'abdulrahman@example.com', 'phone' => '0502345678'],
            ['first_name' => 'سارة', 'last_name' => 'الزهراني', 'email' => 'sara.z@example.com', 'phone' => '0552345678'],
            ['first_name' => 'محمد', 'last_name' => 'البقمي', 'email' => 'mohammed.b@example.com', 'phone' => '0562345678'],
            ['first_name' => 'لمى', 'last_name' => 'السبيعي', 'email' => 'lama@example.com', 'phone' => '0572345678'],
            ['first_name' => 'تركي', 'last_name' => 'العمري', 'email' => 'turki@example.com', 'phone' => '0582345678'],
            ['first_name' => 'دانة', 'last_name' => 'الشهري', 'email' => 'dana@example.com', 'phone' => '0592345678'],
        ];

        foreach ($customers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone_number' => $data['phone'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'user_type' => 'customer',
                    'is_active' => true,
                ]
            );

            // Add address for each customer
            $area = Area::inRandomOrder()->first();
            if ($area) {
                Address::firstOrCreate(
                    ['user_id' => $user->id, 'is_default' => true],
                    [
                        'area_id' => $area->id,
                        'address' => 'بجوار مسجد الحي',
                        'street' => 'شارع الملك فهد',
                        'building_number' => rand(1, 500),
                        'floor_number' => rand(1, 10),
                        'apartment_number' => rand(1, 20),
                        'lat' => 24.7136 + (rand(-100, 100) / 1000),
                        'lang' => 46.6753 + (rand(-100, 100) / 1000),
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ تم إضافة المستخدمين بنجاح');
    }
}
