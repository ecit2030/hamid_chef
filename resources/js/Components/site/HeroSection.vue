<template>
  <section class="relative min-h-screen w-full overflow-hidden">
    <!-- Background with Gradient Overlay -->
    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-600 to-primary-800" />
      <!-- Decorative Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');" />
      </div>
    </div>

    <!-- Floating Shapes -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-secondary/20 rounded-full blur-3xl animate-pulse" />
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-white/5 rounded-full blur-3xl" />

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex items-center">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <!-- Text Content -->
          <div :class="currentLang === 'ar' ? 'text-right order-1' : 'text-left order-1 lg:order-1'">
            <!-- Badge -->
            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full mb-8 bg-white/10 backdrop-blur-sm border border-white/20">
              <span class="w-2 h-2 bg-secondary rounded-full animate-pulse" />
              <span class="text-white/90 font-medium text-sm tracking-wide">
                {{ currentLang === 'ar' ? '🍳 منصة الطهاة الأولى' : '🍳 #1 Chef Platform' }}
              </span>
            </div>

            <!-- Main Title -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
              <span class="block">{{ currentLang === 'ar' ? section?.title_ar?.split(' ').slice(0, 3).join(' ') : section?.title_en?.split(' ').slice(0, 3).join(' ') }}</span>
              <span class="block text-secondary mt-2">{{ currentLang === 'ar' ? section?.title_ar?.split(' ').slice(3).join(' ') : section?.title_en?.split(' ').slice(3).join(' ') }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg lg:text-xl text-white/80 max-w-xl leading-relaxed mb-10">
              {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-wrap gap-4" :class="currentLang === 'ar' ? 'justify-start' : 'justify-start'">
              <a
                href="#contact"
                class="group inline-flex items-center gap-3 px-8 py-4 bg-secondary text-primary font-bold rounded-full hover:bg-white transition-all duration-300 hover:shadow-xl hover:shadow-secondary/30"
              >
                <span>{{ currentLang === 'ar' ? 'احجز طاهيك الآن' : 'Book Your Chef' }}</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" :class="currentLang === 'ar' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </a>
              <a
                href="#how-it-works"
                class="group inline-flex items-center gap-3 px-8 py-4 text-white font-bold rounded-full border-2 border-white/30 hover:border-white hover:bg-white/10 transition-all duration-300"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z" />
                </svg>
                <span>{{ currentLang === 'ar' ? 'شاهد كيف يعمل' : 'Watch How It Works' }}</span>
              </a>
            </div>
          </div>

          <!-- Stats Cards -->
          <div :class="currentLang === 'ar' ? 'order-2' : 'order-2 lg:order-2'">
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                <div class="text-4xl lg:text-5xl font-black text-secondary mb-2">500+</div>
                <div class="text-white/80 font-medium">{{ currentLang === 'ar' ? 'طاهٍ محترف' : 'Pro Chefs' }}</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                <div class="text-4xl lg:text-5xl font-black text-secondary mb-2">10K+</div>
                <div class="text-white/80 font-medium">{{ currentLang === 'ar' ? 'عميل سعيد' : 'Happy Clients' }}</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                <div class="text-4xl lg:text-5xl font-black text-secondary mb-2">15K+</div>
                <div class="text-white/80 font-medium">{{ currentLang === 'ar' ? 'وجبة مقدمة' : 'Meals Served' }}</div>
              </div>
              <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                <div class="flex items-center gap-2 text-4xl lg:text-5xl font-black text-secondary mb-2">
                  4.9
                  <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <div class="text-white/80 font-medium">{{ currentLang === 'ar' ? 'تقييم العملاء' : 'Client Rating' }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20">
      <a href="#features" class="flex flex-col items-center text-white/60 hover:text-white transition-colors">
        <span class="text-sm mb-2">{{ currentLang === 'ar' ? 'اكتشف المزيد' : 'Discover More' }}</span>
        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
      </a>
    </div>
  </section>
</template>

<script setup>
defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' },
  isDarkMode: { type: Boolean, default: false }
})
</script>
