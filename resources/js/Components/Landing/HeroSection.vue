<template>
  <section
    id="hero"
    class="relative h-screen  flex items-center overflow-hidden pt-16 lg:pt-20"
  >
    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900" />
    <div class="absolute inset-0 opacity-30" style="background-image: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=1920&q=80'); background-size: cover; background-position: center;" />
    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 via-primary-800/40 to-transparent" />

    <div class="container relative z-10 mx-auto px-4 lg:px-8 py-24 lg:py-32">
      <div class="max-w-3xl">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
          {{ title }}
        </h1>
        <p class="text-xl text-white/90 mb-8 leading-relaxed">
          {{ description }}
        </p>
        <div class="flex flex-wrap gap-4">
          <a
            href="#top-chefs"
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold bg-[#CBE4F8] text-[#083064] hover:bg-[#A3D1F3] transition-colors shadow-lg"
          >
            {{ currentLang === 'ar' ? 'اكتشف الطهاة' : 'Discover Chefs' }}
            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
          <a
            href="#how-it-works"
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold border-2 border-white/50 text-white hover:bg-white/10 transition-colors"
          >
            {{ currentLang === 'ar' ? 'كيف يعمل' : 'How It Works' }}
          </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-16 pt-16 border-t border-white/20">
          <div v-for="stat in stats" :key="stat.label_ar" class="text-center">
            <div class="text-2xl sm:text-3xl font-bold text-white">{{ stat.number }}</div>
            <div class="text-sm text-white/85 mt-1">{{ currentLang === 'ar' ? stat.label_ar : stat.label_en }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent" />
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const stats = computed(() => props.section?.additional_data?.stats ?? [
  { number: '500+', label_ar: 'طاهي محترف', label_en: 'Professional Chefs' },
  { number: '10,000+', label_ar: 'عميل سعيد', label_en: 'Happy Customers' },
  { number: '15,000+', label_ar: 'حجز ناجح', label_en: 'Successful Bookings' },
  { number: '4.9/5', label_ar: 'تقييم العملاء', label_en: 'Customer Rating' },
])
</script>
