<template>
  <nav
    :class="[
      'fixed top-0 left-0 right-0 z-[1000]',
      navbarClasses
    ]"
  >
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <Link href="/" class="flex items-center gap-2">
            <img
              v-if="logoUrl"
              :src="logoUrl"
              :alt="logoAlt"
              class="h-10 w-auto object-contain"
              @error="handleImageError"
            />
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-1">
          <template v-for="item in navItems" :key="item.href">
            <Link
              v-if="!item.href.startsWith('#')"
              :href="item.href"
              :class="navLinkClasses"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </Link>
            <a
              v-else
              :href="item.href"
              :class="[
                navLinkClasses,
                activeSection === item.href.slice(1)
                  ? 'text-primary dark:text-secondary font-semibold'
                  : ''
              ]"
              @click="handleNavClick"
            >
              {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
            </a>
          </template>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <!-- Dark Mode Toggle -->
          <button
            v-if="showDarkModeToggle"
            @click="toggleDarkMode"
            class="p-2 rounded-lg text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-secondary transition-colors duration-200"
          >
            <svg v-if="isDarkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
          </button>

          <!-- Language Switcher -->
          <button
            v-if="showLanguageSwitcher"
            @click="toggleLanguage"
            class="flex items-center gap-2 px-4 py-2 font-semibold rounded-lg bg-secondary text-primary hover:bg-secondary-600 transition-colors duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
            </svg>
            {{ currentLang === 'ar' ? 'English' : 'عربي' }}
          </button>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="lg:hidden p-2 rounded-lg text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-secondary transition-colors duration-200"
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

    <!-- Mobile Menu - Full Screen Overlay -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isMobileMenuOpen"
        class="lg:hidden fixed inset-0 top-16 bg-white dark:bg-gray-900 z-50"
      >
        <div class="container mx-auto px-4 py-8">
          <div class="space-y-2">
            <template v-for="item in navItems" :key="'mobile-' + item.href">
              <Link
                v-if="!item.href.startsWith('#')"
                :href="item.href"
                class="block px-6 py-4 text-xl font-bold text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-secondary rounded-xl transition-colors duration-200"
                @click="closeMobileMenu"
              >
                {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
              </Link>
              <a
                v-else
                :href="item.href"
                :class="[
                  'block px-6 py-4 text-xl font-bold rounded-xl transition-colors duration-200',
                  activeSection === item.href.slice(1)
                    ? 'bg-primary text-white'
                    : 'text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-secondary'
                ]"
                @click="closeMobileMenu"
              >
                {{ currentLang === 'ar' ? item.label_ar : item.label_en }}
              </a>
            </template>
          </div>
        </div>
      </div>
    </Transition>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  logo: { type: String, default: null },
  logoAlt: { type: String, default: 'Logo' },
  navItems: { type: Array, default: () => [] },
  currentLang: { type: String, default: 'ar' },
  showLanguageSwitcher: { type: Boolean, default: true },
  showDarkModeToggle: { type: Boolean, default: true },
  isDarkMode: { type: Boolean, default: false },
  transparent: { type: Boolean, default: false }
})

const emit = defineEmits(['toggle-language', 'toggle-dark-mode'])

const isMobileMenuOpen = ref(false)
const isScrolled = ref(false)
const activeSection = ref('hero')

const logoUrl = computed(() => {
  if (!props.logo) return '/images/logo/logo.png'
  if (props.logo.startsWith('http') || props.logo.startsWith('/')) return props.logo
  return `/storage/${props.logo}`
})

// Navbar classes - professional style with transparency support
const navbarClasses = computed(() => {
  if (isScrolled.value || !props.transparent) {
    return 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 shadow-sm'
  }
  // When transparent (hero section), use transparent with blur
  return 'bg-transparent'
})

// Professional link classes with smooth transitions
const navLinkClasses = computed(() => {
  const base = 'px-4 py-2 font-semibold rounded-lg transition-all duration-300'

  if (isScrolled.value || !props.transparent) {
    return `${base} text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-secondary hover:bg-gray-50 dark:hover:bg-gray-800`
  }
  return `${base} text-white/90 hover:text-white hover:bg-white/10`
})

const handleScroll = () => {
  if (typeof window !== 'undefined') {
    isScrolled.value = window.scrollY > 50

    // Update active section based on scroll position
    const sections = props.navItems
      .filter((item) => item.href.startsWith('#'))
      .map((item) => item.href.slice(1))

    for (const sectionId of sections.reverse()) {
      const element = document.getElementById(sectionId)
      if (element) {
        const rect = element.getBoundingClientRect()
        if (rect.top <= 100) {
          activeSection.value = sectionId
          break
        }
      }
    }
  }
}

const handleNavClick = () => {
  closeMobileMenu()
}

const handleImageError = (e) => {
  e.target.src = '/images/logo/logo.png'
}

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
  if (isMobileMenuOpen.value) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
  document.body.style.overflow = ''
}

const toggleLanguage = () => {
  emit('toggle-language')
}

const toggleDarkMode = () => {
  emit('toggle-dark-mode')
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    window.addEventListener('scroll', handleScroll, { passive: true })
    handleScroll()
  }
})

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('scroll', handleScroll)
    document.body.style.overflow = ''
  }
})
</script>
