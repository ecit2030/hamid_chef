<template>
  <section class="relative min-h-screen w-full overflow-hidden">
    <!-- Animated Gradient Background -->
    <div class="absolute inset-0">
      <img
        v-if="section?.image"
        :src="`/storage/${section.image}`"
        :alt="currentLang === 'ar' ? section?.title_ar : section?.title_en"
        class="w-full h-full object-cover"
      />
      <div v-else class="w-full h-full bg-gradient-to-br from-primary via-primary-600 to-primary-800 animate-gradient" />

      <!-- Gradient Overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/80 to-primary/60" />
    </div>

    <!-- Floating Elements -->
    <FloatingElements
      :count="8"
      :shapes="['circle', 'blob', 'circle']"
      :colors="['secondary', 'white']"
      speed="slow"
    />

    <!-- Parallax Decorative Elements -->
    <div
      class="absolute top-20 right-20 w-96 h-96 bg-secondary rounded-full opacity-20 blur-3xl"
      :style="parallaxStyle"
    />
    <div
      class="absolute bottom-20 left-20 w-80 h-80 bg-secondary rounded-full opacity-15 blur-3xl"
      :style="parallaxStyleSlow"
    />

    <!-- Content -->
    <div class="relative z-10 h-screen flex items-center">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div
          ref="contentRef"
          class="max-w-4xl"
          :class="[
            currentLang === 'ar' ? 'mr-auto text-right' : 'ml-0 text-left',
            contentAnimationClasses
          ]"
        >
          <!-- Badge -->
          <div
            :class="[
              'inline-flex items-center gap-3 px-6 py-3 rounded-full mb-8 shadow-2xl transition-all duration-500',
              'bg-white/10 backdrop-blur-md border border-white/20',
              badgeAnimationClasses
            ]"
            :style="{ transitionDelay: '100ms' }"
          >
            <span class="w-3 h-3 bg-secondary rounded-full animate-pulse" />
            <span class="text-white font-bold text-lg">
              🍳 {{ currentLang === 'ar' ? 'أفضل خدمة طهاة منزليين' : 'Best Home Chef Service' }}
            </span>
          </div>

          <!-- Main Title with Animation -->
          <h1
            :class="[
              'text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-white leading-tight mb-8',
              titleAnimationClasses
            ]"
            :style="{ transitionDelay: '200ms' }"
          >
            {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
          </h1>

          <!-- Subtitle -->
          <h2
            :class="[
              'text-2xl lg:text-3xl text-secondary font-bold mb-6',
              subtitleAnimationClasses
            ]"
            :style="{ transitionDelay: '300ms' }"
          >
            {{ currentLang === 'ar' ? section?.additional_data?.subtitle_ar : section?.additional_data?.subtitle_en }}
          </h2>

          <!-- Description -->
          <p
            :class="[
              'text-xl text-white/90 max-w-2xl leading-relaxed mb-10',
              descriptionAnimationClasses
            ]"
            :style="{ transitionDelay: '400ms' }"
          >
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- CTA Buttons -->
          <div
            :class="[
              'flex flex-wrap gap-4',
              currentLang === 'ar' ? 'justify-start' : 'justify-start',
              ctaAnimationClasses
            ]"
            :style="{ transitionDelay: '500ms' }"
          >
            <a
              :href="section?.additional_data?.cta_link || '#contact'"
              class="group inline-flex items-center gap-3 px-10 py-5 bg-secondary text-primary text-xl font-black rounded-2xl transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-2xl hover:shadow-secondary/30"
            >
              <span>{{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'احجز طاهيك الآن') : (section?.additional_data?.cta_text_en || 'Book Your Chef') }}</span>
              <svg
                class="w-6 h-6 transition-transform group-hover:translate-x-1"
                :class="currentLang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : ''"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>

            <a
              href="#features"
              class="group inline-flex items-center gap-3 px-10 py-5 bg-white/10 backdrop-blur-sm text-white text-xl font-bold rounded-2xl border-2 border-white/30 transition-all duration-300 hover:bg-white/20 hover:border-white hover:scale-105"
            >
              {{ currentLang === 'ar' ? 'كيف يعمل؟' : 'How it works?' }}
              <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z" />
              </svg>
            </a>
          </div>

          <!-- Stats -->
          <div
            :class="[
              'flex flex-wrap gap-12 mt-16 pt-8 border-t border-white/20',
              statsAnimationClasses
            ]"
            :style="{ transitionDelay: '600ms' }"
          >
            <div class="group">
              <div class="text-5xl font-black text-secondary transition-transform group-hover:scale-110">500+</div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'طاهٍ محترف' : 'Pro Chefs' }}</div>
            </div>
            <div class="group">
              <div class="text-5xl font-black text-secondary transition-transform group-hover:scale-110">10K+</div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'وجبة مقدمة' : 'Meals Served' }}</div>
            </div>
            <div class="group">
              <div class="text-5xl font-black text-secondary flex items-center gap-2 transition-transform group-hover:scale-110">
                4.9
                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'تقييم العملاء' : 'Client Rating' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Animated Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
      <a
        href="#features"
        class="flex flex-col items-center text-white/70 hover:text-white transition-all duration-300 group"
      >
        <span class="text-sm font-medium mb-2 opacity-0 group-hover:opacity-100 transition-opacity">
          {{ currentLang === 'ar' ? 'اكتشف المزيد' : 'Discover More' }}
        </span>
        <div class="w-8 h-12 border-2 border-white/30 rounded-full flex justify-center group-hover:border-white/50 transition-colors">
          <div class="w-1.5 h-3 bg-white/70 rounded-full mt-2 animate-scroll-indicator" />
        </div>
      </a>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useScrollTrigger } from '@/composables/useScrollTrigger'
import { useAnimations } from '@/composables/useAnimations'
import { useParallax } from '@/composables/useParallax'
import FloatingElements from '@/Components/ui/FloatingElements.vue'

defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
})

const contentRef = ref(null)

// Scroll trigger for content animations
const { isVisible, observe } = useScrollTrigger({ threshold: 0.1, once: true })
const { getAnimationClasses, prefersReducedMotion } = useAnimations()

// Parallax effects
const { parallaxStyle } = useParallax({ speed: 0.3 })
const { parallaxStyle: parallaxStyleSlow } = useParallax({ speed: 0.15 })

onMounted(() => {
  if (contentRef.value) {
    observe(contentRef.value)
  }
  // Trigger animations immediately for hero section
  setTimeout(() => {
    isVisible.value = true
  }, 100)
})

const baseAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return 'transition-all duration-700 ease-out'
})

const contentAnimationClasses = computed(() => baseAnimationClasses.value)

const badgeAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0'
    : 'opacity-0 translate-y-4'
})

const titleAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})

const subtitleAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-6 transition-all duration-700'
})

const descriptionAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-6 transition-all duration-700'
})

const ctaAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-6 transition-all duration-700'
})

const statsAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-6 transition-all duration-700'
})
</script>

<style scoped>
@keyframes gradient {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}

@keyframes scroll-indicator {
  0%, 100% {
    transform: translateY(0);
    opacity: 1;
  }
  50% {
    transform: translateY(8px);
    opacity: 0.5;
  }
}

.animate-scroll-indicator {
  animation: scroll-indicator 1.5s ease-in-out infinite;
}
</style>
