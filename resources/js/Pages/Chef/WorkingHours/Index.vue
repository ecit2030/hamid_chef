<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('menu.working_hours')" />
    
    <!-- Success Message -->
    <div v-if="$page.props.flash?.success" class="mb-6">
      <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm text-green-600 dark:text-green-400">{{ $page.props.flash.success }}</p>
      </div>
    </div>

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ t('menu.working_hours') }}</h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ t('chef.working_hours_description') }}</p>
        </div>
      </div>

      <!-- Days Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <div
          v-for="dayIndex in [0, 1, 2, 3, 4, 5, 6]"
          :key="dayIndex"
          class="group relative"
        >
          <!-- Day Card -->
          <div
            :class="[
              'relative rounded-2xl border-2 transition-all duration-300',
              hasActiveSlots(dayIndex)
                ? 'border-primary-500 bg-gradient-to-br from-primary-50 to-white dark:from-primary-900/20 dark:to-gray-800 shadow-lg shadow-primary-500/20'
                : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600'
            ]"
          >
            <!-- Day Header -->
            <div class="p-5 pb-4">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div
                    :class="[
                      'flex h-12 w-12 items-center justify-center rounded-xl text-2xl transition-all',
                      hasActiveSlots(dayIndex)
                        ? '!bg-[#083064] text-white shadow-lg shadow-primary-500/30'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-400'
                    ]"
                  >
                    {{ getDayEmoji(dayIndex) }}
                  </div>
                  <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                      {{ arabicDays[dayIndex] }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ englishDays[dayIndex] }}
                    </p>
                  </div>
                </div>
                
                <!-- Add Slot Button -->
                <button
                  @click="addSlot(dayIndex)"
                  type="button"
                  class="p-2 !bg-[#083064] hover:!bg-[#062650] text-white rounded-lg transition-colors shadow-lg shadow-primary-500/30"
                  :title="t('chef.add_time_slot')"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>

              <!-- Time Slots -->
              <div v-if="getDaySlots(dayIndex).length > 0" class="space-y-3">
                <div
                  v-for="(slot, slotIndex) in getDaySlots(dayIndex)"
                  :key="slot.id || `new-${dayIndex}-${slotIndex}`"
                  class="relative p-3 bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600"
                >
                  <!-- Delete Button -->
                  <button
                    v-if="getDaySlots(dayIndex).length > 1"
                    @click="removeSlot(dayIndex, slotIndex)"
                    type="button"
                    class="absolute top-2 right-2 p-1 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors"
                    :title="t('chef.delete_time_slot')"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>

                  <!-- Status Toggle -->
                  <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                      {{ t('chef.time_slot') }} {{ slotIndex + 1 }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="slot.is_active"
                        type="checkbox"
                        class="sr-only peer"
                        @change="autoSave"
                      />
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:!bg-[#083064]"></div>
                    </label>
                  </div>

                  <div v-if="slot.is_active" class="space-y-2">
                    <!-- Start Time -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                        {{ t('chef.start_time') }}
                      </label>
                      <input
                        v-model="slot.start_time"
                        type="time"
                        @change="autoSave"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                      />
                    </div>

                    <!-- End Time -->
                    <div>
                      <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                        {{ t('chef.end_time') }}
                      </label>
                      <input
                        v-model="slot.end_time"
                        type="time"
                        @change="autoSave"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                      />
                    </div>

                    <!-- Duration Display -->
                    <div v-if="slot.start_time && slot.end_time" class="pt-2 border-t border-gray-200 dark:border-gray-700">
                      <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">{{ t('chef.duration') }}</span>
                        <span class="font-semibold text-primary-600 dark:text-primary-400">
                          {{ calculateDuration(slot.start_time, slot.end_time) }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- Off Slot Message -->
                  <div v-else class="py-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ t('chef.slot_disabled') }}</p>
                  </div>
                </div>
              </div>

              <!-- No Slots Message -->
              <div v-else class="py-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-3">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.no_time_slots') }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ t('chef.click_add_to_create') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions Bar -->
      <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <button
            @click="setBusinessHours"
            type="button"
            class="px-4 py-2 text-sm font-medium text-white !bg-[#083064] hover:!bg-[#062650] rounded-lg transition-colors shadow-lg shadow-primary-500/30"
          >
            {{ t('chef.set_business_hours') }}
          </button>
          <button
            @click="clearAllSlots"
            type="button"
            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
          >
            {{ t('chef.clear_all') }}
          </button>
        </div>

        <div class="flex items-center gap-2">
          <div v-if="isSaving" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ t('common.saving') }}...</span>
          </div>
          <div v-else-if="lastSaved" class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ t('chef.saved_at') }} {{ lastSaved }}</span>
          </div>
        </div>
      </div>

      <!-- Error Messages -->
      <div v-if="form.errors.working_hours" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
        <p class="text-sm text-red-600 dark:text-red-400">{{ form.errors.working_hours }}</p>
      </div>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm, usePage } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'

