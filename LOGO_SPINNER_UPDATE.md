# تحديث السبينر - Logo Spinner Update

## التغييرات المطبقة | Applied Changes

### 1. تغيير لون الدائرة الدوارة

تم تغيير لون الدائرة الدوارة حول الشعار إلى اللون الأساسي الجديد `#083064`.

Changed the spinning circle color around the logo to the new primary color `#083064`.

**قبل:**

```vue
stroke="currentColor" :class="isDark ? 'text-[#edbb5f]' : 'text-[#0a0a0a]'"
```

**بعد:**

```vue
stroke="#083064"
```

### 2. إضافة دعم اللغتين (عربي/إنجليزي)

تم إضافة دعم تلقائي للغة العربية والإنجليزية بناءً على اللغة الحالية للنظام.

Added automatic support for Arabic and English based on the current system locale.

**النصوص:**

- العربية: "جاري التحميل..."
- الإنجليزية: "Loading..."

### 3. تطبيق خط Almarai

تم إضافة class `font-almarai` للنص لضمان استخدام الخط الجديد.

Added `font-almarai` class to the text to ensure the new font is used.

## الكود المحدث | Updated Code

```vue
<template>
    <div
        class="fixed inset-0 z-[99999] flex items-center justify-center backdrop-blur-md bg-white/30 dark:bg-gray-900/30"
    >
        <div class="flex flex-col items-center gap-6">
            <!-- Logo SVG with animation -->
            <div class="relative">
                <!-- Spinning circle with primary color #083064 -->
                <svg
                    class="animate-spin h-32 w-32 absolute inset-0"
                    viewBox="0 0 100 100"
                >
                    <circle
                        class="opacity-25"
                        cx="50"
                        cy="50"
                        r="45"
                        stroke="currentColor"
                        stroke-width="8"
                        fill="none"
                        :class="isDark ? 'text-gray-700' : 'text-gray-200'"
                    />
                    <circle
                        class="opacity-75"
                        cx="50"
                        cy="50"
                        r="45"
                        stroke="#083064"
                        stroke-width="8"
                        fill="none"
                        stroke-dasharray="70 200"
                        stroke-linecap="round"
                    />
                </svg>

                <!-- Logo in center -->
                <div
                    class="relative z-10 flex items-center justify-center h-32 w-32"
                >
                    <img
                        v-if="isDark"
                        src="/images/logo/logo-dark.svg"
                        alt="Logo"
                        class="h-16 w-16 object-contain drop-shadow-2xl"
                    />
                    <img
                        v-else
                        src="/images/logo/logo.svg"
                        alt="Logo"
                        class="h-16 w-16 object-contain drop-shadow-2xl"
                    />
                </div>
            </div>

            <!-- Loading text with bilingual support -->
            <div class="text-center">
                <p
                    class="text-lg font-semibold text-gray-900 dark:text-gray-100 animate-pulse font-almarai"
                >
                    {{ loadingText }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const isDark = ref(false);
const page = usePage();

// Get loading text based on current locale
const loadingText = computed(() => {
    const locale = page.props.locale || "ar";
    return locale === "ar" ? "جاري التحميل..." : "Loading...";
});

onMounted(() => {
    isDark.value = document.documentElement.classList.contains("dark");

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === "class") {
                isDark.value =
                    document.documentElement.classList.contains("dark");
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ["class"],
    });
});
</script>
```

## الملفات المعدلة | Modified Files

- `resources/js/Components/spinner/LogoSpinner.vue`

## المميزات | Features

1. ✅ لون دائرة التحميل: `#083064` (اللون الأساسي الجديد)
2. ✅ دعم اللغة العربية والإنجليزية تلقائياً
3. ✅ خط Almarai للنص
4. ✅ دعم الوضع الداكن (Dark Mode)
5. ✅ رسوم متحركة سلسة (Smooth animations)
6. ✅ شعار ديناميكي حسب الوضع (Light/Dark)

## الاستخدام | Usage

```vue
<template>
    <LogoSpinner v-if="loading" />
</template>

<script setup>
import LogoSpinner from "@/Components/spinner/LogoSpinner.vue";
import { ref } from "vue";

const loading = ref(true);

// Simulate loading
setTimeout(() => {
    loading.value = false;
}, 2000);
</script>
```

## ملاحظات | Notes

- السبينر يظهر في منتصف الشاشة مع خلفية شفافة
- النص يتغير تلقائياً حسب اللغة المختارة في النظام
- الدائرة الدوارة باللون الأساسي `#083064` في جميع الأوضاع
- الشعار يتغير تلقائياً بين النسخة العادية والداكنة

---

**التاريخ | Date:** 2026-02-01
**الحالة | Status:** ✅ مكتمل | Complete
