<template>
  <nav class="fixed top-0 left-0 right-0 z-[1000] bg-white shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        
        <!-- Logo -->
        <div class="flex-shrink-0">
          <Link href="/" class="flex items-center gap-2">
            <img 
              v-if="logoUrl" 
              :src="logoUrl" 
              :alt="logoAlt" 
              class="h-12 w-auto object-contain"
              @error="handleImageError"
            />
          </Link>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-8">
          <template v-for="item in navItems" :key="item.href">
            <Link
              v-if="!item.href.startsWith('#')"
              :href="item.href"
              class="text-gray-700 hover:text-primary font-medium transition-colors"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </Link>
            <a
              v-else
              :href="item.href"
              class="text-gray-700 hover:text-primary font-medium transition-colors"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </a>
          </template>
        </div>
        
        <!-- Language Switcher -->
        <div class="flex items-center gap-3">
          <button
            v-if="showLanguageSwitcher"
            @click="toggleLanguage"
            class="flex items-center gap-2 px-4 py-2 bg-secondary text-primary font-semibold rounded-full hover:bg-secondary-600 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
            </svg>
            {{ currentLang === 'ar' ? 'English' : 'عربي' }}
          </button>
          
          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="lg:hidden p-2 text-gray-700 hover:text-primary"
          >
            <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Mobile Menu -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="isMobileMenuOpen" class="lg:hidden bg-white border-t shadow-lg">
        <div class="container mx-auto px-4 py-4 space-y-2">
          <template v-for="item in navItems" :key="'mobile-' + item.href">
            <Link
              v-if="!item.href.startsWith('#')"
              :href="item.href"
              class="block px-4 py-3 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-lg font-medium"
              @click="closeMobileMenu"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </Link>
            <a
              v-else
              :href="item.href"
              class="block px-4 py-3 text-gray-700 hover:text-primary hover:bg-gray-50 rounded-lg font-medium"
              @click="closeMobileMenu"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </a>
          </template>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  logo: { type: String, default: null },
  logoAlt: { type: String, default: 'Logo' },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
  showLanguageSwitcher: { type: Boolean, default: true },
  showDarkModeToggle: { type: Boolean, default: false },
  isDarkMode: { type: Boolean, default: false },
  transparent: { type: Boolean, default: false }
})

const emit = defineEmits(['toggle-language', 'toggle-dark-mode'])
const isMobileMenuOpen = ref(false)

const logoUrl = computed(() => {
  if (!props.logo) return '/images/logo/logo.png'
  if (props.logo.startsWith('http') || props.logo.startsWith('/')) return props.logo
  return `/storage/${props.logo}`
})

const handleImageError = (e) => { e.target.src = '/images/logo/logo.png' }
const toggleMobileMenu = () => { isMobileMenuOpen.value = !isMobileMenuOpen.value }
const closeMobileMenu = () => { isMobileMenuOpen.value = false }
const toggleLanguage = () => { emit('toggle-language') }
</script>
