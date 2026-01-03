<template>
  <section class="py-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full mb-6 shadow-xl">
          <span class="text-xl">📋</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'كيف نعمل' : 'How It Works' }}</span>
        </div>
        
        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-700 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Steps -->
      <div class="relative max-w-5xl mx-auto">
        <!-- Connection Line -->
        <div class="hidden lg:block absolute top-12 left-0 right-0 h-1 bg-secondary"></div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="(step, index) in steps" 
            :key="index"
            class="relative text-center group"
          >
            <!-- Step Number Circle -->
            <div class="relative mx-auto mb-5">
              <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform relative z-10">
                <span class="text-2xl font-black text-white">{{ index + 1 }}</span>
              </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl p-5 shadow-lg border-2 border-secondary hover:border-primary transition-colors">
              <!-- Icon -->
              <div class="w-12 h-12 mx-auto mb-3 bg-secondary rounded-xl flex items-center justify-center">
                <img 
                  v-if="step.icon" 
                  :src="`/storage/${step.icon}`" 
                  :alt="currentLang === 'ar' ? step.title_ar : step.title_en"
                  class="w-6 h-6 object-contain"
                />
                <svg v-else class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              
              <!-- Title -->
              <h3 class="text-lg font-black text-primary mb-2">
                {{ currentLang === 'ar' ? step.title_ar : step.title_en }}
              </h3>
              
              <!-- Description -->
              <p class="text-gray-600 text-sm">
                {{ currentLang === 'ar' ? step.description_ar : step.description_en }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="text-center mt-10">
        <a href="#contact" class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white font-bold rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-xl">
          {{ currentLang === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
          <svg class="w-5 h-5" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </a>
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

const steps = computed(() => props.section?.additional_data?.steps || [])
</script>
