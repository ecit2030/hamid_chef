<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name_ar' => 'أحمد محمد',
                'name_en' => 'Ahmed Mohammed',
                'comment_ar' => 'تجربة رائعة! الطاهي كان محترف جداً والطعام كان لذيذ. أنصح الجميع بتجربة المنصة.',
                'comment_en' => 'Amazing experience! The chef was very professional and the food was delicious. I recommend everyone to try the platform.',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
                'display_order' => 1,
            ],
            [
                'name_ar' => 'فاطمة علي',
                'name_en' => 'Fatima Ali',
                'comment_ar' => 'خدمة ممتازة وسهولة في الحجز. الطاهية كانت ودودة والوجبة فاقت التوقعات.',
                'comment_en' => 'Excellent service and easy booking. The chef was friendly and the meal exceeded expectations.',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
                'display_order' => 2,
            ],
            [
                'name_ar' => 'خالد سعيد',
                'name_en' => 'Khaled Saeed',
                'comment_ar' => 'أفضل منصة لحجز الطهاة. الطعام كان رائع والخدمة احترافية. سأستخدمها مرة أخرى بالتأكيد.',
                'comment_en' => 'Best platform for booking chefs. The food was amazing and the service was professional. I will definitely use it again.',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150',
                'display_order' => 3,
            ],
            [
                'name_ar' => 'نورة أحمد',
                'name_en' => 'Noura Ahmed',
                'comment_ar' => 'استخدمت المنصة لحفل عيد ميلاد ابنتي وكانت النتيجة مذهلة. الشكر لكم!',
                'comment_en' => 'I used the platform for my daughter\'s birthday party and the result was amazing. Thank you!',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150',
                'display_order' => 4,
            ],
            [
                'name_ar' => 'عمر حسن',
                'name_en' => 'Omar Hassan',
                'comment_ar' => 'تنوع في المطابخ والطهاة. وجدت طاهي المطبخ الهندي المثالي لمناسبتي.',
                'comment_en' => 'Great variety of cuisines and chefs. I found the perfect Indian chef for my occasion.',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150',
                'display_order' => 5,
            ],
            [
                'name_ar' => 'سارة محمد',
                'name_en' => 'Sara Mohammed',
                'comment_ar' => 'سهولة في الحجز والدفع. الطاهي وصل في الوقت المحدد وكان كل شيء منظم.',
                'comment_en' => 'Easy booking and payment. The chef arrived on time and everything was well organized.',
                'rating' => 5,
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150',
                'display_order' => 6,
            ],
        ];

        if (Testimonial::count() > 0) {
            return;
        }

        foreach ($testimonials as $t) {
            Testimonial::create(array_merge($t, ['is_active' => true]));
        }
    }
}
