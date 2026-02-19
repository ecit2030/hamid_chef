<template>
  <div
    class="min-h-screen transition-colors duration-500"
    :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'"
    :class="{ 'dark': isDarkMode }"
  >
    <!-- Loading Overlay -->
    <Transition name="fade">
      <div
        v-if="isLoading"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-white dark:bg-gray-900 transition-colors duration-500"
      >
        <div class="text-center">
          <div class="w-20 h-20 mx-auto mb-4 bg-secondary rounded-2xl flex items-center justify-center animate-bounce">
            <span class="text-4xl">👨‍🍳</span>
          </div>
          <div class="flex gap-1 justify-center">
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 0ms"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 150ms"></span>
            <span class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 300ms"></span>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Unified Background for entire page -->
    <div
      class="fixed inset-0 -z-10 transition-colors duration-500"
      :class="isDarkMode ? 'bg-gray-900' : 'bg-gradient-to-b from-white via-secondary/20 to-secondary/30'"
    >
      <!-- Cooking Pattern Overlay -->
      <div
        class="absolute inset-0 transition-opacity duration-500"
        :class="isDarkMode ? 'opacity-[0.02]' : 'opacity-[0.04]'"
        style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;80&quot; height=&quot;80&quot; viewBox=&quot;0 0 80 80&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23083064&quot;%3E%3Ccircle cx=&quot;40&quot; cy=&quot;40&quot; r=&quot;3&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;70&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;70&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;70&quot; cy=&quot;70&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/svg%3E');"
      ></div>
    </div>

    <!-- Navbar -->
    <SiteNavbar
      transparent
      :current-lang="currentLocale"
      :nav-items="navItems"
      :is-dark-mode="isDarkMode"
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

    <!-- Main Content with unified background -->
    <main class="relative">
      <!-- Features Section -->
      <FeaturesSection
        v-if="sections.features"
        id="features"
        :section="sections.features"
        :current-lang="currentLocale"
      />

      <!-- How It Works Section -->
      <HowItWorksSection
        v-if="sections.how_it_works"
        id="how-it-works"
        :section="sections.how_it_works"
        :current-lang="currentLocale"
      />

      <!-- Why Us Section -->
      <WhyUsSection
        v-if="sections.why_us"
        id="why-us"
        :section="sections.why_us"
        :current-lang="currentLocale"
      />

      <!-- Top Chefs Section -->
      <TopChefsSection
        v-if="sections.top_chefs"
        id="top-chefs"
        :section="sections.top_chefs"
        :current-lang="currentLocale"
      />

      <!-- Categories Section -->
      <CategoriesSection
        v-if="sections.categories"
        id="categories"
        :section="sections.categories"
        :current-lang="currentLocale"
      />

      <!-- Testimonials Section -->
      <TestimonialsSection
        v-if="sections.testimonials"
        id="testimonials"
        :section="sections.testimonials"
        :current-lang="currentLocale"
      />

      <!-- About Us Section -->
      <AboutUsSection
        v-if="sections.about_us"
        id="about"
        :section="sections.about_us"
        :current-lang="currentLocale"
      />

      <!-- Vision & Mission Section -->
      <VisionMissionSection
        v-if="sections.vision_mission"
        id="vision-mission"
        :section="sections.vision_mission"
        :current-lang="currentLocale"
      />

      <!-- Contact Section -->
      <ContactSection
        v-if="sections.contact"
        id="contact"
        :section="sections.contact"
        :current-lang="currentLocale"
      />

      <!-- CTA Section -->
      <CTASection
        v-if="sections.cta"
        id="cta"
        :section="sections.cta"
        :current-lang="currentLocale"
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
  { href: '#about', label_ar: 'من نحن', label_en: 'About' },
  { href: '#contact', label_ar: 'تواصل معنا', label_en: 'Contact' }
])

// Initialize dark mode from localStorage or system preference
const initDarkMode = () => {
  const savedMode = localStorage.getItem('darkMode')
  if (savedMode !== null) {
    isDarkMode.value = savedMode === 'true'
  } else {
    // Check system preference
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

  // Simulate loading (non-blocking)
  setTimeout(() => {
    isLoading.value = false
  }, 800)
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
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
