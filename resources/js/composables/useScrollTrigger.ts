import { ref, onMounted, onUnmounted, type Ref } from 'vue'

export interface ScrollTriggerOptions {
  threshold?: number
  rootMargin?: string
  once?: boolean
}

export interface UseScrollTriggerReturn {
  isVisible: Ref<boolean>
  hasAnimated: Ref<boolean>
  observe: (element: HTMLElement | null) => void
  unobserve: () => void
}

export function useScrollTrigger(options: ScrollTriggerOptions = {}): UseScrollTriggerReturn {
  const {
    threshold = 0.1,
    rootMargin = '0px',
    once = true
  } = options

  const isVisible = ref(false)
  const hasAnimated = ref(false)
  let observer: IntersectionObserver | null = null
  let currentElement: HTMLElement | null = null

  const handleIntersect = (entries: IntersectionObserverEntry[]) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        isVisible.value = true
        hasAnimated.value = true

        if (once && observer && currentElement) {
          observer.unobserve(currentElement)
        }
      } else if (!once) {
        isVisible.value = false
      }
    })
  }

  const observe = (element: HTMLElement | null) => {
    if (!element) return

    currentElement = element

    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
      observer = new IntersectionObserver(handleIntersect, {
        threshold,
        rootMargin
      })
      observer.observe(element)
    } else {
      // Fallback: immediately show element if IntersectionObserver not supported
      isVisible.value = true
      hasAnimated.value = true
    }
  }

  const unobserve = () => {
    if (observer && currentElement) {
      observer.unobserve(currentElement)
    }
    if (observer) {
      observer.disconnect()
      observer = null
    }
    currentElement = null
  }

  onUnmounted(() => {
    unobserve()
  })

  return {
    isVisible,
    hasAnimated,
    observe,
    unobserve
  }
}

export default useScrollTrigger
