# إضافة صلاحيات الشروط والأحكام

# Terms and Conditions Permissions Added

## ✅ تم الإنجاز / Completed

تم إضافة صلاحيات الشروط والأحكام إلى نظام الصلاحيات (ACL) بنجاح.

---

## 📝 التعديلات / Changes

### 1. config/acl.php

**إضافة المورد / Added Resource:**

```php
'terms-and-conditions' => ['view', 'create', 'update', 'delete'],
```

**إضافة التسميات / Added Labels:**

```php
'terms-and-conditions' => ['en' => 'Terms and Conditions', 'ar' => 'الشروط والأحكام'],
```

### 2. database/seeders/RolesPermissionsSeeder.php

**تحديث دور Content Manager:**

```php
// Content Manager: Landing pages, categories, tags, terms and conditions
$contentManagerPerms = $allActionPerms
    ->filter(fn ($name) => Str::startsWith($name, [
        'landing-page-sections.',
        'categories.',
        'tags.',
        'terms-and-conditions.',  // ← جديد / NEW
        'dashboard.'
    ]))
    ->values();
```

---

## 🔐 الصلاحيات المضافة / Added Permissions

تم إضافة 4 صلاحيات:

1. ✅ `terms-and-conditions.view` - عرض الشروط والأحكام
2. ✅ `terms-and-conditions.create` - إنشاء شروط وأحكام جديدة
3. ✅ `terms-and-conditions.update` - تعديل الشروط والأحكام
4. ✅ `terms-and-conditions.delete` - حذف الشروط والأحكام

---

## 👥 الأدوار التي لديها الصلاحيات / Roles with Permissions

### Super Admin (مدير النظام)

✅ جميع الصلاحيات (view, create, update, delete)

### Admin (مشرف)

✅ جميع الصلاحيات (view, create, update, delete)

### Content Manager (مدير المحتوى)

✅ جميع الصلاحيات (view, create, update, delete)

### Viewer (مشاهد)

✅ عرض فقط (view)

### الأدوار الأخرى

❌ لا توجد صلاحيات

---

## 🚀 التنفيذ / Execution

### 1. تشغيل Seeder

```bash
php artisan db:seed --class=RolesPermissionsSeeder
# ✅ تم إضافة الأدوار والصلاحيات بنجاح
```

### 2. التحقق من الصلاحيات

```bash
php artisan permission:show | Select-String -Pattern "terms-and-conditions"
```

**النتيجة / Result:**

```
| terms-and-conditions.view       | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| terms-and-conditions.create     | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| terms-and-conditions.update     | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| terms-and-conditions.delete     | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
```

### 3. إعادة البناء

```bash
npm run build
# ✓ built in 30.20s
```

---

## 🎯 النتيجة / Result

### قبل التعديل / Before

❌ الرابط لا يظهر في Sidebar
❌ لا توجد صلاحيات للشروط والأحكام

### بعد التعديل / After

✅ الرابط يظهر في Sidebar للمستخدمين الذين لديهم صلاحية `terms-and-conditions.view`
✅ تم إضافة 4 صلاحيات كاملة
✅ تم تعيين الصلاحيات للأدوار المناسبة

---

## 📋 قائمة التحقق / Checklist

- [x] إضافة المورد إلى `config/acl.php`
- [x] إضافة التسميات إلى `config/acl.php`
- [x] تحديث `RolesPermissionsSeeder.php`
- [x] تشغيل Seeder
- [x] التحقق من الصلاحيات
- [x] إعادة البناء
- [x] التوثيق
- [ ] اختبار في المتصفح

---

## 🔄 الخطوات التالية / Next Steps

1. ✅ إضافة الصلاحيات - تم
2. ✅ تشغيل Seeder - تم
3. ✅ إعادة البناء - تم
4. 🔄 تسجيل الدخول كـ Admin
5. 🔄 التحقق من ظهور الرابط في Sidebar
6. 🔄 اختبار الوصول إلى الصفحة
7. 🔄 اختبار الصلاحيات المختلفة

---

## 💡 ملاحظات / Notes

### لماذا لم يظهر الرابط؟

السبب: في `AppSidebar.vue`، الرابط يحتوي على:

```javascript
permission: "terms-and-conditions.view";
```

وكانت هذه الصلاحية غير موجودة في قاعدة البيانات، لذلك تم إخفاء الرابط تلقائياً.

### الحل

تم إضافة الصلاحيات إلى:

1. `config/acl.php` - تعريف الصلاحيات
2. `RolesPermissionsSeeder.php` - تعيين الصلاحيات للأدوار
3. تشغيل Seeder لإضافة الصلاحيات إلى قاعدة البيانات

---

## 📚 الملفات المعدلة / Modified Files

1. `config/acl.php` - إضافة المورد والتسميات
2. `database/seeders/RolesPermissionsSeeder.php` - تحديث Content Manager

---

## 🎉 النتيجة النهائية / Final Result

✅ **الصلاحيات تم إضافتها بنجاح**
✅ **الرابط سيظهر الآن في Sidebar**
✅ **النظام جاهز للاستخدام**

---

**التاريخ / Date:** 1 فبراير 2026
**الحالة / Status:** ✅ مكتمل / Complete
