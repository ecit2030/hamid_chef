<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="t('reports.transactions_report')" />
    
    <div class="space-y-6">
      <!-- Filters & Export -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
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
          
          <select 
            v-model="selectedType" 
            @change="applyFilters"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
          >
            <option value="">{{ t('reports.all_types') }}</option>
            <option value="credit">{{ t('reports.credit') }}</option>
            <option value="debit">{{ t('reports.debit') }}</option>
          </select>
        </div>
        
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
          <p class="text-sm text-gray-500">{{ t('reports.total_transactions') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(stats.total_transactions) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_credits') }}</p>
          <p class="text-2xl font-bold text-success-600">{{ formatPrice(stats.total_credits) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_debits') }}</p>
          <p class="text-2xl font-bold text-error-600">{{ formatPrice(stats.total_debits) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.net_amount') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(stats.net_amount) }}</p>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">#</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.chef') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.type') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.amount') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.description') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.date') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in transactions.data" :key="transaction.id" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatNumber(transaction.id) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">
                  {{ transaction.chef?.user?.first_name }} {{ transaction.chef?.user?.last_name }}
                </td>
                <td class="px-4 py-3">
                  <Badge :color="transaction.type === 'credit' ? 'success' : 'error'" size="sm">
                    {{ t(`reports.${transaction.type}`) }}
                  </Badge>
                </td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(transaction.amount) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ transaction.description || '-' }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatDateTime(transaction.created_at) }}</td>
              </tr>
              <tr v-if="!transactions.data?.length">
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                  {{ t('reports.no_data') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="transactions.last_page > 1" class="flex items-center justify-center gap-2 border-t border-gray-200 p-4 dark:border-gray-700">
          <button 
            v-for="page in transactions.last_page" 
            :key="page"
            @click="goToPage(page)"
            :class="[
              'h-8 w-8 rounded-lg text-sm',
              page === transactions.current_page 
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
  transactions: Object,
  stats: Object,
  period: String,
  type: String,
})

const selectedPeriod = ref(props.period || 'month')
const selectedType = ref(props.type || '')

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'excel',
    period: selectedPeriod.value,
    type: selectedType.value || ''
  })
  return route('admin.reports.transactions.export') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'pdf',
    period: selectedPeriod.value,
    type: selectedType.value || ''
  })
  return route('admin.reports.transactions.export') + '?' + params.toString()
})

const applyFilters = () => {
  router.get(route('admin.reports.transactions'), { 
    period: selectedPeriod.value,
    type: selectedType.value || undefined
  }, { preserveState: true })
}

const goToPage = (page) => {
  router.get(route('admin.reports.transactions'), { 
    period: selectedPeriod.value,
    type: selectedType.value || undefined,
    page
  }, { preserveState: true })
}

const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']

const formatNumber = (num) => String(num || 0)
const formatPrice = (price) => Number(price || 0).toFixed(2) + ' ر.س'
const formatDateTime = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${day} ${month} ${year} ${hours}:${minutes}`
}
</script>
