<template>
  <section class="relative py-20 overflow-hidden">
    <!-- Secondary Background with Gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-secondary via-secondary-400 to-secondary">
      <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%23083064&quot;%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;4&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;10&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;10&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3Ccircle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/svg%3E');" />
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div
        ref="sectionRef"
        :class="['max-w-4xl mx-auto text-center', sectionAnimationClasses]"
      >
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 backdrop-blur-sm text-primary rounded-full mb-6 border border-primary/20">
          <span class="text-xl">🎉</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'عرض خاص' : 'Special Offer' }}</span>
        </div>

        <!-- Title -->
        <h2 class="text-3xl lg:text-5xl font-black text-primary mb-4 leading-tight">
          {{ currentLang === 'ar' ? section?.title_ar || 'احجز طاهيك الآن واحصل على خصم 20%' : section?.title_en || 'Book Your Chef Now & Get 20% Off' }}
        </h2>

        <!-- Description -->
        <p class="text-lg text-primary/80 mb-8 max-w-2xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar || 'استمتع بأشهى الأطباق في منزلك مع أفضل الطهاة المحترفين' : section?.description_en || 'Enjoy delicious dishes at home with the best professional chefs' }}
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-wrap gap-4 justify-center">
          <a
            :href="section?.additional_data?.cta_link || '#contact'"
            class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary to-primary-600 text-white font-black rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-primary/30"
          >
            <span>{{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'احجز الآن') : (section?.additional_data?.cta_text_en || 'Book Now') }}</span>
            <svg
              class="w-5 h-5 transition-transform group-hover:translate-x-1"
              :class="currentLang === 'ar' ? 'rotate-180 group-hover:-translate-x-1' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
            </svg>
          </a>

          <a
            href="tel:+966500000000"
            class="group inline-flex items-center gap-3 px-8 py-4 bg-white text-primary font-bold rounded-xl border-2 border-primary transition-all duration-300 hover:bg-primary hover:text-white hover:scale-105"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span>{{ currentLang === 'ar' ? 'اتصل بنا' : 'Call Us' }}</span>
          </a>
        </div>

        <!-- Trust Badges -->
        <div class="flex flex-wrap items-center justify-center gap-6 mt-10 pt-8 border-t-2 border-primary/20">
          <div class="flex items-center gap-2 text-primary group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-sm">{{ currentLang === 'ar' ? 'ضمان الجودة' : 'Quality Guaranteed' }}</span>
          </div>
          <div class="flex items-center gap-2 text-primary group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-sm">{{ currentLang === 'ar' ? 'دفع آمن' : 'Secure Payment' }}</span>
          </div>
          <div class="flex items-center gap-2 text-primary group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-bold text-sm">{{ currentLang === 'ar' ? 'دعم 24/7' : '24/7 Support' }}</span>
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
