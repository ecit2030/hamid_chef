<template>
  <section class="relative py-24 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-16', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 backdrop-blur-sm text-primary rounded-full mb-6 border border-primary/20">
          <span class="text-2xl">⭐</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'لماذا نحن؟' : 'Why Choose Us?' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Reasons Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <GlassCard
          v-for="(reason, index) in reasons"
          :key="index"
          :animated="true"
          animation-type="scaleIn"
          :hover-effect="true"
          :glow="true"
          padding="lg"
          class="group text-center"
          :style="{ transitionDelay: `${index * 100}ms` }"
        >
          <!-- Icon Circle -->
          <div class="relative mx-auto mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center shadow-xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-primary/30">
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <!-- Number Badge -->
            <div class="absolute -top-2 -right-2 w-8 h-8 bg-secondary text-primary font-black text-sm rounded-full flex items-center justify-center shadow-lg border-2 border-white transition-transform group-hover:scale-110">
              {{ index + 1 }}
            </div>
          </div>

          <!-- Title -->
          <h3 class="text-xl font-black text-primary mb-3 transition-colors group-hover:text-primary-600">
            {{ currentLang === 'ar' ? reason.title_ar : reason.title_en }}
          </h3>

          <!-- Description -->
          <p class="text-gray-600 text-sm leading-relaxed">
            {{ currentLang === 'ar' ? reason.description_ar : reason.description_en }}
          </p>
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
