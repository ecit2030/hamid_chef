# ترجمة صفحات التقارير - Reports Pages Translations

## ملخص التحديثات

تم إضافة جميع الترجمات العربية والإنجليزية المطلوبة لصفحات التقارير.

## الترجمات المضافة

### 1. أزرار التصدير (Export Buttons)
- **export_excel**: تصدير Excel / Export Excel
- **export_pdf**: تصدير PDF / Export PDF

### 2. إحصائيات الحجوزات (Bookings Statistics)
- **total_bookings**: إجمالي الحجوزات / Total Bookings
- **completed_bookings**: الحجوزات المكتملة / Completed Bookings
- **pending_bookings**: الحجوزات قيد الانتظار / Pending Bookings
- **accepted_bookings**: الحجوزات المقبولة / Accepted Bookings
- **cancelled_bookings**: الحجوزات الملغية / Cancelled Bookings
- **total_amount**: المبلغ الإجمالي / Total Amount
- **total_hours**: إجمالي الساعات / Total Hours

### 3. عناوين الأقسام (Section Titles)
- **bookings_overview**: نظرة عامة على الحجوزات / Bookings Overview

### 4. أعمدة الجدول (Table Columns)
- **customer**: العميل / Customer
- **chef**: الطاهي / Chef
- **service**: الخدمة / Service
- **date**: التاريخ / Date
- **hours**: الساعات / Hours
- **amount**: المبلغ / Amount
- **status**: الحالة / Status

### 5. الفلاتر (Filters)
- **this_week**: هذا الأسبوع / This Week
- **this_month**: هذا الشهر / This Month
- **this_quarter**: هذا الربع / This Quarter
- **this_year**: هذا العام / This Year
- **all_time**: كل الوقت / All Time
- **all_statuses**: جميع الحالات / All Statuses

### 6. رسائل عامة (General Messages)
- **no_data**: لا توجد بيانات / No data available

## الملفات المحدثة

1. **resources/js/locales/ar.json** - الترجمات العربية
2. **resources/js/locales/en.json** - الترجمات الإنجليزية

## الاستخدام في الكود

### مثال 1: عرض الإحصائيات
```vue
<template>
  <div>
    <p>{{ t('reports.total_bookings') }}</p>
    <p>{{ stats.total }}</p>
  </div>
</template>
```

### مثال 2: أزرار التصدير
```vue
<template>
  <a :href="exportExcelUrl">
    {{ t('reports.export_excel') }}
  </a>
  <a :href="exportPdfUrl">
    {{ t('reports.export_pdf') }}
  </a>
</template>
```

### مثال 3: أعمدة الجدول
```vue
<template>
  <thead>
    <tr>
      <th>{{ t('reports.customer') }}</th>
      <th>{{ t('reports.chef') }}</th>
      <th>{{ t('reports.service') }}</th>
      <th>{{ t('reports.date') }}</th>
      <th>{{ t('reports.hours') }}</th>
      <th>{{ t('reports.amount') }}</th>
      <th>{{ t('reports.status') }}</th>
    </tr>
  </thead>
</template>
```

### مثال 4: الفلاتر
```vue
<template>
  <select v-model="selectedPeriod">
    <option value="week">{{ t('reports.this_week') }}</option>
    <option value="month">{{ t('reports.this_month') }}</option>
    <option value="quarter">{{ t('reports.this_quarter') }}</option>
    <option value="year">{{ t('reports.this_year') }}</option>
    <option value="all">{{ t('reports.all_time') }}</option>
  </select>
</template>
```

## الصفحات المتأثرة

جميع صفحات التقارير تستخدم هذه الترجمات:

1. **تقرير الحجوزات**: `resources/js/Pages/Admin/Reports/Bookings.vue`
2. **تقرير العملاء**: `resources/js/Pages/Admin/Reports/Customers.vue`
3. **تقرير الطهاة**: `resources/js/Pages/Admin/Reports/Chefs.vue`
4. **تقرير الخدمات**: `resources/js/Pages/Admin/Reports/Services.vue`
5. **تقرير الأرباح**: `resources/js/Pages/Admin/Reports/Earnings.vue`
6. **تقرير المعاملات**: `resources/js/Pages/Admin/Reports/Transactions.vue`

## الترجمات الكاملة في قسم reports

```json
{
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
    "transactions_report_desc": "سجل كامل للمعاملات المالية",
    "export_excel": "تصدير Excel",
    "export_pdf": "تصدير PDF",
    "total_bookings": "إجمالي الحجوزات",
    "completed_bookings": "الحجوزات المكتملة",
    "pending_bookings": "الحجوزات قيد الانتظار",
    "accepted_bookings": "الحجوزات المقبولة",
    "cancelled_bookings": "الحجوزات الملغية",
    "total_amount": "المبلغ الإجمالي",
    "total_hours": "إجمالي الساعات",
    "bookings_overview": "نظرة عامة على الحجوزات",
    "customer": "العميل",
    "chef": "الطاهي",
    "service": "الخدمة",
    "date": "التاريخ",
    "hours": "الساعات",
    "amount": "المبلغ",
    "status": "الحالة",
    "no_data": "لا توجد بيانات",
    "this_week": "هذا الأسبوع",
    "this_month": "هذا الشهر",
    "this_quarter": "هذا الربع",
    "this_year": "هذا العام",
    "all_time": "كل الوقت",
    "all_statuses": "جميع الحالات"
  }
}
```

## التحقق من التحديثات

تم بناء المشروع بنجاح:
```bash
npm run build
✓ built in 9.91s
```

## ملاحظات

- جميع الترجمات متوافقة مع نظام i18n
- تم اختبار البناء بنجاح
- الترجمات تغطي جميع النصوص الظاهرة في صفحات التقارير
- يمكن استخدام نفس الترجمات في تقارير الطاهي (Chef Reports) أيضاً

## تاريخ التحديث

التاريخ: 4 يناير 2026
الحالة: ✅ مكتمل ومختبر
