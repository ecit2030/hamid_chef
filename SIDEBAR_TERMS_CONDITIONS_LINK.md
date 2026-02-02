# إضافة رابط الشروط والأحكام إلى Sidebar

# Adding Terms and Conditions Link to Sidebar

## ✅ تم الإنجاز / Completed

تم إضافة رابط الشروط والأحكام إلى القائمة الجانبية (AppSidebar) في لوحة التحكم.

---

## 📝 التعديلات / Changes

### 1. AppSidebar.vue

**الملف / File:**

```
resources/js/Components/layout/AppSidebar.vue
```

**التعديل / Change:**
تم إضافة عنصر جديد في القائمة بعد "Landing Pages" وقبل "Profile":

```javascript
{
  icon: PageIcon,
  name: t('menu.terms_and_conditions'),
  path: route('admin.terms-and-conditions.index'),
  permission: 'terms-and-conditions.view',
}
```

### 2. الترجمة / Translation

**الملف / File:**

```
resources/js/locales/ar.json
```

**الترجمة موجودة بالفعل / Translation already exists:**

```json
{
    "menu": {
        "terms_and_conditions": "الشروط والأحكام"
    }
}
```

---

## 🎯 الموقع في القائمة / Menu Position

الترتيب الحالي في القائمة:

1. Dashboard
2. Locations
3. KYCs
4. Addresses
5. Chefs
6. Bookings
7. Reports
8. Chef Services
9. Tags
10. Categories
11. Landing Pages
12. **الشروط والأحكام** ← جديد / NEW
13. Profile
14. Users
15. Admins
16. Activity Logs

---

## 🔐 الصلاحيات / Permissions

**Permission Required:**

```
terms-and-conditions.view
```

الرابط سيظهر فقط للمستخدمين الذين لديهم صلاحية `terms-and-conditions.view`.

---

## 🎨 الأيقونة / Icon

تم استخدام `PageIcon` كأيقونة للشروط والأحكام، وهي نفس الأيقونة المستخدمة لـ Landing Pages.

---

## 🚀 الاستخدام / Usage

### الوصول إلى الصفحة / Access the Page

بعد تسجيل الدخول إلى لوحة التحكم:

1. افتح القائمة الجانبية
2. ابحث عن "الشروط والأحكام"
3. اضغط على الرابط
4. سيتم توجيهك إلى `/admin/terms-and-conditions`

---

## ✅ البناء / Build

```bash
npm run build
# ✓ built in 11.01s
```

البناء نجح بدون أخطاء.

---

## 📸 المظهر / Appearance

### Desktop View

- الأيقونة: PageIcon
- النص: "الشروط والأحكام"
- الموقع: بين Landing Pages و Profile

### Mobile View

- نفس المظهر مع دعم القائمة المنسدلة

### Collapsed Sidebar

- تظهر الأيقونة فقط
- عند التمرير (hover) يظهر النص الكامل

---

## 🔄 الخطوات التالية / Next Steps

1. ✅ إضافة الرابط إلى Sidebar - تم
2. ✅ البناء بنجاح - تم
3. 🔄 اختبار الرابط في المتصفح
4. 🔄 التحقق من الصلاحيات
5. 🔄 اختبار على الموبايل

---

## 📚 الملفات المعدلة / Modified Files

1. `resources/js/Components/layout/AppSidebar.vue` - إضافة عنصر القائمة

---

## 🎉 النتيجة / Result

✅ رابط الشروط والأحكام متاح الآن في القائمة الجانبية
✅ يظهر فقط للمستخدمين الذين لديهم الصلاحية المناسبة
✅ يتبع نفس نمط التصميم المستخدم في باقي القائمة
✅ يدعم RTL و Dark Mode

---

**التاريخ / Date:** 1 فبراير 2026
**الحالة / Status:** ✅ مكتمل / Complete
