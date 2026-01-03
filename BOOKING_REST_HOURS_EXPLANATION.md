# شرح ساعات الراحة في الحجوزات (Booking Rest Hours)

## السؤال: هل الحجوزات المرجعة تشمل ساعات الراحة؟

**الإجابة: نعم، بالتأكيد! ✅**

كل حجز يُرجع يحتوي على معلومات كاملة عن ساعات الراحة والوقت المحجوز الفعلي.

---

## كيف تعمل ساعات الراحة؟

### 1. في الـ Response - حقل `bookings`

كل حجز في `day_details.bookings` يحتوي على:

```json
{
  "id": 123,
  "start_time": "10:00",           // وقت بداية الحجز
  "end_time": "12:00",             // وقت نهاية الحجز (start_time + hours_count)
  "hours_count": 2,                // عدد ساعات الحجز الفعلية
  "rest_hours_required": 3,        // ساعات الراحة المطلوبة (من الخدمة)
  "blocked_until": "15:00",        // الوقت المحجوز حتى (end_time + rest_hours_required)
  "total_blocked_hours": 5,        // إجمالي الساعات المحجوزة (2 + 3)
  "booking_status": "accepted",
  "service": {
    "id": 5,
    "name": "طبخ حفلات",
    "rest_hours_required": 3       // ساعات الراحة من الخدمة
  }
}
```

### شرح الحقول:

| الحقل | الوصف | المثال |
|------|-------|--------|
| `start_time` | وقت بداية الحجز | 10:00 |
| `end_time` | وقت نهاية الحجز | 12:00 (10:00 + 2 ساعات) |
| `hours_count` | عدد ساعات الحجز | 2 ساعات |
| `rest_hours_required` | ساعات الراحة المطلوبة | 3 ساعات |
| `blocked_until` | الوقت المحجوز حتى | 15:00 (12:00 + 3 ساعات راحة) |
| `total_blocked_hours` | إجمالي الوقت المحجوز | 5 ساعات (2 + 3) |

---

## مثال عملي

### السيناريو:
- طاهي لديه حجز من **10:00 صباحاً** لمدة **2 ساعات**
- الخدمة تتطلب **3 ساعات راحة** بعد الانتهاء

### الحساب:
```
start_time = 10:00
hours_count = 2
end_time = 10:00 + 2 = 12:00

rest_hours_required = 3 (من الخدمة)
blocked_until = 12:00 + 3 = 15:00

total_blocked_hours = 2 + 3 = 5 ساعات
```

### النتيجة:
- ✅ الحجز من **10:00 إلى 12:00** (ساعتان)
- ⏰ فترة الراحة من **12:00 إلى 15:00** (3 ساعات)
- 🚫 **الطاهي غير متاح من 10:00 إلى 15:00** (5 ساعات إجمالي)
- ✅ الطاهي متاح مرة أخرى من **15:00**

---

## في الـ API Response

### مثال كامل:

```json
{
  "success": true,
  "data": {
    "chef_id": 1,
    "chef_name": "أحمد الرشيدي",
    "day_details": {
      "date": "2026-01-15",
      "is_working_day": true,
      "working_hours": [
        {
          "start_time": "09:00:00",
          "end_time": "22:00:00"
        }
      ],
      "bookings": [
        {
          "id": 1,
          "start_time": "10:00",
          "end_time": "12:00",
          "hours_count": 2,
          "rest_hours_required": 3,
          "blocked_until": "15:00",
          "total_blocked_hours": 5,
          "service": {
            "id": 5,
            "name": "طبخ حفلات",
            "rest_hours_required": 3
          }
        }
      ],
      "available_slots": [
        {
          "start_time": "09:00",
          "end_time": "10:00",
          "duration_hours": 1
        },
        {
          "start_time": "15:00",
          "end_time": "22:00",
          "duration_hours": 7
        }
      ]
    }
  }
}
```

### ملاحظات على المثال:

1. **الحجز الموجود:**
   - من 10:00 إلى 12:00 (ساعتان)
   - محجوز حتى 15:00 (مع الراحة)

2. **الفترات المتاحة:**
   - ✅ من 09:00 إلى 10:00 (قبل الحجز)
   - 🚫 من 10:00 إلى 15:00 (محجوز + راحة)
   - ✅ من 15:00 إلى 22:00 (بعد انتهاء الراحة)

