<template>
  <div
    class="min-h-screen"
    :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'"
    :class="isDarkMode ? 'dark bg-gray-900' : 'bg-white'"
  >
    <!-- Simple Loading Overlay -->
    <Transition name="fade">
      <div
        v-if="isLoading"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-white dark:bg-gray-900"
      >
        <div class="text-center">
          <div class="w-16 h-16 mx-auto mb-4 bg-secondary rounded-xl flex items-center justify-center">
            <span class="text-3xl">👨‍🍳</span>
          </div>
          <div class="flex gap-1 justify-center">
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 150ms"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 300ms"></span>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Navbar -->
    <SiteNavbar
      transparent
      :current-lang="currentLocale"
      :nav-items="navItems"
      :is-dark-mode="isDarkMode"
      :show-dark-mode-toggle="true"
      @toggle-language="toggleLanguage"
      @toggle-dark-mode="toggleDarkMode"
    />

    <!-- Hero Section -->
    <HeroSection
      v-if="sections.hero"
      id="hero"
      :section="sections.hero"
      :current-lang="currentLocale"
      :is-dark-mode="isDarkMode"
    />

    <!-- Main Content -->
    <main>
      <!-- Features Section -->
      <FeaturesSection
        v-if="sections.features"
        id="features"
        :section="sections.features"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />

      <!-- How It Works Section -->
      <HowItWorksSection
        v-if="sections.how_it_works"
        id="how-it-works"
        :section="sections.how_it_works"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />

      <!-- Why Us Section -->
      <WhyUsSection
        v-if="sections.why_us"
        id="why-us"
        :section="sections.why_us"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />

      <!-- Top Chefs Section -->
      <TopChefsSection
        v-if="sections.top_chefs"
        id="top-chefs"
        :section="sections.top_chefs"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
        class="bg-gray-50 dark:bg-gray-800"
      />

      <!-- Categories Section -->
      <CategoriesSection
        v-if="sections.categories"
        id="categories"
        :section="sections.categories"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
        class="bg-white dark:bg-gray-900"
      />

      <!-- Testimonials Section -->
      <TestimonialsSection
        v-if="sections.testimonials"
        id="testimonials"
        :section="sections.testimonials"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />

      <!-- About Us Section -->
      <AboutUsSection
        v-if="sections.about_us"
        id="about"
        :section="sections.about_us"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
        class="bg-white dark:bg-gray-900"
      />

      <!-- Vision & Mission Section -->
      <VisionMissionSection
        v-if="sections.vision_mission"
        id="vision-mission"
        :section="sections.vision_mission"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
        class="bg-gray-50 dark:bg-gray-800"
      />

      <!-- Partners Section -->
      <PartnersSection
        v-if="sections.partners"
        id="partners"
        :section="sections.partners"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
        class="bg-gray-50 dark:bg-gray-800"
      />

      <!-- CTA Section -->
      <CTASection
        v-if="sections.cta"
        id="cta"
        :section="sections.cta"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />

      <!-- Contact Section -->
      <ContactSection
        v-if="sections.contact"
        id="contact"
        :section="sections.contact"
        :current-lang="currentLocale"
        :is-dark-mode="isDarkMode"
      />
    </main>

    <!-- Footer -->
    <SiteFooter :current-lang="currentLocale" :is-dark-mode="isDarkMode" />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import {
  HeroSection,
  WhyUsSection,
  HowItWorksSection,
  FeaturesSection,
  TestimonialsSection,
  ContactSection,
  AboutUsSection,
  VisionMissionSection,
  TopChefsSection,
  CategoriesSection,
  CTASection,
  PartnersSection,
  SiteNavbar,
  SiteFooter
} from '@/Components/site'

const props = defineProps({
  sections: {
    type: Object,
    required: true
  },
  locale: {
    type: String,
    default: 'ar'
  }
})

const currentLocale = ref(props.locale)
const isDarkMode = ref(false)
const isLoading = ref(true)

const navItems = ref([
  { href: '#hero', label_ar: 'الرئيسية', label_en: 'Home' },
  { href: '#features', label_ar: 'المميزات', label_en: 'Features' },
  { href: '#how-it-works', label_ar: 'كيف يعمل', label_en: 'How It Works' },
  { href: '#why-us', label_ar: 'لماذا نحن', label_en: 'Why Us' },
  { href: '#top-chefs', label_ar: 'أفضل الطهاة', label_en: 'Top Chefs' },
  { href: '#categories', label_ar: 'التصنيفات', label_en: 'Categories' },
  { href: '#about', label_ar: 'من نحن', label_en: 'About' },
  { href: '#partners', label_ar: 'الشركاء', label_en: 'Partners' },
  { href: '#contact', label_ar: 'تواصل معنا', label_en: 'Contact' }
])

// Initialize dark mode from localStorage or system preference
const initDarkMode = () => {
  const savedMode = localStorage.getItem('darkMode')
  if (savedMode !== null) {
    isDarkMode.value = savedMode === 'true'
  } else {
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches
  }

  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

// Watch for dark mode changes
watch(isDarkMode, (newValue) => {
  if (newValue) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
  localStorage.setItem('darkMode', String(newValue))
})

onMounted(() => {
  initDarkMode()
  setTimeout(() => {
    isLoading.value = false
  }, 500)
})

const toggleLanguage = () => {
  const newLocale = currentLocale.value === 'ar' ? 'en' : 'ar'
  router.post(route('locale.switch'), { locale: newLocale }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      currentLocale.value = newLocale
      window.location.reload()
    }
  })
}

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
