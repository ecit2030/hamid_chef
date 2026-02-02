# خطة نظام أكواد الخصم - Discount Codes System Plan

## نظرة عامة | Overview

نظام شامل لإدارة أكواد الخصم يسمح للمسؤول بإنشاء وإدارة أكواد الخصم، والعملاء باستخدامها عند الحجز.

A comprehensive discount code management system that allows admins to create and manage discount codes, and customers to use them when booking.

---

## 📋 المتطلبات الوظيفية | Functional Requirements

### 1. لوحة تحكم المسؤول | Admin Dashboard

#### إدارة أكواد الخصم:

- ✅ إنشاء كود خصم جديد
- ✅ تعديل كود خصم موجود
- ✅ حذف/تعطيل كود خصم
- ✅ عرض قائمة جميع أكواد الخصم
- ✅ البحث والفلترة (نشط/منتهي/مستخدم)
- ✅ إحصائيات الاستخدام

#### معلومات الكود:

- **الكود** (Code): نص فريد (مثل: SUMMER2026)
- **نوع الخصم** (Type): نسبة مئوية أو مبلغ ثابت
- **قيمة الخصم** (Value): القيمة (مثل: 20% أو 50 ريال)
- **الحد الأدنى للطلب** (Min Order): الحد الأدنى لقيمة الحجز
- **الحد الأقصى للخصم** (Max Discount): أقصى مبلغ خصم (للنسبة المئوية)
- **تاريخ البداية** (Start Date): متى يبدأ الكود
- **تاريخ الانتهاء** (End Date): متى ينتهي الكود
- **عدد الاستخدامات** (Usage Limit): عدد مرات الاستخدام الكلي
- **استخدام لكل عميل** (Per User Limit): عدد مرات الاستخدام لكل عميل
- **الحالة** (Status): نشط/غير نشط

### 2. واجهة العميل | Customer Interface

#### استخدام الكود:

- ✅ حقل إدخال كود الخصم في صفحة الحجز
- ✅ زر "تطبيق الكود" (Apply Code)
- ✅ عرض قيمة الخصم بعد التطبيق
- ✅ عرض السعر قبل وبعد الخصم
- ✅ رسائل خطأ واضحة (كود غير صحيح، منتهي، مستخدم)
- ✅ إمكانية إزالة الكود

#### التحقق من الكود:

- ✅ التحقق من صحة الكود
- ✅ التحقق من تاريخ الصلاحية
- ✅ التحقق من عدد الاستخدامات
- ✅ التحقق من الحد الأدنى للطلب
- ✅ التحقق من استخدام العميل السابق

---

## 🗄️ قاعدة البيانات | Database Structure

### جدول أكواد الخصم | discount_codes Table

```sql
CREATE TABLE discount_codes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- معلومات الكود الأساسية
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT NULL,

    -- نوع وقيمة الخصم
    type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    value DECIMAL(10, 2) NOT NULL,

    -- قيود الاستخدام
    min_order_amount DECIMAL(10, 2) NULL DEFAULT 0,
    max_discount_amount DECIMAL(10, 2) NULL,

    -- تواريخ الصلاحية
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,

    -- حدود الاستخدام
    usage_limit INT UNSIGNED NULL,
    usage_count INT UNSIGNED DEFAULT 0,
    per_user_limit INT UNSIGNED DEFAULT 1,

    -- الحالة
    is_active BOOLEAN DEFAULT TRUE,

    -- تتبع
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,

    -- الطوابع الزمنية
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,

    -- المفاتيح الخارجية
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES admins(id) ON DELETE SET NULL,

    -- الفهارس
    INDEX idx_code (code),
    INDEX idx_active (is_active),
    INDEX idx_dates (start_date, end_date),
    INDEX idx_created_at (created_at)
);
```

### جدول استخدام أكواد الخصم | discount_code_usages Table

```sql
CREATE TABLE discount_code_usages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- العلاقات
    discount_code_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    booking_id BIGINT UNSIGNED NOT NULL,

    -- تفاصيل الاستخدام
    original_amount DECIMAL(10, 2) NOT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    final_amount DECIMAL(10, 2) NOT NULL,

    -- الطوابع الزمنية
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- المفاتيح الخارجية
    FOREIGN KEY (discount_code_id) REFERENCES discount_codes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,

    -- الفهارس
    INDEX idx_discount_code (discount_code_id),
    INDEX idx_user (user_id),
    INDEX idx_booking (booking_id),
    INDEX idx_used_at (used_at)
);
```