---

## كيف يتم حساب التوفر؟

### في `calendar.partially_booked_days` و `fully_booked_days`:

الكود يحسب الوقت المحجوز **بما في ذلك ساعات الراحة**:

```php
protected function calculateBookedMinutesWithServiceRest(Collection $bookings): int
{
    return $bookings->sum(function($booking) {
        // Get rest hours from service or use default
        $restHours = $booking->service->rest_hours_required ?? $this->defaultRestHours;
        
        // Total blocked time = booking hours + rest hours
        return ($booking->hours_count + $restHours) * 60;
    });
}
```

### مثال:
- ساعات العمل: 13 ساعة (09:00 - 22:00)
- حجز واحد: 2 ساعات + 3 ساعات راحة = 5 ساعات محجوزة
- الوقت المتاح: 13 - 5 = 8 ساعات
- نسبة التوفر: (8 / 13) × 100 = **61.5%**

---

## الفرق بين الحقول

### `hours_count` vs `total_blocked_hours`

| الحقل | المعنى | مثال |
|------|--------|------|
| `hours_count` | ساعات الحجز الفعلية فقط | 2 ساعات |
| `total_blocked_hours` | الحجز + الراحة | 5 ساعات (2 + 3) |

### `end_time` vs `blocked_until`

| الحقل | المعنى | مثال |
|------|--------|------|
| `end_time` | وقت انتهاء الحجز | 12:00 |
| `blocked_until` | وقت انتهاء الحجز + الراحة | 15:00 |

---

## لماذا ساعات الراحة مهمة؟

1. **منع التعارضات**: لا يمكن حجز طاهي خلال فترة الراحة
2. **جودة الخدمة**: الطاهي يحتاج وقت للراحة والتحضير
3. **حساب دقيق للتوفر**: نسبة التوفر تأخذ في الاعتبار الراحة
4. **تخطيط أفضل**: العميل يعرف متى الطاهي متاح فعلياً

---

## مثال من الواقع

### حجوزات يوم كامل:

```json
{
  "bookings": [
    {
      "id": 1,
      "start_time": "10:00",
      "end_time": "12:00",
      "hours_count": 2,
      "rest_hours_required": 2,
      "blocked_until": "14:00",
      "total_blocked_hours": 4
    },
    {
      "id": 2,
      "start_time": "15:00",
      "end_time": "18:00",
      "hours_count": 3,
      "rest_hours_required": 3,
      "blocked_until": "21:00",
      "total_blocked_hours": 6
    }
  ],
  "available_slots": [
    {
      "start_time": "09:00",
      "end_time": "10:00",
      "duration_hours": 1
    },
    {
      "start_time": "14:00",
      "end_time": "15:00",
      "duration_hours": 1
    },
    {
      "start_time": "21:00",
      "end_time": "22:00",
      "duration_hours": 1
    }
  ]
}
```

### الجدول الزمني:

| الوقت | الحالة |
|-------|--------|
| 09:00 - 10:00 | ✅ متاح (1 ساعة) |
| 10:00 - 12:00 | 🔴 حجز #1 (2 ساعات) |
| 12:00 - 14:00 | ⏰ راحة #1 (2 ساعات) |
| 14:00 - 15:00 | ✅ متاح (1 ساعة) |
| 15:00 - 18:00 | 🔴 حجز #2 (3 ساعات) |
| 18:00 - 21:00 | ⏰ راحة #2 (3 ساعات) |
| 21:00 - 22:00 | ✅ متاح (1 ساعة) |

---

## الخلاصة

✅ **نعم، الحجوزات المرجعة تشمل ساعات الراحة بالكامل**

كل حجز يحتوي على:
- `hours_count`: ساعات الحجز الفعلية
- `rest_hours_required`: ساعات الراحة المطلوبة
- `blocked_until`: الوقت المحجوز الفعلي (حجز + راحة)
- `total_blocked_hours`: إجمالي الوقت المحجوز

هذا يضمن:
- 🎯 حساب دقيق للتوفر
- 🚫 منع التعارضات
- ✅ تخطيط أفضل للعملاء
- 💯 جودة خدمة أعلى
