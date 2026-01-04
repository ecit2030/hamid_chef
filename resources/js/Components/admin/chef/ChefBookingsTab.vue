<template>
  <div class="space-y-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('chefs.recentBookings') }}</h3>
    
    <div v-if="bookings.length > 0" class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
            <th class="px-4 py-3 text-right font-medium text-gray-500">#</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.customer') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.service') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.date') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.time') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.hours') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.amount') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('common.status') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="booking in bookings"
            :key="booking.id"
            class="border-b border-gray-100 dark:border-gray-800"
          >
            <td class="px-4 py-3 text-gray-800 dark:text-white">{{ booking.id }}</td>
            <td class="px-4 py-3 text-gray-800 dark:text-white">
              {{ booking.customer_name }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ booking.service_name }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ formatDate(booking.date) }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ booking.hours_count }} {{ t('common.hours') }}
            </td>
            <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white">
              {{ formatPrice(booking.total_amount) }}
            </td>
            <td class="px-4 py-3">
              <span
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                :class="getStatusClass(booking.booking_status)"
              >
                {{ t(`booking.${booking.booking_status}`) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div v-else class="rounded-xl border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-800 dark:bg-gray-800/50">
      <p class="text-gray-500 dark:text-gray-400">{{ t('chefs.noBookings') }}</p>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  bookings: {
    type: Array,
    required: true
  }
})

const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  return `${day} ${month} ${year}`
}

const formatTime = (time) => {
  if (!time) return '-'
  const [hours, minutes] = time.split(':')
  return `${hours}:${minutes}`
}

const formatPrice = (price) => {
  return Number(price || 0).toFixed(2) + ' ر.س'
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-warning-100 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400',
    accepted: 'bg-info-100 text-info-700 dark:bg-info-500/20 dark:text-info-400',
    completed: 'bg-success-100 text-success-700 dark:bg-success-500/20 dark:text-success-400',
    rejected: 'bg-error-100 text-error-700 dark:bg-error-500/20 dark:text-error-400',
    cancelled_by_customer: 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
    cancelled_by_chef: 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
    cancelled_by_admin: 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
  }
  return classes[status] || 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
}
</script>
