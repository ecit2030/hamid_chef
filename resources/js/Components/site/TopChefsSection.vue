<template>
  <section class="relative py-24 overflow-hidden">
    <!-- Primary Background Section with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-600 to-primary-800">
      <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23CBE4F8&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/svg%3E');" />
    </div>

    <!-- Floating Elements -->
    <FloatingElements
      :count="5"
      :shapes="['circle', 'blob']"
      :colors="['secondary', 'white']"
      speed="slow"
    />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div
        ref="headerRef"
        :class="['text-center mb-12', headerAnimationClasses]"
      >
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-sm text-white rounded-full mb-6 border border-white/20">
          <span class="text-xl">👨‍🍳</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'طهاتنا المميزون' : 'Our Top Chefs' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-white mb-4">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-secondary max-w-2xl mx-auto">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Chefs Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div
          v-for="(chef, index) in chefs"
          :key="index"
          :class="['group', cardAnimationClasses]"
          :style="{ transitionDelay: `${index * 100}ms` }"
        >
          <GlassCard
            :animated="false"
            :hover-effect="true"
            :glow="true"
            glow-color="secondary"
            padding="none"
            class="overflow-hidden"
          >
            <!-- Image -->
            <div class="relative h-56 overflow-hidden">
              <img
                v-if="chef.image"
                :src="`/storage/${chef.image}`"
                :alt="chef.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
              <div v-else class="w-full h-full bg-gradient-to-br from-secondary to-secondary-600 flex items-center justify-center">
                <span class="text-6xl">👨‍🍳</span>
              </div>
              <!-- Overlay on hover -->
              <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
            </div>

            <!-- Info -->
            <div class="p-5 text-center">
              <h3 class="text-lg font-black text-primary mb-1 transition-colors group-hover:text-primary-600">{{ chef.name }}</h3>
              <p class="text-gray-600 text-sm mb-3">
                {{ currentLang === 'ar' ? chef.specialty_ar : chef.specialty_en }}
              </p>

              <!-- Rating -->
              <div class="flex items-center justify-center gap-1">
                <svg v-for="i in 5" :key="i" class="w-4 h-4 text-yellow-500 transition-transform hover:scale-125" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
          </GlassCard>
        </div>
      </div>

      <!-- CTA -->
      <div v-if="section?.additional_data?.show_cta" class="text-center mt-10">
        <a
          :href="section?.additional_data?.cta_link || '/chefs'"
          class="group inline-flex items-center gap-3 px-8 py-4 bg-secondary text-primary font-black rounded-xl transition-all duration-300 hover:bg-white hover:scale-105 hover:shadow-xl hover:shadow-secondary/30"
        >
          {{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'عرض جميع الطهاة') : (section?.additional_data?.cta_text_en || 'View All Chefs') }}
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
import FloatingElements from '@/Components/ui/FloatingElements.vue'

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

const chefs = computed(() => props.section?.additional_data?.chefs || [])

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
