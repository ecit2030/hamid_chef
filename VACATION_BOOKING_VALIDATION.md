# Vacation Booking Validation Feature

## Overview
تم إضافة نظام validation متقدم لإدارة الإجازات في لوحة الشيف. النظام يمنع إنشاء أو تعديل إجازات في تواريخ تحتوي على حجوزات نشطة.

## Features Implemented

### 1. Validation عند إضافة إجازة جديدة
- يتحقق النظام من وجود حجوزات نشطة (pending أو accepted) في التاريخ المحدد
- إذا وجدت حجوزات، يعرض رسالة تفصيلية تحتوي على:
  - اسم العميل
  - اسم الخدمة
  - وقت الحجز
- الرسالة توضح أن العميل يجب أن يلغي أو يؤجل الحجز أولاً

### 2. Validation عند تعديل إجازة موجودة
- نفس التحقق يتم عند تغيير تاريخ الإجازة
- إذا لم يتغير التاريخ، لا يتم التحقق (لتحسين الأداء)

### 3. عرض رسائل الخطأ المحسّن
- رسائل الخطأ تظهر في صندوق مميز بلون أحمر
- النص يدعم أسطر متعددة (whitespace-pre-line)
- التصميم متوافق مع الوضع الليلي

### 4. رسائل النجاح
- رسالة نجاح خضراء عند إضافة/تعديل/حذف إجازة
- الرسالة تظهر في أعلى الصفحة

### 5. تحسين عرض التواريخ
- التواريخ تعرض بالعربية (الأرقام إنجليزية، الأشهر والأيام عربية)
- مثال: "15 يناير 2025"
- أسماء الأيام بالعربية: "الأحد، الإثنين، الثلاثاء..."

## Technical Implementation

### Backend Changes

#### VacationController.php
```php
// في store() و update()
$bookings = Booking::where('chef_id', $chef->id)
    ->whereDate('date', $validated['date'])
    ->whereIn('booking_status', ['pending', 'accepted'])
    ->with(['customer:id,first_name,last_name', 'service:id,name'])
    ->get();

if ($bookings->isNotEmpty()) {
    // بناء رسالة تفصيلية مع معلومات كل حجز
    $bookingDetails = $bookings->map(function($booking) {
        // ...
    })->join("\n");
    
    return back()->withErrors(['date' => $errorMessage]);
}
```

### Frontend Changes

#### Create.vue & Edit.vue
```vue
<!-- عرض رسالة الخطأ المحسّن -->
<div v-if="form.errors.date" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
  <p class="text-sm text-red-600 dark:text-red-400 whitespace-pre-line">{{ form.errors.date }}</p>
</div>
```

#### Index.vue
```javascript
// أسماء الأشهر والأيام بالعربية
const arabicMonths = ['يناير', 'فبراير', 'مارس', ...]
const arabicDays = ['الأحد', 'الإثنين', 'الثلاثاء', ...]

const formatDate = (date) => {
  const d = new Date(date)
  const day = d.getDate()
  const month = arabicMonths[d.getMonth()]
  const year = d.getFullYear()
  return `${day} ${month} ${year}`
}
```

## User Experience

### سيناريو 1: محاولة إضافة إجازة في يوم به حجز
1. الشيف يحاول إضافة إجازة في 15 يناير
2. يوجد حجز مع "أحمد محمد" - "خدمة الطبخ المنزلي" في الساعة 02:00 PM
3. النظام يعرض رسالة:
```
لا يمكن إضافة إجازة في هذا التاريخ. لديك الحجوزات التالية:

• حجز مع أحمد محمد - خدمة الطبخ المنزلي في الساعة 02:00 PM

يجب على العميل إلغاء أو تأجيل الحجز أولاً.
```

### سيناريو 2: إضافة إجازة بنجاح
1. الشيف يختار تاريخ ليس به حجوزات
2. يضيف ملاحظة (اختياري)
3. يضغط حفظ
4. رسالة نجاح: "تم إضافة الإجازة بنجاح"
5. يتم توجيهه لصفحة قائمة الإجازات

## Files Modified

### Backend
- `app/Http/Controllers/Chef/VacationController.php`
  - Added booking conflict validation in `store()` method
  - Added booking conflict validation in `update()` method
  - Added Carbon import for date handling

### Frontend
- `resources/js/Pages/Chef/Vacations/Index.vue`
  - Added success message display
  - Changed date formatting to Arabic
  - Changed day names to Arabic
  
- `resources/js/Pages/Chef/Vacations/Create.vue`
  - Enhanced error message display with styled box
  - Added whitespace-pre-line for multi-line errors
  
- `resources/js/Pages/Chef/Vacations/Edit.vue`
  - Enhanced error message display with styled box
  - Added whitespace-pre-line for multi-line errors

## Testing

### Manual Testing Steps
1. Login as chef: chef.ahmed@example.com / password123
2. Navigate to Vacations page
3. Try to add vacation on a date with existing booking
4. Verify error message shows booking details
5. Try to add vacation on a date without bookings
6. Verify success message appears
7. Try to edit vacation to a date with bookings
8. Verify error message appears
9. Delete a vacation
10. Verify success message appears

### Database Seeding
```bash
php artisan migrate:fresh --seed
```
This will create test bookings that can be used to test the validation.

## Future Enhancements
- Add ability to view conflicting bookings in a modal
- Add option to automatically notify customers about vacation dates
- Add bulk vacation creation (date range)
- Add vacation templates (e.g., "Summer vacation", "Ramadan break")
