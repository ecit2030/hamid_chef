<template>
  <section class="relative py-24 overflow-hidden bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-16', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary rounded-full mb-6 border border-primary/20 dark:border-secondary/30">
          <span class="text-xl">💬</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'آراء عملائنا' : 'Testimonials' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary dark:text-white mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Testimonials Carousel/Grid -->
      <div class="relative max-w-6xl mx-auto">
        <!-- Navigation Buttons -->
        <button
          v-if="testimonials.length > 3"
          @click="prevSlide"
          class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 w-14 h-14 bg-white dark:bg-gray-900 rounded-full flex items-center justify-center text-primary dark:text-white hover:bg-primary hover:text-white dark:hover:bg-secondary dark:hover:text-primary transition-all duration-300 shadow-lg hidden lg:flex"
        >
          <svg class="w-6 h-6" :class="currentLang === 'ar' ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <button
          v-if="testimonials.length > 3"
          @click="nextSlide"
          class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 w-14 h-14 bg-white dark:bg-gray-900 rounded-full flex items-center justify-center text-primary dark:text-white hover:bg-primary hover:text-white dark:hover:bg-secondary dark:hover:text-primary transition-all duration-300 shadow-lg hidden lg:flex"
        >
          <svg class="w-6 h-6" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-3 gap-8 overflow-hidden">
          <div
            v-for="(testimonial, index) in visibleTestimonials"
            :key="index"
            :class="['group', cardAnimationClasses]"
            :style="{ transitionDelay: `${index * 100}ms` }"
          >
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 h-full border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1 relative">
              <!-- Quote Icon Background -->
              <div class="absolute top-4 right-4 text-8xl text-primary/5 dark:text-secondary/5 font-serif leading-none">"</div>

              <!-- Stars -->
              <div class="flex items-center gap-1 mb-6 relative z-10">
                <svg
                  v-for="star in 5"
                  :key="star"
                  class="w-5 h-5 text-yellow-500"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>

              <!-- Content -->
              <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-8 relative z-10">
                {{ currentLang === 'ar' ? (testimonial.content_ar || testimonial.comment_ar) : (testimonial.content_en || testimonial.comment_en) }}
              </p>

              <!-- Author -->
              <div class="flex items-center gap-4 pt-6 border-t border-gray-100 dark:border-gray-700 relative z-10">
                <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-600 rounded-full flex items-center justify-center overflow-hidden shadow-lg shadow-primary/20">
                  <img
                    v-if="testimonial.avatar"
                    :src="testimonial.avatar?.startsWith('http') ? testimonial.avatar : `/storage/${testimonial.avatar}`"
                    :alt="currentLang === 'ar' ? (testimonial.name_ar || testimonial.name) : (testimonial.name_en || testimonial.name)"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-white font-bold text-xl">
                    {{ (currentLang === 'ar' ? (testimonial.name_ar || testimonial.name) : (testimonial.name_en || testimonial.name))?.charAt(0) }}
                  </span>
                </div>
                <div>
                  <h4 class="font-bold text-primary dark:text-white text-lg">{{ currentLang === 'ar' ? (testimonial.name_ar || testimonial.name) : (testimonial.name_en || testimonial.name) }}</h4>
                  <p class="text-gray-500 dark:text-gray-400">{{ currentLang === 'ar' ? testimonial.role_ar : testimonial.role_en }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Dots Indicator -->
        <div v-if="testimonials.length > 3" class="flex justify-center gap-3 mt-10">
          <button
            v-for="(_, index) in Math.ceil(testimonials.length / 3)"
            :key="index"
            @click="goToSlide(index)"
            :class="[
              'h-3 rounded-full transition-all duration-300',
              currentSlide === index
                ? 'bg-primary w-10'
                : 'bg-gray-300 dark:bg-gray-600 hover:bg-primary/50 w-3'
            ]"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useScrollTrigger } from '@/composables/useScrollTrigger'
import { useAnimations } from '@/composables/useAnimations'

const props = defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
})

const headerRef = ref(null)
const currentSlide = ref(0)

const { isVisible: headerVisible, observe: observeHeader } = useScrollTrigger({ threshold: 0.2 })
const { prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (headerRef.value) {
    observeHeader(headerRef.value)
  }
})

const testimonials = computed(() => props.section?.additional_data?.testimonials || [])

const visibleTestimonials = computed(() => {
  const start = currentSlide.value * 3
  return testimonials.value.slice(start, start + 3)
})

const nextSlide = () => {
  const maxSlide = Math.ceil(testimonials.value.length / 3) - 1
  currentSlide.value = currentSlide.value >= maxSlide ? 0 : currentSlide.value + 1
}

const prevSlide = () => {
  const maxSlide = Math.ceil(testimonials.value.length / 3) - 1
  currentSlide.value = currentSlide.value <= 0 ? maxSlide : currentSlide.value - 1
}

const goToSlide = (index) => {
  currentSlide.value = index
}

const headerAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})

const cardAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-500'
    : 'opacity-0 translate-y-8 transition-all duration-500'
})
</script>
