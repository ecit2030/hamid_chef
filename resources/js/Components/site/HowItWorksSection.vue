<template>
  <section class="py-24 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-12', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 backdrop-blur-sm text-primary rounded-full mb-6 border border-primary/20">
          <span class="text-xl">📋</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'كيف نعمل' : 'How It Works' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Steps -->
      <div class="relative max-w-5xl mx-auto">
        <!-- Connection Line with Gradient -->
        <div class="hidden lg:block absolute top-12 left-0 right-0 h-1 bg-gradient-to-r from-secondary via-primary/30 to-secondary rounded-full" />

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="(step, index) in steps"
            :key="index"
            :class="['relative text-center group', stepAnimationClasses]"
            :style="{ transitionDelay: `${index * 150}ms` }"
          >
            <!-- Step Number Circle -->
            <div class="relative mx-auto mb-5">
              <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-600 rounded-full flex items-center justify-center shadow-xl transition-all duration-300 group-hover:scale-110 group-hover:shadow-primary/30 relative z-10">
                <span class="text-2xl font-black text-white">{{ index + 1 }}</span>
              </div>
              <!-- Pulse Ring -->
              <div class="absolute inset-0 rounded-full bg-primary/20 animate-ping opacity-0 group-hover:opacity-100" />
            </div>

            <!-- Card -->
            <GlassCard
              :animated="false"
              :hover-effect="true"
              :glow="true"
              padding="md"
            >
              <!-- Icon -->
              <div class="w-12 h-12 mx-auto mb-3 bg-gradient-to-br from-secondary to-secondary-600 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 group-hover:rotate-3">
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
              <h3 class="text-lg font-black text-primary mb-2 transition-colors group-hover:text-primary-600">
                {{ currentLang === 'ar' ? step.title_ar : step.title_en }}
              </h3>

              <!-- Description -->
              <p class="text-gray-600 text-sm leading-relaxed">
                {{ currentLang === 'ar' ? step.description_ar : step.description_en }}
              </p>
            </GlassCard>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div
        :class="['text-center mt-10', ctaAnimationClasses]"
      >
        <a
          href="#contact"
          class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary to-primary-600 text-white font-bold rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-primary/30"
        >
          {{ currentLang === 'ar' ? 'ابدأ الآن' : 'Get Started' }}
          <svg
            class="w-5 h-5 transition-transform group-hover:translate-x-1"
            :class="currentLang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : ''"
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
import GlassCard from '@/Components/ui/GlassCard.vue'

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
