<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\ChefServiceRating;

class ChefServiceRatingSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة تقييمات الخدمات...');

        $completedBookings = Booking::where('booking_status', 'completed')->get();

        $reviews = [
            'شيف ممتاز والطعام لذيذ جداً، أنصح به بشدة',
            'خدمة رائعة وطعام شهي، سأحجز مرة أخرى',
            'الشيف محترف جداً والضيوف أعجبوا بالطعام',
            'تجربة مميزة، الطعام كان طازج ولذيذ',
            'أفضل شيف تعاملت معه، شكراً جزيلاً',
            'الطعام كان رائع والشيف ملتزم بالمواعيد',
            'تجربة لا تنسى، الطعام كان أكثر من رائع',
            'شيف محترف وذوق عالي في الطبخ',
        ];

        foreach ($completedBookings as $booking) {
            ChefServiceRating::firstOrCreate([
                'booking_id' => $booking->id,
            ], [
                'customer_id' => $booking->customer_id,
                'chef_id' => $booking->chef_id,
                'rating' => rand(4, 5),
                'review' => $reviews[array_rand($reviews)],
                'is_active' => true,
            ]);
        }

        $this->command->info('✅ تم إضافة التقييمات بنجاح');
    }
}
