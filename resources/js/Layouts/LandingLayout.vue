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
  </div>
</template>

<script setup>
import LandingAppBar from '@/Components/Landing/AppBar.vue'
import LandingFooter from '@/Components/Landing/Footer.vue'

defineProps({
  transparent: { type: Boolean, default: false },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
  dir: { type: String, default: 'rtl' },
})

defineEmits(['toggle-language'])
</script>
