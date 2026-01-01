<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة المشرفين...');

        $admins = [
            ['first_name' => 'عبدالله', 'last_name' => 'المالكي', 'email' => 'admin@monchef.sa', 'role' => 'super_admin'],
            ['first_name' => 'سارة', 'last_name' => 'القحطاني', 'email' => 'sara@monchef.sa', 'role' => 'admin'],
            ['first_name' => 'محمد', 'last_name' => 'العتيبي', 'email' => 'support@monchef.sa', 'role' => 'support'],
        ];

        foreach ($admins as $data) {
            $admin = Admin::firstOrCreate(
                ['email' => $data['email']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );
            
            $role = Role::where('name', $data['role'])->first();
            if ($role) {
                $admin->roles()->syncWithoutDetaching([$role->id]);
            }
        }

        $this->command->info('✅ تم إضافة المشرفين بنجاح');
    }
}
