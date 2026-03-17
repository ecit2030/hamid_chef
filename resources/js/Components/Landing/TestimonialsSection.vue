<template>
  <section id="testimonials" class="py-16 lg:py-24 bg-gradient-to-b from-[#E6EBF2] to-white overflow-hidden">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <span class="inline-block px-4 py-2 rounded-full bg-[#083064]/10 text-[#083064] font-bold text-sm mb-4">
          {{ currentLang === 'ar' ? 'آراء العملاء' : 'Customer Reviews' }}
        </span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <div
          v-for="(t, i) in testimonials"
          :key="t.id ?? i"
          class="group relative"
        >
          <div class="h-full p-6 lg:p-8 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:border-[#99AFCB]/50 transition-all duration-300 flex flex-col">

            <div class="flex gap-1 mb-4 mt-4">
              <span
                v-for="n in 5"
                :key="n"
                class="text-[#D97706] text-lg"
                :class="n <= (t.rating ?? 5) ? 'opacity-100' : 'opacity-30'"
              >
                ★
              </span>
            </div>

            <p class="text-gray-700 leading-relaxed flex-1 text-[15px]">
              {{ currentLang === 'ar' ? t.comment_ar : t.comment_en }}
            </p>

            <div v-if="testimonialName(t) || t.avatar" class="mt-6 pt-5 border-t border-gray-100 flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[#E6EBF2] overflow-hidden flex items-center justify-center shrink-0">
                <img
                  v-if="t.avatar"
                  :src="t.avatar"
                  :alt="testimonialName(t)"
                  class="w-full h-full object-cover"
                  loading="lazy"
                />
                <span v-else class="text-[#083064] font-bold">
                  {{ (testimonialName(t) || 'U').slice(0, 1).toUpperCase() }}
                </span>
              </div>
              <div class="min-w-0">
                <div class="font-bold text-[#051D3C] leading-snug truncate">
                  {{ testimonialName(t) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="testimonials.length === 0" class="text-center py-16 px-6 rounded-2xl bg-white border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#E6EBF2] flex items-center justify-center text-3xl">💬</div>
        <p class="text-gray-600 text-lg">{{ currentLang === 'ar' ? 'سيتم عرض آراء العملاء قريباً' : 'Customer reviews will be displayed soon' }}</p>
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
const testimonials = computed(() => props.section?.additional_data?.testimonials ?? [])

function testimonialName(t) {
  return (props.currentLang === 'ar' ? (t?.name_ar ?? t?.name_en) : (t?.name_en ?? t?.name_ar) ?? '').trim()
}
</script>
