<template>
  <section id="contact" class="py-16 lg:py-24 bg-[#020A14] text-white">
    <div class="container mx-auto px-4 lg:px-8 flex flex-col items-center">
      <div class="!text-center max-w-3xl w-full mb-12 lg:mb-16 mx-auto">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">{{ title }}</h2>
        <p class="text-white/90 text-lg">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16 w-full max-w-6xl mx-auto">
        <div class="lg:col-span-2">
          <!-- EN: icon + text, tight gap (like LTR). AR: icon on right, text hugging icon (flex-row-reverse + text-right), block aligned right -->
          <div
            class="flex flex-col gap-8 w-full max-w-md"
            :class="currentLang === 'ar' ? 'ml-auto' : 'mr-auto'"
          >
            <div
              class="flex w-full items-center gap-2"
              :class="currentLang === 'ar' ? 'flex-row-reverse' : 'flex-row'"
            >
              <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div
                class="min-w-0 flex-1"
                :class="currentLang === 'ar' ? 'text-right' : 'text-left'"
              >
                <h4 class="font-bold text-white mb-1 leading-tight">{{ contactLabels.email }}</h4>
                <a
                  :href="`mailto:${contact.email}`"
                  class="text-white/90 hover:text-white transition-colors block break-all"
                  dir="ltr"
                >
                  {{ contact.email }}
                </a>
              </div>
            </div>
            <div
              class="flex w-full items-center gap-2"
              :class="currentLang === 'ar' ? 'flex-row-reverse' : 'flex-row'"
            >
              <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div
                class="min-w-0 flex-1"
                :class="currentLang === 'ar' ? 'text-right' : 'text-left'"
              >
                <h4 class="font-bold text-white mb-1 leading-tight">{{ contactLabels.phone }}</h4>
                <a :href="`tel:${contact.phone}`" class="text-white/90 hover:text-white transition-colors block" dir="ltr">{{ contact.phone }}</a>
              </div>
            </div>
            <div
              class="flex w-full items-center gap-2"
              :class="currentLang === 'ar' ? 'flex-row-reverse' : 'flex-row'"
            >
              <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div
                class="min-w-0 flex-1"
                :class="currentLang === 'ar' ? 'text-right' : 'text-left'"
              >
                <h4 class="font-bold text-white mb-1 leading-tight">{{ contactLabels.address }}</h4>
                <p class="text-white/90">{{ currentLang === 'ar' ? contact.address_ar : contact.address_en }}</p>
              </div>
            </div>
            <div
              class="flex w-full items-center gap-2"
              :class="currentLang === 'ar' ? 'flex-row-reverse' : 'flex-row'"
            >
              <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div
                class="min-w-0 flex-1"
                :class="currentLang === 'ar' ? 'text-right' : 'text-left'"
              >
                <h4 class="font-bold text-white mb-1 leading-tight">{{ contactLabels.workingHours }}</h4>
                <p class="text-white/90">{{ currentLang === 'ar' ? contact.working_hours_ar : contact.working_hours_en }}</p>
              </div>
            </div>
          </div>

          <div
            class="flex gap-3 pt-10"
            :class="currentLang === 'ar' ? 'justify-end' : 'justify-start'"
          >
          <a
            v-for="s in socialLinks"
            :key="s.platform"
            :href="s.url"
            target="_blank"
            rel="noopener"
            class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#CBE4F8] hover:text-[#083064] transition-all text-white"
          >
            <component :is="getSocialIconComponent(s.platform)" class="w-5 h-5" />
          </a>
        </div>
        </div>

        <div class="lg:col-span-3">
          <form
            class="p-8 lg:p-10 rounded-2xl bg-white/5 border border-white/10"
            @submit.prevent="submitForm"
          >
            <h3 class="text-xl font-bold text-white mb-6">{{ contactLabels.sendUsMessage }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-white/90 mb-2">{{ contactLabels.name }}</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                  :placeholder="contactLabels.namePlaceholder"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-white/90 mb-2">{{ contactLabels.email }}</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                  :placeholder="contactLabels.emailPlaceholder"
                />
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-white/90 mb-2">{{ contactLabels.phone }}</label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                :placeholder="contactLabels.phonePlaceholder"
              />
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-white/90 mb-2">{{ contactLabels.message }}</label>
              <textarea
                v-model="form.message"
                required
                rows="4"
                class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all resize-none"
                :placeholder="contactLabels.messagePlaceholder"
              />
            </div>

            <div class="flex flex-col items-center">
              <button
                type="submit"
                :disabled="formLoading"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-bold bg-[#CBE4F8] text-[#083064] hover:bg-[#A3D1F3] disabled:opacity-50 transition-colors"
              >
                <span v-if="formLoading">{{ contactLabels.sendMessageLoading }}</span>
                <span v-else>{{ contactLabels.sendMessage }}</span>
                <svg v-if="!formLoading" :class="currentLang === 'ar' ? 'w-5 h-5 rotate-180' : 'w-5 h-5'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </button>
              <p v-if="formSuccess" class="mt-4 text-green-400 text-sm text-center">{{ contactLabels.success }}</p>
              <p v-if="formError" class="mt-4 text-red-400 text-sm text-center">{{ formError }}</p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, h } from 'vue'
import { router } from '@inertiajs/vue3'
import { getLandingLabels } from '@/data/landingLabels'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const contactLabels = computed(() => getLandingLabels(props.currentLang).contact)
const contact = computed(() => ({
  email: props.section?.additional_data?.email ?? 'info@monchef.com',
  // Force these values for consistent public landing UI
  phone: '0582800034',
  address_ar: 'الرياض، المملكة العربية السعودية',
  address_en: 'Riyadh, Saudi Arabia',
  working_hours_ar: 'من 9 صباحاً حتى 5 مساءً',
  working_hours_en: '9 AM to 5 PM',
}))

const DEFAULT_SOCIAL_LINKS = [
  { platform: 'facebook', url: '#' },
  { platform: 'x', url: '#' },
  { platform: 'instagram', url: '#' },
  { platform: 'whatsapp', url: 'https://wa.me/0582800034' },
]

const socialLinks = computed(() => {
  const fromApi = Array.isArray(props.section?.additional_data?.social_links)
    ? props.section.additional_data.social_links
    : []

  // Always render all icons; fill URLs from API when provided.
  const byPlatform = new Map(
    fromApi
      .filter((s) => s && typeof s === 'object')
      .map((s) => [String(s.platform ?? '').toLowerCase(), s.url])
      .filter(([p, url]) => p && typeof url === 'string' && url.trim())
  )

  return DEFAULT_SOCIAL_LINKS.map((d) => {
    const p = d.platform.toLowerCase()
    const url = byPlatform.get(p) ?? d.url
    return { platform: d.platform, url }
  })
})

const form = ref({
  name: '',
  email: '',
  phone: '',
  message: '',
})
const formLoading = ref(false)
const formSuccess = ref(false)
const formError = ref('')

const IconFacebook = {
  render() {
    return h('svg', { class: 'w-5 h-5', fill: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { d: 'M22 12a10 10 0 1 0-11.5 9.87v-6.99H8v-2.88h2.5V9.41c0-2.46 1.47-3.82 3.72-3.82 1.08 0 2.22.19 2.22.19v2.44h-1.25c-1.23 0-1.61.77-1.61 1.56v1.87H18l-.4 2.88h-2.54v6.99A10 10 0 0 0 22 12Z' })
    ])
  },
}

