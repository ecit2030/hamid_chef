<template>
  <section id="contact" class="py-24 bg-gradient-to-br from-primary via-primary-600 to-primary-800 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');" />
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <!-- Content Side -->
        <div class="text-white" :class="currentLang === 'ar' ? 'text-right' : 'text-left'">
          <div class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 text-white rounded-full mb-8 border border-white/20 backdrop-blur-sm">
            <span class="text-xl">📞</span>
            <span class="font-bold">{{ currentLang === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</span>
          </div>

          <h2 class="text-4xl lg:text-5xl font-black mb-6 leading-tight">
            {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
          </h2>
          <p class="text-xl text-white/80 mb-10 leading-relaxed">
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- Contact Info Cards -->
          <div class="space-y-4">
            <div class="flex items-center gap-5 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div class="w-14 h-14 bg-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div>
                <div class="text-secondary text-sm font-medium mb-1">{{ currentLang === 'ar' ? 'اتصل بنا' : 'Call Us' }}</div>
                <div class="text-xl font-bold" dir="ltr">+966 50 000 0000</div>
              </div>
            </div>

            <div class="flex items-center gap-5 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div class="w-14 h-14 bg-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <div class="text-secondary text-sm font-medium mb-1">{{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</div>
                <div class="text-xl font-bold">info@moonchef.com</div>
              </div>
            </div>

            <div class="flex items-center gap-5 bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20 hover:bg-white/20 transition-all duration-300 group">
              <div class="w-14 h-14 bg-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <div>
                <div class="text-secondary text-sm font-medium mb-1">{{ currentLang === 'ar' ? 'العنوان' : 'Address' }}</div>
                <div class="text-xl font-bold">{{ currentLang === 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Side -->
        <div class="bg-white dark:bg-gray-900 rounded-3xl p-10 shadow-2xl">
          <h3 class="text-2xl font-bold text-primary dark:text-white mb-8 text-center">
            {{ currentLang === 'ar' ? 'أرسل لنا رسالة' : 'Send us a message' }}
          </h3>

          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                {{ currentLang === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:outline-none focus:border-primary dark:focus:border-secondary text-gray-900 dark:text-white transition-colors duration-200 text-lg"
                :placeholder="currentLang === 'ar' ? 'أدخل اسمك' : 'Enter your name'"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                {{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
              </label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:outline-none focus:border-primary dark:focus:border-secondary text-gray-900 dark:text-white transition-colors duration-200 text-lg"
                :placeholder="currentLang === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email'"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                {{ currentLang === 'ar' ? 'رسالتك' : 'Your Message' }}
              </label>
              <textarea
                v-model="form.message"
                rows="5"
                required
                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:outline-none focus:border-primary dark:focus:border-secondary text-gray-900 dark:text-white transition-colors duration-200 resize-none text-lg"
                :placeholder="currentLang === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...'"
              />
            </div>

            <button
              type="submit"
              class="w-full px-8 py-5 bg-gradient-to-r from-primary to-primary-600 text-white font-bold text-lg rounded-xl hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-3"
            >
              <span>{{ currentLang === 'ar' ? 'إرسال الرسالة' : 'Send Message' }}</span>
              <svg
                class="w-6 h-6"
                :class="currentLang === 'ar' ? 'rotate-180' : ''"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' },
  isDarkMode: { type: Boolean, default: false }
})

const form = ref({
  name: '',
  email: '',
  message: ''
})

const handleSubmit = () => {
  console.log('Form submitted:', form.value)
}
</script>
