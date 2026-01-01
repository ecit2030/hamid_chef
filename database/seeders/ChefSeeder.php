<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Chef;
use App\Models\ChefCategory;
use App\Models\Category;

class ChefSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة الطهاة...');

        $chefs = [
            [
                'first_name' => 'أحمد',
                'last_name' => 'الرشيدي',
                'email' => 'chef.ahmed@example.com',
                'phone' => '0503456789',
                'bio' => 'شيف سعودي متخصص في المطبخ السعودي التقليدي، خبرة أكثر من 15 سنة في أفخم المطاعم',
                'specialties' => ['المطبخ السعودي', 'المشويات'],
            ],
            [
                'first_name' => 'فاطمة',
                'last_name' => 'السالم',
                'email' => 'chef.fatima@example.com',
                'phone' => '0553456789',
                'bio' => 'شيف متخصصة في الحلويات العربية والغربية، حاصلة على شهادات دولية في فن الحلويات',
                'specialties' => ['الحلويات', 'المطبخ الشامي'],
            ],
            [
                'first_name' => 'عمر',
                'last_name' => 'الحسيني',
                'email' => 'chef.omar@example.com',
                'phone' => '0563456789',
                'bio' => 'شيف مصري متخصص في المأكولات المصرية الأصيلة، خبرة 10 سنوات في المطاعم الفاخرة',
                'specialties' => ['المطبخ المصري', 'المشويات'],
            ],
            [
                'first_name' => 'ليلى',
                'last_name' => 'الخالدي',
                'email' => 'chef.layla@example.com',
                'phone' => '0573456789',
                'bio' => 'شيف سعودية متخصصة في الأكل الصحي والحميات، حاصلة على شهادة في التغذية العلاجية',
                'specialties' => ['الأكل الصحي', 'المطبخ الخليجي'],
            ],
            [
                'first_name' => 'راشد',
                'last_name' => 'المري',
                'email' => 'chef.rashed@example.com',
                'phone' => '0583456789',
                'bio' => 'شيف إماراتي متخصص في المأكولات البحرية والخليجية، خبرة في أشهر فنادق دبي',
                'specialties' => ['المأكولات البحرية', 'المطبخ الخليجي'],
            ],
            [
                'first_name' => 'منى',
                'last_name' => 'الشهراني',
                'email' => 'chef.mona@example.com',
                'phone' => '0593456789',
                'bio' => 'شيف سعودية من عسير، متخصصة في المطبخ الجنوبي والعسيري التقليدي',
                'specialties' => ['المطبخ السعودي', 'المطبخ الخليجي'],
            ],
        ];

        foreach ($chefs as $chefData) {
            $user = User::firstOrCreate(
                ['email' => $chefData['email']],
                [
                    'first_name' => $chefData['first_name'],
                    'last_name' => $chefData['last_name'],
                    'phone_number' => $chefData['phone'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'user_type' => 'chef',
                    'is_active' => true,
                ]
            );

            $chef = Chef::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $chefData['first_name'] . ' ' . $chefData['last_name'],
                    'short_description' => $chefData['bio'],
                    'long_description' => $chefData['bio'],
                    'base_hourly_rate' => rand(100, 200),
                    'governorate_id' => 1,
                    'district_id' => 1,
                    'area_id' => 1,
                    'is_active' => true,
                    'rating_avg' => rand(40, 50) / 10,
                ]
            );

            // Add specialties
            foreach ($chefData['specialties'] as $specialty) {
                $category = Category::where('name', $specialty)->first();
                if ($category) {
                    ChefCategory::firstOrCreate([
                        'chef_id' => $chef->id,
                        'cuisine_id' => $category->id,
                    ]);
                }
            }
        }

        $this->command->info('✅ تم إضافة الطهاة بنجاح');
    }
}
