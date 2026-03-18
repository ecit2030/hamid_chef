<template>
  <section id="features" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="section-header !text-center max-w-3xl w-full mb-12 lg:mb-16 mx-auto" :dir="currentLang === 'ar' ? 'rtl' : 'ltr'">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 w-full max-w-6xl mx-auto">
        <div
          v-for="(feature, i) in features"
          :key="i"
          class="group p-6 lg:p-8 rounded-2xl border-2 border-gray-200 hover:border-[#99AFCB] hover:shadow-lg hover:shadow-[#E6EBF2] transition-all duration-300 text-center flex flex-col items-center"
        >
          <div class="w-14 h-14 rounded-xl bg-[#CCD7E5] text-[#083064] flex items-center justify-center mb-4 group-hover:bg-[#083064] group-hover:text-white transition-colors">
            <component :is="getIconComponent(feature.icon)" class="w-7 h-7" />
          </div>
          <h3 class="text-xl font-bold text-[#051D3C] mb-2">{{ currentLang === 'ar' ? feature.title_ar : feature.title_en }}</h3>
          <p class="text-gray-700 text-center">{{ currentLang === 'ar' ? feature.description_ar : feature.description_en }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, h } from 'vue'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const features = computed(() => props.section?.additional_data?.features ?? [])

const IconChefHat = {
  name: 'IconChefHat',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M4 10a4 4 0 014-4 4 4 0 018 0 4 4 0 014 4',
        }),
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M7 10h10v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6z',
        }),
      ],
    )
  },
}

const IconCalendar = {
  name: 'IconCalendar',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('rect', { x: '3', y: '4', width: '18', height: '18', rx: '2', ry: '2', 'stroke-width': '2' }),
        h('path', { d: 'M16 2v4M8 2v4M3 10h18', 'stroke-width': '2', 'stroke-linecap': 'round' }),
      ],
    )
  },
}

const IconStar = {
  name: 'IconStar',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'currentColor', viewBox: '0 0 24 24' },
      [
        h('path', {
          d: 'M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z',
        }),
      ],
    )
  },
}

const IconShield = {
  name: 'IconShield',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M12 2l7 4v6c0 4.418-3.134 8-7 8s-7-3.582-7-8V6l7-4z',
        }),
      ],
    )
  },
}

const IconUtensils = {
  name: 'IconUtensils',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M4 3v7a3 3 0 003 3v8M10 3v7a3 3 0 003 3v8M17 21V3a2 2 0 012 2v7a4 4 0 01-4 4',
        }),
      ],
    )
  },
}

const IconSupport = {
  name: 'IconSupport',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M18 13a3 3 0 003-3V7a3 3 0 00-3-3H6a3 3 0 00-3 3v3a3 3 0 003 3h1v4l4-4h7z',
        }),
      ],
    )
  },
}

const IconSparkles = {
  name: 'IconSparkles',
  render() {
    return h(
      'svg',
      { xmlns: 'http://www.w3.org/2000/svg', fill: 'none', viewBox: '0 0 24 24', stroke: 'currentColor' },
      [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M5 3l2 4 4 2-4 2-2 4-2-4-4-2 4-2 2-4zm14 4l1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2zm-4 7l1.5 3L20 18.5 17.5 20 16 23l-1.5-3L12 18.5 14.5 17 16 14z',
        }),
      ],
    )
  },
}

const iconComponents = {
  'chef-hat': IconChefHat,
  calendar: IconCalendar,
  star: IconStar,
  shield: IconShield,
  utensils: IconUtensils,
  support: IconSupport,
}

function getIconComponent(icon) {
  return iconComponents[icon] ?? IconSparkles
}
</script>
