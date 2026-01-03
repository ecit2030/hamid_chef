<template>
  <section id="contact" class="relative py-24 overflow-hidden">
    <!-- Primary Background -->
    <div class="absolute inset-0 bg-primary">
      <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23CBE4F8&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <!-- Content Side -->
        <div class="text-white" :class="currentLang === 'ar' ? 'text-right' : 'text-left'">
          <div class="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-primary rounded-full mb-6 shadow-xl">
            <span class="text-xl">📞</span>
            <span class="font-bold">{{ currentLang === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</span>
          </div>

          <h2 class="text-4xl lg:text-5xl font-black mb-4">
            {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
          </h2>
          <p class="text-lg text-secondary mb-8 leading-relaxed">
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- Contact Info -->
          <div class="space-y-4">
            <div class="flex items-center gap-4 bg-white/10 rounded-xl p-4">
              <div class="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <div>
                <div class="text-secondary text-sm">{{ currentLang === 'ar' ? 'اتصل بنا' : 'Call Us' }}</div>
                <div class="text-lg font-bold">+966 50 000 0000</div>
              </div>
            </div>

            <div class="flex items-center gap-4 bg-white/10 rounded-xl p-4">
              <div class="w-12 h-12 bg-secondary rounded-lg flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <div class="text-secondary text-sm">{{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</div>
                <div class="text-lg font-bold">info@chefplatform.com</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Side -->
        <div>
          <div class="bg-white rounded-2xl p-8 shadow-2xl border-4 border-secondary">
            <h3 class="text-xl font-black text-primary mb-6 text-center">
              {{ currentLang === 'ar' ? 'أرسل لنا رسالة' : 'Send us a message' }}
            </h3>
            
            <form @submit.prevent="handleSubmit" class="space-y-4">
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  {{ currentLang === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                </label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  required
                  class="w-full px-4 py-3 bg-gray-50 border-2 border-secondary rounded-xl focus:outline-none focus:border-primary focus:bg-white transition-all"
                  :placeholder="currentLang === 'ar' ? 'أدخل اسمك' : 'Enter your name'"
                />
              </div>
              
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  {{ currentLang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                </label>
                <input 
                  v-model="form.email" 
                  type="email" 
                  required
                  class="w-full px-4 py-3 bg-gray-50 border-2 border-secondary rounded-xl focus:outline-none focus:border-primary focus:bg-white transition-all"
                  :placeholder="currentLang === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email'"
                />
              </div>
              
              <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                  {{ currentLang === 'ar' ? 'رسالتك' : 'Your Message' }}
                </label>
                <textarea 
                  v-model="form.message" 
                  rows="4"
                  required
                  class="w-full px-4 py-3 bg-gray-50 border-2 border-secondary rounded-xl focus:outline-none focus:border-primary focus:bg-white transition-all resize-none"
                  :placeholder="currentLang === 'ar' ? 'اكتب رسالتك هنا...' : 'Write your message here...'"
                ></textarea>
              </div>

              <button 
                type="submit"
                class="w-full px-6 py-4 bg-primary text-white font-black text-lg rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-xl flex items-center justify-center gap-3"
              >
                <span>{{ currentLang === 'ar' ? 'إرسال الرسالة' : 'Send Message' }}</span>
                <svg class="w-5 h-5" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
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