### تحديث جدول الحجوزات | Update bookings Table

```sql
ALTER TABLE bookings ADD COLUMN discount_code_id BIGINT UNSIGNED NULL AFTER total_amount;
ALTER TABLE bookings ADD COLUMN discount_amount DECIMAL(10, 2) DEFAULT 0 AFTER discount_code_id;
ALTER TABLE bookings ADD COLUMN original_amount DECIMAL(10, 2) NULL AFTER discount_amount;

ALTER TABLE bookings ADD FOREIGN KEY (discount_code_id) REFERENCES discount_codes(id) ON DELETE SET NULL;
ALTER TABLE bookings ADD INDEX idx_discount_code (discount_code_id);
```

---

## 🏗️ البنية البرمجية | Code Architecture

### 1. Models

#### DiscountCode Model

```php
app/Models/DiscountCode.php
```

**العلاقات:**

- `hasMany(DiscountCodeUsage)` - الاستخدامات
- `hasMany(Booking)` - الحجوزات
- `belongsTo(Admin, 'created_by')` - المنشئ
- `belongsTo(Admin, 'updated_by')` - المحدث

**الدوال:**

- `isValid()` - التحقق من صلاحية الكود
- `canBeUsedBy($userId)` - التحقق من إمكانية الاستخدام
- `calculateDiscount($amount)` - حساب قيمة الخصم
- `incrementUsage()` - زيادة عداد الاستخدام
- `scopeActive()` - الأكواد النشطة فقط
- `scopeValid()` - الأكواد الصالحة (ضمن التاريخ)

#### DiscountCodeUsage Model

```php
app/Models/DiscountCodeUsage.php
```

**العلاقات:**

- `belongsTo(DiscountCode)` - الكود
- `belongsTo(User)` - المستخدم
- `belongsTo(Booking)` - الحجز

### 2. DTOs

#### DiscountCodeDTO

```php
app/DTOs/DiscountCodeDTO.php
```

**الخصائص:**

- جميع حقول الجدول
- إحصائيات الاستخدام
- حالة الصلاحية

#### DiscountCodeUsageDTO

```php
app/DTOs/DiscountCodeUsageDTO.php
```

### 3. Repositories

#### DiscountCodeRepository

```php
app/Repositories/DiscountCodeRepository.php
app/Repositories/Contracts/DiscountCodeRepositoryInterface.php
```

**الدوال:**

- `findByCode($code)` - البحث بالكود
- `getActive()` - الأكواد النشطة
- `getValid()` - الأكواد الصالحة
- `getUsageStats($id)` - إحصائيات الاستخدام
- `getUserUsageCount($codeId, $userId)` - عدد استخدامات المستخدم

#### DiscountCodeUsageRepository

```php
app/Repositories/DiscountCodeUsageRepository.php
app/Repositories/Contracts/DiscountCodeUsageRepositoryInterface.php
```

### 4. Services

#### DiscountCodeService

```php
app/Services/DiscountCodeService.php
```

**الدوال الرئيسية:**

- `validateCode($code, $userId, $amount)` - التحقق من الكود
- `applyDiscount($code, $userId, $amount)` - تطبيق الخصم
- `calculateDiscount($code, $amount)` - حساب قيمة الخصم
- `recordUsage($codeId, $userId, $bookingId, $amounts)` - تسجيل الاستخدام
- `getAvailableCodes()` - الأكواد المتاحة
- `getCodeStatistics($id)` - إحصائيات الكود

**منطق التحقق:**

```php
public function validateCode($code, $userId, $amount)
{
    // 1. التحقق من وجود الكود
    // 2. التحقق من أن الكود نشط
    // 3. التحقق من تاريخ الصلاحية
    // 4. التحقق من عدد الاستخدامات الكلي
    // 5. التحقق من استخدام المستخدم
    // 6. التحقق من الحد الأدنى للطلب
    // 7. إرجاع النتيجة مع قيمة الخصم
}
```

