<template>
  <section class="relative py-24 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-12', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 backdrop-blur-sm text-primary rounded-full mb-6 border border-primary/20">
          <span class="text-xl">🍽️</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'تصنيفات الطعام' : 'Food Categories' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Categories Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <a
          v-for="(category, index) in categories"
          :key="index"
          :href="category.link || '#'"
          :class="['group block', cardAnimationClasses]"
          :style="{ transitionDelay: `${index * 100}ms` }"
        >
          <GlassCard
            :animated="false"
            :hover-effect="true"
            :glow="true"
            padding="lg"
            class="text-center h-full"
          >
            <!-- Icon -->
            <div class="relative w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-primary to-primary-600 rounded-xl flex items-center justify-center shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-primary/30">
              <img
                v-if="category.icon"
                :src="`/storage/${category.icon}`"
                :alt="currentLang === 'ar' ? category.name_ar : category.name_en"
                class="w-8 h-8 object-contain brightness-0 invert"
              />
              <svg v-else class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>

            <!-- Name -->
            <h3 class="relative text-lg font-black text-primary mb-2 transition-colors group-hover:text-primary-600">
              {{ currentLang === 'ar' ? category.name_ar : category.name_en }}
            </h3>

            <!-- Description -->
            <p v-if="category.description_ar || category.description_en" class="relative text-gray-600 text-sm leading-relaxed">
              {{ currentLang === 'ar' ? category.description_ar : category.description_en }}
            </p>

            <!-- Arrow -->
            <div class="relative mt-4 flex items-center justify-center text-primary opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
              <span class="text-sm font-semibold mr-2">{{ currentLang === 'ar' ? 'استكشف' : 'Explore' }}</span>
              <svg class="w-5 h-5" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </div>
          </GlassCard>
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

const categories = computed(() => props.section?.additional_data?.categories || [])

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
