# إصلاح علاقة Bookings في User Model

## المشكلة
```
BadMethodCallException
Call to undefined method App\Models\User::bookings()
```

عند محاولة الوصول إلى صفحة تقرير العملاء (`/admin/reports/customers`)، ظهر خطأ يفيد بأن `User` model لا يحتوي على method `bookings()`.

## السبب
كان `UserRepository` يستخدم علاقة `bookings` على `User` model في عدة أماكن:
- `withCount('bookings')`
- `withSum('bookings', 'total_amount')`
- `whereHas('bookings')`

لكن هذه العلاقة لم تكن معرّفة في `User` model.

## الحل
تم إضافة علاقة `bookings()` إلى `User` model:

```php
public function bookings()
{
    return $this->hasMany(Booking::class, 'customer_id');
}
```

## التفاصيل التقنية

### نوع العلاقة
- **One-to-Many (hasMany)**: مستخدم واحد يمكن أن يكون لديه عدة حجوزات
- **Foreign Key**: `customer_id` في جدول `bookings`
- **Local Key**: `id` في جدول `users`

### الاستخدامات في الكود
هذه العلاقة تُستخدم في `UserRepository` في:

1. **getCustomersForReport()** - للحصول على عدد الحجوزات ومجموع المبالغ
2. **getCustomersStats()** - لحساب العملاء النشطين
3. **getCustomersForExport()** - لتصدير بيانات العملاء مع حجوزاتهم

## الملفات المعدلة
- `app/Models/User.php` - إضافة علاقة `bookings()`

## الاختبار
يمكنك اختبار الإصلاح من خلال:
1. الدخول إلى لوحة تحكم الأدمن
2. الذهاب إلى "التقارير" > "تقرير العملاء"
3. التحقق من أن الصفحة تعمل بدون أخطاء
4. التحقق من ظهور عدد الحجوزات والمبالغ لكل عميل

## ملاحظات
- العلاقة تربط `User` (customer) بـ `Booking` عبر `customer_id`
- العلاقة تدعم eager loading و lazy loading
- يمكن استخدام العلاقة مع `withCount`, `withSum`, `whereHas`, إلخ
- تم الحفاظ على Design Patterns الموجودة (Repository Pattern)