### 5. Controllers

#### Admin Panel

```php
app/Http/Controllers/Admin/DiscountCodeController.php
```

**الدوال:**

- `index()` - عرض القائمة
- `create()` - صفحة الإنشاء
- `store()` - حفظ كود جديد
- `show($id)` - عرض التفاصيل
- `edit($id)` - صفحة التعديل
- `update($id)` - تحديث الكود
- `destroy($id)` - حذف الكود
- `toggle($id)` - تفعيل/تعطيل
- `statistics($id)` - الإحصائيات

#### API

```php
app/Http/Controllers/Api/DiscountCodeController.php
```

**الدوال:**

- `validate()` - التحقق من الكود (POST)
- `apply()` - تطبيق الكود (POST)

### 6. Requests

```php
app/Http/Requests/StoreDiscountCodeRequest.php
app/Http/Requests/UpdateDiscountCodeRequest.php
app/Http/Requests/ValidateDiscountCodeRequest.php
```

**قواعد التحقق:**

- `code`: required, unique, max:50, alpha_dash
- `type`: required, in:percentage,fixed
- `value`: required, numeric, min:0
- `start_date`: required, date, before:end_date
- `end_date`: required, date, after:start_date
- `usage_limit`: nullable, integer, min:1
- `per_user_limit`: nullable, integer, min:1

### 7. Resources

```php
app/Http/Resources/DiscountCodeResource.php
app/Http/Resources/DiscountCodeUsageResource.php
```

### 8. Policies

```php
app/Policies/DiscountCodePolicy.php
```

**الصلاحيات:**

- `viewAny` - عرض القائمة (Admin)
- `view` - عرض التفاصيل (Admin)
- `create` - إنشاء (Admin)
- `update` - تعديل (Admin)
- `delete` - حذف (Admin)

---

## 🎨 الواجهات | Frontend

### 1. Admin Panel (Vue.js)

#### صفحات:

```
resources/js/Pages/Admin/DiscountCode/
├── Index.vue          # قائمة الأكواد
├── Create.vue         # إنشاء كود جديد
├── Edit.vue           # تعديل كود
└── Show.vue           # عرض التفاصيل والإحصائيات
```

#### مكونات:

```
resources/js/Components/admin/discount-code/
├── DiscountCodeTable.vue      # جدول الأكواد
├── DiscountCodeForm.vue       # نموذج الإنشاء/التعديل
├── DiscountCodeStats.vue      # الإحصائيات
└── UsageHistory.vue           # سجل الاستخدام
```

#### الميزات:

- ✅ جدول مع فلترة وبحث
- ✅ نموذج إنشاء/تعديل شامل
- ✅ عرض الإحصائيات (عدد الاستخدامات، المبلغ الموفر)
- ✅ سجل الاستخدام مع تفاصيل الحجوزات
- ✅ تفعيل/تعطيل سريع
- ✅ نسخ الكود بنقرة واحدة

### 2. Customer Interface (API + Mobile)

#### API Endpoints:

```
POST /api/discount-codes/validate
POST /api/discount-codes/apply
```

#### حقول الحجز:

```json
{
    "discount_code": "SUMMER2026",
    "original_amount": 500.0,
    "discount_amount": 100.0,
    "final_amount": 400.0
}
```

---

## 🔄 سير العمل | Workflow

### 1. إنشاء كود خصم (Admin)

```
1. المسؤول يدخل إلى صفحة أكواد الخصم
2. ينقر على "إنشاء كود جديد"
3. يملأ النموذج:
   - الكود: SUMMER2026
   - النوع: نسبة مئوية
   - القيمة: 20%
   - الحد الأدنى: 200 ريال
   - الحد الأقصى: 100 ريال
   - تاريخ البداية: 2026-06-01
   - تاريخ الانتهاء: 2026-08-31
   - عدد الاستخدامات: 100
   - لكل عميل: 1
4. يحفظ الكود
5. يظهر الكود في القائمة
```

### 2. استخدام كود الخصم (Customer)

