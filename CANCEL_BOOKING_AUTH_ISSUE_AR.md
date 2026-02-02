# حل مشكلة Authentication عند إلغاء الحجز

## المشكلة

عند محاولة إلغاء الحجز من العميل، يظهر خطأ Authentication (Unauthenticated).

## السبب

المسار `POST /api/bookings/{id}/cancel-by-customer` يتطلب authentication (Bearer Token).

## الحل

### 1. تسجيل الدخول أولاً

قبل استخدام أي API محمي، يجب تسجيل الدخول للحصول على Token:

**الطلب:**

```http
POST /api/login
Content-Type: application/json

{
  "email": "customer@example.com",
  "password": "password123"
}
```

**الاستجابة:**

```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "first_name": "أحمد",
      "last_name": "محمد",
      "email": "customer@example.com",
      ...
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz123456"
  }
}
```

**احفظ الـ Token** من الاستجابة!

### 2. استخدام الـ Token في طلب الإلغاء

**الطلب:**

```http
POST /api/bookings/123/cancel-by-customer
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456
Accept: application/json

{
  "cancellation_reason": "اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور في الموعد المحدد"
}
```

**ملاحظة مهمة:** يجب كتابة `Bearer` قبل الـ Token مع مسافة بينهما.

## أمثلة عملية

### مثال 1: باستخدام CURL

```bash
# 1. تسجيل الدخول
TOKEN=$(curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@example.com","password":"password123"}' \
  | jq -r '.data.token')

# 2. إلغاء الحجز
curl -X POST "http://localhost:8000/api/bookings/123/cancel-by-customer" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" \
  -d '{
    "cancellation_reason": "اضطررت للسفر بشكل مفاجئ"
  }'
```

### مثال 2: باستخدام JavaScript

```javascript
// 1. تسجيل الدخول
async function login() {
    const response = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            email: "customer@example.com",
            password: "password123",
        }),
    });

    const data = await response.json();

    if (data.success) {
        // حفظ الـ token
        localStorage.setItem("auth_token", data.data.token);
        return data.data.token;
    }

    throw new Error("فشل تسجيل الدخول");
}

// 2. إلغاء الحجز
async function cancelBooking(bookingId, reason) {
    const token = localStorage.getItem("auth_token");

    if (!token) {
        throw new Error("يجب تسجيل الدخول أولاً");
    }

    const response = await fetch(
        `http://localhost:8000/api/bookings/${bookingId}/cancel-by-customer`,
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
            body: JSON.stringify({
                cancellation_reason: reason,
            }),
        },
    );

    const data = await response.json();

    if (response.status === 401) {
        // الـ token منتهي، يجب تسجيل الدخول مرة أخرى
        localStorage.removeItem("auth_token");
        throw new Error("انتهت صلاحية الجلسة، يرجى تسجيل الدخول مرة أخرى");
    }

    return data;
}

// الاستخدام
try {
    // تسجيل الدخول أولاً
    await login();

    // ثم إلغاء الحجز
    const result = await cancelBooking(123, "اضطررت للسفر بشكل مفاجئ");
    console.log("تم الإلغاء بنجاح:", result);
} catch (error) {
    console.error("خطأ:", error.message);
}
```

### مثال 3: باستخدام Axios

```javascript
import axios from "axios";

// إعداد axios مع الـ token
const api = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

// إضافة الـ token تلقائياً لكل طلب
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// معالجة خطأ 401 (Unauthenticated)
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem("auth_token");
            // إعادة توجيه المستخدم لصفحة تسجيل الدخول
            window.location.href = "/login";
        }
        return Promise.reject(error);
    },
);

// 1. تسجيل الدخول
async function login(email, password) {
    const response = await api.post("/login", { email, password });
    if (response.data.success) {
        localStorage.setItem("auth_token", response.data.data.token);
    }
    return response.data;
}

// 2. إلغاء الحجز
async function cancelBooking(bookingId, reason) {
    const response = await api.post(
        `/bookings/${bookingId}/cancel-by-customer`,
        {
            cancellation_reason: reason,
        },
    );
    return response.data;
}

