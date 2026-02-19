import { computed, type ComputedRef } from 'vue'

export interface GlassOptions {
  blur?: number
  opacity?: number
  borderOpacity?: number
  darkMode?: boolean
  hoverIntensity?: number
}

export interface UseGlassmorphismReturn {
  glassClasses: ComputedRef<string>
  glassStyle: ComputedRef<Record<string, string>>
  hoverGlassClasses: ComputedRef<string>
}

export function useGlassmorphism(options: GlassOptions = {}): UseGlassmorphismReturn {
  const {
    blur = 12,
    opacity = 0.8,
    borderOpacity = 0.2,
    darkMode = false,
    hoverIntensity = 1.2
  } = options

  const glassClasses = computed(() => {
    const baseClasses = [
      'backdrop-blur-md',
      'backdrop-saturate-150',
      'border',
      'transition-all',
      'duration-300'
    ]

    if (darkMode) {
      baseClasses.push(
        'bg-gray-900/70',
        'border-white/10',
        'shadow-lg',
        'shadow-black/20'
      )
    } else {
      baseClasses.push(
        'bg-white/80',
        'border-white/20',
        'shadow-lg',
        'shadow-primary/5'
      )
    }

    return baseClasses.join(' ')
  })

  const glassStyle = computed(() => {
    const bgOpacity = darkMode ? opacity * 0.7 : opacity

    return {
      backdropFilter: `blur(${blur}px) saturate(150%)`,
      WebkitBackdropFilter: `blur(${blur}px) saturate(150%)`,
      backgroundColor: darkMode
        ? `rgba(17, 24, 39, ${bgOpacity})`
        : `rgba(255, 255, 255, ${bgOpacity})`,
      borderColor: darkMode
        ? `rgba(255, 255, 255, ${borderOpacity})`
        : `rgba(255, 255, 255, ${borderOpacity * 2})`
    }
  })

  const hoverGlassClasses = computed(() => {
    const hoverBlur = Math.round(blur * hoverIntensity)

    return [
      `hover:backdrop-blur-lg`,
      darkMode ? 'hover:bg-gray-800/80' : 'hover:bg-white/90',
      'hover:shadow-xl',
      darkMode ? 'hover:shadow-black/30' : 'hover:shadow-primary/10',
      'hover:border-opacity-30'
    ].join(' ')
  })

  return {
    glassClasses,
    glassStyle,
    hoverGlassClasses
  }
}

// Preset glass styles for common use cases
export const glassPresets = {
  card: {
    blur: 12,
    opacity: 0.8,
    borderOpacity: 0.2
  },
  navbar: {
    blur: 16,
    opacity: 0.9,
    borderOpacity: 0.1
  },
  modal: {
    blur: 20,
    opacity: 0.95,
    borderOpacity: 0.15
  },
  subtle: {
    blur: 8,
    opacity: 0.6,
    borderOpacity: 0.1
  }
}

export default useGlassmorphism
