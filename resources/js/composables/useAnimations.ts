import { ref, computed, onMounted, type Ref, type ComputedRef } from 'vue'

export interface AnimationOptions {
  duration?: number
  delay?: number
  easing?: string
  stagger?: number
}

export interface UseAnimationsReturn {
  prefersReducedMotion: Ref<boolean>
  getAnimationClasses: (isVisible: boolean, type?: AnimationType) => string
  getAnimationStyle: (isVisible: boolean, options?: AnimationOptions) => Record<string, string>
  getStaggerDelay: (index: number, baseDelay?: number) => number
}

export type AnimationType = 'fadeIn' | 'slideUp' | 'slideDown' | 'slideLeft' | 'slideRight' | 'scaleIn' | 'none'

// Default animation durations (in ms)
export const ANIMATION_DURATIONS = {
  scroll: 600,    // Max 600ms for scroll animations
  hover: 300,     // Max 300ms for hover transitions
  darkMode: 300   // Max 300ms for dark mode transitions
}

export function useAnimations(): UseAnimationsReturn {
  const prefersReducedMotion = ref(false)

  onMounted(() => {
    // Check for reduced motion preference
    if (typeof window !== 'undefined') {
      const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)')
      prefersReducedMotion.value = mediaQuery.matches

      // Listen for changes
      const handleChange = (e: MediaQueryListEvent) => {
        prefersReducedMotion.value = e.matches
      }

      mediaQuery.addEventListener('change', handleChange)
    }
  })

  const getAnimationClasses = (isVisible: boolean, type: AnimationType = 'fadeIn'): string => {
    // If reduced motion is preferred, skip animations
    if (prefersReducedMotion.value) {
      return isVisible ? 'opacity-100' : 'opacity-100'
    }

    const baseClasses = 'transition-all duration-500 ease-out'

    if (!isVisible) {
      switch (type) {
        case 'fadeIn':
          return `${baseClasses} opacity-0`
        case 'slideUp':
          return `${baseClasses} opacity-0 translate-y-8`
        case 'slideDown':
          return `${baseClasses} opacity-0 -translate-y-8`
        case 'slideLeft':
          return `${baseClasses} opacity-0 translate-x-8`
        case 'slideRight':
          return `${baseClasses} opacity-0 -translate-x-8`
        case 'scaleIn':
          return `${baseClasses} opacity-0 scale-95`
        case 'none':
          return ''
        default:
          return `${baseClasses} opacity-0`
      }
    }

    // Visible state
    switch (type) {
      case 'fadeIn':
        return `${baseClasses} opacity-100`
      case 'slideUp':
      case 'slideDown':
        return `${baseClasses} opacity-100 translate-y-0`
      case 'slideLeft':
      case 'slideRight':
        return `${baseClasses} opacity-100 translate-x-0`
      case 'scaleIn':
        return `${baseClasses} opacity-100 scale-100`
      case 'none':
        return ''
      default:
        return `${baseClasses} opacity-100`
    }
  }

  const getAnimationStyle = (
    isVisible: boolean,
    options: AnimationOptions = {}
  ): Record<string, string> => {
    const {
      duration = ANIMATION_DURATIONS.scroll,
      delay = 0,
      easing = 'cubic-bezier(0.4, 0, 0.2, 1)'
    } = options

    // If reduced motion is preferred, use instant transitions
    if (prefersReducedMotion.value) {
      return {
        transitionDuration: '0ms',
        transitionDelay: '0ms'
      }
    }

    // Ensure duration doesn't exceed max
    const safeDuration = Math.min(duration, ANIMATION_DURATIONS.scroll)

    return {
      transitionDuration: `${safeDuration}ms`,
      transitionDelay: `${delay}ms`,
      transitionTimingFunction: easing
    }
  }

  const getStaggerDelay = (index: number, baseDelay: number = 100): number => {
    if (prefersReducedMotion.value) return 0
    return index * baseDelay
  }

  return {
    prefersReducedMotion,
    getAnimationClasses,
    getAnimationStyle,
    getStaggerDelay
  }
}

export default useAnimations