```
1. العميل يختار الخدمة والتفاصيل
2. يصل إلى صفحة الدفع
3. يرى السعر الأصلي: 500 ريال
4. يدخل كود الخصم: SUMMER2026
5. ينقر "تطبيق"
6. النظام يتحقق من:
   ✓ الكود صحيح
   ✓ ضمن تاريخ الصلاحية
   ✓ لم يستخدمه من قبل
   ✓ المبلغ أكبر من الحد الأدنى (500 > 200)
   ✓ عدد الاستخدامات متاح
7. يحسب الخصم: 500 × 20% = 100 ريال
8. يطبق الحد الأقصى: 100 ريال (ضمن الحد)
9. يعرض:
   - السعر الأصلي: 500 ريال
   - الخصم: -100 ريال
   - المجموع: 400 ريال
10. يكمل الحجز
11. يسجل الاستخدام في قاعدة البيانات
```

### 3. تتبع الاستخدام (Admin)

```
1. المسؤول يدخل إلى تفاصيل الكود
2. يرى:
   - عدد الاستخدامات: 45/100
   - المبلغ الموفر: 4,500 ريال
   - آخر استخدام: منذ ساعتين
3. يرى قائمة الاستخدامات:
   - العميل | التاريخ | المبلغ الأصلي | الخصم | المبلغ النهائي
4. يمكنه تصدير التقرير
```

---

## 🔒 الأمان والتحقق | Security & Validation

### قواعد التحقق:

1. **الكود فريد**: لا يمكن تكرار نفس الكود
2. **التواريخ صحيحة**: تاريخ البداية قبل تاريخ الانتهاء
3. **القيم منطقية**:
    - النسبة المئوية: 0-100%
    - المبلغ الثابت: > 0
4. **الحدود منطقية**: الحد الأقصى > 0
5. **منع الاستخدام المتكرر**: تتبع استخدام كل عميل
6. **منع التلاعب**: التحقق من الكود في الخادم فقط

### الحماية من الهجمات:

- ✅ Rate Limiting على API التحقق
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection (Input Sanitization)
- ✅ Authorization (Policies)

---

## 📊 التقارير والإحصائيات | Reports & Analytics

### تقارير المسؤول:

1. **إحصائيات عامة:**
    - عدد الأكواد النشطة
    - إجمالي الاستخدامات
    - إجمالي المبلغ الموفر
    - متوسط قيمة الخصم

2. **تقرير لكل كود:**
    - عدد الاستخدامات
    - المبلغ الموفر
    - العملاء المستخدمين
    - معدل التحويل

3. **تقارير زمنية:**
    - الاستخدام اليومي/الأسبوعي/الشهري
    - الأكواد الأكثر استخداماً
    - أوقات الذروة

---

## 🧪 الاختبار | Testing

### Unit Tests:

```php
tests/Unit/Services/DiscountCodeServiceTest.php
tests/Unit/Models/DiscountCodeTest.php
```

### Feature Tests:

```php
tests/Feature/Admin/DiscountCodeManagementTest.php
tests/Feature/Api/DiscountCodeValidationTest.php
tests/Feature/Api/DiscountCodeApplicationTest.php
```

### سيناريوهات الاختبار:

1. ✅ إنشاء كود خصم صحيح
2. ✅ رفض كود مكرر
3. ✅ التحقق من كود صحيح
4. ✅ رفض كود منتهي
5. ✅ رفض كود مستخدم بالكامل
6. ✅ رفض استخدام متكرر من نفس العميل
7. ✅ رفض طلب أقل من الحد الأدنى
8. ✅ تطبيق الحد الأقصى للخصم
9. ✅ حساب الخصم بالنسبة المئوية
10. ✅ حساب الخصم بالمبلغ الثابت

---

## 📝 خطوات التنفيذ | Implementation Steps

### المرحلة 1: قاعدة البيانات (Database)

1. ✅ إنشاء Migration لجدول discount_codes
2. ✅ إنشاء Migration لجدول discount_code_usages
3. ✅ تحديث جدول bookings
4. ✅ إنشاء Seeder للبيانات التجريبية

### المرحلة 2: Backend (Laravel)

1. ✅ إنشاء Models + Relationships
2. ✅ إنشاء DTOs
3. ✅ إنشاء Repositories + Interfaces
4. ✅ إنشاء Services
5. ✅ إنشاء Controllers (Admin + API)
6. ✅ إنشاء Requests (Validation)
7. ✅ إنشاء Resources
8. ✅ إنشاء Policies
9. ✅ تحديث Routes
10. ✅ تحديث BookingService لدعم الأكواد

