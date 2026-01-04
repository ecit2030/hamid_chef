<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="t('reports.earnings_report')" />
    
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
          <p class="text-sm text-gray-500">{{ t('reports.total_earnings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.total_earnings) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_commission') }}</p>
          <p class="text-2xl font-bold text-success-600">{{ formatPrice(summary.total_commission) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.net_earnings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.net_earnings) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_bookings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_bookings) }}</p>
        </div>
      </div>

      <!-- Earnings Chart -->
      <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.earnings_overview') }}</h3>
        <div class="h-64">
          <canvas ref="earningsChartRef"></canvas>
        </div>
      </div>

      <!-- Daily Earnings Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.date') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.bookings_count') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.hours') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_revenue') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.commission') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.net_earnings') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="earning in dailyEarnings" :key="earning.date" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatDate(earning.date) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(earning.bookings_count) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(earning.hours) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(earning.total) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatPrice(earning.commission) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(earning.net) }}</td>
              </tr>
              <tr v-if="!dailyEarnings?.length">
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                  {{ t('reports.no_data') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'

Chart.register(...registerables)

const DownloadIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  dailyEarnings: Array,
  summary: Object,
  period: String,
})

const selectedPeriod = ref(props.period || 'month')
const earningsChartRef = ref(null)
let earningsChart = null

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'excel',
    period: selectedPeriod.value
  })
  return route('admin.reports.earnings.export') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'pdf',
    period: selectedPeriod.value
  })
  return route('admin.reports.earnings.export') + '?' + params.toString()
})

const applyFilters = () => {
  router.get(route('admin.reports.earnings'), { 
    period: selectedPeriod.value
  }, { preserveState: true })
}

const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']

const formatNumber = (num) => String(num || 0)
const formatPrice = (price) => Number(price || 0).toFixed(2) + ' ر.س'
const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  return `${day} ${month} ${year}`
}

const initChart = () => {
  if (earningsChartRef.value && props.dailyEarnings) {
    const ctx = earningsChartRef.value.getContext('2d')
    
    if (earningsChart) {
      earningsChart.destroy()
    }
    
    const labels = props.dailyEarnings.map(e => formatDate(e.date)).reverse()
    const netData = props.dailyEarnings.map(e => e.net).reverse()
    const commissionData = props.dailyEarnings.map(e => e.commission).reverse()
    
    earningsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'صافي الأرباح',
            data: netData,
            borderColor: '#083064',
            backgroundColor: 'rgba(8, 48, 100, 0.1)',
            tension: 0.4,
            fill: true
          },
          {
            label: 'العمولة',
            data: commissionData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            labels: {
              font: { family: 'Cairo' }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              font: { family: 'Cairo' }
            }
          },
          x: {
            ticks: {
              font: { family: 'Cairo' },
              maxRotation: 45,
              minRotation: 45
            }
          }
        }
      }
    })
  }
}

onMounted(() => {
  initChart()
})
</script>
