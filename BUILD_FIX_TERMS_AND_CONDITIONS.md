# إصلاح خطأ البناء - نظام الشروط والأحكام

# Build Fix - Terms and Conditions System

## المشكلة / Problem

عند تشغيل `npm run build`، ظهر خطأ:

```
Could not load D:\Web Projects\hamid_chef\resources\js/Components/Pagination.vue
(imported by resources/js/Pages/Admin/TermsAndConditions/Index.vue):
ENOENT: no such file or directory
```

When running `npm run build`, an error occurred:

- The `Pagination.vue` component doesn't exist in the project
- Other admin pages use inline pagination instead

## الحل / Solution

### 1. إزالة استيراد المكون غير الموجود

Removed the non-existent component import:

```javascript
// BEFORE
import Pagination from "@/Components/Pagination.vue";

// AFTER
// Removed this line
```

### 2. استبدال Pagination بـ Inline Pagination

Replaced with inline pagination similar to other admin pages:

```vue
<!-- BEFORE -->
<Pagination :links="terms.links" />

<!-- AFTER -->
<div
    v-if="terms.last_page > 1"
    class="flex items-center justify-center gap-2 border-t border-gray-200 px-6 py-4 dark:border-gray-700"
>
    <button
        v-for="page in terms.last_page"
        :key="page"
        @click="goToPage(page)"
        :class="[
            'h-8 w-8 rounded-lg text-sm',
            page === terms.current_page
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300',
        ]"
    >
        {{ page }}
    </button>
</div>
```

### 3. إضافة دالة goToPage

Added `goToPage` method to handle pagination:

```javascript
const goToPage = (page) => {
    router.get(
        route("admin.terms-and-conditions.index"),
        { page },
        { preserveState: true },
    );
};
```

## النتيجة / Result

✅ **البناء نجح بدون أخطاء**
✅ **Build succeeded without errors**

```bash
npm run build
# ✓ built in 10.25s
```

✅ **جميع المسارات مسجلة بنجاح**
✅ **All routes registered successfully**

```bash
php artisan route:list --path=terms-and-conditions
# Showing [12] routes
```

## الملفات المعدلة / Modified Files

- `resources/js/Pages/Admin/TermsAndConditions/Index.vue`

## الخطوات التالية / Next Steps

1. ✅ إصلاح خطأ البناء - تم
2. 🔄 اختبار لوحة التحكم في `/admin/terms-and-conditions`
3. 🔄 اختبار نقاط النهاية API
4. 🔄 إضافة بيانات تجريبية باستخدام Seeder

## ملاحظات / Notes

- تم اتباع نفس نمط Pagination المستخدم في صفحات التقارير الأخرى
- Followed the same pagination pattern used in other report pages
- النمط متسق مع باقي صفحات لوحة التحكم
- Pattern is consistent with other admin pages
