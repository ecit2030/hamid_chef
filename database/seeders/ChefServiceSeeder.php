<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chef;
use App\Models\ChefService;
use App\Models\User;

class ChefServiceSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة خدمات الطهاة...');

        $services = [
            'chef.ahmed@example.com' => [
                ['name' => 'كبسة لحم فاخرة', 'type' => 'package', 'price' => 350, 'min_hours' => 3, 'rest_hours' => 2],
                ['name' => 'مندي دجاج', 'type' => 'package', 'price' => 250, 'min_hours' => 2, 'rest_hours' => 2],
                ['name' => 'طبخ بالساعة', 'type' => 'hourly', 'price' => 150, 'min_hours' => 2, 'rest_hours' => 3],
            ],
            'chef.fatima@example.com' => [
                ['name' => 'كيكة عيد ميلاد فاخرة', 'type' => 'package', 'price' => 400, 'min_hours' => 4, 'rest_hours' => 2],
                ['name' => 'حلويات عربية متنوعة', 'type' => 'package', 'price' => 300, 'min_hours' => 3, 'rest_hours' => 2],
                ['name' => 'تحضير حلويات بالساعة', 'type' => 'hourly', 'price' => 120, 'min_hours' => 2, 'rest_hours' => 2],
            ],
            'chef.omar@example.com' => [
                ['name' => 'كشري مصري أصلي', 'type' => 'package', 'price' => 180, 'min_hours' => 2, 'rest_hours' => 2],
                ['name' => 'فتة وممبار', 'type' => 'package', 'price' => 280, 'min_hours' => 3, 'rest_hours' => 3],
                ['name' => 'طبخ مصري بالساعة', 'type' => 'hourly', 'price' => 100, 'min_hours' => 2, 'rest_hours' => 2],
            ],
            'chef.layla@example.com' => [
                ['name' => 'وجبات صحية أسبوعية', 'type' => 'package', 'price' => 800, 'min_hours' => 5, 'rest_hours' => 4],
                ['name' => 'وجبة صحية يومية', 'type' => 'package', 'price' => 150, 'min_hours' => 2, 'rest_hours' => 2],
                ['name' => 'استشارة وطبخ صحي', 'type' => 'hourly', 'price' => 180, 'min_hours' => 2, 'rest_hours' => 2],
            ],
            'chef.rashed@example.com' => [
                ['name' => 'سمك مشوي مع الأرز', 'type' => 'package', 'price' => 450, 'min_hours' => 3, 'rest_hours' => 3],
                ['name' => 'روبيان مقلي', 'type' => 'package', 'price' => 350, 'min_hours' => 2, 'rest_hours' => 2],
                ['name' => 'طبخ بحري بالساعة', 'type' => 'hourly', 'price' => 200, 'min_hours' => 2, 'rest_hours' => 3],
            ],
            'chef.mona@example.com' => [
                ['name' => 'عريكة عسيرية', 'type' => 'package', 'price' => 200, 'min_hours' => 2, 'rest_hours' => 2],
                ['name' => 'مرقوق باللحم', 'type' => 'package', 'price' => 300, 'min_hours' => 3, 'rest_hours' => 2],
                ['name' => 'طبخ جنوبي بالساعة', 'type' => 'hourly', 'price' => 130, 'min_hours' => 2, 'rest_hours' => 2],
            ],
        ];

        foreach ($services as $email => $chefServices) {
            $user = User::where('email', $email)->first();
            if (!$user) continue;

            $chef = Chef::where('user_id', $user->id)->first();
            if (!$chef) continue;

            foreach ($chefServices as $serviceData) {
                ChefService::firstOrCreate(
                    ['chef_id' => $chef->id, 'name' => $serviceData['name']],
                    [
                        'description' => 'خدمة ' . $serviceData['name'] . ' من الشيف ' . $chef->name,
                        'service_type' => $serviceData['type'],
                        'hourly_rate' => $serviceData['type'] === 'hourly' ? $serviceData['price'] : null,
                        'package_price' => $serviceData['type'] === 'package' ? $serviceData['price'] : null,
                        'min_hours' => $serviceData['min_hours'],
                        'rest_hours_required' => $serviceData['rest_hours'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('✅ تم إضافة خدمات الطهاة بنجاح');
    }
}
