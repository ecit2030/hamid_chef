# كيفية الحصول على API Token للعميل

## المشكلة

أنت مسجل دخول في **الموقع** (web session) لكن الـ API يحتاج **Bearer Token** مختلف.

## الفرق بين تسجيل الدخول في الموقع والـ API

### تسجيل الدخول في الموقع (Web)

- يستخدم **Session Cookies**
- يعمل فقط في المتصفح
- لا يعمل مع API requests

### تسجيل الدخول في الـ API

- يستخدم **Bearer Token** (Sanctum)
- يعمل مع أي client (Postman, Mobile App, etc.)
- يجب إرساله في header كل طلب

## الحل: احصل على API Token

### الطريقة 1: استخدام Postman

#### الخطوة 1: تسجيل الدخول للحصول على Token

```
POST http://localhost:8000/api/login
Headers:
  Content-Type: application/json
  Accept: application/json

Body (raw JSON):
{
  "email": "test.user@example.com",
  "password": "password"
}
```

#### الخطوة 2: انسخ الـ Token من الاستجابة

```json
{
  "success": true,
  "message": "تم تسجيل الدخول بنجاح",
  "data": {
    "user": { ... },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

**انسخ الـ token**: `1|abcdefghijklmnopqrstuvwxyz123456`

#### الخطوة 3: استخدم الـ Token في طلب الإلغاء

```
POST http://localhost:8000/api/bookings/1/cancel-by-customer
Headers:
  Content-Type: application/json
  Accept: application/json
  Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456

Body (raw JSON):
{
  "cancellation_reason": "اضطررت للسفر بشكل مفاجئ"
}
```

### الطريقة 2: استخدام PowerShell Script

قم بتشغيل السكريبت الذي أنشأناه:

```powershell
# عدل المتغيرات في الملف أولاً
.\test_cancel_simple.ps1
```

### الطريقة 3: استخدام CURL

```bash
# 1. تسجيل الدخول والحصول على Token
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test.user@example.com","password":"password"}'

# النتيجة: انسخ الـ token من الاستجابة

# 2. إلغاء الحجز باستخدام الـ Token
curl -X POST "http://localhost:8000/api/bookings/1/cancel-by-customer" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|YOUR_TOKEN_HERE" \
  -d '{"cancellation_reason":"اضطررت للسفر بشكل مفاجئ"}'
```

## التحقق من الـ Token

للتأكد من أن الـ Token يعمل، جرب هذا الطلب:

```
GET http://localhost:8000/api/me
Headers:
  Authorization: Bearer {your_token}
  Accept: application/json
```

إذا حصلت على بيانات المستخدم، فالـ Token صحيح! ✅

## الأخطاء الشائعة

### ❌ خطأ: "Unauthenticated"

**السبب**: لم يتم إرسال الـ Token أو الـ Token غير صحيح

**الحل**:

1. تأكد من تسجيل الدخول عبر `/api/login` أولاً
2. تأكد من نسخ الـ Token بالكامل
3. تأكد من إضافة `Bearer` قبل الـ Token مع مسافة
4. تأكد من إضافة header: `Authorization: Bearer {token}`

### ❌ خطأ: "Token not found"

**السبب**: نسيت كلمة `Bearer` قبل الـ Token

**الحل**: استخدم `Authorization: Bearer {token}` وليس `Authorization: {token}`

### ❌ خطأ: "Invalid token"

**السبب**: الـ Token منتهي أو تم حذفه

**الحل**: سجل دخول مرة أخرى للحصول على token جديد

## مثال كامل في Postman

### Request 1: Login

```
Method: POST
URL: http://localhost:8000/api/login
Headers:
  Content-Type: application/json
  Accept: application/json
Body (raw JSON):
{
  "email": "test.user@example.com",
  "password": "password"
}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "token": "1|abc123xyz..."
    }
}
```

### Request 2: Cancel Booking

```
Method: POST
URL: http://localhost:8000/api/bookings/1/cancel-by-customer
Headers:
  Content-Type: application/json
  Accept: application/json
  Authorization: Bearer 1|abc123xyz...
Body (raw JSON):
{
  "cancellation_reason": "اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور"
}
```

**Response:**

```json
{
    "success": true,
    "message": "تم إلغاء الحجز بنجاح"
}
```

## ملاحظات مهمة

1. **الـ Token لا ينتهي تلقائياً** في Laravel Sanctum (ما لم تقم بتسجيل الخروج)

2. **يمكنك استخدام نفس الـ Token** لعدة طلبات حتى تسجل الخروج

3. **لتسجيل الخروج** وإلغاء الـ Token:

    ```
    POST /api/logout
    Authorization: Bearer {token}
    ```

4. **لتسجيل الخروج من جميع الأجهزة**:
    ```
    POST /api/logout-all-devices
    Authorization: Bearer {token}
    ```

## الخلاصة

✅ **تسجيل الدخول في الموقع ≠ تسجيل الدخول في الـ API**

✅ **يجب الحصول على Bearer Token** عبر `POST /api/login`

✅ **يجب إرسال الـ Token** في كل طلب API: `Authorization: Bearer {token}`

✅ **تأكد من كتابة `Bearer`** قبل الـ Token مع مسافة

✅ **استخدم Postman أو السكريبت** للاختبار بسهولة
