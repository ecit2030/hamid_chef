<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;
use App\Models\District;
use App\Models\Area;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('إضافة المناطق السعودية...');

        $locations = [
            ['ar' => 'منطقة الرياض', 'en' => 'Riyadh Region', 'districts' => [
                ['ar' => 'الرياض', 'en' => 'Riyadh', 'areas' => [
                    ['ar' => 'حي العليا', 'en' => 'Al Olaya'],
                    ['ar' => 'حي السليمانية', 'en' => 'Al Sulaimaniyah'],
                    ['ar' => 'حي الملز', 'en' => 'Al Malaz'],
                    ['ar' => 'حي النخيل', 'en' => 'Al Nakheel'],
                    ['ar' => 'حي الياسمين', 'en' => 'Al Yasmin'],
                    ['ar' => 'حي الربوة', 'en' => 'Al Rabwah'],
                    ['ar' => 'حي المروج', 'en' => 'Al Muruj'],
                    ['ar' => 'حي الورود', 'en' => 'Al Wurud'],
                ]],
                ['ar' => 'الخرج', 'en' => 'Al Kharj', 'areas' => [
                    ['ar' => 'حي الخالدية', 'en' => 'Al Khalidiyah'],
                    ['ar' => 'حي السلام', 'en' => 'Al Salam'],
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                ]],
                ['ar' => 'الدرعية', 'en' => 'Diriyah', 'areas' => [
                    ['ar' => 'حي الدرعية القديمة', 'en' => 'Old Diriyah'],
                    ['ar' => 'حي البجيري', 'en' => 'Al Bujairi'],
                    ['ar' => 'حي الطريف', 'en' => 'At Turaif'],
                ]],
            ]],
            ['ar' => 'منطقة مكة المكرمة', 'en' => 'Makkah Region', 'districts' => [
                ['ar' => 'مكة المكرمة', 'en' => 'Makkah', 'areas' => [
                    ['ar' => 'حي العزيزية', 'en' => 'Al Aziziyah'],
                    ['ar' => 'حي الشوقية', 'en' => 'Al Shawqiyah'],
                    ['ar' => 'حي النسيم', 'en' => 'Al Naseem'],
                    ['ar' => 'حي الرصيفة', 'en' => 'Al Rusayfah'],
                    ['ar' => 'حي الكعكية', 'en' => 'Al Kakiyah'],
                ]],
                ['ar' => 'جدة', 'en' => 'Jeddah', 'areas' => [
                    ['ar' => 'حي الحمراء', 'en' => 'Al Hamra'],
                    ['ar' => 'حي الروضة', 'en' => 'Al Rawdah'],
                    ['ar' => 'حي الصفا', 'en' => 'Al Safa'],
                    ['ar' => 'حي البوادي', 'en' => 'Al Bawadi'],
                    ['ar' => 'حي الشاطئ', 'en' => 'Al Shati'],
                    ['ar' => 'حي النهضة', 'en' => 'Al Nahdah'],
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                ]],
                ['ar' => 'الطائف', 'en' => 'Taif', 'areas' => [
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                    ['ar' => 'حي الحوية', 'en' => 'Al Hawiyah'],
                    ['ar' => 'حي شهار', 'en' => 'Shihar'],
                ]],
            ]],
            ['ar' => 'المنطقة الشرقية', 'en' => 'Eastern Region', 'districts' => [
                ['ar' => 'الدمام', 'en' => 'Dammam', 'areas' => [
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                    ['ar' => 'حي الشاطئ', 'en' => 'Al Shati'],
                    ['ar' => 'حي الجلوية', 'en' => 'Al Jalawiyah'],
                    ['ar' => 'حي المريكبات', 'en' => 'Al Murabaat'],
                    ['ar' => 'حي الأنوار', 'en' => 'Al Anwar'],
                ]],
                ['ar' => 'الخبر', 'en' => 'Khobar', 'areas' => [
                    ['ar' => 'حي الراكة', 'en' => 'Al Rakah'],
                    ['ar' => 'حي العقربية', 'en' => 'Al Aqrabiyah'],
                    ['ar' => 'حي الثقبة', 'en' => 'Al Thuqbah'],
                    ['ar' => 'حي اليرموك', 'en' => 'Al Yarmouk'],
                    ['ar' => 'حي الخبر الشمالية', 'en' => 'North Khobar'],
                ]],
                ['ar' => 'الظهران', 'en' => 'Dhahran', 'areas' => [
                    ['ar' => 'حي الدوحة', 'en' => 'Al Doha'],
                    ['ar' => 'حي الجامعة', 'en' => 'University'],
                    ['ar' => 'حي غرناطة', 'en' => 'Granada'],
                ]],
                ['ar' => 'الأحساء', 'en' => 'Al Ahsa', 'areas' => [
                    ['ar' => 'حي المبرز', 'en' => 'Al Mubarraz'],
                    ['ar' => 'حي الهفوف', 'en' => 'Al Hofuf'],
                    ['ar' => 'حي المطيرفي', 'en' => 'Al Mutairfi'],
                ]],
            ]],
            ['ar' => 'منطقة المدينة المنورة', 'en' => 'Madinah Region', 'districts' => [
                ['ar' => 'المدينة المنورة', 'en' => 'Madinah', 'areas' => [
                    ['ar' => 'حي قباء', 'en' => 'Quba'],
                    ['ar' => 'حي العوالي', 'en' => 'Al Awali'],
                    ['ar' => 'حي الحرة الشرقية', 'en' => 'Al Harra Al Sharqiyah'],
                    ['ar' => 'حي السلام', 'en' => 'Al Salam'],
                    ['ar' => 'حي الدفاع', 'en' => 'Al Difaa'],
                ]],
                ['ar' => 'ينبع', 'en' => 'Yanbu', 'areas' => [
                    ['ar' => 'حي الصناعية', 'en' => 'Industrial'],
                    ['ar' => 'حي السويق', 'en' => 'Al Suwaiq'],
                    ['ar' => 'حي الشرم', 'en' => 'Al Sharm'],
                ]],
            ]],
            ['ar' => 'منطقة القصيم', 'en' => 'Qassim Region', 'districts' => [
                ['ar' => 'بريدة', 'en' => 'Buraidah', 'areas' => [
                    ['ar' => 'حي الصفراء', 'en' => 'Al Safra'],
                    ['ar' => 'حي الريان', 'en' => 'Al Rayyan'],
                    ['ar' => 'حي الإسكان', 'en' => 'Al Iskan'],
                    ['ar' => 'حي النقع', 'en' => 'Al Naqa'],
                ]],
                ['ar' => 'عنيزة', 'en' => 'Unaizah', 'areas' => [
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                    ['ar' => 'حي الروضة', 'en' => 'Al Rawdah'],
                    ['ar' => 'حي السلام', 'en' => 'Al Salam'],
                ]],
            ]],
            ['ar' => 'منطقة عسير', 'en' => 'Asir Region', 'districts' => [
                ['ar' => 'أبها', 'en' => 'Abha', 'areas' => [
                    ['ar' => 'حي المنسك', 'en' => 'Al Mansak'],
                    ['ar' => 'حي الموظفين', 'en' => 'Al Muwazzafin'],
                    ['ar' => 'حي شمسان', 'en' => 'Shamsan'],
                    ['ar' => 'حي الربوة', 'en' => 'Al Rabwah'],
                ]],
                ['ar' => 'خميس مشيط', 'en' => 'Khamis Mushait', 'areas' => [
                    ['ar' => 'حي الراقي', 'en' => 'Al Raqi'],
                    ['ar' => 'حي الموسى', 'en' => 'Al Musa'],
                    ['ar' => 'حي أم سرار', 'en' => 'Um Sarar'],
                ]],
            ]],
            ['ar' => 'منطقة تبوك', 'en' => 'Tabuk Region', 'districts' => [
                ['ar' => 'تبوك', 'en' => 'Tabuk', 'areas' => [
                    ['ar' => 'حي المروج', 'en' => 'Al Muruj'],
                    ['ar' => 'حي السليمانية', 'en' => 'Al Sulaimaniyah'],
                    ['ar' => 'حي الفيصلية', 'en' => 'Al Faisaliyah'],
                    ['ar' => 'حي المصيف', 'en' => 'Al Masif'],
                ]],
            ]],
            ['ar' => 'منطقة حائل', 'en' => 'Hail Region', 'districts' => [
                ['ar' => 'حائل', 'en' => 'Hail', 'areas' => [
                    ['ar' => 'حي المطار', 'en' => 'Airport'],
                    ['ar' => 'حي الخزامى', 'en' => 'Al Khuzama'],
                    ['ar' => 'حي الياسمين', 'en' => 'Al Yasmin'],
                ]],
            ]],
        ];

        foreach ($locations as $govData) {
            $governorate = Governorate::firstOrCreate(
                ['name_ar' => $govData['ar']],
                ['name_ar' => $govData['ar'], 'name_en' => $govData['en'], 'is_active' => true]
            );

            foreach ($govData['districts'] as $distData) {
                $district = District::firstOrCreate(
                    ['governorate_id' => $governorate->id, 'name_ar' => $distData['ar']],
                    ['name_ar' => $distData['ar'], 'name_en' => $distData['en'], 'is_active' => true]
                );

                foreach ($distData['areas'] as $areaData) {
                    Area::firstOrCreate(
                        ['district_id' => $district->id, 'name_ar' => $areaData['ar']],
                        ['name_ar' => $areaData['ar'], 'name_en' => $areaData['en'], 'is_active' => true]
                    );
                }
            }
        }

        $this->command->info('✅ تم إضافة المناطق بنجاح');
    }
}
