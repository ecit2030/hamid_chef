<template>
  <section id="features" class="py-24 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary rounded-full mb-6 border border-primary/20 dark:border-secondary/30">
          <span class="text-xl">🍽️</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'خدماتنا المميزة' : 'Our Services' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary dark:text-white mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Features Grid - Modern Professional Design -->
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="(feature, index) in features"
          :key="index"
          class="group relative bg-white dark:bg-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1"
        >
          <!-- Accent Line -->
          <div class="absolute top-0 left-8 right-8 h-1 bg-gradient-to-r from-primary to-primary-600 rounded-b-full opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

          <!-- Icon -->
          <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <img
              v-if="feature.icon"
              :src="`/storage/${feature.icon}`"
              :alt="currentLang === 'ar' ? feature.title_ar : feature.title_en"
              class="w-8 h-8 object-contain brightness-0 invert"
            />
            <svg v-else class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <!-- Content -->
          <h3 class="text-xl font-bold text-primary dark:text-white mb-4 group-hover:text-primary-600 dark:group-hover:text-secondary transition-colors duration-300">
            {{ currentLang === 'ar' ? feature.title_ar : feature.title_en }}
          </h3>

          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ currentLang === 'ar' ? feature.description_ar : feature.description_en }}
          </p>

          <!-- Arrow Icon -->
          <div class="mt-6 flex items-center gap-2 text-primary dark:text-secondary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <span class="text-sm font-semibold">{{ currentLang === 'ar' ? 'اعرف المزيد' : 'Learn More' }}</span>
            <svg class="w-4 h-4" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' },
  isDarkMode: { type: Boolean, default: false }
})

const features = computed(() => props.section?.additional_data?.features || [])
</script>
