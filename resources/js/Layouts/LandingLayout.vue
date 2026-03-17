<template>
  <!-- Always LTR layout so structure matches English; RTL applied to text only via .landing-layout-ar -->
  <div
    class="min-h-screen bg-white"
    dir="ltr"
    :class="{ 'landing-layout-ar': currentLang === 'ar' }"
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

    <FloatingWhatsApp v-if="whatsappUrl" :whatsapp-url="whatsappUrl" :aria-label="ariaLabelWhatsapp" />
    <BackToTop v-if="showBackToTop" :aria-label="ariaLabelBackToTop" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import LandingAppBar from '@/Components/Landing/AppBar.vue'
import LandingFooter from '@/Components/Landing/Footer.vue'
import FloatingWhatsApp from '@/Components/Landing/FloatingWhatsApp.vue'
import BackToTop from '@/Components/Landing/BackToTop.vue'
import { getLandingLabels } from '@/data/landingLabels'

const props = defineProps({
  transparent: { type: Boolean, default: false },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
  whatsappUrl: { type: String, default: '' },
  showBackToTop: { type: Boolean, default: true },
})

const labels = computed(() => getLandingLabels(props.currentLang))
const ariaLabelBackToTop = computed(() => labels.value.aria?.backToTop ?? 'Back to top')
const ariaLabelWhatsapp = computed(() => labels.value.aria?.whatsapp ?? 'Contact via WhatsApp')

defineEmits(['toggle-language'])
</script>
