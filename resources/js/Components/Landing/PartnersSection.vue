<template>
  <section id="partners" class="py-16 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <span
          v-if="showPill"
          class="inline-block px-4 py-2 rounded-full bg-[#083064]/10 text-[#083064] font-semibold text-sm mb-4"
        >
          {{ currentLang === 'ar' ? 'شركاؤنا' : 'Our Partners' }}
        </span>
        <h2 class="text-3xl lg:text-4xl font-extrabold text-[#051D3C] mb-4">{{ title }}</h2>
        <p class="text-lg text-gray-700">{{ description }}</p>
      </div>

      <div v-if="partnersToShow.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 lg:gap-8">
        <div
          v-for="(p, i) in partnersToShow"
          :key="partnerKey(p, i)"
          class="group relative flex flex-col p-6 lg:p-7 rounded-2xl bg-white border border-[#E6EBF2] shadow-sm hover:shadow-md transition-shadow duration-300"
        >
          <div class="flex items-center justify-center w-full h-24 rounded-xl bg-[#F7FAFF] border border-[#E6EBF2]">
            <img
              v-if="getLogoUrl(p)"
              :src="getLogoUrl(p)"
              :alt="partnerName(p)"
              class="max-h-14 w-[85%] object-contain"
              loading="lazy"
              @error="handleImageError"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center"
            >
              <div class="w-12 h-12 rounded-full bg-white border border-[#D9E2F0] flex items-center justify-center text-[#083064] font-bold">
                {{ partnerInitials(p) }}
              </div>
            </div>
          </div>

          <h3 class="mt-5 text-[#051D3C] text-base sm:text-lg font-bold leading-snug line-clamp-2">
            {{ partnerName(p) }}
          </h3>
          <p
            v-if="partnerDescription(p)"
            class="mt-2 text-sm sm:text-base text-gray-600 leading-relaxed line-clamp-3"
          >
            {{ partnerDescription(p) }}
          </p>
        </div>
      </div>

      <div v-else class="text-center py-16 px-6 rounded-2xl bg-white border-2 border-dashed border-gray-200">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#E6EBF2] flex items-center justify-center text-3xl">🤝</div>
        <p class="text-gray-600 text-lg">{{ currentLang === 'ar' ? 'سيتم عرض الشركاء قريباً' : 'Partners will be displayed soon' }}</p>
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
const partners = computed(() => props.section?.additional_data?.partners ?? [])

const showPill = computed(() => {
  const t = String(title.value ?? '').trim().toLowerCase()
  // Avoid showing "Our Partners" twice (pill + title)
  if (props.currentLang === 'en') return t !== 'our partners'
  if (props.currentLang === 'ar') return t !== 'شركاؤنا'
  return true
})

function partnerName(p) {
  return (props.currentLang === 'ar' ? (p?.name_ar ?? p?.name_en) : (p?.name_en ?? p?.name_ar) ?? '').trim()
}

function partnerDescription(p) {
  return (props.currentLang === 'ar' ? (p?.description_ar ?? p?.description_en) : (p?.description_en ?? p?.description_ar) ?? '').trim()
}

function partnerInitials(p) {
  const name = partnerName(p)
  if (!name) return 'P'
  const parts = name.split(/\s+/).filter(Boolean)
  const a = parts[0]?.[0] ?? ''
  const b = parts[1]?.[0] ?? ''
  const out = `${a}${b}`.toUpperCase()
  return out || name.slice(0, 2).toUpperCase()
}

function partnerKey(p, i) {
  return `${partnerName(p)}__${partnerDescription(p)}__${i}`
}

const partnersToShow = computed(() => {
  const normalize = (s) =>
    String(s ?? '')
      .replace(/\s+/g, ' ')
      .trim()
      .toLowerCase()

  // Some data sources duplicate partners (e.g. same name repeated).
  // For UI, dedupe by partner name only, and keep the richest description.
  const byName = new Map()
  for (const p of partners.value) {
    const nameKey = normalize(partnerName(p))
    if (!nameKey) continue

    const existing = byName.get(nameKey)
    if (!existing) {
      byName.set(nameKey, p)
      continue
    }

    const existingDesc = normalize(partnerDescription(existing))
    const nextDesc = normalize(partnerDescription(p))
    if (nextDesc.length > existingDesc.length) {
      byName.set(nameKey, p)
    }
  }

  return Array.from(byName.values())
})

const getLogoUrl = (partner) => {
  const logo = partner?.logo
  if (!logo || logo === 'null' || logo === 'undefined') return null
  const path = String(logo).trim()
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  if (path.startsWith('/storage/')) return path
  return path.startsWith('storage/') ? `/${path}` : `/storage/${path}`
}

const handleImageError = (e) => {
  e.target.style.display = 'none'
}
</script>
