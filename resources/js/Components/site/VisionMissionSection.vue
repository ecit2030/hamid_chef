<template>
  <section
    ref="sectionRef"
    class="relative py-24 overflow-hidden bg-primary dark:bg-gray-900"
    :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
    :style="{ transition: 'opacity 0.8s ease-out, transform 0.8s ease-out' }"
  >
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-12">
        <div
          class="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-primary rounded-full mb-6"
          :class="{ 'opacity-0 -translate-y-4': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s' }"
        >
          <span class="text-xl">🎯</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'رؤيتنا ورسالتنا' : 'Vision & Mission' }}</span>
        </div>

        <h2
          class="text-4xl lg:text-5xl font-black text-white mb-4"
          :class="{ 'opacity-0 -translate-y-4': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s' }"
        >
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
      </div>

      <!-- Cards Grid -->
      <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <!-- Mission Card -->
        <div
          v-if="mission"
          class="text-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:border-secondary transition-colors duration-300"
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.4s, transform 0.6s ease-out 0.4s' }"
        >
          <div class="w-16 h-16 mx-auto mb-5 bg-primary rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-2xl font-black text-primary dark:text-white mb-4">
            {{ currentLang === 'ar' ? 'الرسالة' : 'Mission' }}
          </h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            {{ currentLang === 'ar' ? mission.content_ar : mission.content_en }}
          </p>
        </div>

        <!-- Vision Card -->
        <div
          v-if="vision"
          class="text-center bg-secondary rounded-xl p-6"
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.5s, transform 0.6s ease-out 0.5s' }"
        >
          <div class="w-16 h-16 mx-auto mb-5 bg-primary rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </div>
          <h3 class="text-2xl font-black text-primary mb-4">
            {{ currentLang === 'ar' ? 'الرؤية' : 'Vision' }}
          </h3>
          <p class="text-primary/80 leading-relaxed">
            {{ currentLang === 'ar' ? vision.content_ar : vision.content_en }}
          </p>
        </div>

        <!-- Values Card -->
        <div
          class="text-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 hover:border-secondary transition-colors duration-300"
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.6s, transform 0.6s ease-out 0.6s' }"
        >
          <div class="w-16 h-16 mx-auto mb-5 bg-primary rounded-xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <h3 class="text-2xl font-black text-primary dark:text-white mb-4">
            {{ currentLang === 'ar' ? 'القيم' : 'Values' }}
          </h3>
          <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
            {{ currentLang === 'ar' ? (values[0]?.description_ar || 'الجودة والتميز في كل وجبة') : (values[0]?.description_en || 'Quality and excellence in every meal') }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
})

const sectionRef = ref(null)
const isVisible = ref(false)
let observer = null

const vision = computed(() => props.section?.additional_data?.vision)
const mission = computed(() => props.section?.additional_data?.mission)
const values = computed(() => props.section?.additional_data?.values || [])

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true
        }
      })
    },
    { threshold: 0.2 }
  )

  if (sectionRef.value) {
    observer.observe(sectionRef.value)
  }
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>
