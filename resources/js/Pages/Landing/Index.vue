<template>
  <Head :title="pageTitle" />
  <LandingLayout
    :transparent="true"
    :nav-items="navItems"
    :current-lang="currentLocale"
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
      v-if="sanitizedSections.hero"
      id="hero"
      :section="sanitizedSections.hero"
      :current-lang="currentLocale"
    />

    <FeaturesSection
      v-if="sanitizedSections.features"
      id="features"
      :section="sanitizedSections.features"
      :current-lang="currentLocale"
    />

    <HowItWorksSection
      v-if="sanitizedSections.how_it_works"
      id="how-it-works"
      :section="sanitizedSections.how_it_works"
      :current-lang="currentLocale"
    />

    <TopChefsSection
      v-if="sanitizedSections.top_chefs"
      id="top-chefs"
      :section="sanitizedSections.top_chefs"
      :current-lang="currentLocale"
    />

    <CategoriesSection
      v-if="sanitizedSections.categories"
      id="categories"
      :section="sanitizedSections.categories"
      :current-lang="currentLocale"
    />

    <TestimonialsSection
      v-if="sanitizedSections.testimonials"
      id="testimonials"
      :section="sanitizedSections.testimonials"
      :current-lang="currentLocale"
    />

    <AboutUsSection
      v-if="sanitizedSections.about_us"
      id="about"
      :section="sanitizedSections.about_us"
      :current-lang="currentLocale"
    />

    <VisionMissionSection
      v-if="sanitizedSections.vision_mission"
      id="vision-mission"
      :section="sanitizedSections.vision_mission"
      :current-lang="currentLocale"
    />

    <WhyUsSection
      v-if="sanitizedSections.why_us"
      id="why-us"
      :section="sanitizedSections.why_us"
      :current-lang="currentLocale"
    />

    <PartnersSection
      v-if="sanitizedSections.partners"
      id="partners"
      :section="sanitizedSections.partners"
      :current-lang="currentLocale"
    />

    <ContactSection
      v-if="sanitizedSections.contact"
      id="contact"
      :section="sanitizedSections.contact"
      :current-lang="currentLocale"
    />
  </LandingLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { applyDirection } from '@/utils/direction'
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
import { getLandingLabels } from '@/data/landingLabels'

const props = defineProps({
  sections: { type: Object, default: () => ({}) },
  locale: { type: String, default: 'ar' },
})

const currentLocale = ref(props.locale)
const isLoading = ref(true)

const pageTitle = computed(() => getLandingLabels(currentLocale.value).pageTitle)

function sanitizeBrandText(value) {
  if (typeof value !== 'string') return value
  return value.replace(/Moon Chef/gi, 'Mon Chef')
}

function deepSanitize(value) {
  if (typeof value === 'string') return sanitizeBrandText(value)
  if (Array.isArray(value)) return value.map(deepSanitize)
  if (value && typeof value === 'object') {
    const out = {}
    for (const [k, v] of Object.entries(value)) {
      out[k] = deepSanitize(v)
    }
    return out
  }
  return value
}

const sanitizedSections = computed(() => deepSanitize(props.sections ?? {}))

const whatsappUrl = computed(() => {
  const contact = sanitizedSections.value?.contact?.additional_data
  if (!contact) return 'https://wa.me/966582800034'
  const url = contact.whatsapp_url?.trim()
  if (url) return url.startsWith('http') ? url : `https://wa.me/${url.replace(/\D/g, '')}`
  let phone = contact.phone?.replace(/\D/g, '') || ''
  if (phone.startsWith('0') && phone.length >= 9) phone = '966' + phone.slice(1)
  return phone ? `https://wa.me/${phone}` : 'https://wa.me/966582800034'
})

const navItems = computed(() => {
  const labels = getLandingLabels(currentLocale.value).nav
  const orderEn = [
    { href: '#hero', label: labels.home },
    { href: '#features', label: labels.features },
    { href: '#how-it-works', label: labels.howItWorks },
    { href: '#top-chefs', label: labels.topChefs },
    { href: '#categories', label: labels.categories },
    { href: '#about', label: labels.about },
    { href: '#why-us', label: labels.whyUs },
    { href: '#contact', label: labels.contact },
  ]
  const orderAr = [
    { href: '#contact', label: labels.contact },
    { href: '#why-us', label: labels.whyUs },
    { href: '#about', label: labels.about },
    { href: '#categories', label: labels.categories },
    { href: '#top-chefs', label: labels.topChefs },
    { href: '#how-it-works', label: labels.howItWorks },
    { href: '#features', label: labels.features },
    { href: '#hero', label: labels.home },
  ]
  return currentLocale.value === 'en' ? orderEn : orderAr
})

onMounted(() => {
  document.documentElement.classList.remove('dark')
  /* Keep layout LTR on landing so Arabic and English are visually identical; only text direction changes via .landing-layout-ar */
  applyDirection('ltr')
  document.documentElement.setAttribute('lang', props.locale)
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
