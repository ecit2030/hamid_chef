<template>
  <section id="vision-mission" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="p-8 rounded-2xl bg-[#E6EBF2] border-2 border-[#CCD7E5]">
          <div class="w-14 h-14 rounded-xl bg-[#083064] text-white flex items-center justify-center mb-4 text-2xl">👁️</div>
          <h3 class="text-xl font-bold text-[#051D3C] mb-2">{{ currentLang === 'ar' ? vision?.title_ar : vision?.title_en }}</h3>
          <p class="text-gray-700">{{ currentLang === 'ar' ? vision?.description_ar : vision?.description_en }}</p>
        </div>
        <div class="p-8 rounded-2xl bg-[#F5FAFD] border-2 border-[#E0EFF9]">
          <div class="w-14 h-14 rounded-xl bg-[#CBE4F8] text-[#083064] flex items-center justify-center mb-4 text-2xl">🎯</div>
          <h3 class="text-xl font-bold text-[#051D3C] mb-2">{{ currentLang === 'ar' ? mission?.title_ar : mission?.title_en }}</h3>
          <p class="text-gray-700">{{ currentLang === 'ar' ? mission?.description_ar : mission?.description_en }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(g, i) in goals"
          :key="i"
          class="p-6 rounded-xl bg-gray-50 text-center"
        >
          <h4 class="font-bold text-[#062650] mb-2">{{ currentLang === 'ar' ? g.title_ar : g.title_en }}</h4>
          <p class="text-sm text-gray-700">{{ currentLang === 'ar' ? g.description_ar : g.description_en }}</p>
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
const vision = computed(() => props.section?.additional_data?.vision ?? {})
const mission = computed(() => props.section?.additional_data?.mission ?? {})
const goals = computed(() => props.section?.additional_data?.goals ?? [])
</script>
