<template>
  <section id="about" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center w-full max-w-6xl">
        <div class="text-center">
          <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
          <p class="text-lg text-gray-700 mb-6">{{ description }}</p>
          <p class="text-gray-700">{{ currentLang === 'ar' ? story_ar : story_en }}</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div
            v-for="(v, i) in values"
            :key="i"
            class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow text-center flex flex-col items-center"
          >
            <h3 class="font-bold text-[#062650] mb-2">{{ currentLang === 'ar' ? v.title_ar : v.title_en }}</h3>
            <p class="text-sm text-gray-700 text-center">{{ currentLang === 'ar' ? v.description_ar : v.description_en }}</p>
          </div>
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
const story_ar = computed(() => props.section?.additional_data?.story_ar ?? '')
const story_en = computed(() => props.section?.additional_data?.story_en ?? '')
const values = computed(() => props.section?.additional_data?.values ?? [])
</script>
