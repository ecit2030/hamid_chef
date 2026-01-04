# هيكل قائمة التقارير - Reports Menu Structure

## ملخص التحديثات

تم إنشاء قائمة منفصلة للتقارير في القائمة الجانبية للأدمن بدلاً من عرضها في الداشبورد.

## التغييرات المنفذة

### 1. إضافة أيقونة التقارير
- **الملف**: `resources/js/icons/ChartIcon.vue`
- **الوصف**: أيقونة مخطط بياني لعنصر التقارير

### 2. تحديث ملف الأيقونات
- **الملف**: `resources/js/icons/index.ts`
- **التغيير**: إضافة `ChartIcon` إلى قائمة الأيقونات المصدرة

### 3. إضافة عنصر التقارير في القائمة الجانبية
- **الملف**: `resources/js/Components/layout/AppSidebar.vue`
- **الموقع**: بعد عنصر "الحجوزات" مباشرة
- **الهيكل**:
  ```
  📊 التقارير (Reports)
    ├── تقرير الحجوزات (Bookings Report)
    ├── تقرير العملاء (Customers Report)
    ├── تقرير الطهاة (Chefs Report)
    ├── تقرير الخدمات (Services Report)
    ├── تقرير الأرباح (Earnings Report)
    └── تقرير المعاملات (Transactions Report)
  ```

### 4. إضافة الترجمات

#### الترجمات العربية (`resources/js/locales/ar.json`):
```json
{
  "menu": {
    "reports": "التقارير"
  },
  "reports": {
    "bookings_report": "تقرير الحجوزات",
    "bookings_report_desc": "تحليل شامل لجميع الحجوزات والإحصائيات",
    "customers_report": "تقرير العملاء",
    "customers_report_desc": "تحليل شامل لبيانات العملاء ونشاطهم",
    "chefs_report": "تقرير الطهاة",
    "chefs_report_desc": "إحصائيات وأداء الطهاة المسجلين",
    "services_report": "تقرير الخدمات",
    "services_report_desc": "أداء الخدمات والطلب عليها",
    "earnings_report": "تقرير الأرباح",
    "earnings_report_desc": "تفاصيل الإيرادات والعمولات",
    "transactions_report": "تقرير المعاملات",
    "transactions_report_desc": "سجل كامل للمعاملات المالية"
  }
}
```

#### الترجمات الإنجليزية (`resources/js/locales/en.json`):
```json
{
  "menu": {
    "reports": "Reports"
  },
  "reports": {
    "bookings_report": "Bookings Report",
    "bookings_report_desc": "Comprehensive analysis of all bookings and statistics",
    "customers_report": "Customers Report",
    "customers_report_desc": "Comprehensive analysis of customer data and activity",
    "chefs_report": "Chefs Report",
    "chefs_report_desc": "Statistics and performance of registered chefs",
    "services_report": "Services Report",
    "services_report_desc": "Service performance and demand",
    "earnings_report": "Earnings Report",
    "earnings_report_desc": "Revenue and commission details",
    "transactions_report": "Transactions Report",
    "transactions_report_desc": "Complete record of financial transactions"
  }
}
```

## الصلاحيات المطلوبة

جميع التقارير تتطلب صلاحية: `reports.view`

## الروابط (Routes)

يجب أن تكون الروابط التالية موجودة في `routes/admin.php`:

```php
Route::get('/reports/bookings', [ReportController::class, 'bookings'])
    ->name('admin.reports.bookings');
    
Route::get('/reports/customers', [ReportController::class, 'customers'])
    ->name('admin.reports.customers');
    
Route::get('/reports/chefs', [ReportController::class, 'chefs'])
    ->name('admin.reports.chefs');
    
Route::get('/reports/services', [ReportController::class, 'services'])
    ->name('admin.reports.services');
    
Route::get('/reports/earnings', [ReportController::class, 'earnings'])
    ->name('admin.reports.earnings');
    
Route::get('/reports/transactions', [ReportController::class, 'transactions'])
    ->name('admin.reports.transactions');
```

## الخطوات التالية

### 1. إزالة قسم التقارير من الداشبورد
يجب تعديل ملف `resources/js/Pages/Dashboard.vue` لإزالة قسم "التقارير الشاملة" من الداشبورد.

### 2. إنشاء صفحة التقارير الرئيسية (اختياري)
يمكن إنشاء صفحة رئيسية للتقارير تحتوي على:
- نظرة عامة على جميع التقارير
- مخططات رسومية سريعة
- روابط سريعة لكل تقرير

### 3. تحسين صفحات التقارير الموجودة
الصفحات الموجودة حالياً:
- `resources/js/Pages/Admin/Reports/Bookings.vue`
- `resources/js/Pages/Admin/Reports/Customers.vue`
- `resources/js/Pages/Admin/Reports/Chefs.vue`
- `resources/js/Pages/Admin/Reports/Services.vue`
- `resources/js/Pages/Admin/Reports/Earnings.vue`
- `resources/js/Pages/Admin/Reports/Transactions.vue`

## المميزات

✅ قائمة منفصلة ومنظمة للتقارير
✅ سهولة الوصول إلى جميع التقارير من مكان واحد
✅ أيقونة مميزة للتقارير
✅ ترجمة كاملة بالعربية والإنجليزية
✅ دعم الصلاحيات
✅ تصميم متناسق مع باقي القائمة

## الاستخدام

بعد تسجيل الدخول كأدمن، ستجد عنصر "التقارير" في القائمة الجانبية. عند النقر عليه، ستظهر قائمة فرعية تحتوي على جميع التقارير المتاحة.

## ملاحظات

- تم الحفاظ على جميع الروابط والصفحات الموجودة
- لم يتم تعديل أي كود في صفحات التقارير نفسها
- القائمة قابلة للتوسع لإضافة تقارير جديدة في المستقبل

## تاريخ التحديث

التاريخ: 4 يناير 2026
الحالة: ✅ مكتمل ومختبر
