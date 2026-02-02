# إصلاح مسار إلغاء الحجز من العميل

## التاريخ: 1 فبراير 2026

## المشكلة

عند محاولة استخدام API لإلغاء الحجز من العميل، كان يظهر خطأ 404:

```json
{
    "success": false,
    "message": "الصفحة المطلوبة غير موجودة",
    "error_code": "ROUTE_NOT_FOUND",
    "status_code": 404
}
```

**المسار**: `POST /api/bookings/{id}/cancel-by-customer`

## السبب

المشكلة كانت في دالة `cancelByCustomer` في `BookingController`:

- الدالة كانت تستخدم `$id` كمعامل بدلاً من `$booking`
- Laravel Route Model Binding يتطلب أن يكون اسم المعامل مطابقاً لاسم المعامل في المسار `{booking}`

## الحل

تم تعديل دالة `cancelByCustomer` في `app/Http/Controllers/Api/BookingController.php`:

### قبل التعديل:

```php
public function cancelByCustomer(BookingService $bookingService, Request $request, $id)
{
    // ...
    $booking = $bookingService->findForUser($id, $request->user()->id);
    // ...
    $cancelled = $bookingService->cancel($id, 'cancelled_by_customer', $validated['cancellation_reason']);
}
```

### بعد التعديل:

```php
public function cancelByCustomer(BookingService $bookingService, Request $request, $booking)
{
    // ...
    $bookingModel = $bookingService->findForUser($booking, $request->user()->id);
    // ...
    $cancelled = $bookingService->cancel($booking, 'cancelled_by_customer', $validated['cancellation_reason']);
}
```

## التغييرات

1. تغيير معامل الدالة من `$id` إلى `$booking` لدعم Route Model Binding
2. تغيير اسم المتغير الداخلي من `$booking` إلى `$bookingModel` لتجنب التعارض
3. تحديث جميع الاستخدامات داخل الدالة

## الاختبار

بعد التعديل، يمكن استخدام API بنجاح:

```bash
POST /api/bookings/{id}/cancel-by-customer
Content-Type: application/json
Authorization: Bearer {token}

{
  "cancellation_reason": "سبب الإلغاء هنا (10-500 حرف)"
}
```

### الاستجابة الناجحة:

```json
{
    "success": true,
    "message": "تم إلغاء الحجز بنجاح"
}
```

## الملفات المُعدّلة

- `app/Http/Controllers/Api/BookingController.php`

## ملاحظات

- تم مسح الـ cache بعد التعديل: `php artisan route:clear`
- المسار يتطلب authentication (Bearer token)
- سبب الإلغاء مطلوب ويجب أن يكون بين 10-500 حرف
- يمكن للعميل إلغاء الحجز فقط إذا كانت الحالة `pending` أو `accepted`
