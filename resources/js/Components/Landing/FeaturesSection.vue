<template>
  <section id="features" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16 mt-0 -translate-y-6 lg:-translate-y-10">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <div
          v-for="(feature, i) in features"
          :key="i"
          class="group p-6 lg:p-8 rounded-2xl border-2 border-gray-200 hover:border-[#99AFCB] hover:shadow-lg hover:shadow-[#E6EBF2] transition-all duration-300"
        >
          <div class="w-14 h-14 rounded-xl bg-[#CCD7E5] text-[#083064] flex items-center justify-center mb-4 group-hover:bg-[#083064] group-hover:text-white transition-colors">
            <span class="text-2xl">{{ getIcon(feature.icon) }}</span>
          </div>
          <h3 class="text-xl font-bold text-[#051D3C] mb-2">{{ currentLang === 'ar' ? feature.title_ar : feature.title_en }}</h3>
          <p class="text-gray-700">{{ currentLang === 'ar' ? feature.description_ar : feature.description_en }}</p>
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
const features = computed(() => props.section?.additional_data?.features ?? [])

const icons = {
  'chef-hat': '👨‍🍳',
  calendar: '📅',
  star: '⭐',
  shield: '🛡️',
  utensils: '🍽️',
  support: '💬',
}

function getIcon(icon) {
  return icons[icon] ?? '✨'
}
</script>