const { t } = useI18n()
const page = usePage()

const workingHours = computed(() => page.props.workingHours || {})

const arabicDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']
const englishDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

const isSaving = ref(false)
const lastSaved = ref(null)
let saveTimeout = null

// Format time for input (HH:mm)
const formatTime = (time) => {
  if (!time) return ''
  return time.substring(0, 5)
}

// Initialize form with grouped slots
const initializeSlots = () => {
  const slots = []
  
  for (let day = 0; day <= 6; day++) {
    const daySlots = workingHours.value[day] || []
    
    if (daySlots.length > 0) {
      daySlots.forEach(slot => {
        slots.push({
          id: slot.id,
          day_of_week: slot.day_of_week,
          start_time: formatTime(slot.start_time),
          end_time: formatTime(slot.end_time),
          is_active: slot.is_active,
        })
      })
    } else {
      // Add default slot for days with no slots
      slots.push({
        id: null,
        day_of_week: day,
        start_time: '09:00',
        end_time: '17:00',
        is_active: false,
      })
    }
  }
  
  return slots
}

const form = useForm({
  working_hours: initializeSlots(),
})

const getDaySlots = (dayIndex) => {
  return form.working_hours.filter(slot => slot.day_of_week === dayIndex)
}

const hasActiveSlots = (dayIndex) => {
  return getDaySlots(dayIndex).some(slot => slot.is_active)
}

const addSlot = (dayIndex) => {
  const existingSlots = getDaySlots(dayIndex)
  const lastSlot = existingSlots[existingSlots.length - 1]
  
  form.working_hours.push({
    id: null,
    day_of_week: dayIndex,
    start_time: lastSlot?.end_time || '09:00',
    end_time: '17:00',
    is_active: true,
  })
  
  autoSave()
}

const removeSlot = (dayIndex, slotIndex) => {
  const daySlots = getDaySlots(dayIndex)
  const slotToRemove = daySlots[slotIndex]
  const indexInForm = form.working_hours.indexOf(slotToRemove)
  
  if (indexInForm !== -1) {
    form.working_hours.splice(indexInForm, 1)
    autoSave()
  }
}

const getDayEmoji = (dayIndex) => {
  const emojis = ['☀️', '💼', '💼', '💼', '💼', '🕌', '🎉']
  return emojis[dayIndex]
}

const calculateDuration = (start, end) => {
  if (!start || !end) return ''
  
  const [startHour, startMin] = start.split(':').map(Number)
  const [endHour, endMin] = end.split(':').map(Number)
  
  let hours = endHour - startHour
  let minutes = endMin - startMin
  
  if (minutes < 0) {
    hours--
    minutes += 60
  }
  
  if (hours < 0) hours += 24
  
  return `${hours} ${t('chef.hours')} ${minutes > 0 ? `${minutes} ${t('chef.minutes')}` : ''}`
}

const autoSave = () => {
  clearTimeout(saveTimeout)
  saveTimeout = setTimeout(() => {
    submit()
  }, 1000)
}

const submit = () => {
  isSaving.value = true
  form.put(route('chef.working-hours.update'), {
    preserveScroll: true,
    onSuccess: () => {
      isSaving.value = false
      const now = new Date()
      lastSaved.value = now.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })
      setTimeout(() => {
        lastSaved.value = null
      }, 3000)
    },
    onError: () => {
      isSaving.value = false
    }
  })
}

const setBusinessHours = () => {
  // Clear all slots first
  form.working_hours = []
  
  // Add business hours for Sunday to Thursday (0-4)
  for (let day = 0; day <= 4; day++) {
    form.working_hours.push({
      id: null,
      day_of_week: day,
      start_time: '09:00',
      end_time: '17:00',
      is_active: true,
    })
  }
  
  // Add disabled slots for Friday and Saturday (5-6)
  for (let day = 5; day <= 6; day++) {
    form.working_hours.push({
      id: null,
      day_of_week: day,
      start_time: '09:00',
      end_time: '17:00',
      is_active: false,
    })
  }
  
  autoSave()
}

const clearAllSlots = () => {
  // Keep one disabled slot per day
  form.working_hours = []
  
  for (let day = 0; day <= 6; day++) {
    form.working_hours.push({
      id: null,
      day_of_week: day,
      start_time: '09:00',
      end_time: '17:00',
      is_active: false,
    })
  }
  
  autoSave()
}
</script>
