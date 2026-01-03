<template>
  <section class="relative py-24 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full mb-6 shadow-xl">
          <span class="text-xl">💬</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'آراء عملائنا' : 'Testimonials' }}</span>
        </div>
        
        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-700 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Testimonials Grid -->
      <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <div 
          v-for="(testimonial, index) in testimonials" 
          :key="index"
          class="group"
        >
          <div class="h-full bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-secondary hover:border-primary">
            <!-- Stars -->
            <div class="flex items-center gap-1 mb-4">
              <svg v-for="star in 5" :key="star" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Quote Icon -->
            <div class="text-5xl text-primary mb-3">"</div>

            <!-- Content -->
            <p class="text-gray-700 leading-relaxed mb-6">
              {{ currentLang === 'ar' ? testimonial.content_ar : testimonial.content_en }}
            </p>

            <!-- Author -->
            <div class="flex items-center gap-3 pt-4 border-t-2 border-secondary">
              <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center overflow-hidden shadow-lg">
                <img 
                  v-if="testimonial.avatar" 
                  :src="`/storage/${testimonial.avatar}`" 
                  :alt="testimonial.name"
                  class="w-full h-full object-cover"
                />
                <span v-else class="text-white font-black text-lg">
                  {{ testimonial.name?.charAt(0) }}
                </span>
              </div>
              <div>
                <h4 class="font-black text-primary">{{ testimonial.name }}</h4>
                <p class="text-gray-500 text-sm">{{ currentLang === 'ar' ? testimonial.role_ar : testimonial.role_en }}</p>
              </div>
            </div>
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

const testimonials = computed(() => props.section?.additional_data?.testimonials || [])
</script>
