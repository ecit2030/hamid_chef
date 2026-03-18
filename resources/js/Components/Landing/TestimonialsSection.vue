<template>
  <section id="testimonials" class="py-16 lg:py-24 bg-gradient-to-b from-[#E6EBF2] to-white overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="section-header !text-center max-w-3xl w-full mb-12 lg:mb-16 mx-auto" :dir="currentLang === 'ar' ? 'rtl' : 'ltr'">
        <span class="inline-block px-4 py-2 rounded-full bg-[#083064]/10 text-[#083064] font-bold text-sm mb-4">
          {{ testimonialsPillLabel }}
        </span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 w-full max-w-6xl mx-auto">
        <div
          v-for="(t, i) in testimonials"
          :key="t.id ?? i"
          class="group relative"
        >
          <div class="h-full p-6 lg:p-8 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#99AFCB]/50 transition-all duration-300 flex flex-col text-center">

            <div class="flex gap-1 mb-4 mt-4 justify-center">
              <span
                v-for="n in 5"
                :key="n"
                class="text-[#D97706] text-lg"
                :class="n <= (t.rating ?? 5) ? 'opacity-100' : 'opacity-30'"
              >
                ★
              </span>
            </div>

            <p class="text-gray-700 leading-relaxed flex-1 text-[15px] text-center">
              {{ currentLang === 'ar' ? t.comment_ar : t.comment_en }}
            </p>

            <div v-if="testimonialName(t, i) || t.avatar" class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[#E6EBF2] overflow-hidden flex items-center justify-center shrink-0">
                <img
                  v-if="t.avatar"
                  :src="t.avatar"
                  :alt="testimonialName(t, i)"
                  class="w-full h-full object-cover"
                  loading="lazy"
                />
                <span v-else class="text-[#083064] font-bold">
                  {{ (testimonialName(t, i) || 'U').slice(0, 1).toUpperCase() }}
                </span>
              </div>
              <div class="min-w-0">
                <div class="font-bold text-[#051D3C] leading-snug truncate">
                  {{ testimonialName(t, i) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="testimonials.length === 0" class="text-center w-full max-w-2xl py-16 px-6 rounded-2xl bg-white border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#E6EBF2] flex items-center justify-center text-3xl">💬</div>
        <p class="text-gray-600 text-lg">{{ emptyLabel }}</p>
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
const testimonials = computed(() => props.section?.additional_data?.testimonials ?? [])
const testimonialsPillLabel = computed(() => getLandingLabels(props.currentLang).testimonialsPill)
const emptyLabel = computed(() => getLandingLabels(props.currentLang).empty.testimonials)

const OVERRIDE_EN_NAMES = [
  'Emily Johnson',
  'Michael Smith',
  'Sophia Brown',
]

function testimonialName(t, index) {
  // Use anonymous/fake names on the landing page (both languages)
  const overrideIndex = index % OVERRIDE_EN_NAMES.length
  if (props.currentLang === 'en') {
    return OVERRIDE_EN_NAMES[overrideIndex]
  }
  if (props.currentLang === 'ar') {
    const OVERRIDE_AR_NAMES = ['أحمد علي', 'فاطمة خالد', 'سارة محمد']
    return OVERRIDE_AR_NAMES[overrideIndex]
  }
  return (props.currentLang === 'ar' ? (t?.name_ar ?? t?.name_en) : (t?.name_en ?? t?.name_ar) ?? '').trim()
}
</script>
