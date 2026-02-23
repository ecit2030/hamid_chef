<template>
  <footer
    ref="footerRef"
    class="relative overflow-hidden bg-primary"
  >
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');" />
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-20 pb-12">
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
        <!-- Brand -->
        <div
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.1s, transform 0.6s ease-out 0.1s' }"
        >
          <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-secondary rounded-2xl flex items-center justify-center shadow-lg shadow-secondary/30">
              <span class="text-4xl">👨‍🍳</span>
            </div>
            <span class="text-2xl font-black text-white">{{ currentLang === 'ar' ? 'منصة الطهاة' : 'Chef Platform' }}</span>
          </div>
          <p class="text-white/70 leading-relaxed mb-8 text-lg">
            {{ currentLang === 'ar' ? 'منصتك الأولى لحجز أفضل الطهاة المحترفين في المملكة العربية السعودية' : 'Your premier platform for booking the best professional chefs in Saudi Arabia' }}
          </p>

          <!-- Social Links -->
          <div class="flex gap-4">
            <a href="#" class="w-12 h-12 bg-white/10 hover:bg-secondary rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 group">
              <svg class="w-6 h-6 text-white group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
              </svg>
            </a>
            <a href="#" class="w-12 h-12 bg-white/10 hover:bg-secondary rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 group">
              <svg class="w-6 h-6 text-white group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
              </svg>
            </a>
            <a href="#" class="w-12 h-12 bg-white/10 hover:bg-secondary rounded-xl flex items-center justify-center transition-all duration-300 hover:-translate-y-1 group">
              <svg class="w-6 h-6 text-white group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.2s, transform 0.6s ease-out 0.2s' }"
        >
          <h4 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
            <span class="w-2 h-8 bg-secondary rounded-full"></span>
            {{ currentLang === 'ar' ? 'روابط سريعة' : 'Quick Links' }}
          </h4>
          <ul class="space-y-4">
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg flex items-center gap-2 group">
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                {{ currentLang === 'ar' ? 'الرئيسية' : 'Home' }}
              </a>
            </li>
            <li>
              <a href="#features" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg flex items-center gap-2 group">
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                {{ currentLang === 'ar' ? 'خدماتنا' : 'Services' }}
              </a>
            </li>
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg flex items-center gap-2 group">
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                {{ currentLang === 'ar' ? 'الطهاة' : 'Chefs' }}
              </a>
            </li>
            <li>
              <a href="#contact" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg flex items-center gap-2 group">
                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                {{ currentLang === 'ar' ? 'تواصل معنا' : 'Contact' }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Services -->
        <div
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.3s, transform 0.6s ease-out 0.3s' }"
        >
          <h4 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
            <span class="w-2 h-8 bg-secondary rounded-full"></span>
            {{ currentLang === 'ar' ? 'خدماتنا' : 'Our Services' }}
          </h4>
          <ul class="space-y-4">
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg">
                {{ currentLang === 'ar' ? 'طهاة منزليون' : 'Home Chefs' }}
              </a>
            </li>
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg">
                {{ currentLang === 'ar' ? 'حفلات خاصة' : 'Private Events' }}
              </a>
            </li>
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg">
                {{ currentLang === 'ar' ? 'دروس طبخ' : 'Cooking Classes' }}
              </a>
            </li>
            <li>
              <a href="#" class="text-white/70 hover:text-secondary transition-colors duration-300 font-medium text-lg">
                {{ currentLang === 'ar' ? 'تموين الشركات' : 'Corporate Catering' }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div
          :class="{ 'opacity-0 translate-y-8': !isVisible, 'opacity-100 translate-y-0': isVisible }"
          :style="{ transition: 'opacity 0.6s ease-out 0.4s, transform 0.6s ease-out 0.4s' }"
        >
          <h4 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
            <span class="w-2 h-8 bg-secondary rounded-full"></span>
            {{ currentLang === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
          </h4>
          <ul class="space-y-5">
            <li class="flex items-center gap-4 text-white/70">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
              </div>
              <span class="text-lg">{{ currentLang === 'ar' ? 'الرياض، المملكة العربية السعودية' : 'Riyadh, Saudi Arabia' }}</span>
            </li>
            <li class="flex items-center gap-4 text-white/70">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
              </div>
              <span dir="ltr" class="text-lg">+966 50 000 0000</span>
            </li>
            <li class="flex items-center gap-4 text-white/70">
              <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <span class="text-lg">info@chefplatform.com</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div
        class="pt-10 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6"
        :class="{ 'opacity-0': !isVisible, 'opacity-100': isVisible }"
        :style="{ transition: 'opacity 0.6s ease-out 0.5s' }"
      >
        <p class="text-white/60 text-center md:text-right text-lg">
          © {{ new Date().getFullYear() }} {{ currentLang === 'ar' ? 'منصة الطهاة. جميع الحقوق محفوظة' : 'Chef Platform. All rights reserved' }}
        </p>
        <div class="flex items-center gap-8">
          <a href="#" class="text-white/60 hover:text-secondary transition-colors duration-300">{{ currentLang === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
          <a href="#" class="text-white/60 hover:text-secondary transition-colors duration-300">{{ currentLang === 'ar' ? 'الشروط والأحكام' : 'Terms & Conditions' }}</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineProps({
  currentLang: { type: String, default: 'ar' },
  isDarkMode: { type: Boolean, default: false }
})

const footerRef = ref(null)
const isVisible = ref(false)
let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true
        }
      })
    },
    { threshold: 0.1 }
  )

  if (footerRef.value) {
    observer.observe(footerRef.value)
  }
})

onUnmounted(() => {
  if (observer) {
    observer.disconnect()
  }
})
</script>
