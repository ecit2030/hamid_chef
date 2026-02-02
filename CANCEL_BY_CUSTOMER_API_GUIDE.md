# دليل استخدام API إلغاء الحجز من العميل

## التاريخ: 1 فبراير 2026

## نظرة عامة

هذا الدليل يشرح كيفية استخدام API لإلغاء الحجز من قبل العميل مع كتابة سبب الإلغاء.

## المتطلبات

1. يجب أن يكون المستخدم مسجل دخول (لديه Bearer Token)
2. يجب أن يكون المستخدم هو صاحب الحجز (العميل)
3. يجب أن تكون حالة الحجز `pending` أو `accepted`

## الخطوات

### 1. تسجيل الدخول أولاً

قبل استخدام API الإلغاء، يجب الحصول على Bearer Token:

```bash
POST /api/login
Content-Type: application/json

{
  "email": "customer@example.com",
  "password": "password"
}
```

**الاستجابة:**

```json
{
  "success": true,
  "data": {
    "user": { ... },
    "token": "1|xxxxxxxxxxxxxxxxxxxxx"
  }
}
```

احفظ الـ `token` لاستخدامه في الطلبات التالية.

### 2. إلغاء الحجز مع سبب الإلغاء

```bash
POST /api/bookings/{booking_id}/cancel-by-customer
Content-Type: application/json
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxx

{
  "cancellation_reason": "سبب الإلغاء هنا - يجب أن يكون بين 10 إلى 500 حرف"
}
```

**مثال باستخدام CURL:**

```bash
curl -X POST "http://your-domain.com/api/bookings/123/cancel-by-customer" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxx" \
  -H "Accept: application/json" \
  -d '{
    "cancellation_reason": "اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور"
  }'
```

**مثال باستخدام JavaScript (Fetch):**

```javascript
const token = localStorage.getItem("auth_token"); // أو من أي مكان تخزن فيه الـ token

fetch("http://your-domain.com/api/bookings/123/cancel-by-customer", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
    },
    body: JSON.stringify({
        cancellation_reason: "اضطررت للسفر بشكل مفاجئ ولن أتمكن من الحضور",
    }),
})
    .then((response) => response.json())
    .then((data) => console.log(data))
    .catch((error) => console.error("Error:", error));
```

## الاستجابات المحتملة

### نجاح (200)

```json
{
    "success": true,
    "message": "تم إلغاء الحجز بنجاح"
}
```

### خطأ في التحقق من البيانات (422)

```json
{
    "success": false,
    "message": "سبب الإلغاء مطلوب",
    "errors": {
        "cancellation_reason": ["سبب الإلغاء يجب أن يكون 10 أحرف على الأقل"]
    }
}
```

### حالة الحجز لا تسمح بالإلغاء (422)

```json
{
    "success": false,
    "message": "لا يمكن للعميل إلغاء الحجز في حالته الحالية",
    "errors": {
        "booking_status": [
            "الحالة الحالية لا تسمح بالإلغاء",
            "current_status: completed"
        ]
    }
}
```

### غير مصرح (401)

```json
{
    "message": "Unauthenticated."
}
```

هذا يعني أن الـ token غير صحيح أو منتهي. يجب تسجيل الدخول مرة أخرى.

### غير مصرح (403)

```json
{
    "success": false,
    "message": "غير مصرح لك بتنفيذ هذا الإجراء"
}
```

هذا يعني أن المستخدم ليس صاحب الحجز.

### الحجز غير موجود (404)

```json
{
    "success": false,
    "message": "الحجز المطلوب غير موجود"
}
```

## قواعد التحقق

### حقل `cancellation_reason`

- **مطلوب**: نعم
- **النوع**: نص (string)
- **الحد الأدنى**: 10 أحرف
- **الحد الأقصى**: 500 حرف

## الحالات المسموح بها للإلغاء

يمكن للعميل إلغاء الحجز فقط إذا كانت الحالة:

- `pending` (قيد الانتظار)
- `accepted` (مقبول من الطاهي)

## ملاحظات مهمة

1. **Bearer Token**: تأكد من إضافة `Bearer` قبل الـ token في الـ header

    ```
    Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxx
    ```

2. **Content-Type**: يجب أن يكون `application/json`

3. **Accept Header**: يفضل إضافة `Accept: application/json` للحصول على استجابة JSON

4. **انتهاء الـ Token**: إذا حصلت على خطأ 401، يجب تسجيل الدخول مرة أخرى للحصول على token جديد

5. **التحقق من الصلاحيات**: يجب أن يكون المستخدم المسجل دخوله هو صاحب الحجز (العميل)

## اختبار باستخدام Postman

1. افتح Postman
2. اختر `POST` كـ method
3. أدخل الـ URL: `http://your-domain.com/api/bookings/{booking_id}/cancel-by-customer`
4. في تبويب `Headers`:
    - أضف `Content-Type: application/json`
    - أضف `Authorization: Bearer {your_token}`
    - أضف `Accept: application/json`
5. في تبويب `Body`:
    - اختر `raw`
    - اختر `JSON`
    - أدخل:
        ```json
        {
            "cancellation_reason": "سبب الإلغاء هنا"
        }
        ```
6. اضغط `Send`

## استرجاع سبب الإلغاء

بعد إلغاء الحجز، يمكن استرجاع سبب الإلغاء عند عرض تفاصيل الحجز:

```bash
GET /api/bookings/{booking_id}
Authorization: Bearer {token}
```

**الاستجابة:**

```json
{
  "success": true,
  "data": {
    "id": 123,
    "booking_status": "cancelled_by_customer",
    "cancellation_reason": "اضطررت للسفر بشكل مفاجئ",
    ...
  }
}
```

## الفرق بين `rejection_reason` و `cancellation_reason`

- **`rejection_reason`**: يستخدم عندما يرفض الطاهي الحجز
- **`cancellation_reason`**: يستخدم عندما يلغي العميل الحجز

كلاهما يظهران في تفاصيل الحجز حسب الحالة.
