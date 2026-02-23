<template>
  <section class="relative py-24 overflow-hidden bg-gradient-to-r from-secondary via-secondary-400 to-secondary">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2" />
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2" />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div
        ref="sectionRef"
        :class="['max-w-5xl mx-auto text-center', sectionAnimationClasses]"
      >
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-full mb-8 shadow-lg shadow-primary/30">
          <span class="text-xl">🎉</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'عرض خاص' : 'Special Offer' }}</span>
        </div>

        <!-- Title -->
        <h2 class="text-4xl lg:text-6xl font-black text-primary mb-6 leading-tight">
          {{ currentLang === 'ar' ? section?.title_ar || 'احجز طاهيك الآن واحصل على خصم 20%' : section?.title_en || 'Book Your Chef Now & Get 20% Off' }}
        </h2>

        <!-- Description -->
        <p class="text-xl text-primary/70 mb-10 max-w-3xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar || 'استمتع بأشهى الأطباق في منزلك مع أفضل الطهاة المحترفين' : section?.description_en || 'Enjoy delicious dishes at home with the best professional chefs' }}
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap gap-5 justify-center mb-12">
          <a
            :href="section?.additional_data?.cta_link || '#contact'"
            class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-primary to-primary-600 text-white font-bold text-lg rounded-full hover:shadow-2xl hover:shadow-primary/40 transition-all duration-300 hover:-translate-y-1"
          >
            <span>{{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'احجز الآن') : (section?.additional_data?.cta_text_en || 'Book Now') }}</span>
            <svg
              class="w-6 h-6"
              :class="currentLang === 'ar' ? 'rotate-180' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>

          <a
            href="tel:+966500000000"
            class="inline-flex items-center gap-3 px-10 py-5 bg-white text-primary font-bold text-lg rounded-full border-2 border-primary/20 hover:border-primary hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span>{{ currentLang === 'ar' ? 'اتصل بنا' : 'Call Us' }}</span>
          </a>
        </div>

        <!-- Trust Badges -->
        <div class="flex flex-wrap items-center justify-center gap-8 pt-10 border-t-2 border-primary/10">
          <div class="flex items-center gap-3 text-primary bg-white/50 px-5 py-3 rounded-full">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="font-bold">{{ currentLang === 'ar' ? 'ضمان الجودة' : 'Quality Guaranteed' }}</span>
          </div>
          <div class="flex items-center gap-3 text-primary bg-white/50 px-5 py-3 rounded-full">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="font-bold">{{ currentLang === 'ar' ? 'دفع آمن' : 'Secure Payment' }}</span>
          </div>
          <div class="flex items-center gap-3 text-primary bg-white/50 px-5 py-3 rounded-full">
            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <span class="font-bold">{{ currentLang === 'ar' ? 'دعم 24/7' : '24/7 Support' }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useScrollTrigger } from '@/composables/useScrollTrigger'
import { useAnimations } from '@/composables/useAnimations'

defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
})

const sectionRef = ref(null)

const { isVisible, observe } = useScrollTrigger({ threshold: 0.2 })
const { prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (sectionRef.value) {
    observe(sectionRef.value)
  }
})

const sectionAnimationClasses = computed(() => {
  if (prefersReducedMotion.value) return 'opacity-100'
  return isVisible.value
    ? 'opacity-100 translate-y-0 transition-all duration-700'
    : 'opacity-0 translate-y-8 transition-all duration-700'
})
</script>
