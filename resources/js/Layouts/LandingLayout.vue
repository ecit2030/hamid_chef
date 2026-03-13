<template>
  <div
    class="min-h-screen bg-white"
    :dir="dir"
  >
    <LandingAppBar
      :transparent="transparent"
      :nav-items="navItems"
      :current-lang="currentLang"
      @toggle-language="$emit('toggle-language')"
    />

    <main>
      <slot />
    </main>

    <LandingFooter :current-lang="currentLang" />

    <FloatingWhatsApp v-if="whatsappUrl" :whatsapp-url="whatsappUrl" />
    <BackToTop v-if="showBackToTop" />
  </div>
</template>

<script setup>
import LandingAppBar from '@/Components/Landing/AppBar.vue'
import LandingFooter from '@/Components/Landing/Footer.vue'
import FloatingWhatsApp from '@/Components/Landing/FloatingWhatsApp.vue'
import BackToTop from '@/Components/Landing/BackToTop.vue'

defineProps({
  transparent: { type: Boolean, default: false },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
  dir: { type: String, default: 'rtl' },
  whatsappUrl: { type: String, default: '' },
  showBackToTop: { type: Boolean, default: true },
})

defineEmits(['toggle-language'])
</script>
