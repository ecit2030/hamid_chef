<template>
  <section id="how-it-works" class="py-16 lg:py-24 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="section-header !text-center max-w-3xl w-full mb-12 lg:mb-16 mx-auto" :dir="currentLang === 'ar' ? 'rtl' : 'ltr'">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="flex flex-col md:flex-row md:items-center gap-8 lg:gap-4 w-full max-w-5xl mx-auto">
        <template v-for="(step, i) in steps" :key="i">
          <div class="flex-1 flex flex-col items-center text-center">
            <div class="flex justify-center mb-6">
              <div class="w-20 h-20 rounded-2xl bg-[#083064] flex items-center justify-center text-2xl font-bold text-white shadow-lg">
                {{ step.step }}
              </div>
            </div>
            <h3 class="text-xl font-bold text-[#051D3C] mb-2">{{ currentLang === 'ar' ? step.title_ar : step.title_en }}</h3>
            <p class="text-gray-700">{{ currentLang === 'ar' ? step.description_ar : step.description_en }}</p>
          </div>
          <div
            v-if="i < steps.length - 1"
            class="hidden md:flex flex-shrink-0 items-center justify-center w-12 lg:w-16 py-10"
          >
            <svg
              class="w-8 h-8 text-[#99AFCB]"
              :class="currentLang === 'ar' ? 'rotate-180' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
          </div>
        </template>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const rawSteps = computed(() => props.section?.additional_data?.steps ?? [])
const steps = computed(() =>
  props.currentLang === 'ar' ? [...rawSteps.value].reverse() : rawSteps.value
)
</script>
