<template>
  <div class="space-y-4">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('chefs.services') }}</h3>
    
    <div v-if="services.length > 0" class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.serviceName') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.price') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.minHours') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.restHours') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.totalBookings') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.completedBookings') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('chefs.rating') }}</th>
            <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('common.status') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="service in services"
            :key="service.id"
            class="border-b border-gray-100 dark:border-gray-800"
          >
            <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">
              {{ service.name }}
            </td>
            <td class="px-4 py-3 text-gray-800 dark:text-white">
              {{ formatPrice(service.price) }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ service.min_hours }} {{ t('common.hours') }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ service.rest_hours_required }} {{ t('common.hours') }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ service.total_bookings }}
            </td>
            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
              {{ service.completed_bookings }}
            </td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-1">
                <span class="text-warning-500">⭐</span>
                <span class="text-gray-800 dark:text-white">{{ service.average_rating }}</span>
              </div>
            </td>
            <td class="px-4 py-3">
              <span
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                :class="service.is_active ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400'"
              >
                {{ service.is_active ? t('common.active') : t('common.inactive') }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div v-else class="rounded-xl border border-gray-200 bg-gray-50 p-8 text-center dark:border-gray-800 dark:bg-gray-800/50">
      <p class="text-gray-500 dark:text-gray-400">{{ t('chefs.noServices') }}</p>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

defineProps({
  services: {
    type: Array,
    required: true
  }
})

const formatPrice = (price) => {
  return Number(price || 0).toFixed(2) + ' ر.س'
}
</script>
