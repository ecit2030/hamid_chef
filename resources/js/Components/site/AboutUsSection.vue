<template>
  <section class="relative py-24 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Image Side -->
        <div class="relative" :class="currentLang === 'ar' ? 'order-2' : 'order-1'">
          <!-- Main Image -->
          <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-secondary">
            <img 
              v-if="section?.image" 
              :src="`/storage/${section.image}`" 
              :alt="currentLang === 'ar' ? section?.title_ar : section?.title_en"
              class="w-full h-auto object-cover"
            />
            <div v-else class="w-full aspect-[4/3] bg-primary flex items-center justify-center">
              <span class="text-9xl">👨‍🍳</span>
            </div>
          </div>
          
          <!-- Floating Card -->
          <div class="absolute -bottom-6 -right-6 bg-primary rounded-2xl p-5 shadow-2xl">
            <div class="text-4xl font-black text-secondary">15+</div>
            <div class="text-white font-bold text-sm">{{ currentLang === 'ar' ? 'سنوات خبرة' : 'Years Experience' }}</div>
          </div>

          <!-- Decorative Circle -->
          <div class="absolute -top-6 -left-6 w-20 h-20 bg-secondary rounded-full flex items-center justify-center shadow-xl border-4 border-primary">
            <span class="text-3xl">🍳</span>
          </div>
        </div>

        <!-- Content Side -->
        <div class="space-y-6" :class="currentLang === 'ar' ? 'order-1 text-right' : 'order-2 text-left'">
          <!-- Badge -->
          <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full shadow-xl">
            <span class="text-xl">👋</span>
            <span class="font-bold">{{ currentLang === 'ar' ? 'تعرف علينا' : 'About Us' }}</span>
          </div>

          <!-- Title -->
          <h2 class="text-4xl lg:text-5xl font-black text-primary leading-tight">
            {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
          </h2>
          
          <!-- Description -->
          <p class="text-lg text-gray-700 leading-relaxed">
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- Features List -->
          <div v-if="features.length" class="space-y-3 pt-4">
            <div 
              v-for="(feature, index) in features" 
              :key="index"
              class="flex items-center gap-4 bg-white rounded-xl p-4 shadow-md border-2 border-secondary hover:border-primary transition-colors"
            >
              <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-gray-800 font-bold">
                {{ currentLang === 'ar' ? feature.text_ar : feature.text_en }}
              </span>
            </div>
          </div>

          <!-- CTA Button -->
          <a href="#contact" class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-white font-bold text-lg rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-xl">
            {{ currentLang === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
            <svg class="w-5 h-5" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>
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
