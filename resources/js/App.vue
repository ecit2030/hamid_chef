<template>
  <ThemeProvider>
    <SidebarProvider>
      <GlobalLoadingOverlay />
      <slot />
    </SidebarProvider>
  </ThemeProvider>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { setHtmlDirection } from '@/i18n'
import ThemeProvider from '@/Components/layout/ThemeProvider.vue'
import SidebarProvider from '@/Components/layout/SidebarProvider.vue'
import GlobalLoadingOverlay from '@/Components/common/GlobalLoadingOverlay.vue'

// Watch for locale changes and update direction
const { locale } = useI18n()
watch(locale, (newLocale) => {
  setHtmlDirection(newLocale)
}, { immediate: true })
</script>
