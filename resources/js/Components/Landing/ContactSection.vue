<template>
  <section id="contact" class="py-16 lg:py-24 bg-[#020A14] text-white">
    <div class="container mx-auto px-4 lg:px-8">
      <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">{{ title }}</h2>
        <p class="text-white/90 text-lg">{{ description }}</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16">
        <div class="lg:col-span-2 space-y-8">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">{{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</h4>
              <a :href="`mailto:${contact.email}`" class="text-white/90 hover:text-white transition-colors">{{ contact.email }}</a>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">{{ currentLang === 'ar' ? 'الهاتف' : 'Phone' }}</h4>
              <a :href="`tel:${contact.phone}`" class="text-white/90 hover:text-white transition-colors">{{ contact.phone }}</a>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">{{ currentLang === 'ar' ? 'العنوان' : 'Address' }}</h4>
              <p class="text-white/90">{{ currentLang === 'ar' ? contact.address_ar : contact.address_en }}</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">{{ currentLang === 'ar' ? 'ساعات العمل' : 'Working Hours' }}</h4>
              <p class="text-white/90">{{ currentLang === 'ar' ? contact.working_hours_ar : contact.working_hours_en }}</p>
            </div>
          </div>

          <div class="flex gap-3 pt-4">
            <a
              v-for="s in socialLinks"
              :key="s.platform"
              :href="s.url"
              target="_blank"
              rel="noopener"
              class="w-11 h-11 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#CBE4F8] hover:text-[#083064] transition-all text-white"
            >
              <span class="text-lg">{{ getSocialIcon(s.platform) }}</span>
            </a>
          </div>
        </div>

        <div class="lg:col-span-3">
          <form
            class="p-8 lg:p-10 rounded-2xl bg-white/5 border border-white/10"
            @submit.prevent="submitForm"
          >
            <h3 class="text-xl font-bold text-white mb-6">{{ currentLang === 'ar' ? 'أرسل لنا رسالة' : 'Send us a message' }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-white/90 mb-2">{{ currentLang === 'ar' ? 'الاسم' : 'Name' }}</label>
                <input
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                  :placeholder="currentLang === 'ar' ? 'أدخل اسمك' : 'Enter your name'"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-white/90 mb-2">{{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                  :placeholder="currentLang === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email'"
                />
              </div>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-white/90 mb-2">{{ currentLang === 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all"
                :placeholder="currentLang === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number'"
              />
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-white/90 mb-2">{{ currentLang === 'ar' ? 'الرسالة' : 'Message' }}</label>
              <textarea
                v-model="form.message"
                required
                rows="4"
                class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-[#CBE4F8] focus:border-transparent transition-all resize-none"
                :placeholder="currentLang === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...'"
              />
            </div>

            <button
              type="submit"
              :disabled="formLoading"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-bold bg-[#CBE4F8] text-[#083064] hover:bg-[#A3D1F3] disabled:opacity-50 transition-colors"
            >
              <span v-if="formLoading">{{ currentLang === 'ar' ? 'جاري الإرسال...' : 'Sending...' }}</span>
              <span v-else>{{ currentLang === 'ar' ? 'إرسال الرسالة' : 'Send Message' }}</span>
              <svg v-if="!formLoading" class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>

            <p v-if="formSuccess" class="mt-4 text-green-400 text-sm">{{ currentLang === 'ar' ? 'تم إرسال رسالتك بنجاح!' : 'Your message has been sent successfully!' }}</p>
            <p v-if="formError" class="mt-4 text-red-400 text-sm">{{ formError }}</p>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  section: { type: Object, default: () => ({}) },
  currentLang: { type: String, default: 'ar' },
})

const title = computed(() => props.currentLang === 'ar' ? props.section?.title_ar : props.section?.title_en)
const description = computed(() => props.currentLang === 'ar' ? props.section?.description_ar : props.section?.description_en)
const contact = computed(() => ({
  email: props.section?.additional_data?.email ?? 'info@hamidchef.com',
  phone: props.section?.additional_data?.phone ?? '+967 777 777 777',
  address_ar: props.section?.additional_data?.address_ar ?? 'صنعاء، اليمن',
  address_en: props.section?.additional_data?.address_en ?? "Sana'a, Yemen",
  working_hours_ar: props.section?.additional_data?.working_hours_ar ?? 'السبت - الخميس: 9:00 ص - 9:00 م',
  working_hours_en: props.section?.additional_data?.working_hours_en ?? 'Saturday - Thursday: 9:00 AM - 9:00 PM',
}))
const socialLinks = computed(() => props.section?.additional_data?.social_links ?? [])

const form = ref({
  name: '',
  email: '',
  phone: '',
  message: '',
})
const formLoading = ref(false)
const formSuccess = ref(false)
const formError = ref('')

function getSocialIcon(platform) {
  const icons = { facebook: 'f', instagram: '📷', twitter: '𝕏', whatsapp: '💬' }
  return icons[platform] ?? '•'
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
      formError.value = data.message || (props.currentLang === 'ar' ? 'حدث خطأ، حاول مرة أخرى' : 'An error occurred, please try again')
    }
  } catch {
    formError.value = props.currentLang === 'ar' ? 'حدث خطأ في الاتصال، حاول مرة أخرى' : 'Connection error, please try again'
  } finally {
    formLoading.value = false
  }
}
</script>
