# cURL Examples - أمثلة cURL للاختبار السريع

## 🔐 Authentication

### Login

```bash
curl -X POST https://monchef.codebrains.net/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "chef@example.com",
    "password": "password123"
  }'
```

### Get Current User

```bash
curl -X GET https://monchef.codebrains.net/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Logout

```bash
curl -X POST https://monchef.codebrains.net/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📋 Booking Rejection

### Reject Booking (Chef)

```bash
curl -X POST https://monchef.codebrains.net/api/chef/bookings/1/reject \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت"
  }'
```

### Get Booking Details

```bash
curl -X GET https://monchef.codebrains.net/api/bookings/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### List All Bookings

```bash
curl -X GET https://monchef.codebrains.net/api/bookings \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 👤 User Profile

### Get User Profile

```bash
curl -X GET https://monchef.codebrains.net/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Update User Profile

```bash
curl -X POST https://monchef.codebrains.net/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "أحمد",
    "last_name": "محمد",
    "email": "ahmed@example.com",
    "phone_number": "+967777777777",
    "address": "صنعاء، اليمن"
  }'
```

---

## 👨‍🍳 Chef Profile

### Get Chef Profile

```bash
curl -X GET https://monchef.codebrains.net/api/chef/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Update Chef Profile

```bash
curl -X POST https://monchef.codebrains.net/api/chef/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "الشيف أحمد",
    "description_ar": "شيف متخصص في المأكولات اليمنية",
    "description_en": "Chef specialized in Yemeni cuisine",
    "base_hourly_rate": 50.00,
    "phone_number": "+967777777777",
    "governorate_id": 1,
    "district_id": 1,
    "area_id": 1
  }'
```

---

## 📜 KYC Certificates

### Upload Identity Document

```bash
curl -X POST https://monchef.codebrains.net/api/chef/kyc/certificates \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "certificate_type=identity_document" \
  -F "file=@/path/to/identity.pdf"
```

### Upload Health Certificate

```bash
curl -X POST https://monchef.codebrains.net/api/chef/kyc/certificates \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "certificate_type=health_certificate" \
  -F "file=@/path/to/health_cert.pdf"
```

### Upload Professional Certificate

```bash
curl -X POST https://monchef.codebrains.net/api/chef/kyc/certificates \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "certificate_type=professional_certificate" \
  -F "file=@/path/to/professional_cert.pdf"
```

### List All Certificates

```bash
curl -X GET https://monchef.codebrains.net/api/chef/kyc/certificates \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Delete Certificate

```bash
curl -X DELETE https://monchef.codebrains.net/api/chef/kyc/certificates/identity_document \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🔄 Complete Workflow Example

### 1. Login and Save Token

```bash
# Login
TOKEN=$(curl -s -X POST https://monchef.codebrains.net/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"chef@example.com","password":"password123"}' \
  | jq -r '.token')

echo "Token: $TOKEN"
```

### 2. Update Chef Profile

```bash
curl -X POST https://monchef.codebrains.net/api/chef/profile \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "الشيف أحمد",
    "base_hourly_rate": 50.00
  }'
```

### 3. Upload Certificate

```bash
curl -X POST https://monchef.codebrains.net/api/chef/kyc/certificates \
  -H "Authorization: Bearer $TOKEN" \
  -F "certificate_type=health_certificate" \
  -F "file=@certificate.pdf"
```

### 4. Reject Booking

```bash
curl -X POST https://monchef.codebrains.net/api/chef/bookings/1/reject \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"rejection_reason":"عذراً، لدي التزام آخر"}'
```

### 5. View Booking

```bash
curl -X GET https://monchef.codebrains.net/api/bookings/1 \
  -H "Authorization: Bearer $TOKEN"
```

---

## 💡 Tips

### Save Token to Variable (Windows PowerShell)

```powershell
$response = Invoke-RestMethod -Uri "https://monchef.codebrains.net/api/login" `
  -Method Post `
  -ContentType "application/json" `
  -Body '{"email":"chef@example.com","password":"password123"}'

$token = $response.token
Write-Host "Token: $token"
```

### Use Token in Subsequent Requests

```powershell
Invoke-RestMethod -Uri "https://monchef.codebrains.net/api/profile" `
  -Method Get `
  -Headers @{"Authorization"="Bearer $token"}
```

### Pretty Print JSON Response (with jq)

```bash
curl -X GET https://monchef.codebrains.net/api/profile \
  -H "Authorization: Bearer $TOKEN" | jq '.'
```

---

## 🐛 Debugging

### View Full Response Headers

```bash
curl -v -X GET https://monchef.codebrains.net/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Test with Invalid Token

```bash
curl -X GET https://monchef.codebrains.net/api/profile \
  -H "Authorization: Bearer invalid_token"
```

### Test Validation Errors

```bash
curl -X POST https://monchef.codebrains.net/api/chef/bookings/1/reject \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"rejection_reason":""}'
```

---

**ملاحظة**: استبدل `YOUR_TOKEN_HERE` بالـ token الفعلي الذي حصلت عليه من Login
