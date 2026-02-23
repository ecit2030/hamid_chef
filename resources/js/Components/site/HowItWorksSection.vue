<template>
  <section class="py-24 relative overflow-hidden bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-16', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary rounded-full mb-6 border border-primary/20 dark:border-secondary/30">
          <span class="text-xl">📋</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'كيف نعمل' : 'How It Works' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary dark:text-white mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Steps -->
      <div class="relative max-w-6xl mx-auto">
        <!-- Connection Line -->
        <div class="hidden lg:block absolute top-20 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-primary via-secondary to-primary rounded-full" />

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="(step, index) in steps"
            :key="index"
            :class="['relative text-center group', stepAnimationClasses]"
            :style="{ transitionDelay: `${index * 150}ms` }"
          >
            <!-- Step Number Circle -->
            <div class="relative mx-auto mb-8">
              <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-600 rounded-full flex items-center justify-center relative z-10 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-primary/30">
                <span class="text-3xl font-black text-white">{{ index + 1 }}</span>
              </div>
              <!-- Pulse Effect -->
              <div class="absolute inset-0 w-20 h-20 bg-primary/20 rounded-full animate-ping opacity-0 group-hover:opacity-75" />
            </div>

            <!-- Card -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 group-hover:border-primary/30 dark:group-hover:border-secondary/30 transition-all duration-300 group-hover:-translate-y-1">
              <!-- Icon -->
              <div class="w-14 h-14 mx-auto mb-4 bg-secondary/50 dark:bg-secondary/20 rounded-xl flex items-center justify-center">
                <img
                  v-if="step.icon"
                  :src="`/storage/${step.icon}`"
                  :alt="currentLang === 'ar' ? step.title_ar : step.title_en"
                  class="w-7 h-7 object-contain"
                />
                <svg v-else class="w-7 h-7 text-primary dark:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>

              <!-- Title -->
              <h3 class="text-xl font-bold text-primary dark:text-white mb-3">
                {{ currentLang === 'ar' ? step.title_ar : step.title_en }}
              </h3>

              <!-- Description -->
              <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ currentLang === 'ar' ? step.description_ar : step.description_en }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div
        :class="['text-center mt-16', ctaAnimationClasses]"
      >
        <a
          href="#contact"
          class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-primary to-primary-600 text-white font-bold text-lg rounded-full hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 hover:-translate-y-1"
        >
          {{ currentLang === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
          <svg
            class="w-6 h-6"
            :class="currentLang === 'ar' ? 'rotate-180' : ''"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </a>
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

const { isVisible: headerVisible, observe: observeHeader } = useScrollTrigger({ threshold: 0.2 })
const { prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (headerRef.value) {
    observeHeader(headerRef.value)
  }
})

const steps = computed(() => props.section?.additional_data?.steps || [])

const headerAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})

const stepAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-500'
    : 'opacity-0 translate-y-8 transition-all duration-500'
})

const ctaAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700 delay-500'
    : 'opacity-0 translate-y-4 transition-all duration-700'
})
</script>
