<template>
  <section class="relative py-24 overflow-hidden bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 dark:bg-primary/20 text-primary dark:text-secondary rounded-full mb-6 border border-primary/20 dark:border-secondary/30">
          <span class="text-xl">🤝</span>
          <span class="font-bold">{{ currentLang === 'ar' ? 'شركاؤنا' : 'Our Partners' }}</span>
        </div>

        <h2 class="text-4xl lg:text-5xl font-black text-primary dark:text-white mb-6">
          {{ currentLang === 'ar' ? section?.title_ar : section?.title_en }}
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
          {{ currentLang === 'ar' ? section?.description_ar : section?.description_en }}
        </p>
      </div>

      <!-- Partners Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <div
          v-for="(partner, index) in partners"
          :key="index"
          class="group bg-gray-50 dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 hover:border-primary/30 dark:hover:border-secondary/30 transition-all duration-300 hover:-translate-y-1 text-center"
        >
          <!-- Logo -->
          <div class="mb-6 flex justify-center">
            <div class="w-32 h-20 flex items-center justify-center overflow-hidden">
              <img
                v-if="partner.logo"
                :src="partner.logo?.startsWith('http') ? partner.logo : `/storage/${partner.logo}`"
                :alt="currentLang === 'ar' ? partner.name_ar : partner.name_en"
                class="max-h-16 w-auto object-contain"
              />
              <div v-else class="w-16 h-16 bg-primary/20 rounded-xl flex items-center justify-center">
                <span class="text-2xl">🏢</span>
              </div>
            </div>
          </div>

          <!-- Name -->
          <h3 class="text-xl font-bold text-primary dark:text-white mb-3">
            {{ currentLang === 'ar' ? partner.name_ar : partner.name_en }}
          </h3>

          <!-- Description -->
          <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
            {{ currentLang === 'ar' ? partner.description_ar : partner.description_en }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  section: { type: Object, required: true },
  currentLang: { type: String, default: 'ar' },
  isDarkMode: { type: Boolean, default: false }
})

const partners = computed(() => props.section?.additional_data?.partners || [])
</script>
