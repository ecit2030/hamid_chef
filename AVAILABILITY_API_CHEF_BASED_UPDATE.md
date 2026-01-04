# تحديث API التوفر - الاعتماد على الطاهي بدلاً من الخدمة

## نظرة عامة
تم تعديل دالة الـ availability API بحيث يتم جلب الحجوزات بناءً على `chef_id` وليس `service_id`. الـ `service_id` يُستخدم فقط للحصول على معلومات الخدمة.

## التغييرات المنفذة

### 1. تعديل `ChefAvailabilityService.php`

#### دالة `getBookingsInRange`:
**قبل التعديل:**
```php
// كانت تفلتر الحجوزات حسب service_id
if ($chefServiceId) {
    $query->where('chef_service_id', $chefServiceId);
}
```

**بعد التعديل:**
```php
// تجلب جميع حجوزات الطاهي بدون فلترة حسب الخدمة
// الـ service_id يُستخدم فقط لجلب معلومات الخدمة
$query = Booking::where('chef_id', $chefId)
    ->where('is_active', true)
    ->whereNotIn('booking_status', ['cancelled_by_customer', 'cancelled_by_chef', 'rejected'])
    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

// لا يوجد فلترة حسب chef_service_id
// جميع حجوزات الطاهي تؤثر على توفره
```

#### دالة `getChefAvailability`:
- تم تحديث التعليقات لتوضيح الاستخدام الصحيح
- `chef_service_id` يُستخدم فقط للحصول على:
  - اسم الخدمة (`service_name`)
  - الحد الأدنى للساعات (`min_hours`)
  - ساعات الراحة المطلوبة (`rest_hours_required`)

### 2. تحديث `ChefController.php`

#### دالة `availability`:
تم تحديث التوثيق لتوضيح:
- الحجوزات تُجلب بناءً على `chef_id` (جميع حجوزات الطاهي)
- `chef_service_id` يُستخدم فقط لمعلومات الخدمة
- حساب التوفر يعتمد على جميع حجوزات الطاهي بغض النظر عن الخدمة

## المنطق الجديد

### جلب الحجوزات:
```
1. يتم جلب جميع حجوزات الطاهي في الفترة المحددة
2. لا يتم فلترة الحجوزات حسب الخدمة
3. كل حجز يحتوي على معلومات خدمته الخاصة (service details)
```

### استخدام service_id:
```
1. اسم الخدمة - لعرضه في الواجهة
2. min_hours - الحد الأدنى لساعات الحجز
3. rest_hours_required - ساعات الراحة بعد الخدمة
```

### حساب التوفر:
```
1. يعتمد على جميع حجوزات الطاهي
2. كل حجز يستخدم rest_hours الخاصة بخدمته
3. الفترات المتاحة تُحسب بناءً على جميع الحجوزات
```

## مثال على الاستخدام

### طلب API:
```json
POST /api/chefs/1/availability-calendar
{
  "date": "2026-01-15",
  "chef_service_id": 5
}
```

### الاستجابة:
```json
{
  "chef_id": 1,
  "chef_name": "أحمد محمد",
  "service_id": 5,
  "service_name": "طبخ عربي",
  "min_hours": 3,
  "rest_hours_required": 2,
  "calendar": {
    "available_days": [...],
    "partially_booked_days": [...],
    "fully_booked_days": [...]
  },
  "day_details": {
    "bookings": [
      {
        "id": 10,
        "service": {
          "id": 3,
          "name": "طبخ إيطالي",
          "rest_hours_required": 3
        }
      },
      {
        "id": 11,
        "service": {
          "id": 5,
          "name": "طبخ عربي",
          "rest_hours_required": 2
        }
      }
    ]
  }
}
```

**ملاحظة:** الحجوزات تشمل جميع خدمات الطاهي، ليس فقط الخدمة المحددة.

## الفوائد

### 1. دقة أكبر في حساب التوفر:
- يتم احتساب جميع حجوزات الطاهي
- لا يوجد تضارب بين خدمات مختلفة لنفس الطاهي

### 2. مرونة أكبر:
- يمكن عرض معلومات خدمة معينة
- مع احتساب جميع حجوزات الطاهي

### 3. منطق أوضح:
- التوفر يعتمد على الطاهي نفسه
- الخدمة تُستخدم فقط للمعلومات

## الملفات المعدلة
1. `app/Services/ChefAvailabilityService.php` - تعديل منطق جلب الحجوزات
2. `app/Http/Controllers/Api/ChefController.php` - تحديث التوثيق

## التوافق مع الكود الحالي
- جميع الدوال الأخرى تعمل بنفس الطريقة
- `BookingConflictService` يعمل بشكل صحيح
- حساب ساعات الراحة لكل خدمة محفوظ
- التحقق من التعارضات يعمل بشكل صحيح

## الاختبار
يُنصح باختبار:
1. جلب التوفر بدون تحديد خدمة
2. جلب التوفر مع تحديد خدمة
3. التحقق من عرض جميع الحجوزات في day_details
4. التحقق من حساب الفترات المتاحة بشكل صحيح
