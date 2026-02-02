# Terms and Conditions API - Complete Documentation

## ✅ Status: COMPLETE

تم إنشاء وتفعيل API الشروط والأحكام بنجاح مع دعم كامل للغتين العربية والإنجليزية.

---

## 📋 Features

✅ **Public API** - لا يتطلب مصادقة (authentication)
✅ **Bilingual Support** - يعيد البيانات بالعربي والإنجليزي معاً
✅ **Version Management** - دعم إصدارات متعددة
✅ **Active Terms** - جلب الشروط النشطة تلقائياً
✅ **Full Testing** - 6 اختبارات ناجحة 100%

---

## 🔗 API Endpoints

### 1. Get Active Terms and Conditions

```http
GET /api/terms-and-conditions
```

**Response:**

```json
{
    "success": true,
    "message": "تم جلب الشروط والأحكام بنجاح",
    "status_code": 200,
    "data": {
        "id": 1,
        "title_ar": "الشروط والأحكام",
        "title_en": "Terms and Conditions",
        "content_ar": "محتوى الشروط والأحكام بالعربي...",
        "content_en": "Terms and Conditions content in English...",
        "version": "1.0",
        "is_active": true,
        "effective_date": "2026-02-01T12:00:00.000000Z",
        "created_at": "2026-02-01T12:00:00.000000Z",
        "updated_at": "2026-02-01T12:00:00.000000Z"
    }
}
```

---

### 2. Get All Versions

```http
GET /api/terms-and-conditions/versions
```

**Response:**

```json
{
    "success": true,
    "message": "تم جلب جميع إصدارات الشروط والأحكام بنجاح",
    "status_code": 200,
    "data": [
        {
            "id": 1,
            "title_ar": "الشروط والأحكام - النسخة 1",
            "title_en": "Terms and Conditions - Version 1",
            "content_ar": "...",
            "content_en": "...",
            "version": "1.0",
            "is_active": true,
            "effective_date": "2026-02-01T12:00:00.000000Z",
            "created_at": "2026-02-01T12:00:00.000000Z",
            "updated_at": "2026-02-01T12:00:00.000000Z"
        },
        {
            "id": 2,
            "title_ar": "الشروط والأحكام - النسخة 2",
            "title_en": "Terms and Conditions - Version 2",
            "content_ar": "...",
            "content_en": "...",
            "version": "2.0",
            "is_active": false,
            "effective_date": "2026-03-01T12:00:00.000000Z",
            "created_at": "2026-02-01T12:00:00.000000Z",
            "updated_at": "2026-02-01T12:00:00.000000Z"
        }
    ]
}
```

---

### 3. Get Specific Version by ID

```http
GET /api/terms-and-conditions/{id}
```

**Response:**

```json
{
    "success": true,
    "message": "تم جلب الشروط والأحكام بنجاح",
    "status_code": 200,
    "data": {
        "id": 2,
        "title_ar": "الشروط والأحكام - النسخة 2",
        "title_en": "Terms and Conditions - Version 2",
        "content_ar": "...",
        "content_en": "...",
        "version": "2.0",
        "is_active": false,
        "effective_date": "2026-03-01T12:00:00.000000Z",
        "created_at": "2026-02-01T12:00:00.000000Z",
        "updated_at": "2026-02-01T12:00:00.000000Z"
    }
}
```

---

## 🧪 Testing

تم إنشاء 6 اختبارات شاملة:

```bash
php artisan test --filter=TermsAndConditionsApiTest
```

### Test Results: ✅ 6/6 PASSED

1. ✅ `it_can_get_active_terms_and_conditions_without_auth`
2. ✅ `it_returns_both_arabic_and_english_content`
3. ✅ `it_can_get_all_versions_without_auth`
4. ✅ `it_can_get_specific_version_by_id_without_auth`
5. ✅ `it_returns_404_when_no_active_terms_exist`
6. ✅ `it_returns_404_when_specific_version_not_found`

---

## 📱 Mobile App Integration

### استخدام الـ API في التطبيق

```javascript
// Get active terms
const response = await fetch(
    "https://your-domain.com/api/terms-and-conditions",
);
const data = await response.json();

// Display based on user language
const title = userLanguage === "ar" ? data.data.title_ar : data.data.title_en;
const content =
    userLanguage === "ar" ? data.data.content_ar : data.data.content_en;
```

### React Native Example

```javascript
import React, { useEffect, useState } from "react";
import { View, Text, ScrollView } from "react-native";

const TermsScreen = () => {
    const [terms, setTerms] = useState(null);
    const userLanguage = "ar"; // or 'en'

    useEffect(() => {
        fetch("https://your-domain.com/api/terms-and-conditions")
            .then((res) => res.json())
            .then((data) => setTerms(data.data));
    }, []);

    if (!terms) return <Text>Loading...</Text>;

    return (
        <ScrollView>
            <Text style={{ fontSize: 24, fontWeight: "bold" }}>
                {userLanguage === "ar" ? terms.title_ar : terms.title_en}
            </Text>
            <Text style={{ fontSize: 16, marginTop: 20 }}>
                {userLanguage === "ar" ? terms.content_ar : terms.content_en}
            </Text>
            <Text style={{ fontSize: 12, color: "gray", marginTop: 20 }}>
                Version: {terms.version}
            </Text>
        </ScrollView>
    );
};
```

---

## 📂 Files Created/Modified

### Backend

- ✅ `app/Http/Controllers/Api/TermsAndConditionsController.php` (Modified)
- ✅ `app/DTOs/TermsAndConditionsDTO.php` (Already exists)
- ✅ `database/factories/TermsAndConditionsFactory.php` (Created)
- ✅ `tests/Feature/Api/TermsAndConditionsApiTest.php` (Created)

### Routes

- ✅ `routes/api.php` (Already configured)

---

## 🎯 Key Features

### 1. Bilingual Response

الـ API يعيد البيانات بالعربي والإنجليزي معاً في نفس الاستجابة، مما يسمح للتطبيق باختيار اللغة المناسبة.

### 2. No Authentication Required

جميع endpoints عامة ولا تتطلب مصادقة، مما يسهل الوصول إليها من التطبيق.

### 3. Version Management

يمكن الوصول إلى جميع الإصدارات أو إصدار محدد، مع تحديد الإصدار النشط تلقائياً.

### 4. Error Handling

- 404: عند عدم وجود شروط نشطة
- 404: عند طلب إصدار غير موجود

---

## 🚀 Next Steps

1. ✅ API جاهز للاستخدام
2. ✅ الاختبارات ناجحة 100%
3. ✅ دعم كامل للغتين
4. ✅ بدون مصادقة (public)

يمكنك الآن استخدام الـ API في التطبيق مباشرة!
