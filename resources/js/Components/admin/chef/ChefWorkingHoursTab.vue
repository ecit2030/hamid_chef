<template>
  <div class="space-y-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('chefs.workingHours') }}</h3>
    
    <div v-if="Object.keys(workingHours).length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="(slots, day) in workingHours"
        :key="day"
        class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5"
      >
        <div class="mb-3 flex items-center gap-2">
          <span class="text-2xl">{{ getDayEmoji(day) }}</span>
          <h4 class="text-base font-semibold text-gray-800 dark:text-white">
            {{ t(`days.${day}`) }}
          </h4>
        </div>
        
        <div class="space-y-2">
          <div
            v-for="(slot, index) in slots"
            :key="slot.id"
            class="rounded-lg border border-gray-200 p-3 dark:border-gray-700"
            :class="slot.is_active ? 'bg-[#083064]/10 dark:bg-[#083064]/20' : 'bg-gray-50 dark:bg-gray-800/50'"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                  {{ t('chefs.slot') }} {{ index + 1 }}
                </span>
                <span
                  class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                  :class="slot.is_active ? 'bg-[#083064]/20 text-[#083064] dark:bg-[#083064]/30 dark:text-[#083064]' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400'"
                >
                  {{ slot.is_active ? t('common.active') : t('common.inactive') }}
                </span>
              </div>
            </div>
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              {{ formatTime(slot.start_time) }} - {{ formatTime(slot.end_time) }}
            </div>
            <div class="mt-1 text-xs text-gray-500 dark:text-gray-500">
              {{ calculateDuration(slot.start_time, slot.end_time) }}
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div v-else class="rounded-xl border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-800 dark:bg-gray-800/50">
      <p class="text-gray-500 dark:text-gray-400">{{ t('chefs.noWorkingHours') }}</p>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  workingHours: {
    type: Object,
    required: true
  }
})

const getDayEmoji = (day) => {
  const emojis = {
    saturday: '🌙',
    sunday: '☀️',
    monday: '💼',
    tuesday: '📅',
    wednesday: '🌟',
    thursday: '📊',
    friday: '🕌'
  }
  return emojis[day] || '📅'
}

const formatTime = (time) => {
  if (!time) return '-'
  const [hours, minutes] = time.split(':')
  return `${hours}:${minutes}`
}

const calculateDuration = (startTime, endTime) => {
  if (!startTime || !endTime) return '-'
  
  const [startHours, startMinutes] = startTime.split(':').map(Number)
  const [endHours, endMinutes] = endTime.split(':').map(Number)
  
  const startTotalMinutes = startHours * 60 + startMinutes
  const endTotalMinutes = endHours * 60 + endMinutes
  
  const durationMinutes = endTotalMinutes - startTotalMinutes
  const hours = Math.floor(durationMinutes / 60)
  const minutes = durationMinutes % 60
  
  if (hours > 0 && minutes > 0) {
    return `${hours} ${t('common.hours')} ${minutes} ${t('common.minutes')}`
  } else if (hours > 0) {
    return `${hours} ${t('common.hours')}`
  } else {
    return `${minutes} ${t('common.minutes')}`
  }
}
</script>
