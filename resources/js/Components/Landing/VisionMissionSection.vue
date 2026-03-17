<template>
  <section id="vision-mission" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="!text-center max-w-3xl w-full mb-12 lg:mb-16 mx-auto">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-12 w-full max-w-6xl mx-auto">
        <div class="relative overflow-hidden rounded-2xl border border-[#D9E2F0] bg-white shadow-sm">
          <div class="absolute inset-0 bg-gradient-to-br from-[#083064]/8 via-transparent to-transparent" />
          <div class="relative p-7 lg:p-8">
            <div class="flex items-start gap-4 flex-row">
              <div class="shrink-0 w-12 h-12 rounded-2xl bg-[#083064] text-white flex items-center justify-center text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  <circle cx="12" cy="12" r="3" stroke-width="2" />
                </svg>
              </div>
              <div class="min-w-0 text-start">
                <h3 class="text-xl font-extrabold text-[#051D3C] leading-snug">
                  {{ currentLang === 'ar' ? vision?.title_ar : vision?.title_en }}
                </h3>
                <p class="mt-2 text-gray-700 leading-relaxed">
                  {{ currentLang === 'ar' ? vision?.description_ar : vision?.description_en }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-[#D9E2F0] bg-white shadow-sm">
          <div class="absolute inset-0 bg-gradient-to-br from-[#CBE4F8] via-transparent to-transparent" />
          <div class="relative p-7 lg:p-8">
            <div class="flex items-start gap-4 flex-row">
              <div class="shrink-0 w-12 h-12 rounded-2xl bg-[#CBE4F8] text-[#083064] flex items-center justify-center text-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="8" stroke-width="2" />
                  <circle cx="12" cy="12" r="4" stroke-width="2" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l2 2" />
                </svg>
              </div>
              <div class="min-w-0 text-start">
                <h3 class="text-xl font-extrabold text-[#051D3C] leading-snug">
                  {{ currentLang === 'ar' ? mission?.title_ar : mission?.title_en }}
                </h3>
                <p class="mt-2 text-gray-700 leading-relaxed">
                  {{ currentLang === 'ar' ? mission?.description_ar : mission?.description_en }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Our Core Values: match English layout – centered title + single centered paragraph, no box -->
      <div v-if="goals.length" class="w-full max-w-6xl">
        <template v-if="coreValuesBlurb">
          <div class="text-center mb-6">
            <h4 class="text-lg lg:text-xl font-extrabold text-[#051D3C]">
              {{ coreValuesLabel }}
            </h4>
          </div>
          <div class="max-w-3xl mx-auto text-center">
            <p class="text-base lg:text-lg text-gray-700 leading-relaxed">
              {{ coreValuesBlurb }}
            </p>
          </div>
        </template>

        <div v-else class="rounded-2xl border border-[#E6EBF2] bg-[#F7FAFF] p-6 lg:p-8">
          <div class="text-center mb-6">
            <h4 class="text-lg lg:text-xl font-extrabold text-[#051D3C]">
              {{ coreValuesLabel }}
            </h4>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
          <div
            v-for="(g, i) in goals"
            :key="i"
            class="rounded-xl bg-white border border-[#E6EBF2] p-5 lg:p-6 shadow-sm text-start"
          >
            <div class="flex items-start gap-3 flex-row">
              <div class="mt-1 w-2.5 h-2.5 rounded-full bg-[#083064] shrink-0" />
              <div class="min-w-0">
                <h5 class="font-extrabold text-[#062650] leading-snug">
                  {{ currentLang === 'ar' ? g.title_ar : g.title_en }}
                </h5>
                <p class="mt-2 text-sm text-gray-700 leading-relaxed">
                  {{ currentLang === 'ar' ? g.description_ar : g.description_en }}
                </p>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { getLandingLabels } from '@/data/landingLabels'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const vision = computed(() => props.section?.additional_data?.vision ?? {})
const mission = computed(() => props.section?.additional_data?.mission ?? {})
const goals = computed(() => props.section?.additional_data?.goals ?? [])
const coreValuesLabel = computed(() => getLandingLabels(props.currentLang).coreValues)

const coreValuesBlurb = computed(() => {
  // If content is stored as a single "goal" item with a long description,
  // render it as a centered paragraph instead of repeating the title as a card.
  if (!Array.isArray(goals.value) || goals.value.length !== 1) return ''
  const g = goals.value[0] ?? {}
  const t = String(props.currentLang === 'ar' ? (g.title_ar ?? '') : (g.title_en ?? '')).trim().toLowerCase()
  const isCoreValuesTitle =
    (props.currentLang === 'en' && t === 'our core values') ||
    (props.currentLang === 'ar' && t === 'قيمنا الأساسية')
  if (!isCoreValuesTitle) return ''
  return String(props.currentLang === 'ar' ? (g.description_ar ?? '') : (g.description_en ?? '')).trim()
})
</script>
