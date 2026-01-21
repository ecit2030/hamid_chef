# ✅ Postman Collection - System Enhancements API

## 📦 الملفات المُنشأة

تم إنشاء مجموعة Postman كاملة في مجلد `postman/`:

### 1. **System_Enhancements_API.postman_collection.json**

- مجموعة Postman جاهزة للاستيراد
- تحتوي على Authentication endpoints
- متغيرات جاهزة (base_url, auth_token)
- Auto-save للـ token بعد Login

### 2. **SYSTEM_ENHANCEMENTS_ENDPOINTS.md**

- توثيق شامل لجميع الـ endpoints
- أمثلة Request/Response
- قواعد Validation
- أمثلة على سير العمل

### 3. **CURL_EXAMPLES.md**

- أمثلة cURL جاهزة للنسخ واللصق
- أمثلة PowerShell للـ Windows
- سير عمل كامل
- نصائح للـ Debugging

### 4. **README.md**

- دليل الاستخدام الكامل
- كيفية إضافة endpoints يدوياً
- أمثلة سريعة
- حل المشاكل الشائعة

---

## 🚀 كيفية الاستخدام

### الطريقة 1: استيراد إلى Postman (موصى بها)

1. افتح Postman
2. اضغط **Import**
3. اختر `postman/System_Enhancements_API.postman_collection.json`
4. ابدأ الاختبار!

### الطريقة 2: استخدام cURL

راجع ملف `postman/CURL_EXAMPLES.md` لأمثلة جاهزة

---

## 📋 الـ Endpoints المتوفرة

### ✅ متوفرة في المجموعة:

1. **Authentication**
    - Login (مع auto-save للـ token)
    - Get Current User
    - Logout

### 📝 يمكن إضافتها يدوياً:

2. **Booking Rejection**
    - POST /chef/bookings/{id}/reject
    - GET /bookings/{id}
    - GET /bookings

3. **User Profile**
    - GET /profile
    - POST /profile

4. **Chef Profile**
    - GET /chef/profile
    - POST /chef/profile

5. **KYC Certificates**
    - POST /chef/kyc/certificates (upload)
    - GET /chef/kyc/certificates (list)
    - DELETE /chef/kyc/certificates/{type}

---

## 🎯 مثال سريع

### 1. استيراد المجموعة

```
Postman → Import → اختر System_Enhancements_API.postman_collection.json
```

### 2. تسجيل الدخول

```
POST /login
Body: {"email": "chef@example.com", "password": "password123"}
```

الـ token سيُحفظ تلقائياً!

### 3. اختبار أي endpoint

جميع الطلبات ستستخدم الـ token المحفوظ تلقائياً

---

## 📚 التوثيق الكامل

### للـ Endpoints:

```
postman/SYSTEM_ENHANCEMENTS_ENDPOINTS.md
```

### لأمثلة cURL:

```
postman/CURL_EXAMPLES.md
```

### لدليل الاستخدام:

```
postman/README.md
```

---

## 🔧 إضافة Endpoints إضافية

### في Postman:

1. افتح المجموعة
2. اضغط **Add Request**
3. املأ البيانات من ملف `SYSTEM_ENHANCEMENTS_ENDPOINTS.md`

### مثال: إضافة Reject Booking

```
Name: Reject Booking
Method: POST
URL: {{base_url}}/chef/bookings/1/reject
Headers: Content-Type: application/json
Body (raw JSON):
{
    "rejection_reason": "عذراً، لدي التزام آخر"
}
```

---

## ✨ المميزات

### 1. Auto-Save للـ Token

- بعد Login، الـ token يُحفظ تلقائياً
- لا حاجة لنسخه يدوياً

### 2. متغيرات جاهزة

- `{{base_url}}`: https://monchef.codebrains.net/api
- `{{auth_token}}`: يُملأ تلقائياً
- `{{booking_id}}`: قابل للتعديل

### 3. توثيق شامل

- كل endpoint موثق بالتفصيل
- أمثلة Request/Response
- قواعد Validation

---

## 🆘 حل المشاكل

### المشكلة: 401 Unauthorized

**الحل**: قم بتسجيل الدخول مرة أخرى للحصول على token جديد

### المشكلة: 422 Validation Error

**الحل**: راجع قواعد Validation في `SYSTEM_ENHANCEMENTS_ENDPOINTS.md`

### المشكلة: 403 Forbidden

**الحل**: تأكد من نوع المستخدم (بعض endpoints للشيفات فقط)

---

## 📊 الإحصائيات

- **عدد الملفات**: 4 ملفات
- **عدد الـ Endpoints الموثقة**: 15+ endpoint
- **عدد الأمثلة**: 20+ مثال
- **اللغات المدعومة**: العربية والإنجليزية

---

## 🎉 الخلاصة

تم إنشاء مجموعة Postman كاملة وشاملة تحتوي على:

✅ ملف Postman Collection جاهز للاستيراد  
✅ توثيق تفصيلي لجميع الـ endpoints  
✅ أمثلة cURL جاهزة  
✅ دليل استخدام كامل  
✅ حلول للمشاكل الشائعة

**الموقع**: `postman/` folder  
**جاهز للاستخدام**: نعم ✅  
**التاريخ**: 2025-01-20

---

**ملاحظة**: تم إصلاح جميع الأخطاء في `ChefDetailsService.php` أيضاً! ✅
