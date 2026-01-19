<template>
  <section class="relative min-h-screen w-full overflow-hidden">
    <!-- Full Screen Background -->
    <div class="absolute inset-0">
      <img 
        v-if="section?.image" 
        :src="`/storage/${section.image}`" 
        :alt="currentLang === 'ar' ? section?.title_ar : section?.title_en"
        class="w-full h-full object-cover"
      />
      <div v-else class="w-full h-full bg-gradient-to-br from-primary via-primary-600 to-primary-800"></div>
      
      <!-- Overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-primary/95 via-primary/80 to-primary/60"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 w-96 h-96 bg-secondary rounded-full opacity-20 blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-80 h-80 bg-secondary rounded-full opacity-15 blur-3xl"></div>

    <!-- Content -->
    <div class="relative z-10 h-screen flex items-center">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl" :class="currentLang === 'ar' ? 'mr-auto text-right' : 'ml-0 text-left'">
          <!-- Badge -->
          <div class="inline-flex items-center gap-3 px-6 py-3 bg-secondary rounded-full mb-8 shadow-2xl">
            <span class="w-3 h-3 bg-primary rounded-full animate-pulse"></span>
            <span class="text-primary font-black text-lg">
              🍳 {{ currentLang === 'ar' ? 'أفضل خدمة طهاة منزليين' : 'Best Home Chef Service' }}
            </span>
          </div>

          <!-- Main Title -->
          <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-white leading-tight mb-8">
            {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
          </h1>
          
          <!-- Subtitle -->
          <h2 class="text-2xl lg:text-3xl text-secondary font-bold mb-6">
            {{ currentLang === 'ar' ? section?.additional_data?.subtitle_ar : section?.additional_data?.subtitle_en }}
          </h2>

          <!-- Description -->
          <p class="text-xl text-white/90 max-w-2xl leading-relaxed mb-10">
            {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap gap-4" :class="currentLang === 'ar' ? 'justify-start' : 'justify-start'">
            <a 
              :href="section?.additional_data?.cta_link || '#contact'" 
              class="group inline-flex items-center gap-3 px-10 py-5 bg-secondary text-primary text-xl font-black rounded-2xl hover:bg-white transition-all duration-300 hover:scale-105 shadow-2xl"
            >
              <span>{{ currentLang === 'ar' ? (section?.additional_data?.cta_text_ar || 'احجز طاهيك الآن') : (section?.additional_data?.cta_text_en || 'Book Your Chef') }}</span>
              <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>
            
            <a 
              href="#features" 
              class="inline-flex items-center gap-3 px-10 py-5 bg-white/10 backdrop-blur-sm text-white text-xl font-bold rounded-2xl border-2 border-white/30 hover:bg-white/20 hover:border-white transition-all duration-300"
            >
              {{ currentLang === 'ar' ? 'كيف يعمل؟' : 'How it works?' }}
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z"/>
              </svg>
            </a>
          </div>

          <!-- Stats -->
          <div class="flex flex-wrap gap-12 mt-16 pt-8 border-t border-white/20">
            <div>
              <div class="text-5xl font-black text-secondary">500+</div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'طاهٍ محترف' : 'Pro Chefs' }}</div>
            </div>
            <div>
              <div class="text-5xl font-black text-secondary">10K+</div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'وجبة مقدمة' : 'Meals Served' }}</div>
            </div>
            <div>
              <div class="text-5xl font-black text-secondary flex items-center gap-2">
                4.9 
                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <div class="text-white/80 font-medium text-lg">{{ currentLang === 'ar' ? 'تقييم العملاء' : 'Client Rating' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
      <a href="#features" class="flex flex-col items-center text-white/70 hover:text-white transition-colors">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </a>
    </div>
  </section>
</template>

<script setup>
defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' }
})
</script>
