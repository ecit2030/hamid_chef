<template>
  <div class="min-h-screen" :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'">
    <!-- Unified Background for entire page - Light Secondary with subtle pattern -->
    <div class="fixed inset-0 -z-10 bg-gradient-to-b from-white via-secondary/20 to-secondary/30">
      <!-- Cooking Pattern Overlay -->
      <div class="absolute inset-0 opacity-[0.04]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;80&quot; height=&quot;80&quot; viewBox=&quot;0 0 80 80&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23083064&quot;%3E%3Ccircle cx=&quot;40&quot; cy=&quot;40&quot; r=&quot;3&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;70&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;70&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;70&quot; cy=&quot;70&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
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
    <SiteFooter :current-lang="currentLocale" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
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

const navItems = ref([
  { href: '#hero', label_ar: 'الرئيسية', label_en: 'Home' },
  { href: '#features', label_ar: 'المميزات', label_en: 'Features' },
  { href: '#how-it-works', label_ar: 'كيف يعمل', label_en: 'How It Works' },
  { href: '#why-us', label_ar: 'لماذا نحن', label_en: 'Why Us' },
  { href: '#about', label_ar: 'من نحن', label_en: 'About' },
  { href: '#contact', label_ar: 'تواصل معنا', label_en: 'Contact' }
])

onMounted(() => {
  document.documentElement.classList.remove('dark')
  isDarkMode.value = false
  localStorage.setItem('darkMode', 'false')
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
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('darkMode', 'true')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('darkMode', 'false')
  }
}
</script>
