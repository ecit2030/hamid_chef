<template>
  <div class="space-y-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('chefs.vacations') }}</h3>
    
    <div v-if="vacations.length > 0" class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
            <th class="px-4 py-3 text-right font-medium text-gray-500">#</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.date') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.dayName') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.note') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('common.status') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(vacation, index) in vacations"
            :key="vacation.id"
            class="border-b border-gray-100 dark:border-gray-800"
          >
            <td class="px-4 py-3 text-gray-800 dark:text-white">{{ index + 1 }}</td>
            <td class="px-4 py-3 text-gray-800 dark:text-white">
              <div class="flex items-center gap-2">
                <span>📅</span>
                <span>{{ formatDate(vacation.date) }}</span>
              </div>
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ getDayName(vacation.date) }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ vacation.note || '-' }}
            </td>
            <td class="px-4 py-3">
              <span
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                :class="vacation.is_active ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400'"
              >
                {{ vacation.is_active ? t('common.active') : t('common.inactive') }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div v-else class="rounded-xl border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-800 dark:bg-gray-800/50">
      <p class="text-gray-500 dark:text-gray-400">{{ t('chefs.noVacations') }}</p>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  vacations: {
    type: Array,
    required: true
  }
})

const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']
const arabicDays = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  return `${day} ${month} ${year}`
}

const getDayName = (dateString) => {
  const date = new Date(dateString)
  return arabicDays[date.getDay()]
}
</script>
