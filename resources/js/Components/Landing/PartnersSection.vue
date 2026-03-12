<template>
  <section id="partners" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div v-if="partners.length > 0" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 lg:gap-8">
        <div
          v-for="(p, i) in partners"
          :key="i"
          class="group flex flex-col items-center justify-center p-8 rounded-2xl bg-white border-2 border-gray-100 hover:border-[#99AFCB] hover:shadow-xl transition-all duration-300 min-h-[180px]"
        >
          <div class="flex-1 flex items-center justify-center w-full">
            <img
              :src="p.logo"
              :alt="currentLang === 'ar' ? p.name_ar : p.name_en"
              class="max-h-16 w-auto object-contain grayscale opacity-70 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
            />
          </div>
          <h3 class="font-bold text-[#062650] mt-4 text-center text-sm">{{ currentLang === 'ar' ? p.name_ar : p.name_en }}</h3>
        </div>
      </div>

      <div v-else class="text-center py-16 px-6 rounded-2xl bg-white border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#E6EBF2] flex items-center justify-center text-2xl">🤝</div>
        <p class="text-gray-600 text-lg">{{ currentLang === 'ar' ? 'سيتم عرض الشركاء قريباً' : 'Partners will be displayed soon' }}</p>
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
const partners = computed(() => props.section?.additional_data?.partners ?? [])
</script>