const IconX = {
  render() {
    return h('svg', { class: 'w-5 h-5', fill: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { d: 'M18.9 3H21l-4.6 5.26L21.7 21h-4.2l-3.1-7.5L9.6 21H3l4.9-5.62L2.5 3H7l2.8 6.7L18.9 3Z' })
    ])
  },
}

const IconInstagram = {
  render() {
    return h('svg', { class: 'w-5 h-5', fill: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        d: 'M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm0 2h10c1.66 0 3 1.34 3 3v10c0 1.66-1.34 3-3 3H7c-1.66 0-3-1.34-3-3V7c0-1.66 1.34-3 3-3zm10 1a1 1 0 100 2 1 1 0 000-2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z',
      }),
    ])
  },
}

const IconWhatsapp = {
  render() {
    return h('svg', { class: 'w-5 h-5', fill: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', {
        d: 'M20 4.5A9.8 9.8 0 0012.2 2 10 10 0 002 12.3 9.85 9.85 0 003.7 18L2 22l4.2-1.6A10.2 10.2 0 0012.3 22 10 10 0 0022 11.8 9.8 9.8 0 0020 4.5zm-7.7 15.1a8.3 8.3 0 01-4.2-1.2l-.3-.2-2.5.9.9-2.4-.2-.3a8.1 8.1 0 01-1.2-4.3 8.2 8.2 0 018.3-8.1 8.1 8.1 0 018.1 8.2 8.2 8.2 0 01-8.1 8.1zm4.5-6c-.2-.1-1.3-.6-1.5-.7s-.4-.1-.6.1-.7.7-.8.8-.3.2-.5.1a6.9 6.9 0 01-2-1.2 7.4 7.4 0 01-1.4-1.8c-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3.2-.4a.44.44 0 000-.4C11 9 10.4 7.7 10.2 7.2s-.4-.5-.6-.5h-.5a1 1 0 00-.7.3 2.9 2.9 0 00-.9 2.1 5 5 0 001 2.6 11.5 11.5 0 004.4 4 5 5 0 003.1 1h.6a2.6 2.6 0 001.8-1.2 2.1 2.1 0 00.1-1.1c-.1-.1-.2-.2-.4-.3z',
      }),
    ])
  },
}

function getSocialIconComponent(platform) {
  const map = {
    facebook: IconFacebook,
    x: IconX,
    twitter: IconX,
    instagram: IconInstagram,
    whatsapp: IconWhatsapp,
  }
  return map[platform] ?? IconFacebook
}

function getCsrfToken() {
  const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/)
  return match ? decodeURIComponent(match[1]) : ''
}

async function submitForm() {
  formLoading.value = true
  formSuccess.value = false
  formError.value = ''

  try {
    const response = await fetch(route('contact.store'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-XSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(form.value),
    })

    if (response.ok) {
      formSuccess.value = true
      form.value = { name: '', email: '', phone: '', message: '' }
    } else {
      const data = await response.json().catch(() => ({}))
      formError.value = data.message || contactLabels.value.error
    }
  } catch {
    formError.value = contactLabels.value.errorConnection
  } finally {
    formLoading.value = false
  }
}
</script>
