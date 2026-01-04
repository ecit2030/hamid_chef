<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="t('reports.services_report')" />
    
    <div class="space-y-6">
      <!-- Filters & Export -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <select 
          v-model="selectedPeriod" 
          @change="applyFilters"
          class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
        >
          <option value="week">{{ t('reports.this_week') }}</option>
          <option value="month">{{ t('reports.this_month') }}</option>
          <option value="quarter">{{ t('reports.this_quarter') }}</option>
          <option value="year">{{ t('reports.this_year') }}</option>
          <option value="all">{{ t('reports.all_time') }}</option>
        </select>
        
        <div class="flex items-center gap-2">
          <a 
            :href="exportExcelUrl"
            class="flex items-center gap-2 rounded-lg bg-success-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-success-600"
          >
            <DownloadIcon class="h-4 w-4" />
            {{ t('reports.export_excel') }}
          </a>
          
          <a 
            :href="exportPdfUrl"
            class="flex items-center gap-2 rounded-lg bg-error-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-error-600"
          >
            <DownloadIcon class="h-4 w-4" />
            {{ t('reports.export_pdf') }}
          </a>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_services') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(stats.total_services) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.active_services') }}</p>
          <p class="text-2xl font-bold text-success-600">{{ formatNumber(stats.active_services) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_bookings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(stats.total_bookings) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_earnings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(stats.total_earnings) }}</p>
        </div>
      </div>

      <!-- Services Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">#</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.service_name') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.chef') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.status') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.price') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.bookings_count') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.completed_bookings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_earnings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.rating') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services.data" :key="service.id" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatNumber(service.id) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ service.name }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                  {{ service.chef?.user?.first_name }} {{ service.chef?.user?.last_name }}
                </td>
                <td class="px-4 py-3">
                  <Badge :color="service.is_active ? 'success' : 'error'" size="sm">
                    {{ service.is_active ? t('reports.active') : t('reports.inactive') }}
                  </Badge>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatPrice(service.price) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(service.total_bookings || 0) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(service.completed_bookings || 0) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(service.total_earnings || 0) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatRating(service.average_rating) }}</td>
              </tr>
              <tr v-if="!services.data?.length">
                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                  {{ t('reports.no_data') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="services.last_page > 1" class="flex items-center justify-center gap-2 border-t border-gray-200 p-4 dark:border-gray-700">
          <button 
            v-for="page in services.last_page" 
            :key="page"
            @click="goToPage(page)"
            :class="[
              'h-8 w-8 rounded-lg text-sm',
              page === services.current_page 
                ? 'bg-brand-500 text-white' 
                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'
            ]"
          >
            {{ formatNumber(page) }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import Badge from '@/Components/ui/Badge.vue'

const DownloadIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  services: Object,
  stats: Object,
  period: String,
})

const selectedPeriod = ref(props.period || 'month')

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'excel',
    period: selectedPeriod.value
  })
  return route('admin.reports.services.export') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'pdf',
    period: selectedPeriod.value
  })
  return route('admin.reports.services.export') + '?' + params.toString()
})

const applyFilters = () => {
  router.get(route('admin.reports.services'), { 
    period: selectedPeriod.value
  }, { preserveState: true })
}

const goToPage = (page) => {
  router.get(route('admin.reports.services'), { 
    period: selectedPeriod.value,
    page
  }, { preserveState: true })
}

const formatNumber = (num) => String(num || 0)
const formatPrice = (price) => Number(price || 0).toFixed(2) + ' ر.س'
const formatRating = (rating) => Number(rating || 0).toFixed(1)
</script>
