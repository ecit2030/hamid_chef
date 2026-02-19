<template>
  <div :class="containerClasses">
    <!-- Text Skeleton -->
    <template v-if="type === 'text'">
      <div
        v-for="i in lines"
        :key="i"
        :class="[
          'h-4 rounded',
          skeletonClasses,
          i === lines ? 'w-3/4' : 'w-full',
          i > 1 ? 'mt-2' : ''
        ]"
      />
    </template>

    <!-- Card Skeleton -->
    <template v-else-if="type === 'card'">
      <div :class="['rounded-2xl overflow-hidden', darkMode ? 'bg-gray-800' : 'bg-white']">
        <!-- Image placeholder -->
        <div :class="['h-48 w-full', skeletonClasses]" />
        <!-- Content -->
        <div class="p-4 space-y-3">
          <div :class="['h-6 w-3/4 rounded', skeletonClasses]" />
          <div :class="['h-4 w-full rounded', skeletonClasses]" />
          <div :class="['h-4 w-5/6 rounded', skeletonClasses]" />
        </div>
      </div>
    </template>

    <!-- Image Skeleton -->
    <template v-else-if="type === 'image'">
      <div
        :class="[
          'w-full rounded-xl flex items-center justify-center',
          skeletonClasses
        ]"
        :style="{ height: imageHeight }"
      >
        <svg
          class="w-12 h-12 text-gray-300"
          :class="{ 'text-gray-600': darkMode }"
          fill="currentColor"
          viewBox="0 0 24 24"
        >
          <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
    </template>

    <!-- Avatar Skeleton -->
    <template v-else-if="type === 'avatar'">
      <div class="flex items-center gap-4">
        <div
          :class="[
            'rounded-full flex-shrink-0',
            skeletonClasses
          ]"
          :style="{ width: avatarSize, height: avatarSize }"
        />
        <div class="flex-1 space-y-2">
          <div :class="['h-4 w-1/3 rounded', skeletonClasses]" />
          <div :class="['h-3 w-1/2 rounded', skeletonClasses]" />
        </div>
      </div>
    </template>

    <!-- Section Skeleton -->
    <template v-else-if="type === 'section'">
      <div class="space-y-8">
        <!-- Header -->
        <div class="text-center space-y-4">
          <div :class="['h-8 w-48 mx-auto rounded-full', skeletonClasses]" />
          <div :class="['h-10 w-96 mx-auto rounded', skeletonClasses]" />
          <div :class="['h-4 w-80 mx-auto rounded', skeletonClasses]" />
        </div>
        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="i in 3" :key="i" :class="['h-64 rounded-2xl', skeletonClasses]" />
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

type SkeletonType = 'text' | 'card' | 'image' | 'avatar' | 'section'

interface Props {
  type?: SkeletonType
  lines?: number
  animated?: boolean
  darkMode?: boolean
  imageHeight?: string
  avatarSize?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  lines: 3,
  animated: true,
  darkMode: false,
  imageHeight: '200px',
  avatarSize: '48px'
})

const containerClasses = computed(() => {
  return props.type === 'card' ? '' : 'w-full'
})

const skeletonClasses = computed(() => {
  const base = props.darkMode ? 'bg-gray-700' : 'bg-gray-200'
  const animation = props.animated ? 'animate-pulse' : ''
  return `${base} ${animation}`
})
</script>
