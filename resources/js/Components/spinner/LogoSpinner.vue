<template>
  <div class="fixed inset-0 z-[99999] flex items-center justify-center backdrop-blur-md bg-white/30 dark:bg-gray-900/30">
    <div class="flex flex-col items-center gap-6">
      <!-- Logo SVG with animation -->
      <div class="relative">
        <!-- Spinning circle around logo -->
        <svg class="animate-spin h-32 w-32 absolute inset-0" viewBox="0 0 100 100">
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
        <div class="relative z-10 flex items-center justify-center h-32 w-32">
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

      <!-- Loading text below spinner -->
      <div class="text-center">
        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 animate-pulse font-almarai">
          {{ loadingText }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const isDark = ref(false)
const page = usePage()

// Get loading text based on current locale
const loadingText = computed(() => {
  const locale = page.props.locale || 'ar'
  return locale === 'ar' ? 'جاري التحميل...' : 'Loading...'
})

onMounted(() => {
  // Check if dark mode is enabled
  isDark.value = document.documentElement.classList.contains('dark')

  // Watch for dark mode changes
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === 'class') {
        isDark.value = document.documentElement.classList.contains('dark')
      }
    })
  })

  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['class']
  })
})
</script>
