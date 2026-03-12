<template>
  <header
    class="fixed top-0 start-0 end-0 z-50 transition-all duration-300"
    :class="[
      transparent && !scrolled
        ? 'bg-transparent text-white'
        : 'bg-white shadow-md text-[#083064]',
    ]"
  >
    <nav class="container mx-auto px-4 lg:px-8">
      <div class="flex items-center justify-between h-16 lg:h-20">
        <Link href="/" class="flex items-center gap-2">
          <img
            src="/images/logo/logo.svg"
            alt="Logo"
            class="h-10 lg:h-12 w-auto"
            :class="{ 'brightness-0 invert': transparent && !scrolled }"
          />
        </Link>

        <ul class="hidden lg:flex items-center gap-2">
          <li v-for="item in navItems" :key="item.href">
            <a
              :href="item.href"
              class="px-4 py-2 rounded-lg font-medium transition-colors"
              :class="transparent && !scrolled ? 'text-white hover:bg-white/10' : 'text-[#083064] hover:bg-[#E6EBF2] hover:text-[#062650]'"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </a>
          </li>
        </ul>

        <div class="flex items-center gap-2">
          <button
            type="button"
            class="px-4 py-2 rounded-lg font-medium transition-colors border"
            :class="
              transparent && !scrolled
                ? 'border-white/30 hover:bg-white/10 text-white'
                : 'border-[#99AFCB] text-[#062650] hover:bg-[#E6EBF2]'
            "
            @click="$emit('toggle-language')"
          >
            {{ currentLang === 'ar' ? 'EN' : 'عربي' }}
          </button>

          <button
            type="button"
            class="lg:hidden p-2 rounded-lg"
            :class="transparent && !scrolled ? 'text-white hover:bg-white/10' : 'text-[#083064] hover:bg-[#E6EBF2]'"
            aria-label="Menu"
            @click="mobileOpen = !mobileOpen"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <Transition name="slide">
        <div
          v-show="mobileOpen"
          class="lg:hidden absolute top-full start-0 end-0 bg-white shadow-lg border-t border-gray-100 py-4"
        >
          <ul class="flex flex-col px-4">
            <li v-for="item in navItems" :key="item.href">
              <a
                :href="item.href"
                class="block px-4 py-3 rounded-lg font-medium text-[#062650] hover:bg-[#E6EBF2]"
                @click="mobileOpen = false"
              >
                {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
              </a>
            </li>
          </ul>
        </div>
      </Transition>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  transparent: { type: Boolean, default: false },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
})

defineEmits(['toggle-language'])

const mobileOpen = ref(false)

const scrolled = ref(false)

onMounted(() => {
  const onScroll = () => {
    scrolled.value = window.scrollY > 20
  }
  window.addEventListener('scroll', onScroll)
  onScroll()
})
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
