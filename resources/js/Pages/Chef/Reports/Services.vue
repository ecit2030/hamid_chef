<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('reports.services_report')" />
    
    <div class="space-y-6">
      <!-- Period Filter & Export -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ t('reports.services_report') }}</h2>
        <div class="flex items-center gap-3">
          <select 
            v-model="selectedPeriod" 
            @change="changePeriod"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
          >
            <option value="week">{{ t('reports.this_week') }}</option>
            <option value="month">{{ t('reports.this_month') }}</option>
            <option value="quarter">{{ t('reports.this_quarter') }}</option>
            <option value="year">{{ t('reports.this_year') }}</option>
            <option value="all">{{ t('reports.all_time') }}</option>
          </select>
          
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

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Services -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15">
              <TaskIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_services') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_services) }}</p>
            </div>
          </div>
        </div>

        <!-- Active Services -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 text-success-600 dark:bg-success-500/15">
              <CheckIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.active_services') }}</p>
              <p class="text-2xl font-bold text-success-600">{{ formatNumber(summary.active_services) }}</p>
            </div>
          </div>
        </div>

        <!-- Total Bookings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-info-50 text-info-600 dark:bg-info-500/15">
              <CalenderIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_bookings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_bookings) }}</p>
            </div>
          </div>
        </div>

        <!-- Total Earnings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning-50 text-warning-600 dark:bg-warning-500/15">
              <WalletIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_earnings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.total_earnings) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Services by Earnings Chart -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.earnings_by_service') }}</h3>
          <div class="h-64">
            <canvas ref="earningsChartRef"></canvas>
          </div>
        </div>

        <!-- Services by Bookings Chart -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.bookings_by_service') }}</h3>
          <div class="h-64">
            <canvas ref="bookingsChartRef"></canvas>
          </div>
        </div>
      </div>

      <!-- Best Performers -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- Best Service -->
        <div v-if="summary.best_service" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.best_service') }}</p>
          <p class="text-lg font-bold text-gray-800 dark:text-white">{{ summary.best_service.name }}</p>
          <p class="text-sm text-success-600">{{ formatPrice(summary.best_service.total_earnings) }}</p>
        </div>

        <!-- Most Booked -->
        <div v-if="summary.most_booked" class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.most_booked') }}</p>
          <p class="text-lg font-bold text-gray-800 dark:text-white">{{ summary.most_booked.name }}</p>
          <p class="text-sm text-info-600">{{ formatNumber(summary.most_booked.completed_bookings) }} {{ t('reports.bookings') }}</p>
        </div>
      </div>

      <!-- Services Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="border-b border-gray-200 p-5 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.services_report_desc') }}</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.service') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.status') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_bookings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.completed_bookings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.conversion_rate') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_hours') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_earnings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.average_rating') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services" :key="service.id" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 font-medium text-gray-800 dark:text-white">{{ service.name }}</td>
                <td class="px-4 py-3">
                  <Badge :color="service.is_active ? 'success' : 'light'" size="sm">
                    {{ service.is_active ? t('common.active') : t('common.inactive') }}
                  </Badge>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(service.total_bookings) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(service.completed_bookings) }}</td>
                <td class="px-4 py-3">
                  <span :class="getConversionClass(service.conversion_rate)">
                    {{ formatNumber(service.conversion_rate) }}%
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(service.total_hours) }}</td>
                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-white">{{ formatPrice(service.total_earnings) }}</td>
                <td class="px-4 py-3">
                  <span class="flex items-center gap-1">
                    <span class="text-warning-500">⭐</span>
                    <span class="text-gray-800 dark:text-white">{{ formatNumber(service.average_rating, 1) }}</span>
                  </span>
                </td>
              </tr>
              <tr v-if="!services.length">
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                  {{ t('reports.no_data') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import Badge from '@/Components/ui/Badge.vue'
import { CalenderIcon, TaskIcon, WalletIcon } from '@/icons'

Chart.register(...registerables)

const CheckIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>' }
const DownloadIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  services: Array,
  summary: Object,
  period: String,
})

const selectedPeriod = ref(props.period || 'month')
const earningsChartRef = ref(null)
const bookingsChartRef = ref(null)
let earningsChart = null
let bookingsChart = null

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    type: 'services',
    period: selectedPeriod.value
  })
  return route('chef.reports.export.excel') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    type: 'services',
    period: selectedPeriod.value
  })
  return route('chef.reports.export.pdf') + '?' + params.toString()
})

const changePeriod = () => {
  router.get(route('chef.reports.services'), { period: selectedPeriod.value }, { preserveState: true })
}

const formatNumber = (num, decimals = 0) => {
  return Number(num || 0).toFixed(decimals)
}

const formatPrice = (price) => Number(price || 0).toFixed(2) + ' ر.س'

const getConversionClass = (rate) => {
  if (rate >= 80) return 'text-success-600 font-semibold'
  if (rate >= 50) return 'text-warning-600'
  return 'text-error-600'
}

const chartColors = [
  '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
  '#ec4899', '#06b6d4', '#84cc16', '#f97316', '#6366f1'
]

const initCharts = () => {
  if (!props.services?.length) return
  
  const topServices = props.services.slice(0, 5)
  const labels = topServices.map(s => s.name)
  
  // Earnings Chart
  if (earningsChartRef.value) {
    const ctx = earningsChartRef.value.getContext('2d')
    
    if (earningsChart) {
      earningsChart.destroy()
    }
    
    earningsChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data: topServices.map(s => s.total_earnings),
          backgroundColor: chartColors.slice(0, topServices.length),
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            rtl: true,
            labels: {
              font: { family: 'Cairo' },
              padding: 10
            }
          },
          tooltip: {
            rtl: true,
            titleFont: { family: 'Cairo' },
            bodyFont: { family: 'Cairo' },
            callbacks: {
              label: (context) => context.parsed + ' ر.س'
            }
          }
        }
      }
    })
  }
  
  // Bookings Chart
  if (bookingsChartRef.value) {
    const ctx = bookingsChartRef.value.getContext('2d')
    
    if (bookingsChart) {
      bookingsChart.destroy()
    }
    
    bookingsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'الحجوزات المكتملة',
          data: topServices.map(s => s.completed_bookings),
          backgroundColor: '#10b981',
          borderRadius: 8
        }, {
          label: 'إجمالي الحجوزات',
          data: topServices.map(s => s.total_bookings),
          backgroundColor: '#3b82f6',
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            rtl: true,
            labels: {
              font: { family: 'Cairo' },
              padding: 10
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
              maxRotation: 45
            }
          }
        }
      }
    })
  }
}

onMounted(() => {
  initCharts()
})
</script>
