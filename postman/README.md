# System Enhancements API - Postman Collection

## 📦 الملفات المتوفرة

1. **System_Enhancements_API.postman_collection.json** - مجموعة Postman الأساسية
2. **SYSTEM_ENHANCEMENTS_ENDPOINTS.md** - توثيق شامل لجميع الـ endpoints

## 🚀 كيفية الاستخدام

### 1. استيراد المجموعة إلى Postman

1. افتح Postman
2. اضغط على **Import**
3. اختر ملف `System_Enhancements_API.postman_collection.json`
4. ستظهر المجموعة في قائمة Collections

### 2. إعداد المتغيرات

المجموعة تحتوي على متغيرات جاهزة:

- `base_url`: https://monchef.codebrains.net/api
- `auth_token`: سيتم ملؤه تلقائياً بعد تسجيل الدخول
- `booking_id`: يمكنك تعديله حسب الحاجة

### 3. سير العمل الموصى به

#### الخطوة 1: تسجيل الدخول

```
POST /login
```

- سيتم حفظ الـ token تلقائياً في المتغير `auth_token`
- جميع الطلبات التالية ستستخدم هذا الـ token

#### الخطوة 2: اختبار الـ Endpoints

**للمستخدمين العاديين:**

- Get User Profile
- Update User Profile

**للشيفات:**

- Get Chef Profile
- Update Chef Profile
- Upload KYC Certificates
- Reject Bookings

## 📋 الـ Endpoints المتوفرة

### 1. Authentication - المصادقة ✅

- ✅ Login - تسجيل الدخول
- ✅ Get Current User
- ✅ Logout

### 2. Booking Rejection - رفض الحجوزات (يحتاج إضافة يدوية)

```json
{
    "name": "Reject Booking",
    "request": {
        "method": "POST",
        "url": "{{base_url}}/chef/bookings/{{booking_id}}/reject",
        "body": {
            "rejection_reason": "عذراً، لدي التزام آخر"
        }
    }
}
```

### 3. User Profile - الملف الشخصي (يحتاج إضافة يدوية)

```json
{
    "name": "Update User Profile",
    "request": {
        "method": "POST",
        "url": "{{base_url}}/profile",
        "body": {
            "first_name": "أحمد",
            "last_name": "محمد",
            "email": "ahmed@example.com",
            "phone_number": "+967777777777"
        }
    }
}
```

### 4. Chef Profile - ملف الشيف (يحتاج إضافة يدوية)

```json
{
    "name": "Update Chef Profile",
    "request": {
        "method": "POST",
        "url": "{{base_url}}/chef/profile",
        "body": {
            "name": "الشيف أحمد",
            "base_hourly_rate": 50.0
        }
    }
}
```

### 5. KYC Certificates - شهادات التحقق (يحتاج إضافة يدوية)

```
POST {{base_url}}/chef/kyc/certificates
Content-Type: multipart/form-data

certificate_type: identity_document
file: [اختر ملف]
```

## 🔧 إضافة Endpoints يدوياً

نظراً لقيود حجم الملف، يمكنك إضافة الـ endpoints المتبقية يدوياً:

### إضافة Request جديد:

1. افتح المجموعة في Postman
2. اضغط على **Add Request**
3. املأ البيانات:
    - **Name**: اسم الـ endpoint
    - **Method**: GET/POST/DELETE
    - **URL**: {{base_url}}/endpoint-path
    - **Headers**: Content-Type: application/json (إذا لزم)
    - **Body**: البيانات المطلوبة

### مثال: إضافة Reject Booking

1. اضغط **Add Request** في مجلد "2. Booking Rejection"
2. Name: `Reject Booking`
3. Method: `POST`
4. URL: `{{base_url}}/chef/bookings/1/reject`
5. Body (raw JSON):

```json
{
    "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت"
}
```

## 📝 ملاحظات مهمة

### Authentication

- جميع الـ endpoints تتطلب Bearer Token ما عدا `/login`
- الـ token يُحفظ تلقائياً بعد تسجيل الدخول
- إذا انتهت صلاحية الـ token، قم بتسجيل الدخول مرة أخرى

### Validation Rules

- **rejection_reason**: 1-500 حرف، لا يمكن أن يكون مسافات فقط
- **email**: يجب أن يكون فريد في النظام
- **phone_number**: يجب أن يكون فريد في النظام
- **certificate file**: حد أقصى 5MB، صيغ مسموحة: jpg, jpeg, png, pdf

### Error Handling

- **401**: غير مصرح (تحتاج تسجيل دخول)
- **403**: ممنوع (ليس لديك صلاحية)
- **422**: خطأ في البيانات المدخلة
- **404**: المورد غير موجود

## 🎯 أمثلة سريعة

### مثال 1: تسجيل دخول وتحديث ملف شخصي

```
1. POST /login → احصل على token
2. POST /profile → حدّث البيانات
3. GET /profile → تحقق من التحديث
```

### مثال 2: رفع شهادة KYC

```
1. POST /login (كشيف)
2. POST /chef/kyc/certificates (رفع شهادة)
3. GET /chef/kyc/certificates (عرض جميع الشهادات)
```

### مثال 3: رفض حجز

```
1. POST /login (كشيف)
2. POST /chef/bookings/1/reject (رفض مع سبب)
3. GET /bookings/1 (تحقق من سبب الرفض)
```

## 📚 مصادر إضافية

- **SYSTEM_ENHANCEMENTS_ENDPOINTS.md**: توثيق تفصيلي لكل endpoint
- **routes/api.php**: ملف الـ routes الكامل
- **.kiro/specs/system-enhancements/**: مواصفات المشروع الكاملة

## 🆘 المساعدة

إذا واجهت مشاكل:

1. تأكد من أن الـ token صالح
2. تحقق من نوع المستخدم (user/chef)
3. راجع رسائل الأخطاء في Response
4. تأكد من صحة البيانات المرسلة

---

**تم إنشاؤه بواسطة**: System Enhancements Team  
**التاريخ**: 2025-01-20  
**الإصدار**: 1.0
