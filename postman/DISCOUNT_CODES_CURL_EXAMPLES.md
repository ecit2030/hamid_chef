# Discount Codes API - CURL Examples

## نظرة عامة

أمثلة CURL لاختبار API أكواد الخصم من سطر الأوامر.

## المتغيرات

```bash
BASE_URL="http://127.0.0.1:8000"
TOKEN="your_token_here"
```

---

## 1. تسجيل الدخول والحصول على Token

### الطلب

```bash
curl -X POST "${BASE_URL}/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone_number": "0501234567",
    "password": "password"
  }'
```

### الاستجابة

```json
{
    "token": "2|DYBtg2F3WhY5lOLudWOmdlsZhpN5SeY9ZWTGe8yV7b006715",
    "user": {
        "id": 1,
        "name": "فهد الدوسري",
        "phone_number": "0501234567"
    }
}
```

### حفظ الـ Token

```bash
TOKEN="2|DYBtg2F3WhY5lOLudWOmdlsZhpN5SeY9ZWTGe8yV7b006715"
```

---

## 2. التحقق من كود الخصم

### ✅ كود صالح - مبلغ أعلى من الحد الأدنى

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 200
  }'
```

**الاستجابة:**

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

---

### ✅ كود صالح - مبلغ عند الحد الأدنى

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 100
  }'
```

**الاستجابة:**

```json
{
    "success": true,
    "data": {
        "valid": true,
        "discount_code_id": 3,
        "code": "TEST2024",
        "type": "percentage",
        "value": "20.00",
        "original_amount": 100,
        "discount_amount": 20,
        "final_amount": 80
    },
    "message": "الكود صالح"
}
```

---

### ✅ كود صالح - مبلغ عالي (اختبار الحد الأقصى)

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 500
  }'
```

**الاستجابة:**

```json
{
    "success": true,
    "data": {
        "valid": true,
        "discount_code_id": 3,
        "code": "TEST2024",
        "type": "percentage",
        "value": "20.00",
        "original_amount": 500,
        "discount_amount": 50,
        "final_amount": 450
    },
    "message": "الكود صالح"
}
```

**ملاحظة:** 20% من 500 = 100 ريال، لكن تم تطبيق الحد الأقصى 50 ريال.

---

### ❌ مبلغ أقل من الحد الأدنى

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 50
  }'
```

**الاستجابة (422):**

```json
{
    "success": false,
    "message": "الحد الأدنى للطلب هو 100 ريال"
}
```

---

### ❌ كود غير موجود

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "INVALID123",
    "amount": 200
  }'
```

**الاستجابة (422):**

```json
{
    "success": false,
    "message": "الكود غير موجود"
}
```

---

### ❌ حقل الكود مفقود

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "amount": 200
  }'
```

**الاستجابة (422):**

```json
{
    "message": "The code field is required.",
    "errors": {
        "code": ["The code field is required."]
    }
}
```

---

### ❌ حقل المبلغ مفقود

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024"
  }'
```

**الاستجابة (422):**

```json
{
    "message": "The amount field is required.",
    "errors": {
        "amount": ["The amount field is required."]
    }
}
```

---

### ❌ مبلغ سالب

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": -100
  }'
```

**الاستجابة (422):**

```json
{
    "message": "The amount field must be at least 0.",
    "errors": {
        "amount": ["The amount field must be at least 0."]
    }
}
```

---

### ❌ بدون Token (غير مصادق)

```bash
curl -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 200
  }'
```

**الاستجابة (401):**

```json
{
    "message": "Unauthenticated."
}
```

---

## سكريبت شامل للاختبار

### Bash Script

```bash
#!/bin/bash

BASE_URL="http://127.0.0.1:8000"

# 1. Login and get token
echo "=== Login ==="
LOGIN_RESPONSE=$(curl -s -X POST "${BASE_URL}/api/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone_number": "0501234567",
    "password": "password"
  }')

TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.token')
echo "Token: $TOKEN"
echo ""

# 2. Test valid code - amount above minimum
echo "=== Test 1: Valid Code - Amount Above Minimum ==="
curl -s -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 200
  }' | jq '.'
echo ""

# 3. Test valid code - amount at minimum
echo "=== Test 2: Valid Code - Amount At Minimum ==="
curl -s -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 100
  }' | jq '.'
echo ""

# 4. Test valid code - high amount (max discount)
echo "=== Test 3: Valid Code - High Amount ==="
curl -s -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 500
  }' | jq '.'
echo ""

# 5. Test invalid - amount below minimum
echo "=== Test 4: Invalid - Amount Below Minimum ==="
curl -s -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "TEST2024",
    "amount": 50
  }' | jq '.'
echo ""

# 6. Test invalid - non-existent code
echo "=== Test 5: Invalid - Non-Existent Code ==="
curl -s -X POST "${BASE_URL}/api/discount-codes/validate" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "INVALID123",
    "amount": 200
  }' | jq '.'
echo ""

echo "=== All tests completed ==="
```

### PowerShell Script

```powershell
$BASE_URL = "http://127.0.0.1:8000"

# 1. Login and get token
Write-Host "=== Login ===" -ForegroundColor Cyan
$loginBody = @{
    phone_number = "0501234567"
    password = "password"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "$BASE_URL/api/login" `
    -Method Post `
    -Headers @{
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    } `
    -Body $loginBody

$TOKEN = $loginResponse.token
Write-Host "Token: $TOKEN" -ForegroundColor Green
Write-Host ""

$headers = @{
    "Authorization" = "Bearer $TOKEN"
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

# 2. Test valid code - amount above minimum
Write-Host "=== Test 1: Valid Code - Amount Above Minimum ===" -ForegroundColor Cyan
$body = @{
    code = "TEST2024"
    amount = 200
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "$BASE_URL/api/discount-codes/validate" `
    -Method Post -Headers $headers -Body $body
$response | ConvertTo-Json -Depth 10
Write-Host ""

# Add more tests...
```

---

## ملاحظات

### استخدام jq لتنسيق JSON

إذا كان لديك `jq` مثبت، يمكنك تنسيق الاستجابة:

```bash
curl ... | jq '.'
```

### حفظ الاستجابة في ملف

```bash
curl ... > response.json
```

### عرض Headers

```bash
curl -i ...
```

### عرض الطلب الكامل

```bash
curl -v ...
```

---

## الخلاصة

هذه الأمثلة توفر:

- ✅ أوامر CURL جاهزة للاستخدام
- ✅ جميع السيناريوهات (نجاح وفشل)
- ✅ سكريبتات شاملة للاختبار
- ✅ أمثلة على الاستجابات

جاهز للاستخدام من سطر الأوامر! 🚀
