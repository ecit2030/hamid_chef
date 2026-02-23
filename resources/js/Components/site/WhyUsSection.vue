<template>
  <section class="relative py-24 overflow-hidden bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-16', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary rounded-full mb-6 border border-primary/20 dark:border-secondary/30">
          <span class="text-2xl">⭐</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'لماذا نحن؟' : 'Why Choose Us?' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary dark:text-white mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Reasons Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div
          v-for="(reason, index) in reasons"
          :key="index"
          class="group text-center bg-gray-50 dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1"
          :style="{ transitionDelay: `${index * 100}ms` }"
        >
          <!-- Icon Circle -->
          <div class="relative mx-auto mb-6">
            <div class="w-24 h-24 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-primary/20">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <!-- Number Badge -->
            <div class="absolute -top-2 -right-2 w-10 h-10 bg-secondary text-primary font-black text-lg rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-md">
              {{ index + 1 }}
            </div>
          </div>

          <!-- Title -->
          <h3 class="text-xl font-bold text-primary dark:text-white mb-4 group-hover:text-primary-600 dark:group-hover:text-secondary transition-colors duration-300">
            {{ currentLang === 'ar' ? reason.title_ar : reason.title_en }}
          </h3>

          <!-- Description -->
          <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
            {{ currentLang === 'ar' ? reason.description_ar : reason.description_en }}
          </p>
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

const { isVisible: headerVisible, observe: observeHeader } = useScrollTrigger({ threshold: 0.2 })
const { prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (headerRef.value) {
    observeHeader(headerRef.value)
  }
})

const reasons = computed(() => props.section?.additional_data?.reasons || [])

const headerAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})
</script>
