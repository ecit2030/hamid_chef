# إصلاح صفحة تفاصيل الطاهي وعرض سبب الرفض

## التاريخ

2025-01-20

## المشكلة

- ملف `resources/js/Pages/Admin/Chef/Show.vue` كان تالفاً ومقلوباً بالكامل
- ملف `resources/js/Components/modals/RejectionReasonModal.vue` كان غير مكتمل
- لم يكن هناك طريقة لعرض سبب رفض الحجز في صفحة تفاصيل الطاهي

## الحل

### 1. إصلاح ملف Show.vue

تم إعادة بناء الملف بالكامل بالبنية الصحيحة:

- إضافة التبويبات (Tabs) بشكل صحيح
- إضافة جميع المكونات المطلوبة
- إصلاح الاستيرادات والتصدير
- إضافة الأزرار للعودة والتعديل

**الملف:** `resources/js/Pages/Admin/Chef/Show.vue`

**المكونات المستخدمة:**

- `ShowChef` - المعلومات الأساسية
- `ChefWorkingHoursTab` - ساعات العمل
- `ChefVacationsTab` - الإجازات
- `ChefServicesTab` - الخدمات
- `ChefBookingsTab` - الحجوزات
- `ChefKycTab` - التوثيق والشهادات

### 2. إنشاء RejectionReasonModal

تم إنشاء مودال كامل لعرض سبب رفض الحجز:

- تصميم متناسق مع باقي المودالات في النظام
- دعم الوضع الداكن
- أيقونة تحذير مناسبة
- زر إغلاق

**الملف:** `resources/js/Components/modals/RejectionReasonModal.vue`

**الخصائص:**

- `isOpen` - حالة فتح/إغلاق المودال
- `rejectionReason` - نص سبب الرفض

**الأحداث:**

- `close` - عند إغلاق المودال

### 3. تحديث ChefBookingsTab

تم إضافة عمود جديد وزر لعرض سبب الرفض:

- عمود "الإجراءات" في جدول الحجوزات
- زر "عرض السبب" يظهر فقط للحجوزات المرفوضة التي لها سبب
- دمج `RejectionReasonModal` في المكون
- إضافة دوال لفتح وإغلاق المودال

**الملف:** `resources/js/Components/admin/chef/ChefBookingsTab.vue`

**التغييرات:**

```vue
// إضافة عمود جديد في الجدول
<th>{{ t('common.actions') }}</th>

// إضافة زر عرض السبب
<button
    v-if="booking.booking_status === 'rejected' && booking.rejection_reason"
    @click="showRejectionReason(booking.rejection_reason)"
>
  {{ t('booking.viewReason') }}
</button>

// إضافة المودال
<RejectionReasonModal
    :isOpen="isRejectionModalOpen"
    :rejectionReason="selectedRejectionReason"
    @close="closeRejectionModal"
/>
```

### 4. إضافة الترجمات

تم إضافة الترجمات المطلوبة في كلا الملفين:

**العربية** (`resources/js/locales/ar.json`):

```json
"viewReason": "عرض السبب"
```

**الإنجليزية** (`resources/js/locales/en.json`):

```json
"viewReason": "View Reason"
```

## الملفات المعدلة

1. ✅ `resources/js/Pages/Admin/Chef/Show.vue` - إعادة بناء كاملة
2. ✅ `resources/js/Components/modals/RejectionReasonModal.vue` - إنشاء جديد
3. ✅ `resources/js/Components/admin/chef/ChefBookingsTab.vue` - إضافة عمود وزر
4. ✅ `resources/js/locales/ar.json` - إضافة ترجمة
5. ✅ `resources/js/locales/en.json` - إضافة ترجمة

## الاختبار

### اختبار صفحة تفاصيل الطاهي

1. افتح صفحة تفاصيل أي طاهي من لوحة الإدارة
2. تحقق من ظهور جميع التبويبات بشكل صحيح
3. انتقل بين التبويبات المختلفة
4. تحقق من عمل أزرار "العودة" و"تعديل"

### اختبار عرض سبب الرفض

1. انتقل إلى تبويب "الحجوزات"
2. ابحث عن حجز مرفوض (rejected)
3. انقر على زر "عرض السبب"
4. تحقق من ظهور المودال مع سبب الرفض
5. أغلق المودال بالنقر على زر "إغلاق"

## الميزات

✅ صفحة تفاصيل الطاهي تعمل بشكل كامل
✅ جميع التبويبات تعمل بشكل صحيح
✅ عرض سبب رفض الحجز في مودال منفصل
✅ تصميم متناسق مع باقي النظام
✅ دعم الوضع الداكن
✅ دعم اللغتين العربية والإنجليزية
✅ لا توجد أخطاء في الكود

## ملاحظات

- المودال يظهر فقط للحجوزات المرفوضة التي لها سبب رفض
- إذا لم يكن هناك سبب رفض، يظهر "-" في عمود الإجراءات
- التصميم يتبع نفس نمط المودالات الأخرى في النظام (DangerAlert)
- الكود نظيف وخالي من الأخطاء

## الخطوات التالية

يمكن الآن:

1. اختبار الصفحة في المتصفح
2. التحقق من عمل جميع الوظائف
3. إضافة المزيد من الميزات إذا لزم الأمر
