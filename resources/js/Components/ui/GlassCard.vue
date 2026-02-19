<template>
  <div
    ref="cardRef"
    :class="[
      cardClasses,
      animationClasses,
      { 'cursor-pointer': clickable }
    ]"
    :style="cardStyle"
    @mouseenter="handleMouseEnter"
    @mouseleave="handleMouseLeave"
  >
    <slot />

    <!-- Glow Effect -->
    <div
      v-if="glow && isHovered"
      class="absolute inset-0 rounded-inherit pointer-events-none transition-opacity duration-300"
      :class="glowClasses"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useScrollTrigger } from '@/composables/useScrollTrigger'
import { useAnimations } from '@/composables/useAnimations'

interface Props {
  blur?: number
  opacity?: number
  hoverEffect?: boolean
  glow?: boolean
  glowColor?: string
  animated?: boolean
  animationType?: 'fadeIn' | 'slideUp' | 'scaleIn'
  darkMode?: boolean
  clickable?: boolean
  rounded?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | 'full'
  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl'
}

const props = withDefaults(defineProps<Props>(), {
  blur: 12,
  opacity: 0.8,
  hoverEffect: true,
  glow: true,
  glowColor: 'primary',
  animated: true,
  animationType: 'slideUp',
  darkMode: false,
  clickable: false,
  rounded: '2xl',
  padding: 'md'
})

const cardRef = ref<HTMLElement | null>(null)
const isHovered = ref(false)

// Scroll trigger for animations
const { isVisible, observe } = useScrollTrigger({ threshold: 0.1, once: true })
const { getAnimationClasses, prefersReducedMotion } = useAnimations()

onMounted(() => {
  if (props.animated && cardRef.value) {
    observe(cardRef.value)
  }
})

const handleMouseEnter = () => {
  if (props.hoverEffect) {
    isHovered.value = true
  }
}

const handleMouseLeave = () => {
  isHovered.value = false
}

const roundedClasses: Record<string, string> = {
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
  '2xl': 'rounded-2xl',
  '3xl': 'rounded-3xl',
  full: 'rounded-full'
}

const paddingClasses: Record<string, string> = {
  none: 'p-0',
  sm: 'p-3',
  md: 'p-6',
  lg: 'p-8',
  xl: 'p-10'
}

const cardClasses = computed(() => {
  const classes = [
    'relative',
    'overflow-hidden',
    'backdrop-blur-md',
    'backdrop-saturate-150',
    'border',
    'transition-all',
    'duration-300',
    roundedClasses[props.rounded],
    paddingClasses[props.padding]
  ]

  // Background and border colors
  if (props.darkMode) {
    classes.push(
      'bg-gray-900/70',
      'border-white/10',
      'shadow-lg',
      'shadow-black/20'
    )
  } else {
    classes.push(
      'bg-white/80',
      'border-white/30',
      'shadow-lg',
      'shadow-primary/5'
    )
  }

  // Hover effects
  if (props.hoverEffect) {
    classes.push(
      'hover:shadow-2xl',
      'hover:-translate-y-1',
      'hover:scale-[1.02]'
    )

    if (props.darkMode) {
      classes.push('hover:bg-gray-800/80', 'hover:border-white/20')
    } else {
      classes.push('hover:bg-white/90', 'hover:border-white/40')
    }
  }

  return classes.join(' ')
})

const cardStyle = computed(() => {
  return {
    backdropFilter: `blur(${props.blur}px) saturate(150%)`,
    WebkitBackdropFilter: `blur(${props.blur}px) saturate(150%)`,
    backgroundColor: props.darkMode
      ? `rgba(17, 24, 39, ${props.opacity * 0.7})`
      : `rgba(255, 255, 255, ${props.opacity})`
  }
})

const animationClasses = computed(() => {
  if (!props.animated || prefersReducedMotion.value) {
    return 'opacity-100'
  }
  return getAnimationClasses(isVisible.value, props.animationType)
})

const glowClasses = computed(() => {
  const colorMap: Record<string, string> = {
    primary: 'bg-gradient-to-r from-primary/20 via-transparent to-primary/20',
    secondary: 'bg-gradient-to-r from-secondary/30 via-transparent to-secondary/30',
    white: 'bg-gradient-to-r from-white/20 via-transparent to-white/20'
  }

  return [
    colorMap[props.glowColor] || colorMap.primary,
    'opacity-0',
    isHovered.value ? 'opacity-100' : ''
  ].join(' ')
})
</script>

<style scoped>
.rounded-inherit {
  border-radius: inherit;
}
</style>
