# Discount Codes API - Postman Collection Complete ✅

## نظرة عامة

تم إنشاء Postman Collection شامل لاختبار API أكواد الخصم مع جميع السيناريوهات والتوثيق الكامل.

## الملفات المنشأة

### 1. Postman Collection

**الملف:** `postman/discount_codes.postman_collection.json`

**المحتويات:**

- 🔐 **Authentication** (1 طلب)
    - Login - Get Token
- ✅ **Validate Discount Code** (8 طلبات)
    - 3 سيناريوهات نجاح
    - 5 سيناريوهات فشل

**المميزات:**

- ✅ اختبارات تلقائية لكل طلب
- ✅ حفظ Token تلقائياً بعد تسجيل الدخول
- ✅ متغيرات قابلة للتخصيص
- ✅ توثيق شامل لكل طلب
- ✅ أمثلة على الاستجابات

### 2. README

**الملف:** `postman/DISCOUNT_CODES_README.md`

**المحتويات:**

- 📖 شرح كامل للمجموعة
- 🚀 كيفية الاستخدام
- ⚙️ إعداد المتغيرات
- 🧪 تشغيل الاختبارات
- 📊 أمثلة على الاستجابات
- 🔧 استكشاف الأخطاء

### 3. CURL Examples

**الملف:** `postman/DISCOUNT_CODES_CURL_EXAMPLES.md`

**المحتويات:**

- 💻 أوامر CURL جاهزة
- 📝 جميع السيناريوهات
- 🔄 سكريبتات شاملة (Bash & PowerShell)
- 📋 أمثلة على الاستجابات

## الطلبات المتوفرة

### 🔐 Authentication

#### 1. Login - Get Token

```
POST /api/login
```

**Body:**

```json
{
    "phone_number": "0501234567",
    "password": "password"
}
```

**Response:**

```json
{
    "token": "2|...",
    "user": {
        "id": 1,
        "name": "فهد الدوسري"
    }
}
```

---

### ✅ Success Scenarios

#### 2. Valid Code - Amount Above Minimum

```
POST /api/discount-codes/validate
Authorization: Bearer {token}
```

**Body:**

```json
{
    "code": "TEST2024",
    "amount": 200
}
```

**Response (200):**

```json
{
    "success": true,
    "data": {
        "valid": true,
        "discount_code_id": 3,
        "code": "TEST2024",
        "type": "percentage",
        "value": "20.00",
        "original_amount": 200,
        "discount_amount": 40,
        "final_amount": 160
    },
    "message": "الكود صالح"
}
```

**Tests:**

- ✅ Status code is 200
- ✅ Response has success field
- ✅ Response has discount data
- ✅ Discount calculation is correct

---

#### 3. Valid Code - Amount At Minimum

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "TEST2024",
    "amount": 100
}
```

**Response (200):**

- Original: 100 ريال
- Discount: 20 ريال (20%)
- Final: 80 ريال

---

#### 4. Valid Code - High Amount (Max Discount)

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "TEST2024",
    "amount": 500
}
```

**Response (200):**

- Original: 500 ريال
- Discount: 50 ريال (الحد الأقصى)
- Final: 450 ريال

**Note:** 20% من 500 = 100 ريال، لكن تم تطبيق الحد الأقصى 50 ريال.

---

### ❌ Error Scenarios

#### 5. Invalid - Amount Below Minimum

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "TEST2024",
    "amount": 50
}
```

**Response (422):**

```json
{
    "success": false,
    "message": "الحد الأدنى للطلب هو 100 ريال"
}
```

**Tests:**

- ✅ Status code is 422
- ✅ Error message is returned

---

#### 6. Invalid - Non-Existent Code

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "INVALID123",
    "amount": 200
}
```

**Response (422):**

```json
{
    "success": false,
    "message": "الكود غير موجود"
}
```

---

#### 7. Invalid - Missing Code

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "amount": 200
}
```

**Response (422):**

```json
{
    "message": "The code field is required.",
    "errors": {
        "code": ["The code field is required."]
    }
}
```

---

#### 8. Invalid - Missing Amount

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "TEST2024"
}
```

**Response (422):**

```json
{
    "message": "The amount field is required.",
    "errors": {
        "amount": ["The amount field is required."]
    }
}
```

---

#### 9. Invalid - Negative Amount

```
POST /api/discount-codes/validate
```

**Body:**

```json
{
    "code": "TEST2024",
    "amount": -100
}
```

**Response (422):**

```json
{
    "message": "The amount field must be at least 0.",
    "errors": {
        "amount": ["The amount field must be at least 0."]
    }
}
```

---

#### 10. Unauthorized - No Token

```
POST /api/discount-codes/validate
(بدون Authorization Header)
```

**Response (401):**

```json
{
    "message": "Unauthenticated."
}
```

---

## كيفية الاستخدام

