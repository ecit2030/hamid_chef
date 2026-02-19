import { ref, computed, onMounted, onUnmounted, type Ref, type ComputedRef } from 'vue'

export interface ParallaxOptions {
  speed?: number
  direction?: 'vertical' | 'horizontal'
  disabled?: boolean
  mobileDisabled?: boolean
  mobileBreakpoint?: number
}

export interface UseParallaxReturn {
  parallaxStyle: ComputedRef<Record<string, string>>
  scrollY: Ref<number>
  isMobile: Ref<boolean>
  isDisabled: ComputedRef<boolean>
  updateParallax: () => void
}

export function useParallax(options: ParallaxOptions = {}): UseParallaxReturn {
  const {
    speed = 0.5,
    direction = 'vertical',
    disabled = false,
    mobileDisabled = true,
    mobileBreakpoint = 768
  } = options

  const scrollY = ref(0)
  const isMobile = ref(false)

  const isDisabled = computed(() => {
    return disabled || (mobileDisabled && isMobile.value)
  })

  const parallaxStyle = computed(() => {
    if (isDisabled.value) {
      return {
        transform: 'translate3d(0, 0, 0)'
      }
    }

    const offset = scrollY.value * speed

    if (direction === 'horizontal') {
      return {
        transform: `translate3d(${offset}px, 0, 0)`
      }
    }

    return {
      transform: `translate3d(0, ${offset}px, 0)`
    }
  })

  const updateParallax = () => {
    if (typeof window !== 'undefined') {
      scrollY.value = window.scrollY || window.pageYOffset
    }
  }

  const checkMobile = () => {
    if (typeof window !== 'undefined') {
      isMobile.value = window.innerWidth < mobileBreakpoint
    }
  }

  let rafId: number | null = null
  let ticking = false

  const handleScroll = () => {
    if (!ticking) {
      rafId = requestAnimationFrame(() => {
        updateParallax()
        ticking = false
      })
      ticking = true
    }
  }

  const handleResize = () => {
    checkMobile()
  }

  onMounted(() => {
    if (typeof window !== 'undefined') {
      checkMobile()
      updateParallax()

      window.addEventListener('scroll', handleScroll, { passive: true })
      window.addEventListener('resize', handleResize, { passive: true })
    }
  })

  onUnmounted(() => {
    if (typeof window !== 'undefined') {
      window.removeEventListener('scroll', handleScroll)
      window.removeEventListener('resize', handleResize)

      if (rafId !== null) {
        cancelAnimationFrame(rafId)
      }
    }
  })

  return {
    parallaxStyle,
    scrollY,
    isMobile,
    isDisabled,
    updateParallax
  }
}

// Helper function for creating multiple parallax layers
export function createParallaxLayers(count: number, baseSpeed: number = 0.1) {
  return Array.from({ length: count }, (_, i) => ({
    speed: baseSpeed * (i + 1),
    zIndex: count - i
  }))
}

export default useParallax
