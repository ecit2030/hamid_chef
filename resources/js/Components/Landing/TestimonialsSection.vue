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
            <div class="absolute top-6 start-6 text-6xl text-[#CBE4F8]/40 font-serif leading-none select-none">"</div>

            <div class="flex gap-1 mb-4 mt-4">
              <span
                v-for="n in 5"
                :key="n"
                class="text-[#D97706]"
                :class="n <= (t.rating ?? 5) ? 'opacity-100' : 'opacity-30'"
              >
                ★
              </span>
            </div>

            <p class="text-gray-700 leading-relaxed mb-6 flex-1 text-[15px]">
              {{ currentLang === 'ar' ? t.comment_ar : t.comment_en }}
            </p>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
              <div class="relative flex-shrink-0">
                <img
                  :src="t.avatar || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80'"
                  :alt="currentLang === 'ar' ? t.name_ar : t.name_en"
                  class="w-14 h-14 rounded-full object-cover ring-2 ring-[#CBE4F8] ring-offset-2"
                />
              </div>
              <div>
                <div class="font-bold text-[#051D3C]">{{ currentLang === 'ar' ? t.name_ar : t.name_en }}</div>
                <div class="text-sm text-gray-500">{{ currentLang === 'ar' ? 'عميل سعيد' : 'Happy Customer' }}</div>
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
</script>
