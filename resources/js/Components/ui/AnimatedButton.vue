<template>
  <component
    :is="tag"
    ref="buttonRef"
    :class="buttonClasses"
    :disabled="disabled || loading"
    :href="href"
    @click="handleClick"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
  >
    <!-- Ripple Effect Container -->
    <span
      v-if="ripple"
      class="absolute inset-0 overflow-hidden rounded-inherit"
    >
      <span
        v-for="(r, index) in ripples"
        :key="index"
        class="absolute rounded-full bg-white/30 animate-ripple"
        :style="{
          left: `${r.x}px`,
          top: `${r.y}px`,
          width: `${r.size}px`,
          height: `${r.size}px`
        }"
      />
    </span>

    <!-- Glow Effect -->
    <span
      v-if="glow && isHovered"
      class="absolute inset-0 rounded-inherit pointer-events-none"
      :class="glowClasses"
    />

    <!-- Loading Spinner -->
    <span v-if="loading" class="absolute inset-0 flex items-center justify-center">
      <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
      </svg>
    </span>

    <!-- Content -->
    <span
      :class="[
        'relative z-10 flex items-center justify-center gap-2',
        { 'opacity-0': loading }
      ]"
    >
      <slot name="icon-left" />
      <slot />
      <slot name="icon-right" />
    </span>
  </component>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

interface Props {
  variant?: 'primary' | 'secondary' | 'outline' | 'ghost'
  size?: 'sm' | 'md' | 'lg' | 'xl'
  ripple?: boolean
  glow?: boolean
  loading?: boolean
  disabled?: boolean
  href?: string
  rounded?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'full'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  ripple: true,
  glow: true,
  loading: false,
  disabled: false,
  rounded: '2xl'
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()

const buttonRef = ref<HTMLElement | null>(null)
const isHovered = ref(false)
const ripples = ref<Array<{ x: number; y: number; size: number }>>([])

const tag = computed(() => props.href ? 'a' : 'button')

const handleClick = (event: MouseEvent) => {
  if (props.disabled || props.loading) return

  // Create ripple effect
  if (props.ripple && buttonRef.value) {
    const rect = buttonRef.value.getBoundingClientRect()
    const size = Math.max(rect.width, rect.height) * 2
    const x = event.clientX - rect.left - size / 2
    const y = event.clientY - rect.top - size / 2

    ripples.value.push({ x, y, size })

    // Remove ripple after animation
    setTimeout(() => {
      ripples.value.shift()
    }, 600)
  }

  emit('click', event)
}

const sizeClasses: Record<string, string> = {
  sm: 'px-4 py-2 text-sm',
  md: 'px-6 py-3 text-base',
  lg: 'px-8 py-4 text-lg',
  xl: 'px-10 py-5 text-xl'
}

const roundedClasses: Record<string, string> = {
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
  '2xl': 'rounded-2xl',
  full: 'rounded-full'
}

const buttonClasses = computed(() => {
  const base = [
    'relative',
    'inline-flex',
    'items-center',
    'justify-center',
    'font-bold',
    'transition-all',
    'duration-300',
    'overflow-hidden',
    sizeClasses[props.size],
    roundedClasses[props.rounded]
  ]

  // Variant styles
  switch (props.variant) {
    case 'primary':
      base.push(
        'bg-primary',
        'text-white',
        'hover:bg-primary-600',
        'hover:scale-105',
        'hover:shadow-xl',
        'hover:shadow-primary/30',
        'active:scale-95'
      )
      break
    case 'secondary':
      base.push(
        'bg-secondary',
        'text-primary',
        'hover:bg-secondary-600',
        'hover:scale-105',
        'hover:shadow-xl',
        'hover:shadow-secondary/30',
        'active:scale-95'
      )
      break
    case 'outline':
      base.push(
        'bg-transparent',
        'border-2',
        'border-primary',
        'text-primary',
        'hover:bg-primary',
        'hover:text-white',
        'hover:scale-105',
        'active:scale-95'
      )
      break
    case 'ghost':
      base.push(
        'bg-transparent',
        'text-primary',
        'hover:bg-primary/10',
        'active:bg-primary/20'
      )
      break
  }

  // Disabled state
  if (props.disabled || props.loading) {
    base.push('opacity-50', 'cursor-not-allowed', 'pointer-events-none')
  }

  return base.join(' ')
})

const glowClasses = computed(() => {
  const glowColors: Record<string, string> = {
    primary: 'shadow-[0_0_30px_rgba(8,48,100,0.5)]',
    secondary: 'shadow-[0_0_30px_rgba(203,228,248,0.5)]',
    outline: 'shadow-[0_0_20px_rgba(8,48,100,0.3)]',
    ghost: ''
  }

  return glowColors[props.variant] || ''
})
</script>

<style scoped>
.rounded-inherit {
  border-radius: inherit;
}

@keyframes ripple {
  0% {
    transform: scale(0);
    opacity: 1;
  }
  100% {
    transform: scale(1);
    opacity: 0;
  }
}

.animate-ripple {
  animation: ripple 0.6s ease-out forwards;
}
</style>
