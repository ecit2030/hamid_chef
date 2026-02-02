# إصلاح خطأ إنشاء الحجز - Booking Creation Fix

## المشكلة | Problem

عند محاولة إنشاء حجز جديد عبر API، كان يظهر الخطأ التالي:

```
ArgumentCountError: Too few arguments to function App\DTOs\BookingDTO::__construct(),
22 passed but at least 23 expected
```

When trying to create a new booking via API, the following error appeared:

```
ArgumentCountError: Too few arguments to function App\DTOs\BookingDTO::__construct(),
22 passed but at least 23 expected
```

## السبب | Root Cause

عند إضافة حقل `cancellation_reason` إلى جدول الحجوزات والـ DTO، لم يتم تحديث الـ Controller لتمرير هذا المعامل عند إنشاء BookingDTO جديد.

When the `cancellation_reason` field was added to the bookings table and DTO, the Controller was not updated to pass this parameter when creating a new BookingDTO.

## الحل | Solution

تم تحديث `BookingController::store()` لتمرير `cancellation_reason` (بقيمة null) عند إنشاء حجز جديد:

Updated `BookingController::store()` to pass `cancellation_reason` (with null value) when creating a new booking:

```php
$bookingDTO = new BookingDTO(
    null,                                    // id
    $validated['customer_id'],               // customer_id
    $validated['chef_id'],                   // chef_id
    $validated['chef_service_id'],           // chef_service_id
    $validated['address_id'] ?? null,        // address_id
    $validated['date'],                      // date
    $validated['start_time'],                // start_time
    $validated['hours_count'],               // hours_count
    $validated['number_of_guests'],          // number_of_guests
    $validated['service_type'],              // service_type
    $validated['unit_price'],                // unit_price
    $validated['extra_guests_count'] ?? 0,   // extra_guests_count
    $validated['extra_guests_amount'] ?? 0,  // extra_guests_amount
    $validated['total_amount'],              // total_amount
    $validated['commission_amount'] ?? 0,    // commission_amount
    'pending',                               // payment_status
    'pending',                               // booking_status
    null,                                    // rejection_reason
    null,                                    // cancellation_reason ← تمت الإضافة
    $validated['notes'] ?? null,             // notes
    true,                                    // is_active
    $validated['created_by'],                // created_by
    null                                     // updated_by
);
```

## الملفات المعدلة | Modified Files

- `app/Http/Controllers/Api/BookingController.php`

## الاختبار | Testing

يمكن الآن إنشاء حجز جديد بنجاح باستخدام:

You can now successfully create a new booking using:

```bash
POST /api/bookings
Authorization: Bearer {token}
Content-Type: application/json

{
    "chef_id": 1,
    "chef_service_id": 3,
    "date": "2026-04-08",
    "start_time": "13:00",
    "hours_count": 3,
    "number_of_guests": 2,
    "service_type": "package",
    "unit_price": 350.00,
    "total_amount": 350.00,
    "address_id": 19
}
```

## ملاحظات | Notes

- حقل `cancellation_reason` يُستخدم فقط عند إلغاء الحجز من العميل
- حقل `rejection_reason` يُستخدم فقط عند رفض الحجز من الطاهي
- عند إنشاء حجز جديد، كلا الحقلين يكونان `null`

- `cancellation_reason` field is only used when customer cancels the booking
- `rejection_reason` field is only used when chef rejects the booking
- When creating a new booking, both fields are `null`

---

**التاريخ | Date:** 2026-02-01
**الحالة | Status:** ✅ تم الإصلاح | Fixed
