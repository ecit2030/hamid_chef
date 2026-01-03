<template>
  <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-secondary/30 via-white to-primary/5 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Animated Background Shapes -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <!-- Large Circle -->
      <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-gradient-to-br from-primary/10 to-secondary/20 rounded-full blur-3xl animate-pulse-slow"></div>
      <!-- Small Circles -->
      <div class="absolute top-1/4 left-10 w-20 h-20 bg-secondary/40 rounded-full blur-xl animate-float"></div>
      <div class="absolute bottom-1/4 right-20 w-32 h-32 bg-primary/20 rounded-full blur-2xl animate-float-delayed"></div>
      <div class="absolute bottom-20 left-1/4 w-16 h-16 bg-secondary/30 rounded-full blur-lg animate-bounce-slow"></div>
      <!-- Decorative Dots Pattern -->
      <div class="absolute top-20 right-1/4 grid grid-cols-3 gap-2 opacity-20">
        <div v-for="i in 9" :key="i" class="w-2 h-2 bg-primary rounded-full"></div>
      </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-24 pb-16">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <!-- Text Content -->
        <div class="text-center lg:text-start space-y-6 lg:space-y-8 order-2 lg:order-1">
          <!-- Badge -->
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/50 dark:bg-secondary/20 rounded-full border border-secondary">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
            </span>
            <span class="text-sm font-medium text-primary dark:text-secondary">
              {{ currentLang === 'ar' ? (section?.additional_data?.badge_ar || 'أفضل خدمة طهاة منزليين') : (section?.additional_data?.badge_en || 'Best Home Chef Service') }}
            </span>
          </div>

          <!-- Main Title -->
          <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight">
            <span class="text-gray-900 dark:text-white">
              {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
            </span>
          </h1>

          <!-- Subtitle -->
          <p class="text-lg lg:text-xl text-gray-600 dark:text-gray-300 max-w-xl mx-auto lg:mx-0 leading-relaxed">
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
            <a 
              :href="section?.additional_data?.cta_link || '#contact'" 
              class="group relative px-8 py-4 bg-primary text-white font-bold rounded-xl overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-1"
            >
              <span class="relative z-10 flex items-center gap-2">
                {{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'احجز الآن') : (section?.additional_data?.cta_text_en || 'Book Now') }}
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </span>
              <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
            <a 
              :href="section?.additional_data?.secondary_link || '#how-it-works'" 
              class="group px-8 py-4 bg-white dark:bg-gray-800 text-primary dark:text-white font-bold rounded-xl border-2 border-primary/20 dark:border-gray-700 hover:border-primary dark:hover:border-secondary transition-all duration-300 hover:shadow-lg"
            >
              <span class="flex items-center gap-2">
                {{ currentLang === 'ar' ? (section?.additional_data?.secondary_text_ar || 'كيف يعمل؟') : (section?.additional_data?.secondary_text_en || 'How It Works?') }}
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </span>
            </a>
          </div>

          <!-- Stats -->
          <div v-if="stats.length" class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-200 dark:border-gray-700 mt-8">
            <div v-for="(stat, index) in stats" :key="index" class="text-center lg:text-start">
              <div class="text-2xl lg:text-3xl font-bold text-primary dark:text-secondary">
                {{ stat.value }}
              </div>
              <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ currentLang === 'ar' ? stat.label_ar : stat.label_en }}
              </div>
            </div>
          </div>
        </div>

        <!-- Image/Illustration -->
        <div class="relative order-1 lg:order-2">
          <!-- Main Image Container -->
          <div class="relative">
            <!-- Background Shape -->
            <div class="absolute inset-0 bg-gradient-to-br from-secondary/50 to-primary/20 rounded-[3rem] transform rotate-3 scale-105"></div>
            
            <!-- Image -->
            <div class="relative bg-white dark:bg-gray-800 rounded-[2.5rem] p-4 shadow-2xl">
              <img 
                v-if="section?.image" 
                :src="`/storage/${section.image}`" 
                :alt="currentLang === 'ar' ? section?.title_ar : section?.title_en"
                class="w-full h-auto rounded-[2rem] object-cover aspect-[4/3]"
              />
              <div v-else class="w-full aspect-[4/3] bg-gradient-to-br from-secondary/30 to-primary/10 rounded-[2rem] flex items-center justify-center">
                <svg class="w-32 h-32 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>

            <!-- Floating Cards -->
            <div v-if="section?.additional_data?.floating_card_1" class="absolute -left-4 lg:-left-8 top-1/4 bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-xl animate-float">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-secondary/50 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ currentLang === 'ar' ? section.additional_data.floating_card_1.title_ar : section.additional_data.floating_card_1.title_en }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ currentLang === 'ar' ? section.additional_data.floating_card_1.subtitle_ar : section.additional_data.floating_card_1.subtitle_en }}
                  </div>
                </div>
              </div>
            </div>

            <div v-if="section?.additional_data?.floating_card_2" class="absolute -right-4 lg:-right-8 bottom-1/4 bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-xl animate-float-delayed">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                  </svg>
                </div>
                <div>
                  <div class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ currentLang === 'ar' ? section.additional_data.floating_card_2.title_ar : section.additional_data.floating_card_2.title_en }}
                  </div>
                  <div class="text-xs text-gray-500">
                    {{ currentLang === 'ar' ? section.additional_data.floating_card_2.subtitle_ar : section.additional_data.floating_card_2.subtitle_en }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
      <span class="text-xs text-gray-500 dark:text-gray-400">{{ currentLang === 'ar' ? 'اكتشف المزيد' : 'Discover More' }}</span>
      <svg class="w-6 h-6 text-primary dark:text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
      </svg>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  section: {
    type: Object,
    required: true
  },
  currentLang: {
    type: String,
    default: 'ar'
  }
})

const stats = computed(() => props.section?.additional_data?.stats || [])
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

@keyframes float-delayed {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 0.5; }
  50% { opacity: 0.8; }
}

@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float-delayed 5s ease-in-out infinite;
  animation-delay: 1s;
}

.animate-pulse-slow {
  animation: pulse-slow 6s ease-in-out infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 3s ease-in-out infinite;
}
</style>
