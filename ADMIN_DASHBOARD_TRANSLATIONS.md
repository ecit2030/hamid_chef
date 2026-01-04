# ترجمة لوحة التحكم للأدمن - Admin Dashboard Translations

## ملخص التحديثات

تم إضافة جميع الترجمات العربية المطلوبة لنصوص لوحة التحكم (Dashboard) الخاصة بالأدمن وحالات الحجوزات.

## الترجمات المضافة

### 1. قسم لوحة التحكم الرئيسية (admin.dashboard)

تمت إضافة الترجمات التالية في كل من `resources/js/locales/ar.json` و `resources/js/locales/en.json`:

#### الإحصائيات الرئيسية:
- **totalUsers**: إجمالي المستخدمين / Total Users
- **activeChefs**: الطهاة النشطون / Active Chefs
- **outOf**: من أصل / out of
- **monthlyBookings**: الحجوزات الشهرية / Monthly Bookings
- **pending**: قيد الانتظار / Pending
- **monthlyRevenue**: الإيرادات الشهرية / Monthly Revenue

#### التنبيهات والإشعارات:
- **pendingKyc**: طلبات KYC قيد الانتظار / Pending KYC Requests
- **requestsWaiting**: طلبات في الانتظار / requests waiting

#### المخططات والرسوم البيانية:
- **bookingsChart**: مخطط الحجوزات / Bookings Chart
- **revenueChart**: مخطط الإيرادات / Revenue Chart
- **dailyBookings**: الحجوزات اليومية / Daily Bookings

#### الأقسام الإضافية:
- **topChefs**: أفضل الطهاة / Top Chefs
- **bookings**: حجوزات / bookings
- **recentBookings**: الحجوزات الأخيرة / Recent Bookings
- **bookingsByStatus**: الحجوزات حسب الحالة / Bookings by Status

### 2. قسم التقارير (reports)

تمت إضافة الترجمات التالية:

#### تقارير الحجوزات:
- **bookings_report**: تقرير الحجوزات / Bookings Report
- **bookings_report_desc**: تحليل شامل لجميع الحجوزات والإحصائيات / Comprehensive analysis of all bookings and statistics

#### تقارير الخدمات:
- **services_report**: تقرير الخدمات / Services Report
- **services_report_desc**: أداء الخدمات والطلب عليها / Service performance and demand

#### تقارير الأرباح:
- **earnings_report**: تقرير الأرباح / Earnings Report
- **earnings_report_desc**: تفاصيل الإيرادات والعمولات / Revenue and commission details

### 3. قسم التقارير الشاملة (admin.dashboard.reportsSection)

تمت إضافة الترجمات التالية:

- **reportsSection**: التقارير الشاملة / Comprehensive Reports
- **viewReport**: عرض التقرير / View Report
- **customersReport**: تقرير العملاء / Customers Report
- **customersReportDesc**: تحليل شامل لبيانات العملاء ونشاطهم / Comprehensive analysis of customer data and activity
- **chefsReport**: تقرير الطهاة / Chefs Report
- **chefsReportDesc**: إحصائيات وأداء الطهاة المسجلين / Statistics and performance of registered chefs
- **transactionsReport**: تقرير المعاملات / Transactions Report
- **transactionsReportDesc**: سجل كامل للمعاملات المالية / Complete record of financial transactions

### 4. حالات الحجوزات (booking.status)

تمت إضافة قسم منفصل لحالات الحجوزات لسهولة الوصول:

```json
"booking": {
  "status": {
    "pending": "في الانتظار / Pending",
    "accepted": "مقبول / Accepted",
    "rejected": "مرفوض / Rejected",
    "cancelled_by_customer": "ملغي من العميل / Cancelled by Customer",
    "cancelled_by_chef": "ملغي من الطاهي / Cancelled by Chef",
    "cancelled_by_admin": "ملغي من الإدارة / Cancelled by Admin",
    "completed": "مكتمل / Completed"
  }
}
```

### 5. إضافات عامة (common)

تمت إضافة:
- **noData**: لا توجد بيانات / No data available

## الملفات المعدلة

1. `resources/js/locales/ar.json` - إضافة جميع الترجمات العربية
2. `resources/js/locales/en.json` - إضافة الترجمات الإنجليزية المقابلة

## التحقق من التحديثات

تم بناء المشروع بنجاح باستخدام:
```bash
npm run build
```

## الاستخدام

يمكن الآن استخدام هذه الترجمات في ملفات Vue كالتالي:

### استخدام ترجمات لوحة التحكم:
```vue
<template>
  <h2>{{ t('admin.dashboard.totalUsers') }}</h2>
  <p>{{ t('admin.dashboard.monthlyRevenue') }}</p>
  <button>{{ t('admin.dashboard.viewReport') }}</button>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
```

### استخدام حالات الحجوزات:
```vue
<template>
  <span :class="getStatusClass(booking.status)">
    {{ t('booking.status.' + booking.status) }}
  </span>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

// مثال: booking.status = 'accepted'
// النتيجة: "مقبول" (بالعربية) أو "Accepted" (بالإنجليزية)
</script>
```

## ملاحظات

- جميع الترجمات متوافقة مع نظام i18n المستخدم في المشروع
- تم التأكد من صحة بناء JSON في كلا الملفين
- الترجمات تغطي جميع النصوص الظاهرة في لوحة التحكم الرئيسية للأدمن
- تم إضافة ترجمات لجميع أنواع التقارير المتاحة
- تم إضافة قسم منفصل `booking.status` لسهولة الوصول إلى حالات الحجوزات
- يمكن استخدام `t('booking.status.accepted')` مباشرة للحصول على الترجمة

## تاريخ التحديث

التاريخ: 4 يناير 2026
آخر تحديث: إضافة قسم booking.status المنفصل
