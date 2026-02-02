# دليل الاستخدام السريع - الشروط والأحكام

## 🚀 البدء السريع

### 1. الوصول للنظام

افتح المتصفح وانتقل إلى:

```
http://your-domain.com/admin/terms-and-conditions
```

### 2. إنشاء شروط وأحكام جديدة

1. اضغط على زر **"إضافة شروط وأحكام جديدة"**
2. املأ النموذج:
    - **العنوان بالعربية**: مثل "الشروط والأحكام - الإصدار 2.0"
    - **العنوان بالإنجليزية**: مثل "Terms and Conditions - Version 2.0"
    - **المحتوى بالعربية**: اكتب الشروط والأحكام بالعربية
    - **المحتوى بالإنجليزية**: اكتب الشروط والأحكام بالإنجليزية
    - **الإصدار**: مثل "2.0"
    - **تاريخ السريان**: اختر التاريخ
    - **تعيين كنسخة نشطة**: فعّل إذا أردت تفعيلها مباشرة
3. اضغط **"حفظ"**

### 3. تعديل شروط وأحكام موجودة

1. اضغط على **"تعديل"** بجانب النسخة المطلوبة
2. عدّل البيانات
3. اضغط **"حفظ"**

### 4. تفعيل نسخة

اضغط على **"تفعيل"** بجانب النسخة المطلوبة

> ⚠️ سيتم تعطيل النسخة النشطة الحالية تلقائياً

### 5. حذف نسخة

اضغط على **"حذف"** بجانب النسخة المطلوبة

---

## 📱 استخدام API في التطبيق

### الحصول على النسخة النشطة

```javascript
// JavaScript/React Native
fetch("https://api.example.com/api/terms-and-conditions?locale=ar")
    .then((response) => response.json())
    .then((data) => {
        console.log(data.data.title); // العنوان
        console.log(data.data.content); // المحتوى
        console.log(data.data.version); // الإصدار
    });
```

```dart
// Flutter
Future<void> getTerms() async {
  final response = await http.get(
    Uri.parse('https://api.example.com/api/terms-and-conditions?locale=ar'),
  );

  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    print(data['data']['title']);
    print(data['data']['content']);
  }
}
```

```swift
// Swift/iOS
let url = URL(string: "https://api.example.com/api/terms-and-conditions?locale=ar")!
URLSession.shared.dataTask(with: url) { data, response, error in
    if let data = data {
        let json = try? JSONSerialization.jsonObject(with: data)
        print(json)
    }
}.resume()
```

---

## 🔗 API Endpoints

### 1. النسخة النشطة

```
GET /api/terms-and-conditions?locale=ar
```

### 2. جميع الإصدارات

```
GET /api/terms-and-conditions/versions?locale=ar
```

### 3. نسخة محددة

```
GET /api/terms-and-conditions/{id}?locale=en
```

---

## 💡 نصائح

### ✅ أفضل الممارسات

1. **استخدم نظام الإصدارات**: 1.0, 1.1, 2.0
2. **حدد تاريخ السريان**: لتوثيق متى تم تفعيل كل نسخة
3. **احتفظ بالنسخ القديمة**: لا تحذفها للرجوع إليها عند الحاجة
4. **اكتب محتوى واضح**: استخدم نقاط وعناوين فرعية
5. **راجع المحتوى**: قبل التفعيل تأكد من صحة المحتوى

### ⚠️ تحذيرات

- عند تفعيل نسخة جديدة، سيتم تعطيل النسخة الحالية تلقائياً
- الحذف نهائي (Soft Delete) ولكن يمكن استرجاعه من قاعدة البيانات
- تأكد من كتابة المحتوى بكلا اللغتين

---

## 🎨 تخصيص العرض

### في التطبيق الموبايل

يمكنك عرض الشروط والأحكام بطرق مختلفة:

1. **صفحة كاملة**: عرض المحتوى في صفحة منفصلة
2. **Modal/Dialog**: عرض المحتوى في نافذة منبثقة
3. **WebView**: عرض المحتوى كـ HTML
4. **Markdown**: إذا كان المحتوى بصيغة Markdown

### مثال عرض في Modal (React Native)

```jsx
import React, { useState, useEffect } from "react";
import { Modal, ScrollView, Text } from "react-native";

function TermsModal() {
    const [terms, setTerms] = useState(null);

    useEffect(() => {
        fetch("https://api.example.com/api/terms-and-conditions?locale=ar")
            .then((res) => res.json())
            .then((data) => setTerms(data.data));
    }, []);

    return (
        <Modal visible={true}>
            <ScrollView>
                <Text style={{ fontSize: 24 }}>{terms?.title}</Text>
                <Text>{terms?.content}</Text>
            </ScrollView>
        </Modal>
    );
}
```

---

## 📞 الدعم

إذا واجهت أي مشكلة:

1. راجع ملف `TERMS_AND_CONDITIONS_SYSTEM.md` للتوثيق الكامل
2. تحقق من الـ logs في `storage/logs/laravel.log`
3. تواصل مع فريق التطوير

---

## ✨ مميزات إضافية (قريباً)

- [ ] Rich Text Editor لتنسيق المحتوى
- [ ] معاينة قبل الحفظ
- [ ] تتبع موافقة المستخدمين
- [ ] إشعارات عند التحديث
- [ ] تصدير كـ PDF

---

**تم الإنشاء**: 1 فبراير 2026
**الإصدار**: 1.0