// الاستخدام
try {
    await login("customer@example.com", "password123");
    const result = await cancelBooking(123, "اضطررت للسفر بشكل مفاجئ");
    console.log("تم الإلغاء بنجاح:", result);
} catch (error) {
    console.error("خطأ:", error.response?.data || error.message);
}
```

## الأخطاء الشائعة وحلولها

### 1. خطأ: "Unauthenticated"

**السبب:** لم يتم إرسال الـ Token أو الـ Token غير صحيح

**الحل:**

- تأكد من تسجيل الدخول أولاً
- تأكد من إضافة `Authorization: Bearer {token}` في الـ headers
- تأكد من وجود مسافة بين `Bearer` والـ token

### 2. خطأ: "غير مصرح لك بتنفيذ هذا الإجراء"

**السبب:** المستخدم المسجل دخوله ليس صاحب الحجز

**الحل:**

- تأكد من أن المستخدم المسجل دخوله هو نفسه العميل الذي أنشأ الحجز
- تحقق من `customer_id` في الحجز

### 3. خطأ: "سبب الإلغاء مطلوب"

**السبب:** لم يتم إرسال `cancellation_reason` أو أقل من 10 أحرف

**الحل:**

- أضف حقل `cancellation_reason` في الـ body
- تأكد من أن السبب بين 10-500 حرف

### 4. خطأ: "لا يمكن للعميل إلغاء الحجز في حالته الحالية"

**السبب:** حالة الحجز لا تسمح بالإلغاء

**الحل:**

- يمكن الإلغاء فقط إذا كانت الحالة `pending` أو `accepted`
- إذا كانت الحالة `completed` أو `cancelled_by_customer` أو `cancelled_by_chef` أو `rejected`، لا يمكن الإلغاء

## اختبار باستخدام Postman

### الخطوة 1: تسجيل الدخول

1. افتح Postman
2. أنشئ طلب جديد: `POST http://localhost:8000/api/login`
3. في `Headers`:
    - `Content-Type: application/json`
    - `Accept: application/json`
4. في `Body` (اختر `raw` و `JSON`):
    ```json
    {
        "email": "customer@example.com",
        "password": "password123"
    }
    ```
5. اضغط `Send`
6. **انسخ الـ token** من الاستجابة

### الخطوة 2: إلغاء الحجز

1. أنشئ طلب جديد: `POST http://localhost:8000/api/bookings/123/cancel-by-customer`
2. في `Headers`:
    - `Content-Type: application/json`
    - `Accept: application/json`
    - `Authorization: Bearer {الصق الـ token هنا}`
3. في `Body` (اختر `raw` و `JSON`):
    ```json
    {
        "cancellation_reason": "اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور"
    }
    ```
4. اضغط `Send`

## ملاحظات مهمة

1. **صلاحية الـ Token**: الـ Token لا ينتهي تلقائياً في Laravel Sanctum، لكن يمكن إلغاؤه عند تسجيل الخروج

2. **تسجيل الخروج**: لإلغاء الـ Token:

    ```http
    POST /api/logout
    Authorization: Bearer {token}
    ```

3. **تسجيل الخروج من جميع الأجهزة**:

    ```http
    POST /api/logout-all-devices
    Authorization: Bearer {token}
    ```

4. **التحقق من المستخدم الحالي**:
    ```http
    GET /api/me
    Authorization: Bearer {token}
    ```

## الخلاصة

✅ **يجب تسجيل الدخول أولاً** للحصول على Bearer Token

✅ **يجب إضافة الـ Token** في header كل طلب: `Authorization: Bearer {token}`

✅ **يجب كتابة سبب الإلغاء** بين 10-500 حرف

✅ **يمكن الإلغاء فقط** إذا كانت حالة الحجز `pending` أو `accepted`

✅ **يجب أن يكون المستخدم** هو صاحب الحجز (العميل)
