<template>
  <section id="categories" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6">
        <div
          v-for="(cat, i) in categories"
          :key="cat.id ?? i"
          class="group aspect-[4/3] rounded-2xl overflow-hidden border-2 border-gray-200 hover:border-[#6687B1] transition-all duration-300 relative"
        >
          <img
            :src="cat.image || `https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=400&q=80`"
            :alt="categoryDisplayName(cat)"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-primary-900/80 to-transparent" />
          <div class="absolute bottom-0 start-0 end-0 p-4">
            <h3 class="font-bold text-white text-lg">{{ categoryDisplayName(cat) }}</h3>
          </div>
        </div>
      </div>

      <div v-if="categories.length === 0" class="text-center py-12 text-gray-500">
        {{ currentLang === 'ar' ? 'سيتم عرض التصنيفات قريباً' : 'Categories will be displayed soon' }}
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const CATEGORY_NAME_EN = {
  'saudi-cuisine': 'Saudi Cuisine',
  'gulf-cuisine': 'Gulf Cuisine',
  'levantine-cuisine': 'Levantine Cuisine',
  'egyptian-cuisine': 'Egyptian Cuisine',
  'indian-cuisine': 'Indian Cuisine',
  'italian-cuisine': 'Italian Cuisine',
  'grills': 'Grills',
  'desserts': 'Desserts',
  'seafood': 'Seafood',
  'healthy-food': 'Healthy Food',
}

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const categories = computed(() => props.section?.additional_data?.categories ?? [])

function categoryDisplayName(cat) {
  if (props.currentLang === 'ar') {
    return cat.name_ar ?? cat.name_en ?? ''
  }
  return (cat.slug && CATEGORY_NAME_EN[cat.slug]) ? CATEGORY_NAME_EN[cat.slug] : (cat.name_en ?? cat.name_ar ?? '')
}
</script>
