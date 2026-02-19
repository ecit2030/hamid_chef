<template>
  <section id="features" class="py-24 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-16', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 backdrop-blur-sm text-primary rounded-full mb-6 border border-primary/20">
          <span class="text-xl">🍽️</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'خدماتنا المميزة' : 'Our Services' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Features Grid -->
      <div ref="gridRef" class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <GlassCard
          v-for="(feature, index) in features"
          :key="index"
          :animated="true"
          animation-type="slideUp"
          :hover-effect="true"
          :glow="true"
          padding="lg"
          class="group"
          :style="{ transitionDelay: `${index * 100}ms` }"
        >
          <!-- Icon -->
          <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-600 rounded-xl flex items-center justify-center mb-5 shadow-xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-primary/30">
            <img
              v-if="feature.icon"
              :src="`/storage/${feature.icon}`"
              :alt="currentLang === 'ar' ? feature.title_ar : feature.title_en"
              class="w-8 h-8 object-contain brightness-0 invert"
            />
            <svg v-else class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <!-- Content -->
          <h3 class="text-xl font-black text-primary mb-3 transition-colors group-hover:text-primary-600">
            {{ currentLang === 'ar' ? feature.title_ar : feature.title_en }}
          </h3>

          <p class="text-gray-600 text-sm leading-relaxed">
            {{ currentLang === 'ar' ? feature.description_ar : feature.description_en }}
          </p>

          <!-- Hover Arrow -->
          <div class="mt-4 flex items-center gap-2 text-primary opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
            <span class="text-sm font-semibold">{{ currentLang === 'ar' ? 'اعرف المزيد' : 'Learn more' }}</span>
            <svg class="w-4 h-4" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
        </GlassCard>
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
const gridRef = ref(null)

const { isVisible: headerVisible, observe: observeHeader } = useScrollTrigger({ threshold: 0.2 })
const { getAnimationClasses, prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (headerRef.value) {
    observeHeader(headerRef.value)
  }
})

const features = computed(() => props.section?.additional_data?.features || [])

const headerAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return headerVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})
</script>
