<template>
  <Head :title="pageTitle" />
  <LandingLayout
    :transparent="true"
    :nav-items="navItems"
    :current-lang="currentLocale"
    :dir="currentLocale === 'ar' ? 'rtl' : 'ltr'"
    :whatsapp-url="whatsappUrl"
    :show-back-to-top="true"
    @toggle-language="toggleLanguage"
  >
    <Transition name="fade">
      <div v-if="isLoading" class="fixed inset-0 z-[100] flex items-center justify-center bg-white">
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

    <HeroSection
      v-if="sections.hero"
      id="hero"
      :section="sections.hero"
      :current-lang="currentLocale"
    />

    <FeaturesSection
      v-if="sections.features"
      id="features"
      :section="sections.features"
      :current-lang="currentLocale"
    />

    <HowItWorksSection
      v-if="sections.how_it_works"
      id="how-it-works"
      :section="sections.how_it_works"
      :current-lang="currentLocale"
    />

    <TopChefsSection
      v-if="sections.top_chefs"
      id="top-chefs"
      :section="sections.top_chefs"
      :current-lang="currentLocale"
    />

    <CategoriesSection
      v-if="sections.categories"
      id="categories"
      :section="sections.categories"
      :current-lang="currentLocale"
    />

    <TestimonialsSection
      v-if="sections.testimonials"
      id="testimonials"
      :section="sections.testimonials"
      :current-lang="currentLocale"
    />

    <AboutUsSection
      v-if="sections.about_us"
      id="about"
      :section="sections.about_us"
      :current-lang="currentLocale"
    />

    <VisionMissionSection
      v-if="sections.vision_mission"
      id="vision-mission"
      :section="sections.vision_mission"
      :current-lang="currentLocale"
    />

    <WhyUsSection
      v-if="sections.why_us"
      id="why-us"
      :section="sections.why_us"
      :current-lang="currentLocale"
    />

    <PartnersSection
      v-if="sections.partners"
      id="partners"
      :section="sections.partners"
      :current-lang="currentLocale"
    />

    <ContactSection
      v-if="sections.contact"
      id="contact"
      :section="sections.contact"
      :current-lang="currentLocale"
    />
  </LandingLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { setHtmlDirection } from '@/i18n'
import LandingLayout from '@/Layouts/LandingLayout.vue'
import {
  HeroSection,
  FeaturesSection,
  HowItWorksSection,
  TopChefsSection,
  CategoriesSection,
  TestimonialsSection,
  AboutUsSection,
  VisionMissionSection,
  WhyUsSection,
  PartnersSection,
  ContactSection,
} from '@/Components/Landing'

const props = defineProps({
  sections: { type: Object, default: () => ({}) },
  locale: { type: String, default: 'ar' },
})

const currentLocale = ref(props.locale)
const isLoading = ref(true)

const pageTitle = computed(() => currentLocale.value === 'ar' ? 'مون شيف' : 'Mon Chef')

const whatsappUrl = computed(() => {
  const contact = props.sections?.contact?.additional_data
  if (!contact) return ''
  const url = contact.whatsapp_url?.trim()
  if (url) return url.startsWith('http') ? url : `https://wa.me/${url.replace(/\D/g, '')}`
  const phone = contact.phone?.replace(/\D/g, '')
  return phone ? `https://wa.me/${phone}` : ''
})

const navItems = ref([
  { href: '#hero', label_ar: 'الرئيسية', label_en: 'Home' },
  { href: '#features', label_ar: 'المميزات', label_en: 'Features' },
  { href: '#how-it-works', label_ar: 'كيف يعمل', label_en: 'How It Works' },
  { href: '#top-chefs', label_ar: 'أفضل الطهاة', label_en: 'Top Chefs' },
  { href: '#categories', label_ar: 'التصنيفات', label_en: 'Categories' },
  { href: '#about', label_ar: 'من نحن', label_en: 'About' },
  { href: '#why-us', label_ar: 'لماذا نحن', label_en: 'Why Us' },
  { href: '#contact', label_ar: 'تواصل معنا', label_en: 'Contact' },
])

onMounted(() => {
  document.documentElement.classList.remove('dark')
  setHtmlDirection(props.locale)
  setTimeout(() => {
    isLoading.value = false
  }, 500)
})

function toggleLanguage() {
  const newLocale = currentLocale.value === 'ar' ? 'en' : 'ar'
  router.post(route('locale.switch'), { locale: newLocale }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      currentLocale.value = newLocale
      window.location.reload()
    },
  })
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
