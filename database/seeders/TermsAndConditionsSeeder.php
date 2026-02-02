<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TermsAndConditions;
use App\Models\User;

class TermsAndConditionsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('user_type', 'admin')->first();

        TermsAndConditions::create([
            'title_ar' => 'الشروط والأحكام - الإصدار 1.0',
            'title_en' => 'Terms and Conditions - Version 1.0',
            'content_ar' => $this->getArabicContent(),
            'content_en' => $this->getEnglishContent(),
            'version' => '1.0',
            'is_active' => true,
            'effective_date' => now(),
            'created_by' => $admin?->id,
            'updated_by' => $admin?->id,
        ]);
    }

    private function getArabicContent(): string
    {
        return <<<'ARABIC'
# الشروط والأحكام

## 1. المقدمة
مرحباً بك في تطبيق مون شيف. باستخدامك لهذا التطبيق، فإنك توافق على الالتزام بهذه الشروط والأحكام.

## 2. استخدام الخدمة
- يجب أن تكون بعمر 18 عاماً أو أكثر لاستخدام هذه الخدمة
- يجب تقديم معلومات صحيحة ودقيقة عند التسجيل
- أنت مسؤول عن الحفاظ على سرية حسابك وكلمة المرور

## 3. الحجوزات والدفع
- جميع الحجوزات تخضع للتوافر
- يجب الدفع مقدماً لتأكيد الحجز
- سياسة الإلغاء تطبق حسب الشروط المحددة

## 4. مسؤوليات الطهاة
- يجب على الطهاة تقديم خدمات عالية الجودة
- الالتزام بمعايير النظافة والسلامة الغذائية
- احترام مواعيد الحجوزات

## 5. مسؤوليات العملاء
- توفير بيئة عمل آمنة ومناسبة للطاهي
- الالتزام بالمواعيد المحددة
- الدفع في الوقت المحدد

## 6. الإلغاء والاسترداد
- يمكن إلغاء الحجز قبل 24 ساعة من الموعد المحدد
- رسوم الإلغاء قد تطبق حسب السياسة
- الاسترداد يتم خلال 7-14 يوم عمل

## 7. حقوق الملكية الفكرية
جميع المحتويات والعلامات التجارية في التطبيق محمية بحقوق الملكية الفكرية.

## 8. إخلاء المسؤولية
التطبيق يعمل كوسيط بين الطهاة والعملاء ولا يتحمل مسؤولية مباشرة عن جودة الخدمات.

## 9. التعديلات
نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم إشعارك بأي تغييرات جوهرية.

## 10. القانون الساري
تخضع هذه الشروط لقوانين المملكة العربية السعودية.

## 11. الاتصال
للاستفسارات، يرجى التواصل معنا عبر:
- البريد الإلكتروني: support@monchef.com
- الهاتف: +966 XX XXX XXXX

آخر تحديث: 1 فبراير 2026
ARABIC;
    }

    private function getEnglishContent(): string
    {
        return <<<'ENGLISH'
# Terms and Conditions

## 1. Introduction
Welcome to Mon Chef application. By using this application, you agree to comply with these terms and conditions.

## 2. Use of Service
- You must be 18 years or older to use this service
- You must provide accurate and truthful information during registration
- You are responsible for maintaining the confidentiality of your account and password

## 3. Bookings and Payment
- All bookings are subject to availability
- Payment must be made in advance to confirm booking
- Cancellation policy applies as specified

## 4. Chef Responsibilities
- Chefs must provide high-quality services
- Comply with hygiene and food safety standards
- Respect booking schedules

## 5. Customer Responsibilities
- Provide a safe and suitable working environment for the chef
- Adhere to scheduled times
- Make payment on time

## 6. Cancellation and Refund
- Bookings can be cancelled 24 hours before the scheduled time
- Cancellation fees may apply according to policy
- Refunds are processed within 7-14 business days

## 7. Intellectual Property Rights
All content and trademarks in the application are protected by intellectual property rights.

## 8. Disclaimer
The application acts as an intermediary between chefs and customers and does not bear direct responsibility for service quality.

## 9. Modifications
We reserve the right to modify these terms at any time. You will be notified of any material changes.

## 10. Governing Law
These terms are subject to the laws of the Kingdom of Saudi Arabia.

## 11. Contact
For inquiries, please contact us via:
- Email: support@monchef.com
- Phone: +966 XX XXX XXXX

Last updated: February 1, 2026
ENGLISH;
    }
}
