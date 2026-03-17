<template>
  <section id="vision-mission" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-12">
        <div class="relative overflow-hidden rounded-2xl border border-[#D9E2F0] bg-white shadow-sm">
          <div class="absolute inset-0 bg-gradient-to-br from-[#083064]/8 via-transparent to-transparent" />
          <div class="relative p-7 lg:p-8">
            <div class="flex items-start gap-4">
              <div class="shrink-0 w-12 h-12 rounded-2xl bg-[#083064] text-white flex items-center justify-center text-xl">
                👁️
              </div>
              <div class="min-w-0">
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
            <div class="flex items-start gap-4">
              <div class="shrink-0 w-12 h-12 rounded-2xl bg-[#CBE4F8] text-[#083064] flex items-center justify-center text-xl">
                🎯
              </div>
              <div class="min-w-0">
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

      <div v-if="goals.length" class="rounded-2xl border border-[#E6EBF2] bg-[#F7FAFF] p-6 lg:p-8">
        <div class="flex items-center justify-between gap-4 mb-6">
          <h4 class="text-lg lg:text-xl font-extrabold text-[#051D3C]">
            {{ currentLang === 'ar' ? 'قيمنا الأساسية' : 'Our Core Values' }}
          </h4>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
          <div
            v-for="(g, i) in goals"
            :key="i"
            class="rounded-xl bg-white border border-[#E6EBF2] p-5 lg:p-6 shadow-sm"
          >
            <div class="flex items-start gap-3">
              <div class="mt-1 w-2.5 h-2.5 rounded-full bg-[#083064]" />
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
const vision = computed(() => props.section?.additional_data?.vision ?? {})
const mission = computed(() => props.section?.additional_data?.mission ?? {})
const goals = computed(() => props.section?.additional_data?.goals ?? [])
</script>
