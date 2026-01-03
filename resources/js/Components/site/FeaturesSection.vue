<template>
  <section id="features" class="py-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full mb-6 shadow-xl">
          <span class="text-xl">🍽️</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'خدماتنا المميزة' : 'Our Services' }}</span>
        </div>
        
        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-700 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="(feature, index) in features" 
          :key="index"
          class="group"
        >
          <div class="h-full bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-secondary hover:border-primary">
            <!-- Icon -->
            <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center mb-5 shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all">
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
            <h3 class="text-xl font-black text-primary mb-3">
              {{ currentLang === 'ar' ? feature.title_ar : feature.title_en }}
            </h3>
            
            <p class="text-gray-600 text-sm leading-relaxed">
              {{ currentLang === 'ar' ? feature.description_ar : feature.description_en }}
            </p>
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
  currentLang: { type: String, default: 'ar' }
})

const features = computed(() => props.section?.additional_data?.features || [])
</script>