### 1. استيراد المجموعة في Postman

#### الطريقة الأولى: من الملف

1. افتح Postman
2. اضغط **Import**
3. اختر `postman/discount_codes.postman_collection.json`
4. اضغط **Import**

#### الطريقة الثانية: من الرابط

إذا كانت المجموعة منشورة على Postman:

1. افتح Postman
2. اضغط **Import**
3. الصق رابط المجموعة
4. اضغط **Import**

### 2. إعداد المتغيرات

#### المتغيرات المطلوبة:

```
base_url = http://127.0.0.1:8000
auth_token = (سيتم ملؤه تلقائياً)
user_id = (سيتم ملؤه تلقائياً)
```

#### طريقة التعديل:

1. اضغط على المجموعة
2. اذهب إلى **Variables**
3. عدل `base_url` حسب البيئة

### 3. تسجيل الدخول

1. افتح طلب **Login - Get Token**
2. اضغط **Send**
3. سيتم حفظ الـ Token تلقائياً

### 4. تشغيل الاختبارات

#### تشغيل طلب واحد:

1. اختر الطلب
2. اضغط **Send**
3. راجع النتيجة والاختبارات

#### تشغيل جميع الطلبات:

1. اضغط بزر الماوس الأيمن على المجموعة
2. اختر **Run collection**
3. اضغط **Run Discount Codes API**

---

## الاختبارات التلقائية

### ✅ اختبارات النجاح

```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has success field", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property("success");
    pm.expect(jsonData.success).to.be.true;
});

pm.test("Discount calculation is correct", function () {
    var jsonData = pm.response.json();
    var originalAmount = jsonData.data.original_amount;
    var discountAmount = jsonData.data.discount_amount;
    var finalAmount = jsonData.data.final_amount;
    pm.expect(finalAmount).to.equal(originalAmount - discountAmount);
});
```

### ❌ اختبارات الفشل

```javascript
pm.test("Status code is 422", function () {
    pm.response.to.have.status(422);
});

pm.test("Error message is returned", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property("success");
    pm.expect(jsonData.success).to.be.false;
    pm.expect(jsonData).to.have.property("message");
});
```

---

## معلومات كود الخصم

| الحقل                  | القيمة     | الوصف                      |
| ---------------------- | ---------- | -------------------------- |
| الكود                  | TEST2024   | الكود المستخدم للاختبار    |
| النوع                  | percentage | نسبة مئوية                 |
| القيمة                 | 20%        | نسبة الخصم                 |
| الحد الأدنى            | 100 ريال   | الحد الأدنى للطلب          |
| الحد الأقصى            | 50 ريال    | الحد الأقصى للخصم          |
| الاستخدامات الكلية     | 100        | عدد الاستخدامات الكلي      |
| الاستخدامات لكل مستخدم | 3          | عدد الاستخدامات لكل مستخدم |
| الحالة                 | نشط        | الكود نشط                  |
| تاريخ البداية          | الآن       | تاريخ بدء الصلاحية         |
| تاريخ النهاية          | بعد 30 يوم | تاريخ انتهاء الصلاحية      |

---

## أمثلة CURL

### تسجيل الدخول

```bash
curl -X POST "http://127.0.0.1:8000/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"phone_number": "0501234567", "password": "password"}'
```

### التحقق من الكود

```bash
curl -X POST "http://127.0.0.1:8000/api/discount-codes/validate" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"code": "TEST2024", "amount": 200}'
```

---

## الملفات ذات الصلة

| الملف                                            | الوصف                    |
| ------------------------------------------------ | ------------------------ |
| `postman/discount_codes.postman_collection.json` | ملف المجموعة             |
| `postman/DISCOUNT_CODES_README.md`               | دليل الاستخدام           |
| `postman/DISCOUNT_CODES_CURL_EXAMPLES.md`        | أمثلة CURL               |
| `DISCOUNT_CODES_API_TEST_COMPLETE.md`            | توثيق الاختبارات         |
| `test_discount_api.php`                          | سكريبت إنشاء كود الخصم   |
| `test_discount_api_scenarios.ps1`                | سكريبت اختبار PowerShell |

---

## الخلاصة

✅ **Postman Collection جاهز ومكتمل:**

- 9 طلبات شاملة (1 مصادقة + 8 تحقق)
- اختبارات تلقائية لكل طلب
- توثيق كامل لكل سيناريو
- أمثلة على جميع الاستجابات
- متغيرات قابلة للتخصيص
- سهل الاستخدام والتشغيل

✅ **التوثيق شامل:**

- README مفصل
- أمثلة CURL جاهزة
- سكريبتات اختبار
- استكشاف الأخطاء

✅ **جاهز للاستخدام في:**

- التطوير
- الاختبار
- التوثيق
- التدريب

🚀 **جاهز للاستخدام الآن!**
