<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ChefService;
use App\Models\Booking;
use App\Models\Address;
use App\Models\Area;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة الحجوزات...');

        $customers = User::where('user_type', 'customer')->get();
        $services = ChefService::with('chef')->get();

        if ($customers->isEmpty() || $services->isEmpty()) {
            $this->command->warn('لا توجد عملاء أو خدمات لإنشاء حجوزات');
            return;
        }

        $statuses = ['pending', 'accepted', 'completed', 'cancelled_by_customer'];
        $notes = [
            'أرجو الحضور في الموعد المحدد',
            'يوجد أطفال، أرجو مراعاة ذلك',
            'الحفلة في الحديقة الخلفية',
            'أرجو إحضار جميع المعدات اللازمة',
            'عدد الضيوف قد يزيد قليلاً',
            'نحتاج طعام حلال فقط',
            'يوجد شخص لديه حساسية من المكسرات',
        ];

        foreach ($services as $service) {
            $numBookings = rand(2, 4);
            $selectedCustomers = $customers->random(min($numBookings, $customers->count()));

            foreach ($selectedCustomers as $customer) {
                $address = Address::where('user_id', $customer->id)->first();
                if (!$address) {
                    $area = Area::inRandomOrder()->first();
                    $address = Address::create([
                        'user_id' => $customer->id,
                        'area_id' => $area->id,
                        'address' => 'عنوان العميل',
                        'street' => 'شارع الملك عبدالعزيز',
                        'building_number' => rand(1, 500),
                        'is_default' => true,
                        'is_active' => true,
                    ]);
                }

                $status = $statuses[array_rand($statuses)];
                $date = Carbon::now()->addDays(rand(-10, 30));
                $hoursCount = rand($service->min_hours ?? 2, 6);
                $startHour = rand(9, 18);

                $unitPrice = $service->service_type === 'hourly' 
                    ? $service->hourly_rate 
                    : $service->package_price / $hoursCount;
                $totalAmount = $service->service_type === 'hourly'
                    ? $service->hourly_rate * $hoursCount
                    : $service->package_price;
                
                // Calculate commission (15% of total)
                $commissionRate = 0.15;
                $commissionAmount = $totalAmount * $commissionRate;

                Booking::create([
                    'customer_id' => $customer->id,
                    'chef_id' => $service->chef_id,
                    'chef_service_id' => $service->id,
                    'address_id' => $address->id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => sprintf('%02d:00:00', $startHour),
                    'hours_count' => $hoursCount,
                    'number_of_guests' => rand(4, 20),
                    'service_type' => $service->service_type,
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'commission_amount' => $commissionAmount,
                    'booking_status' => $status,
                    'notes' => $notes[array_rand($notes)],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ تم إضافة الحجوزات بنجاح');
    }
}
