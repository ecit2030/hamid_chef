import { ref, watch, onMounted, type Ref } from 'vue'

export interface UseDarkModeReturn {
  isDark: Ref<boolean>
  toggle: () => void
  enable: () => void
  disable: () => void
  setDarkMode: (value: boolean) => void
}

const STORAGE_KEY = 'darkMode'
const DARK_CLASS = 'dark'

export function useDarkMode(): UseDarkModeReturn {
  const isDark = ref(false)

  const updateDOM = (dark: boolean) => {
    if (typeof document !== 'undefined') {
      if (dark) {
        document.documentElement.classList.add(DARK_CLASS)
      } else {
        document.documentElement.classList.remove(DARK_CLASS)
      }
    }
  }

  const savePreference = (dark: boolean) => {
    try {
      if (typeof localStorage !== 'undefined') {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(dark))
      }
    } catch (e) {
      // localStorage not available, fail silently
      console.warn('localStorage not available for dark mode preference')
    }
  }

  const loadPreference = (): boolean | null => {
    try {
      if (typeof localStorage !== 'undefined') {
        const stored = localStorage.getItem(STORAGE_KEY)
        if (stored !== null) {
          return JSON.parse(stored)
        }
      }
    } catch (e) {
      // localStorage not available or invalid value
    }
    return null
  }

  const getSystemPreference = (): boolean => {
    if (typeof window !== 'undefined' && window.matchMedia) {
      return window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    return false
  }

  const setDarkMode = (value: boolean) => {
    isDark.value = value
    updateDOM(value)
    savePreference(value)
  }

  const toggle = () => {
    setDarkMode(!isDark.value)
  }

  const enable = () => {
    setDarkMode(true)
  }

  const disable = () => {
    setDarkMode(false)
  }

  // Watch for changes and update DOM
  watch(isDark, (newValue) => {
    updateDOM(newValue)
  })

  onMounted(() => {
    // Priority: 1. Stored preference, 2. System preference, 3. Default (light)
    const storedPreference = loadPreference()

    if (storedPreference !== null) {
      isDark.value = storedPreference
    } else {
      isDark.value = getSystemPreference()
    }

    updateDOM(isDark.value)

    // Listen for system preference changes
    if (typeof window !== 'undefined' && window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')

      const handleChange = (e: MediaQueryListEvent) => {
        // Only apply system preference if no stored preference
        const stored = loadPreference()
        if (stored === null) {
          isDark.value = e.matches
          updateDOM(e.matches)
        }
      }

      mediaQuery.addEventListener('change', handleChange)
    }
  })

  return {
    isDark,
    toggle,
    enable,
    disable,
    setDarkMode
  }
}

export default useDarkMode
