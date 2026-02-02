# تحديث عنوان التاب والأيقونة - Browser Tab Title & Favicon Update

## التغييرات المطبقة | Applied Changes

### 1. تحديث عنوان التاب (Browser Tab Title)

تم تغيير عنوان التاب من "Laravel" إلى "Mon Chef".

Changed the browser tab title from "Laravel" to "Mon Chef".

**قبل:**

```html
<title inertia>{{ config('app.name', 'Laravel') }}</title>
```

**بعد:**

```html
<title inertia>Mon Chef</title>
```

### 2. التحقق من الأيقونة (Favicon)

الأيقونة موجودة بالفعل في `public/favicon.svg` وتحتوي على شعار MoonChef (هلال مع قبعة طاهي).

The favicon already exists at `public/favicon.svg` and contains the MoonChef logo (crescent moon with chef hat).

**الأيقونات المضافة:**

```html
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="apple-touch-icon" href="/favicon.svg" />
```

## الملفات المعدلة | Modified Files

- `resources/views/app.blade.php` - تحديث العنوان

## الملفات الموجودة | Existing Files

- `public/favicon.svg` - أيقونة الموقع (MoonChef Logo)

## النتيجة | Result

الآن عند فتح أي صفحة في الموقع:

- ✅ عنوان التاب: **Mon Chef**
- ✅ أيقونة التاب: شعار MoonChef (هلال مع قبعة طاهي)

Now when opening any page on the site:

- ✅ Tab Title: **Mon Chef**
- ✅ Tab Icon: MoonChef logo (crescent moon with chef hat)

## ملاحظات | Notes

### عنوان ديناميكي لكل صفحة

إذا أردت عنواناً مختلفاً لكل صفحة، يمكنك استخدام `Head` component في Vue:

If you want a different title for each page, you can use the `Head` component in Vue:

```vue
<script setup>
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="اسم الصفحة - Mon Chef" />
    <!-- محتوى الصفحة -->
</template>
```

### أنواع الأيقونات المدعومة

- **SVG**: الأفضل للجودة العالية والحجم الصغير (مستخدم حالياً)
- **PNG**: يمكن إضافة أحجام مختلفة (16x16, 32x32, 192x192)
- **ICO**: للمتصفحات القديمة

### إضافة أيقونات إضافية (اختياري)

إذا أردت دعم أفضل لجميع الأجهزة:

```html
<!-- أيقونات بأحجام مختلفة -->
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />

<!-- للأندرويد -->
<link rel="manifest" href="/site.webmanifest" />

<!-- لون شريط العنوان في الموبايل -->
<meta name="theme-color" content="#083064" />
```

## الاختبار | Testing

1. افتح الموقع في المتصفح
2. انظر إلى التاب في الأعلى
3. يجب أن ترى:
    - النص: "Mon Chef"
    - الأيقونة: شعار MoonChef

## مثال على الكود الكامل | Complete Code Example

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Title & Favicon -->
        <title inertia>Mon Chef</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
        <link rel="apple-touch-icon" href="/favicon.svg" />

        <!-- Fonts & Styles -->
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            rel="stylesheet"
        />

        @routes @vite(['resources/js/app.js']) @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
```

---

**التاريخ | Date:** 2026-02-01
**الحالة | Status:** ✅ مكتمل | Complete
