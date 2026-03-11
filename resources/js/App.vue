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
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { setHtmlDirection } from '@/i18n'
import { useNotifications } from '@/composables/useNotifications'
import ThemeProvider from '@/Components/layout/ThemeProvider.vue'
import SidebarProvider from '@/Components/layout/SidebarProvider.vue'
import GlobalLoadingOverlay from '@/Components/common/GlobalLoadingOverlay.vue'

const { locale } = useI18n()
const page = usePage()
const { success, error } = useNotifications()

// Watch for locale changes and update direction
watch(locale, (newLocale) => {
  setHtmlDirection(newLocale)
}, { immediate: true })

// Show flash messages as notifications (e.g. 419 page expired)
watch(() => page.props?.flash, (flash) => {
  if (!flash) return
  if (flash.success) success(flash.success)
  if (flash.error) error(flash.error)
  if (flash.message) success(flash.message)
}, { deep: true })
</script>
