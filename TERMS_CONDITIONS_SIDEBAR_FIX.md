# إصلاح عدم ظهور الشريط الجانبي في صفحات الشروط والأحكام

## المشكلة

كانت صفحات الشروط والأحكام (Index, Create, Edit) لا تعرض الشريط الجانبي (AppSidebar) مثل بقية صفحات لوحة التحكم.

## السبب

كانت صفحات الشروط والأحكام تستخدم `AdminLayout` من المسار الخاطئ:

```javascript
import AdminLayout from "@/Layouts/AdminLayout.vue";
```

بينما يجب استخدام `AdminLayout` من المسار الصحيح الذي يحتوي على الشريط الجانبي:

```javascript
import AdminLayout from "@/Components/layout/AdminLayout.vue";
```

## الحل

تم تحديث جميع صفحات الشروط والأحكام الثلاث لاستخدام المسار الصحيح:

### الملفات المعدلة:

1. `resources/js/Pages/Admin/TermsAndConditions/Index.vue`
2. `resources/js/Pages/Admin/TermsAndConditions/Create.vue`
3. `resources/js/Pages/Admin/TermsAndConditions/Edit.vue`

### التغيير:

```diff
- import AdminLayout from "@/Layouts/AdminLayout.vue";
+ import AdminLayout from "@/Components/layout/AdminLayout.vue";
```

## النتيجة

✅ الآن جميع صفحات الشروط والأحكام تعرض الشريط الجانبي (AppSidebar) بشكل صحيح
✅ التصميم متسق مع بقية صفحات لوحة التحكم (التقارير، الحجوزات، إلخ)
✅ تم إعادة بناء المشروع بنجاح

## ملاحظات

- يوجد نوعان من `AdminLayout` في المشروع:
    - `@/Layouts/AdminLayout.vue` - تخطيط بسيط بدون شريط جانبي
    - `@/Components/layout/AdminLayout.vue` - تخطيط كامل مع الشريط الجانبي
- يجب استخدام `@/Components/layout/AdminLayout.vue` لجميع صفحات لوحة التحكم الإدارية
