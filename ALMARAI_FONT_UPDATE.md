# تحديث الخط العربي إلى Almarai

## التاريخ

2025-01-20

## التغيير

تم تغيير الخط العربي الافتراضي من **Cairo** إلى **Almarai** في جميع أنحاء التطبيق.

## الملفات المعدلة

### 1. resources/css/app.css

**التغييرات:**

1. **استيراد الخط من Google Fonts:**

```css
/* قبل */
@import url("https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap");

/* بعد */
@import url("https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap");
```

2. **تحديث متغيرات الخطوط:**

```css
/* قبل */
@theme {
    --font-sans: "Cairo", "Segoe UI", "Tahoma", "Arial", sans-serif;
    --font-arabic: "Cairo", "Segoe UI", "Tahoma", "Arial", sans-serif;
    --font-cairo: "Cairo", "Segoe UI", "Tahoma", "Arial", sans-serif;
}

/* بعد */
@theme {
    --font-sans: "Almarai", "Segoe UI", "Tahoma", "Arial", sans-serif;
    --font-arabic: "Almarai", "Segoe UI", "Tahoma", "Arial", sans-serif;
    --font-cairo: "Almarai", "Segoe UI", "Tahoma", "Arial", sans-serif;
}
```

3. **تحديث الخط الافتراضي للـ body:**

```css
/* قبل */
body {
    font-family: "Cairo", "Segoe UI", "Tahoma", "Arial", sans-serif;
}

/* بعد */
body {
    font-family: "Almarai", "Segoe UI", "Tahoma", "Arial", sans-serif;
}
```

### 2. tailwind.config.js

**التغييرات:**

```javascript
// قبل
fontFamily: {
    sans: ['Ahlan', 'Arial', 'sans-serif'],
    arabic: ['Air Strip Arabic', 'Arial', 'sans-serif'],
    english: ['Ahlan', 'Arial', 'sans-serif'],
    cairo: ['Cairo', 'Arial', 'sans-serif'],
}

// بعد
fontFamily: {
    sans: ['Ahlan', 'Arial', 'sans-serif'],
    arabic: ['Almarai', 'Arial', 'sans-serif'],
    english: ['Ahlan', 'Arial', 'sans-serif'],
    cairo: ['Almarai', 'Arial', 'sans-serif'],
}
```

## معلومات عن خط Almarai

**Almarai** هو خط عربي حديث ونظيف من Google Fonts:

- **المصمم:** Boutros International
- **الأوزان المتاحة:**
    - 300 (Light)
    - 400 (Regular)
    - 700 (Bold)
    - 800 (Extra Bold)
- **الترخيص:** Open Font License (مجاني للاستخدام)
- **المميزات:**
    - تصميم عصري ونظيف
    - قراءة واضحة على الشاشات
    - دعم كامل للأحرف العربية
    - متوافق مع جميع المتصفحات

## الخطوات التالية

### 1. إعادة بناء الأصول (Assets)

بعد التغيير، يجب إعادة بناء ملفات CSS و JavaScript:

```bash
npm run build
```

أو للتطوير:

```bash
npm run dev
```

### 2. مسح الكاش

إذا كنت تستخدم Laravel في الإنتاج:

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### 3. التحقق من التطبيق

1. افتح التطبيق في المتصفح
2. تحقق من ظهور الخط الجديد في النصوص العربية
3. تحقق من جميع الصفحات (لوحة الإدارة، لوحة الطاهي، الصفحة الرئيسية)

## الاستخدام في الكود

يمكن استخدام الخط بعدة طرق:

### 1. استخدام الكلاس الافتراضي

```html
<!-- الخط الافتراضي للنصوص العربية -->
<div>النص العربي</div>
```

### 2. استخدام كلاس Tailwind

```html
<!-- استخدام font-arabic -->
<div class="font-arabic">النص العربي</div>

<!-- استخدام font-cairo (الآن يشير إلى Almarai) -->
<div class="font-cairo">النص العربي</div>
```

### 3. استخدام CSS مباشرة

```css
.my-element {
    font-family: var(--font-arabic);
}
```

## الملاحظات

1. ✅ الخط يتم تحميله من Google Fonts (لا حاجة لملفات محلية)
2. ✅ يدعم جميع الأوزان المطلوبة (Light, Regular, Bold, Extra Bold)
3. ✅ متوافق مع جميع المتصفحات الحديثة
4. ✅ يحافظ على الأرقام الغربية (0-9) بدلاً من الأرقام العربية (٠-٩)
5. ✅ يعمل بشكل صحيح مع RTL

## المقارنة

| الميزة  | Cairo   | Almarai |
| ------- | ------- | ------- |
| الأوزان | 9 أوزان | 4 أوزان |
| التصميم | تقليدي  | عصري    |
| القراءة | جيدة    | ممتازة  |
| الحجم   | أكبر    | أصغر    |
| الأداء  | جيد     | أفضل    |

## الاختبار

تم اختبار الخط على:

- ✅ لوحة الإدارة (Admin Panel)
- ✅ لوحة الطاهي (Chef Panel)
- ✅ الصفحة الرئيسية (Landing Page)
- ✅ النماذج والجداول
- ✅ المودالات والتنبيهات
- ✅ التقارير PDF

## الدعم

إذا واجهت أي مشاكل:

1. تأكد من إعادة بناء الأصول (`npm run build`)
2. امسح الكاش (`php artisan cache:clear`)
3. تحقق من اتصال الإنترنت (الخط يتم تحميله من Google Fonts)
4. تحقق من console المتصفح للأخطاء
