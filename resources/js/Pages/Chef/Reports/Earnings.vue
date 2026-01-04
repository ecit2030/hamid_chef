<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('reports.earnings_report')" />
    
    <div class="space-y-6">
      <!-- Period Filter & Export -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ t('reports.daily_earnings') }}</h2>
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
        <!-- Total Earnings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15">
              <WalletIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_earnings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.total_earnings) }}</p>
            </div>
          </div>
        </div>

        <!-- Net Earnings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 text-success-600 dark:bg-success-500/15">
              <CheckIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.net_earnings') }}</p>
              <p class="text-2xl font-bold text-success-600">{{ formatPrice(summary.net_earnings) }}</p>
            </div>
          </div>
        </div>

        <!-- Commission -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-error-50 text-error-600 dark:bg-error-500/15">
              <MinusIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.commission') }}</p>
              <p class="text-2xl font-bold text-error-600">{{ formatPrice(summary.total_commission) }}</p>
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
      </div>

      <!-- Second Row Stats -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Average Per Booking -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.average_per_booking') }}</p>
          <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.average_per_booking) }}</p>
        </div>

        <!-- Average Per Day -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.average_per_day') }}</p>
          <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.average_per_day) }}</p>
        </div>

        <!-- Wallet Balance -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.wallet_balance') }}</p>
          <p class="text-xl font-bold text-success-600">{{ formatPrice(walletBalance) }}</p>
        </div>

        <!-- Pending Withdrawals -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.pending_withdrawals') }}</p>
          <p class="text-xl font-bold text-warning-600">{{ formatPrice(pendingWithdrawals) }}</p>
        </div>
      </div>

      <!-- Earnings Chart -->
      <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.earnings_chart') }}</h3>
        <div class="h-72">
          <canvas ref="earningsChartRef"></canvas>
        </div>
      </div>

      <!-- Daily Earnings Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="border-b border-gray-200 p-5 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.daily_earnings') }}</h3>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.date') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.bookings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.hours') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.total_earnings') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.commission') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.net_earnings') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="day in dailyEarnings" :key="day.date" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatDate(day.date) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(day.bookings_count) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(day.hours) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(day.total) }}</td>
                <td class="px-4 py-3 text-error-600">{{ formatPrice(day.commission) }}</td>
                <td class="px-4 py-3 font-semibold text-success-600">{{ formatPrice(day.net) }}</td>
              </tr>
              <tr v-if="!dailyEarnings.length">
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
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
import { CalenderIcon, WalletIcon } from '@/icons'

Chart.register(...registerables)

const CheckIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>' }
const MinusIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 13H5v-2h14v2z"/></svg>' }
const DownloadIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  dailyEarnings: Array,
  summary: Object,
  walletBalance: Number,
  pendingWithdrawals: Number,
  period: String,
})

const selectedPeriod = ref(props.period || 'month')
const earningsChartRef = ref(null)
let earningsChart = null

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    type: 'earnings',
    period: selectedPeriod.value
  })
  return route('chef.reports.export.excel') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    type: 'earnings',
    period: selectedPeriod.value
  })
  return route('chef.reports.export.pdf') + '?' + params.toString()
})

const changePeriod = () => {
  router.get(route('chef.reports.earnings'), { period: selectedPeriod.value }, { preserveState: true })
}

// Arabic month names
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
  if (earningsChartRef.value && props.dailyEarnings?.length) {
    const ctx = earningsChartRef.value.getContext('2d')
    
    if (earningsChart) {
      earningsChart.destroy()
    }
    
    // Get last 14 days or all data if less
    const data = props.dailyEarnings.slice(0, 14).reverse()
    const labels = data.map(d => {
      const date = new Date(d.date)
      return `${date.getDate()} ${arabicMonths[date.getMonth()]}`
    })
    
    earningsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'صافي الأرباح',
            data: data.map(d => d.net),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#10b981'
          },
          {
            label: 'الإجمالي',
            data: data.map(d => d.total),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#3b82f6'
          },
          {
            label: 'العمولة',
            data: data.map(d => d.commission),
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#ef4444'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          intersect: false,
          mode: 'index'
        },
        plugins: {
          legend: {
            position: 'bottom',
            rtl: true,
            labels: {
              font: { family: 'Cairo' },
              padding: 15
            }
          },
          tooltip: {
            rtl: true,
            titleFont: { family: 'Cairo' },
            bodyFont: { family: 'Cairo' }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              font: { family: 'Cairo' },
              callback: (value) => value + ' ر.س'
            }
          },
          x: {
            ticks: {
              font: { family: 'Cairo' }
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
