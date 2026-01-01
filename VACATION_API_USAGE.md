# كيفية استخدام API الإجازات

## المشكلة
كنت تستخدم:
```
GET /api/chefs/10/availability-calendar?date=2026-01-01&chef_service_id=3
```

## المشاكل:
1. ✗ الطاهي رقم 10 غير موجود (لديك فقط 6 طهاة: IDs 1-6)
2. ✗ تستخدم GET - الـ API الآن يتطلب POST
3. ✗ تستخدم query parameters - الـ API الآن يتطلب body parameters

## الحل الصحيح

### استخدم POST مع body parameters:

```bash
POST /api/chefs/1/availability-calendar
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "date": "2026-01-12",
  "chef_service_id": 3
}
```

### الطهاة الموجودون مع إجازاتهم:

| Chef ID | Name | Vacation Date |
|---------|------|---------------|
| 1 | أحمد الرشيدي | 2026-01-12 |
| 2 | فاطمة السالم | 2026-01-07 |
| 3 | عمر الحسيني | 2026-01-12 |
| 4 | ليلى الخالدي | 2026-01-12 |
| 5 | راشد المري | 2026-01-15 |
| 6 | منى الشهراني | 2026-01-14 |

## أمثلة صحيحة

### 1. جلب توفر الطاهي 1 في تاريخ إجازته (2026-01-12):

```bash
curl -X POST "https://monchef.codebrains.net/api/chefs/1/availability-calendar" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "date": "2026-01-12"
  }'
```

**النتيجة المتوقعة:**
- `calendar.vacation_days` سيحتوي على يوم 2026-01-12
- `day_details.is_vacation_day` سيكون `true`
- `day_details.vacation_note` سيكون "إجازة شخصية"

### 2. جلب توفر الطاهي 3 مع تصفية حسب الخدمة:

```bash
curl -X POST "https://monchef.codebrains.net/api/chefs/3/availability-calendar" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "date": "2026-01-12",
    "chef_service_id": 3
  }'
```

**النتيجة المتوقعة:**
- سيعرض فقط الحجوزات للخدمة رقم 3
- سيعرض تفاصيل الخدمة مباشرة بعد معلومات الطاهي:
  - `service_id`: 3
  - `service_name`: اسم الخدمة
  - `min_hours`: الحد الأدنى للساعات
  - `rest_hours_required`: ساعات الراحة المطلوبة
- سيعرض الإجازة في 2026-01-12

### 3. في Postman:

1. اختر **POST** (ليس GET)
2. URL: `{{base_url}}/api/chefs/1/availability-calendar`
3. Headers:
   - `Accept: application/json`
   - `Content-Type: application/json`
   - `Authorization: Bearer {{api_token}}`
4. Body (raw JSON):
```json
{
  "date": "2026-01-12",
  "chef_service_id": 3
}
```

## ملاحظات مهمة

1. **Authentication مطلوب**: يجب إرسال Bearer token في الـ header
2. **POST فقط**: الـ endpoint لا يقبل GET بعد الآن
3. **Body Parameters**: استخدم JSON body وليس query parameters
4. **Chef ID صحيح**: تأكد من استخدام ID موجود (1-6)
5. **التاريخ**: استخدم تاريخ الإجازة لرؤية النتيجة (مثلاً 2026-01-12 للطاهي 1)

## Response Structure

عند وجود إجازة في التاريخ المطلوب:

### بدون تصفية حسب الخدمة:

```json
{
  "success": true,
  "message": "تم جلب بيانات التوفر بنجاح",
  "data": {
    "chef_id": 1,
    "chef_name": "أحمد الرشيدي",
    "default_rest_hours": 2,
    "calendar_start_date": "2026-01-01",
    "calendar_end_date": "2026-01-31",
    "calendar": {
      "vacation_days": [
        {
          "date": "2026-01-12",
          "day_name": "Sunday",
          "day_name_ar": "الأحد",
          "note": "إجازة شخصية"
        }
      ]
    },
    "day_details": {
      "date": "2026-01-12",
      "is_working_day": false,
      "is_off_day": false,
      "is_vacation_day": true,
      "vacation_note": "إجازة شخصية",
      "working_hours": [],
      "bookings": [],
      "available_slots": []
    },
    "services": [...]
  }
}
```

### مع تصفية حسب الخدمة (chef_service_id):

```json
{
  "success": true,
  "message": "تم جلب بيانات التوفر بنجاح",
  "data": {
    "chef_id": 1,
    "chef_name": "أحمد الرشيدي",
    "service_id": 3,
    "service_name": "طبخ منزلي",
    "min_hours": 2,
    "rest_hours_required": 2,
    "default_rest_hours": 2,
    "calendar_start_date": "2026-01-01",
    "calendar_end_date": "2026-01-31",
    "calendar": {
      "vacation_days": [
        {
          "date": "2026-01-12",
          "day_name": "Sunday",
          "day_name_ar": "الأحد",
          "note": "إجازة شخصية"
        }
      ]
    },
    "day_details": {
      "date": "2026-01-12",
      "is_working_day": false,
      "is_off_day": false,
      "is_vacation_day": true,
      "vacation_note": "إجازة شخصية",
      "working_hours": [],
      "bookings": [],
      "available_slots": []
    }
  }
}
```

**ملاحظة**: عند إرسال `chef_service_id`:
- تظهر تفاصيل الخدمة مباشرة بعد معلومات الطاهي (service_id, service_name, min_hours, rest_hours_required)
- لا يتم إرجاع مصفوفة `services` (لأنك تصفي حسب خدمة واحدة)
