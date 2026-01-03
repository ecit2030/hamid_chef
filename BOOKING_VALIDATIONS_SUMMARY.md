# ملخص Validations لـ API الحجز

## نعم! ✅ الـ API يحتوي على validations شاملة جداً

---

## 1. Basic Validations (في StoreBookingRequest)

### الحقول المطلوبة (Required):
```php
✅ chef_id - مطلوب ويجب أن يكون موجود في جدول chefs
✅ chef_service_id - مطلوب ويجب أن يكون موجود في جدول chef_services
✅ date - مطلوب ويجب أن يكون تاريخ اليوم أو بعده
✅ start_time - مطلوب بصيغة H:i (مثل: 14:00)
✅ hours_count - مطلوب، عدد صحيح من 1 إلى 12 ساعة
✅ number_of_guests - مطلوب، عدد صحيح من 1 إلى 50 ضيف
✅ service_type - مطلوب، إما hourly أو package
✅ unit_price - مطلوب، رقم من 0 إلى 9,999,999.99
✅ total_amount - مطلوب، رقم من 0 إلى 9,999,999.99
```

### الحقول الاختيارية (Optional):
```php
⚪ customer_id - اختياري (يتم تعيينه تلقائياً للمستخدم الحالي)
⚪ address_id - اختياري، يجب أن يكون موجود في جدول addresses
⚪ extra_guests_count - اختياري، من 0 إلى 20
⚪ extra_guests_amount - اختياري، رقم من 0 إلى 9,999,999.99
⚪ commission_amount - اختياري
⚪ payment_status - اختياري (pending, paid, refunded, failed)
⚪ booking_status - اختياري (pending, accepted, rejected, ...)
⚪ notes - اختياري، نص حتى 1000 حرف
⚪ is_active - اختياري، boolean
```

---

## 2. Custom Business Validations

### A. Booking Time Validations (validateBookingTime)

#### ✅ ساعات العمل المسموحة:
```php
❌ لا يمكن الحجز قبل 8 صباحاً
❌ لا يمكن الحجز بعد 10 مساءً

مثال:
- start_time: 07:00 ❌ (قبل 8 صباحاً)
- start_time: 09:00, hours_count: 14 ❌ (ينتهي 23:00، بعد 10 مساءً)
- start_time: 10:00, hours_count: 8 ✅ (ينتهي 18:00)
```

#### ✅ الحد الأقصى للحجز المسبق:
```php
❌ لا يمكن الحجز لأكثر من 90 يوم مقدماً (قابل للتعديل في config)

مثال:
- date: 2026-04-01 (100 يوم من الآن) ❌
- date: 2026-02-15 (45 يوم من الآن) ✅
```

### B. Service Type Validations (validateServiceType)

```php
✅ إذا كان service_type = 'package':
   - يجب أن يكون hours_count >= 2 ساعات على الأقل

مثال:
- service_type: 'package', hours_count: 1 ❌
- service_type: 'package', hours_count: 3 ✅
- service_type: 'hourly', hours_count: 1 ✅
```

### C. Pricing Validations (validatePricing)

#### ✅ التحقق من صحة المبلغ الإجمالي:
```php
للخدمة بالساعة (hourly):
expected_total = (unit_price × hours_count) + extra_guests_amount

للباقة (package):
expected_total = unit_price + extra_guests_amount

❌ إذا كان الفرق > 0.01 ريال، يرفض الطلب

مثال:
- unit_price: 100, hours_count: 3, extra_guests_amount: 50
- expected: (100 × 3) + 50 = 350
- total_amount: 350 ✅
- total_amount: 360 ❌ (فرق 10 ريال)
```

#### ✅ التحقق من الضيوف الإضافيين:
```php
❌ إذا كان extra_guests_count > 0 ولكن extra_guests_amount = 0
   يجب دفع مبلغ للضيوف الإضافيين

مثال:
- extra_guests_count: 5, extra_guests_amount: 0 ❌
- extra_guests_count: 5, extra_guests_amount: 100 ✅
- extra_guests_count: 0, extra_guests_amount: 0 ✅
```

---

## 3. Conflict Detection Validations

### في BookingService::createWithConflictCheck()

```php
✅ التحقق من التعارضات مع الحجوزات الموجودة
✅ مراعاة ساعات الراحة (rest_hours_required)
✅ التحقق من ساعات العمل (working_hours)
✅ التحقق من الإجازات (vacations)

يتم رفض الحجز إذا:
❌ يتعارض مع حجز موجود
❌ يتعارض مع فترة راحة حجز موجود
❌ خارج ساعات عمل الطاهي
❌ في يوم إجازة الطاهي
```

