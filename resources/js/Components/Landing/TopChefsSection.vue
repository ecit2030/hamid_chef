<template>
  <section id="top-chefs" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="(chef, i) in chefs"
          :key="i"
          class="group rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl hover:border-primary-200 transition-all duration-300 text-center flex flex-col items-center"
        >
          <div class="aspect-square w-full bg-primary-100 overflow-hidden">
            <img
              :src="chef.image || `https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=400&q=80`"
              :alt="currentLang === 'ar' ? chef.name_ar : chef.name_en"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
          </div>
          <div class="p-4 w-full text-center">
            <h3 class="font-bold text-[#051D3C]">{{ currentLang === 'ar' ? chef.name_ar : chef.name_en }}</h3>
            <p class="text-sm text-gray-500">{{ currentLang === 'ar' ? chef.specialty_ar : chef.specialty_en }}</p>
            <div class="flex items-center justify-center gap-1 mt-2">
              <span class="text-culinary-500">★</span>
              <span class="text-sm font-medium">{{ chef.rating || '4.9' }}</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="chefs.length === 0" class="text-center py-12 text-gray-500">
        {{ currentLang === 'ar' ? 'سيتم عرض أفضل الطهاة قريباً' : 'Top chefs will be displayed soon' }}
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
const chefs = computed(() => props.section?.additional_data?.chefs ?? [])
</script>
