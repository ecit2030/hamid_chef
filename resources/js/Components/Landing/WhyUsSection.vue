<template>
  <section id="why-us" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="text-center max-w-3xl w-full mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16 w-full max-w-6xl">
        <div
          v-for="(r, i) in reasons"
          :key="i"
          class="p-6 rounded-2xl bg-white border border-gray-100 hover:shadow-lg hover:border-primary-200 transition-all text-center flex flex-col items-center justify-center"
        >
          <div class="w-12 h-12 rounded-xl bg-[#CCD7E5] text-[#083064] flex items-center justify-center mb-4 text-xl">✓</div>
          <h3 class="font-bold text-[#051D3C] mb-2 w-full">{{ currentLang === 'ar' ? r.title_ar : r.title_en }}</h3>
          <p class="text-gray-700 text-sm w-full text-center">{{ currentLang === 'ar' ? r.description_ar : r.description_en }}</p>
        </div>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-5xl">
        <div v-for="(s, i) in stats" :key="i" class="text-center p-6 rounded-2xl bg-[#083064] text-white flex flex-col items-center justify-center">
          <div class="text-2xl lg:text-3xl font-bold">{{ s.number }}</div>
          <div class="text-sm text-white/90 mt-1">{{ currentLang === 'ar' ? s.label_ar : s.label_en }}</div>
        </div>
      </div>
    </div>
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
const reasons = computed(() => props.section?.additional_data?.reasons ?? [])
const stats = computed(() => props.section?.additional_data?.stats ?? [])
</script>
