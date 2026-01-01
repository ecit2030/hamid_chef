# تحديث API توفر الطاهي - إضافة معلومات الخدمة في المستوى الأعلى

## التغييرات المنفذة

### 1. تعديل Response Structure

عند إرسال `chef_service_id` في الطلب، الآن يتم إرجاع معلومات الخدمة مباشرة بعد معلومات الطاهي:

**قبل التعديل:**
```json
{
  "chef_id": 1,
  "chef_name": "أحمد الرشيدي",
  "default_rest_hours": 2,
  "calendar": {...},
  "service": {
    "id": 3,
    "name": "طبخ منزلي",
    "min_hours": 2,
    "rest_hours_required": 2
  }
}
```

**بعد التعديل:**
```json
{
  "chef_id": 1,
  "chef_name": "أحمد الرشيدي",
  "service_id": 3,
  "service_name": "طبخ منزلي",
  "min_hours": 2,
  "rest_hours_required": 2,
  "default_rest_hours": 2,
  "calendar": {...}
}
```

### 2. الملفات المعدلة

1. **app/Services/ChefAvailabilityService.php**
   - تم تعديل `getChefAvailability()` لإرجاع معلومات الخدمة في المستوى الأعلى
   - عند وجود `chef_service_id`: يتم إضافة `service_id`, `service_name`, `min_hours`, `rest_hours_required`
   - عند عدم وجود `chef_service_id`: يتم إرجاع مصفوفة `services` كما هو

2. **docs/CHEF_AVAILABILITY_API.md**
   - تحديث التوثيق ليعكس الـ response structure الجديد
   - إضافة أمثلة واضحة للحالتين

3. **VACATION_API_USAGE.md**
   - تحديث الأمثلة والتوثيق
   - إضافة شرح للـ response structure مع وبدون تصفية

4. **tests/Feature/ChefAvailabilityApiTest.php**
   - إضافة test جديد: `it_returns_service_details_at_top_level_when_filtering_by_service()`
   - التأكد من أن معلومات الخدمة تظهر في المستوى الأعلى
   - التأكد من عدم وجود مصفوفة `services` عند التصفية

## كيفية الاستخدام

### مثال 1: بدون تصفية (جميع الخدمات)

```bash
POST /api/chefs/1/availability-calendar
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "date": "2026-01-01"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "chef_id": 1,
    "chef_name": "أحمد الرشيدي",
    "default_rest_hours": 2,
    "calendar": {...},
    "services": [
      {
        "id": 1,
        "name": "طبخ منزلي",
        "min_hours": 2,
        "rest_hours_required": 2
      },
      {
        "id": 2,
        "name": "طبخ حفلات",
        "min_hours": 3,
        "rest_hours_required": 4
      }
    ]
  }
}
```

### مثال 2: مع تصفية حسب الخدمة

```bash
POST /api/chefs/1/availability-calendar
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "date": "2026-01-01",
  "chef_service_id": 3
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "chef_id": 1,
    "chef_name": "أحمد الرشيدي",
    "service_id": 3,
    "service_name": "طبخ منزلي",
    "min_hours": 2,
    "rest_hours_required": 2,
    "default_rest_hours": 2,
    "calendar": {...},
    "day_details": {...}
  }
}
```

**ملاحظة:** لا يوجد حقل `services` عند التصفية حسب خدمة محددة.

## الفوائد

1. **سهولة الوصول للبيانات**: معلومات الخدمة في المستوى الأعلى بدلاً من كائن متداخل
2. **تقليل التعقيد**: لا حاجة للوصول إلى `data.service.id` بل مباشرة `data.service_id`
3. **وضوح أكبر**: الـ response structure أكثر وضوحاً وسهولة في الاستخدام
4. **توافق مع الإجازات**: الإجازات تظهر بشكل صحيح في `calendar.vacation_days`

## الاختبارات

تم إضافة test جديد للتأكد من:
- ✅ معلومات الخدمة تظهر في المستوى الأعلى
- ✅ لا يوجد حقل `services` عند التصفية
- ✅ جميع الحقول المطلوبة موجودة (service_id, service_name, min_hours, rest_hours_required)

```bash
php artisan test --filter=it_returns_service_details_at_top_level_when_filtering_by_service
```

## ملاحظات مهمة

1. **الإجازات تعمل بشكل صحيح**: تظهر في `calendar.vacation_days` و `day_details.is_vacation_day`
2. **Authentication مطلوب**: يجب إرسال Bearer token
3. **POST فقط**: الـ endpoint لا يقبل GET
4. **Body Parameters**: استخدم JSON body وليس query parameters
