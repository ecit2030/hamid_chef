# System Enhancements API Endpoints - التحديثات الجديدة

## Base URL

```
https://monchef.codebrains.net/api
```

## Authentication

جميع الـ endpoints تتطلب Bearer Token ما عدا Login

```
Authorization: Bearer {token}
```

---

## 1. Authentication - المصادقة

### 1.1 Login - تسجيل الدخول

```http
POST /login
Content-Type: application/json

{
    "email": "chef@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "token": "1|xxxxxxxxxxxxx",
    "user": {
        "id": 1,
        "email": "chef@example.com",
        "user_type": "chef"
    }
}
```

### 1.2 Get Current User

```http
GET /me
Authorization: Bearer {token}
```

### 1.3 Logout

```http
POST /logout
Authorization: Bearer {token}
```

---

## 2. Booking Rejection - رفض الحجوزات مع الأسباب

### 2.1 Reject Booking (Chef)

```http
POST /chef/bookings/{booking_id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
    "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت"
}
```

**Response:**

```json
{
    "message": "Booking rejected successfully",
    "booking": {
        "id": 1,
        "booking_status": "rejected",
        "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت"
    }
}
```

### 2.2 Get Booking Details (with rejection reason)

```http
GET /bookings/{booking_id}
Authorization: Bearer {token}
```

**Response:**

```json
{
    "id": 1,
    "booking_status": "rejected",
    "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت",
    "date": "2025-01-25",
    "start_time": "10:00:00",
    "end_time": "14:00:00"
}
```

### 2.3 List All Bookings

```http
GET /bookings
Authorization: Bearer {token}
```

---

## 3. User Profile Update - تحديث الملف الشخصي للمستخدم

### 3.1 Get User Profile

```http
GET /profile
Authorization: Bearer {token}
```

### 3.2 Update User Profile

```http
POST /profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "first_name": "أحمد",
    "last_name": "محمد",
    "email": "ahmed@example.com",
    "phone_number": "+967777777777",
    "address": "صنعاء، اليمن"
}
```

**Response:**

```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "first_name": "أحمد",
        "last_name": "محمد",
        "email": "ahmed@example.com",
        "phone_number": "+967777777777",
        "address": "صنعاء، اليمن"
    }
}
```

---

## 4. Chef Profile Update - تحديث الملف الشخصي للشيف

### 4.1 Get Chef Profile

```http
GET /chef/profile
Authorization: Bearer {token}
```

### 4.2 Update Chef Profile

```http
POST /chef/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "الشيف أحمد",
    "description_ar": "شيف متخصص في المأكولات اليمنية",
    "description_en": "Chef specialized in Yemeni cuisine",
    "base_hourly_rate": 50.00,
    "phone_number": "+967777777777",
    "governorate_id": 1,
    "district_id": 1,
    "area_id": 1
}
```

**Response:**

```json
{
    "message": "Chef profile updated successfully",
    "chef": {
        "id": 1,
        "name": "الشيف أحمد",
        "description_ar": "شيف متخصص في المأكولات اليمنية",
        "base_hourly_rate": 50.0,
        "user": {
            "phone_number": "+967777777777"
        }
    }
}
```

---

## 5. KYC Certificates Management - إدارة شهادات التحقق

### 5.1 Upload Certificate

```http
POST /chef/kyc/certificates
Authorization: Bearer {token}
Content-Type: multipart/form-data

certificate_type: identity_document
file: [PDF/Image File]
```

**Certificate Types:**

- `identity_document` - وثيقة الهوية
- `health_certificate` - الشهادة الصحية
- `professional_certificate` - الشهادة المهنية

**Response:**

```json
{
    "message": "Certificate uploaded successfully",
    "kyc": {
        "id": 1,
        "certificates": {
            "identity_document": {
                "path": "kyc/certificates/identity_123.pdf",
                "uploaded_at": "2025-01-20T10:00:00Z",
                "file_type": "pdf"
            }
        }
    }
}
```

### 5.2 List All Certificates

```http
GET /chef/kyc/certificates
Authorization: Bearer {token}
```

**Response:**

```json
{
    "certificates": {
        "identity_document": {
            "path": "kyc/certificates/identity_123.pdf",
            "uploaded_at": "2025-01-20T10:00:00Z",
            "file_type": "pdf",
            "download_url": "https://monchef.codebrains.net/api/chef/kyc/certificates/download/..."
        },
        "health_certificate": {
            "path": "kyc/certificates/health_456.pdf",
            "uploaded_at": "2025-01-20T11:00:00Z",
            "file_type": "pdf",
            "download_url": "https://monchef.codebrains.net/api/chef/kyc/certificates/download/..."
        }
    }
}
```

### 5.3 Delete Certificate

```http
DELETE /chef/kyc/certificates/{certificate_type}
Authorization: Bearer {token}
```

**Example:**

```http
DELETE /chef/kyc/certificates/identity_document
Authorization: Bearer {token}
```

**Response:**

```json
{
    "message": "Certificate deleted successfully"
}
```

---

## Validation Rules - قواعد التحقق

### Booking Rejection

- `rejection_reason`: مطلوب، نص، 1-500 حرف، لا يمكن أن يكون مسافات فقط

### User Profile

- `email`: صيغة بريد إلكتروني صحيحة، فريد في النظام
- `phone_number`: صيغة رقم هاتف صحيحة، فريد في النظام

### Chef Profile

- `base_hourly_rate`: رقم موجب (إذا تم توفيره)

### KYC Certificates

- `certificate_type`: مطلوب، يجب أن يكون أحد القيم: identity_document, health_certificate, professional_certificate
- `file`: مطلوب، يجب أن يكون صورة (jpg, jpeg, png) أو PDF، الحد الأقصى 5MB

---

## Error Responses - استجابات الأخطاء

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 422 Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "rejection_reason": ["The rejection reason field is required."]
    }
}
```

### 403 Forbidden

```json
{
    "message": "This action is unauthorized."
}
```

### 404 Not Found

```json
{
    "message": "Resource not found."
}
```

---

## Testing Notes - ملاحظات الاختبار

1. **احصل على Token أولاً**: قم بتسجيل الدخول أولاً للحصول على token
2. **استخدم Token في جميع الطلبات**: أضف `Authorization: Bearer {token}` في headers
3. **تحقق من نوع المستخدم**: بعض الـ endpoints تتطلب نوع مستخدم محدد (chef)
4. **استخدم بيانات صحيحة**: تأكد من صحة البيانات المرسلة

---

## Example Workflow - مثال على سير العمل

### 1. Login as Chef

```http
POST /login
{
    "email": "chef@example.com",
    "password": "password123"
}
```

### 2. Update Chef Profile

```http
POST /chef/profile
Authorization: Bearer {token}
{
    "name": "الشيف أحمد",
    "base_hourly_rate": 50.00
}
```

### 3. Upload KYC Certificate

```http
POST /chef/kyc/certificates
Authorization: Bearer {token}
Content-Type: multipart/form-data

certificate_type: health_certificate
file: [health_cert.pdf]
```

### 4. Reject a Booking

```http
POST /chef/bookings/1/reject
Authorization: Bearer {token}
{
    "rejection_reason": "عذراً، لدي التزام آخر"
}
```

### 5. View Booking with Rejection Reason

```http
GET /bookings/1
Authorization: Bearer {token}
```
