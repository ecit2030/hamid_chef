<template>
  <section
    id="hero"
    class="relative h-screen  flex items-center overflow-hidden pt-16 lg:pt-20"
  >
    <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900" />
    <div class="absolute inset-0 opacity-30" style="background-image: url('https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=1920&q=80'); background-size: cover; background-position: center;" />
    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 via-primary-800/40 to-transparent" />

    <div
      class="container relative z-10 mx-auto px-4 lg:px-8 py-24 lg:py-32 flex flex-col w-full"
      :class="currentLang === 'ar' ? 'items-end text-right' : currentLang === 'en' ? 'items-start text-left' : 'items-center text-center'"
    >
      <div class="max-w-3xl w-full">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
          {{ title }}
        </h1>
        <p class="text-xl text-white/90 mb-8 leading-relaxed">
          {{ description }}
        </p>
        <div
          class="flex flex-wrap gap-4"
          :class="currentLang === 'ar' ? 'justify-end' : currentLang === 'en' ? 'justify-start' : 'justify-center'"
        >
          <a
            href="#top-chefs"
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold bg-[#CBE4F8] text-[#083064] hover:bg-[#A3D1F3] transition-colors shadow-lg"
          >
            {{ heroLabels.ctaDiscover }}
            <svg :class="currentLang === 'ar' ? 'w-5 h-5 shrink-0 rotate-180' : 'w-5 h-5 shrink-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </a>
          <a
            href="#how-it-works"
            class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold border-2 border-white/50 text-white hover:bg-white/10 transition-colors"
          >
            {{ heroLabels.ctaHowItWorks }}
          </a>
        </div>

        <div
          class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-16 pt-16 border-t border-white/20 w-full max-w-4xl"
          :class="currentLang === 'ar' ? 'ml-auto text-right' : currentLang === 'en' ? 'mr-auto' : 'mx-auto text-center'"
        >
          <div v-for="stat in stats" :key="stat.label_ar" :class="currentLang === 'ar' ? 'text-right' : currentLang === 'en' ? 'text-left' : 'text-center'">
            <div class="text-2xl sm:text-3xl font-bold text-white">{{ stat.number }}</div>
            <div class="text-sm text-white/85 mt-1">{{ currentLang === 'ar' ? stat.label_ar : stat.label_en }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="pointer-events-none absolute bottom-0 inset-x-0 h-32 bg-gradient-to-b from-transparent via-white/70 to-white" />
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { getLandingLabels, heroDefaultStats } from '@/data/landingLabels'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const heroLabels = computed(() => getLandingLabels(props.currentLang).hero)

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const stats = computed(() => props.section?.additional_data?.stats ?? heroDefaultStats)
</script>