---

## 4. Status Validations (في Controller)

### A. إلغاء من العميل (cancelByCustomer):
```php
✅ يمكن الإلغاء فقط إذا كانت الحالة:
   - pending (قيد الانتظار)
   - accepted (مقبول)

❌ لا يمكن الإلغاء إذا كانت الحالة:
   - rejected
   - cancelled_by_customer
   - cancelled_by_chef
   - completed
```

### B. قبول من الطاهي (accept):
```php
✅ يمكن القبول فقط إذا كانت الحالة:
   - pending (قيد الانتظار)

❌ لا يمكن القبول في أي حالة أخرى
```

### C. رفض من الطاهي (reject):
```php
✅ يمكن الرفض فقط إذا كانت الحالة:
   - pending (قيد الانتظار)

❌ لا يمكن الرفض في أي حالة أخرى
```

### D. إلغاء من الطاهي (cancelByChef):
```php
✅ يمكن الإلغاء فقط إذا كانت الحالة:
   - accepted (مقبول)

❌ لا يمكن الإلغاء في أي حالة أخرى
```

### E. إكمال الحجز (complete):
```php
✅ يمكن الإكمال فقط إذا كانت الحالة:
   - accepted (مقبول)

❌ لا يمكن الإكمال في أي حالة أخرى
```

---

## 5. Authorization Validations

```php
✅ Authentication مطلوب (auth:sanctum)
✅ Policy checks لكل action:
   - view: يمكن للعميل أو الطاهي رؤية حجزه فقط
   - update: يمكن للعميل تحديث حجزه فقط
   - delete: يمكن للعميل أو الطاهي إلغاء الحجز
   - accept/reject: للطاهي فقط
   - complete: للطاهي فقط
   - cancelByCustomer: للعميل فقط
   - cancelByChef: للطاهي فقط
```

---

## 6. Additional Validations في checkAvailability

```php
✅ date - مطلوب، تاريخ اليوم أو بعده
✅ start_time - مطلوب، صيغة H:i
✅ hours_count - مطلوب، من 1 إلى 12
✅ chef_service_id - اختياري، يجب أن يكون موجود
✅ exclude_booking_id - اختياري، يجب أن يكون موجود
```

---

## مثال على Response عند فشل Validation

### 1. Basic Validation Error:
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "chef_id": ["حقل الطاهي مطلوب"],
    "date": ["يجب أن يكون التاريخ اليوم أو بعده"],
    "hours_count": ["يجب أن يكون عدد الساعات بين 1 و 12"]
  }
}
```

### 2. Business Logic Error:
```json
{
  "success": false,
  "message": "فشل إنشاء الحجز",
  "errors": {
    "start_time": ["لا يمكن الحجز قبل الساعة 8 صباحاً"],
    "hours_count": ["وقت انتهاء الحجز يتجاوز الساعة 10 مساءً"]
  }
}
```

### 3. Conflict Error:
```json
{
  "success": false,
  "message": "يوجد تعارض مع حجز موجود",
  "errors": {
    "booking": ["الوقت المطلوب غير متاح"]
  },
  "conflicting_bookings": [
    {
      "id": 123,
      "start_time": "14:00",
      "end_time": "16:00",
      "blocked_until": "18:00"
    }
  ]
}
```

### 4. Status Error:
```json
{
  "success": false,
  "message": "لا يمكن للعميل إلغاء الحجز في حالته الحالية",
  "errors": {
    "booking_status": [
      "الحالة الحالية لا تسمح بالإلغاء",
      "current_status: completed"
    ]
  }
}
```

---

## الخلاصة

✅ **نعم، الـ API يحتوي على validations شاملة جداً!**

### يشمل:
1. ✅ Basic field validations (required, format, range)
2. ✅ Business logic validations (working hours, advance booking)
3. ✅ Pricing validations (total amount calculation)
4. ✅ Conflict detection (overlapping bookings, rest hours)
5. ✅ Status validations (allowed transitions)
6. ✅ Authorization validations (policies)
7. ✅ Custom error messages (Arabic & English)

### الفوائد:
- 🛡️ حماية من البيانات الخاطئة
- 🚫 منع التعارضات
- ✅ ضمان جودة البيانات
- 📝 رسائل خطأ واضحة
- 🔒 أمان عالي