### المرحلة 3: Frontend (Vue.js)

1. ✅ صفحات Admin Panel
2. ✅ مكونات الإدارة
3. ✅ إضافة إلى Sidebar
4. ✅ الترجمات (عربي/إنجليزي)
5. ✅ التحقق من الأكواد في واجهة الحجز

### المرحلة 4: API للموبايل

1. ✅ API Endpoints
2. ✅ Documentation
3. ✅ Postman Collection

### المرحلة 5: الاختبار

1. ✅ Unit Tests
2. ✅ Feature Tests
3. ✅ Integration Tests
4. ✅ Manual Testing

### المرحلة 6: التوثيق

1. ✅ User Guide (Admin)
2. ✅ API Documentation
3. ✅ Developer Guide

---

## 🎯 أمثلة الاستخدام | Usage Examples

### مثال 1: خصم نسبة مئوية

```
الكود: SUMMER20
النوع: نسبة مئوية
القيمة: 20%
الحد الأدنى: 100 ريال
الحد الأقصى: 50 ريال

السعر الأصلي: 300 ريال
الخصم: 300 × 20% = 60 ريال
الحد الأقصى: 50 ريال
السعر النهائي: 300 - 50 = 250 ريال
```

### مثال 2: خصم مبلغ ثابت

```
الكود: WELCOME50
النوع: مبلغ ثابت
القيمة: 50 ريال
الحد الأدنى: 200 ريال

السعر الأصلي: 250 ريال
الخصم: 50 ريال
السعر النهائي: 250 - 50 = 200 ريال
```

### مثال 3: كود محدود

```
الكود: FLASH100
النوع: مبلغ ثابت
القيمة: 100 ريال
عدد الاستخدامات: 10
لكل عميل: 1
التاريخ: 2026-02-01 إلى 2026-02-02

✓ أول 10 عملاء يحصلون على الخصم
✗ العميل لا يمكنه استخدامه مرتين
✗ بعد 10 استخدامات، الكود لا يعمل
```

---

## 💡 ميزات إضافية (اختيارية) | Additional Features

1. **أكواد خاصة بالطهاة**: كود يعمل فقط مع طاهي معين
2. **أكواد خاصة بالخدمات**: كود يعمل فقط مع خدمة معينة
3. **أكواد للعملاء الجدد**: first_order_only
4. **أكواد الإحالة**: referral codes
5. **أكواد تلقائية**: تطبيق تلقائي بدون إدخال
6. **أكواد مجمعة**: stackable codes
7. **إشعارات**: تنبيه المسؤول عند نفاد الكود
8. **تصدير**: تصدير الأكواد والإحصائيات

---

## 📱 API Documentation

### Validate Discount Code

```http
POST /api/discount-codes/validate
Authorization: Bearer {token}
Content-Type: application/json

{
  "code": "SUMMER2026",
  "amount": 500.00
}

Response 200:
{
  "success": true,
  "data": {
    "valid": true,
    "code": "SUMMER2026",
    "type": "percentage",
    "value": 20,
    "discount_amount": 100.00,
    "final_amount": 400.00
  },
  "message": "الكود صالح"
}

Response 422:
{
  "success": false,
  "message": "الكود غير صالح",
  "errors": {
    "code": ["الكود منتهي الصلاحية"]
  }
}
```

---

## ✅ الخلاصة | Summary

هذا النظام يوفر:

- ✅ إدارة كاملة لأكواد الخصم من لوحة التحكم
- ✅ تطبيق سهل للعملاء
- ✅ تتبع دقيق للاستخدام
- ✅ حماية من التلاعب
- ✅ تقارير وإحصائيات شاملة
- ✅ مرونة في أنواع الخصم
- ✅ قابل للتوسع

**الوقت المتوقع للتنفيذ:** 3-5 أيام عمل

**الأولوية:** متوسطة إلى عالية (ميزة تسويقية مهمة)

---

هل تريد البدء في التنفيذ؟ يمكنني البدء بأي مرحلة تفضلها! 🚀
