<template>
  <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
      <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t(label) }}</h2>
      <p v-if="images.length > 0" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
        {{ t('common.imagesCount', { count: images.length }) }}
      </p>
    </div>
    
    <div class="p-4 sm:p-6">
      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-500"></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="images.length === 0" class="text-center py-12">
        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full border border-gray-200 text-gray-400 dark:border-gray-800 dark:text-gray-600 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          {{ emptyMessage || t('common.noGalleryImages') }}
        </p>
      </div>

      <!-- Gallery Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="(image, index) in images" 
          :key="image.id || index"
          class="relative rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700"
        >
          <div class="aspect-square relative">
            <img 
              :src="getImageUrl(image.image)" 
              :alt="`Gallery image ${index + 1}`" 
              class="w-full h-full object-cover"
              loading="lazy"
              @load="onImageLoad"
              @error="onImageError"
            />
          </div>
          <!-- Image number indicator -->
          <div class="absolute top-3 left-3 bg-black/60 text-white text-sm font-medium px-2 py-1 rounded-full">
            {{ index + 1 }} / {{ images.length }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const props = defineProps({
  images: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  label: {
    type: String,
    default: 'common.gallery'
  },
  emptyMessage: {
    type: String,
    default: null
  }
})

const { t } = useI18n()

function getImageUrl(imagePath) {
  if (!imagePath) return ''
  if (imagePath.startsWith('http')) {
    return imagePath
  }
  return `/storage/${imagePath}`
}

function onImageLoad(event) {
  event.target.style.opacity = '1'
}

function onImageError(event) {
  event.target.style.opacity = '0.5'
  console.warn('Failed to load image:', event.target.src)
}
</script>

