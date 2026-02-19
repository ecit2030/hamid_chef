<template>
  <div class="absolute inset-0 overflow-hidden pointer-events-none" :class="{ 'opacity-50': darkMode }">
    <div
      v-for="(element, index) in elements"
      :key="index"
      class="absolute"
      :class="getShapeClasses(element.shape)"
      :style="getElementStyle(element, index)"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'

type Shape = 'circle' | 'square' | 'triangle' | 'blob'
type Speed = 'slow' | 'medium' | 'fast'

interface Props {
  count?: number
  shapes?: Shape[]
  colors?: string[]
  speed?: Speed
  darkMode?: boolean
  minSize?: number
  maxSize?: number
}

interface FloatingElement {
  shape: Shape
  color: string
  size: number
  x: number
  y: number
  delay: number
  duration: number
}

const props = withDefaults(defineProps<Props>(), {
  count: 6,
  shapes: () => ['circle', 'circle', 'blob'],
  colors: () => ['primary', 'secondary'],
  speed: 'medium',
  darkMode: false,
  minSize: 40,
  maxSize: 120
})

const speedDurations: Record<Speed, { min: number; max: number }> = {
  slow: { min: 15, max: 25 },
  medium: { min: 10, max: 18 },
  fast: { min: 5, max: 12 }
}

const colorMap: Record<string, { light: string; dark: string }> = {
  primary: {
    light: 'rgba(8, 48, 100, 0.1)',
    dark: 'rgba(8, 48, 100, 0.2)'
  },
  secondary: {
    light: 'rgba(203, 228, 248, 0.3)',
    dark: 'rgba(203, 228, 248, 0.15)'
  },
  white: {
    light: 'rgba(255, 255, 255, 0.2)',
    dark: 'rgba(255, 255, 255, 0.1)'
  }
}

const random = (min: number, max: number) => Math.random() * (max - min) + min

const elements = computed<FloatingElement[]>(() => {
  const result: FloatingElement[] = []
  const { min, max } = speedDurations[props.speed]

  for (let i = 0; i < props.count; i++) {
    result.push({
      shape: props.shapes[i % props.shapes.length],
      color: props.colors[i % props.colors.length],
      size: random(props.minSize, props.maxSize),
      x: random(0, 100),
      y: random(0, 100),
      delay: random(0, 5),
      duration: random(min, max)
    })
  }

  return result
})

const getShapeClasses = (shape: Shape): string => {
  switch (shape) {
    case 'circle':
      return 'rounded-full'
    case 'square':
      return 'rounded-lg rotate-45'
    case 'triangle':
      return 'triangle'
    case 'blob':
      return 'blob'
    default:
      return 'rounded-full'
  }
}

const getElementStyle = (element: FloatingElement, index: number) => {
  const colors = colorMap[element.color] || colorMap.primary
  const bgColor = props.darkMode ? colors.dark : colors.light

  return {
    width: `${element.size}px`,
    height: `${element.size}px`,
    left: `${element.x}%`,
    top: `${element.y}%`,
    backgroundColor: bgColor,
    animation: `float-${index % 3} ${element.duration}s ease-in-out infinite`,
    animationDelay: `${element.delay}s`,
    filter: 'blur(1px)',
    opacity: props.darkMode ? '0.5' : '0.7'
  }
}
</script>

<style scoped>
.triangle {
  clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
}

.blob {
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
}

@keyframes float-0 {
  0%, 100% {
    transform: translate(0, 0) rotate(0deg);
  }
  25% {
    transform: translate(10px, -15px) rotate(5deg);
  }
  50% {
    transform: translate(-5px, -25px) rotate(-5deg);
  }
  75% {
    transform: translate(-15px, -10px) rotate(3deg);
  }
}

@keyframes float-1 {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(-20px, 10px) scale(1.05);
  }
  66% {
    transform: translate(15px, -20px) scale(0.95);
  }
}

@keyframes float-2 {
  0%, 100% {
    transform: translate(0, 0) rotate(0deg);
  }
  50% {
    transform: translate(20px, 20px) rotate(180deg);
  }
}
</style>
