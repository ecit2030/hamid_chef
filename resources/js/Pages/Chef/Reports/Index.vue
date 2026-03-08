<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('reports.title')" />

    <div class="space-y-6">
      <!-- Period Filter -->
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ t('reports.overview') }}</h2>
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
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Bookings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/15">
              <CalenderIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_bookings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_bookings) }}</p>
            </div>
          </div>
        </div>

        <!-- Completed Bookings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-50 text-success-600 dark:bg-success-500/15">
              <CheckIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.completed_bookings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.completed_bookings) }}</p>
            </div>
          </div>
        </div>

        <!-- Net Earnings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning-50 text-warning-600 dark:bg-warning-500/15">
              <WalletIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.net_earnings') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.net_earnings) }}</p>
            </div>
          </div>
        </div>

        <!-- Average Rating -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-info-50 text-info-600 dark:bg-info-500/15">
              <StarIcon class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.average_rating') }}</p>
              <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.average_rating, 1) }} ⭐</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Second Row Stats -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Earnings -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_earnings') }}</p>
          <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatPrice(summary.total_earnings) }}</p>
        </div>

        <!-- Commission -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.commission') }}</p>
          <p class="text-xl font-bold text-error-600">{{ formatPrice(summary.total_commission) }}</p>
        </div>

        <!-- Total Hours -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_hours') }}</p>
          <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_hours) }} {{ t('reports.hours') }}</p>
        </div>

        <!-- Total Guests -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('reports.total_guests') }}</p>
          <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatNumber(summary.total_guests) }} {{ t('reports.guests') }}</p>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Bookings by Status Chart -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.bookings_by_status') }}</h3>
          <div class="h-64">
            <canvas ref="statusChartRef"></canvas>
          </div>
        </div>

        <!-- Earnings by Month Chart -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.earnings_chart') }}</h3>
          <div class="h-64">
            <canvas ref="earningsChartRef"></canvas>
          </div>
        </div>
      </div>

      <!-- Top Services & Status List -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Bookings by Status List -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.bookings_by_status') }}</h3>
          <div class="space-y-3">
            <div v-for="(count, status) in bookingsByStatus" :key="status" class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span :class="getStatusDotClass(status)" class="h-3 w-3 rounded-full"></span>
                <span class="text-sm text-gray-600 dark:text-gray-300">{{ t(`booking.${status}`) }}</span>
              </div>
              <span class="font-semibold text-gray-800 dark:text-white">{{ formatNumber(count) }}</span>
            </div>
          </div>
        </div>

        <!-- Top Services -->
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
          <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.top_services') }}</h3>
          <div class="space-y-3">
            <div v-for="service in topServices" :key="service.id" class="flex items-center justify-between">
              <span class="text-sm text-gray-600 dark:text-gray-300">{{ service.name }}</span>
              <div class="text-right">
                <span class="font-semibold text-gray-800 dark:text-white">{{ formatNumber(service.bookings_count) }}</span>
                <span class="text-xs text-gray-500"> {{ t('reports.bookings') }}</span>
              </div>
            </div>
            <div v-if="!topServices.length" class="text-center text-sm text-gray-500">
              {{ t('reports.no_data') }}
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Bookings -->
      <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.recent_bookings') }}</h3>
          <Link :href="route('chef.reports.bookings')" class="text-sm text-brand-500 hover:text-brand-600">
            {{ t('reports.view_all') }} →
          </Link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 dark:border-gray-700">
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">#</th>
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ t('reports.customer') }}</th>
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ t('reports.service') }}</th>
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ t('reports.date') }}</th>
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ t('reports.amount') }}</th>
                <th class="py-3 text-right font-medium text-gray-500 dark:text-gray-400">{{ t('reports.status') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in recentBookings" :key="booking.id" class="border-b border-gray-100 dark:border-gray-800">
                <td class="py-3 text-gray-800 dark:text-white">{{ formatNumber(booking.id) }}</td>
                <td class="py-3 text-gray-800 dark:text-white">{{ booking.customer_name }}</td>
                <td class="py-3 text-gray-600 dark:text-gray-300">{{ booking.service_name }}</td>
                <td class="py-3 text-gray-600 dark:text-gray-300">{{ formatDate(booking.date) }}</td>
                <td class="py-3 text-gray-800 dark:text-white">{{ formatPrice(booking.total_amount) }}</td>
                <td class="py-3">
                  <Badge :color="getStatusColor(booking.status)" size="sm">
                    {{ t(`booking.${booking.status}`) }}
                  </Badge>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <Link :href="route('chef.reports.bookings')" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-brand-500 dark:border-gray-800 dark:bg-white/5">
          <CalenderIcon class="h-8 w-8 text-brand-500" />
          <div>
            <p class="font-semibold text-gray-800 dark:text-white">{{ t('reports.bookings_report') }}</p>
            <p class="text-sm text-gray-500">{{ t('reports.bookings_report_desc') }}</p>
          </div>
        </Link>
        <Link :href="route('chef.reports.earnings')" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-brand-500 dark:border-gray-800 dark:bg-white/5">
          <WalletIcon class="h-8 w-8 text-success-500" />
          <div>
            <p class="font-semibold text-gray-800 dark:text-white">{{ t('reports.earnings_report') }}</p>
            <p class="text-sm text-gray-500">{{ t('reports.earnings_report_desc') }}</p>
          </div>
        </Link>
        <Link :href="route('chef.reports.services')" class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 transition hover:border-brand-500 dark:border-gray-800 dark:bg-white/5">
          <TaskIcon class="h-8 w-8 text-warning-500" />
          <div>
            <p class="font-semibold text-gray-800 dark:text-white">{{ t('reports.services_report') }}</p>
            <p class="text-sm text-gray-500">{{ t('reports.services_report_desc') }}</p>
          </div>
        </Link>
      </div>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import Badge from '@/Components/ui/Badge.vue'
import { CalenderIcon, WalletIcon, TaskIcon } from '@/icons'

Chart.register(...registerables)

// Simple icon Components
const CheckIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>' }
const StarIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  summary: Object,
  bookingsByStatus: Object,
  earningsByMonth: Array,
  topServices: Array,
  recentBookings: Array,
  period: String,
})

const selectedPeriod = ref(props.period || 'month')
const statusChartRef = ref(null)
const earningsChartRef = ref(null)
let statusChart = null
let earningsChart = null

const changePeriod = () => {
  router.get(route('chef.reports.index'), { period: selectedPeriod.value }, { preserveState: true })
}

// Arabic month names
const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']

const formatNumber = (num, decimals = 0) => {
  return Number(num || 0).toFixed(decimals)
}

const formatPrice = (price) => {
  return Number(price || 0).toFixed(2) + ' ر.س'
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  return `${day} ${month} ${year}`
}

const getStatusColor = (status) => {
  const colors = {
    pending: 'warning',
    accepted: 'success',
    rejected: 'error',
    completed: 'info',
    cancelled_by_customer: 'light',
    cancelled_by_chef: 'light',
    cancelled_by_admin: 'light'
  }
  return colors[status] || 'light'
}

const getStatusDotClass = (status) => {
  const classes = {
    pending: 'bg-warning-500',
    accepted: 'bg-success-500',
    rejected: 'bg-error-500',
    completed: 'bg-info-500',
    cancelled_by_customer: 'bg-gray-400',
    cancelled_by_chef: 'bg-gray-400',
    cancelled_by_admin: 'bg-gray-400'
  }
  return classes[status] || 'bg-gray-400'
}

const statusLabels = {
  pending: 'قيد الانتظار',
  accepted: 'مقبول',
  rejected: 'مرفوض',
  completed: 'مكتمل',
  cancelled_by_customer: 'ملغي من العميل',
  cancelled_by_chef: 'ملغي من الشيف',
  cancelled_by_admin: 'ملغي من الإدارة'
}

const statusColors = {
  pending: '#f59e0b',
  accepted: '#10b981',
  rejected: '#ef4444',
  completed: '#3b82f6',
  cancelled_by_customer: '#9ca3af',
  cancelled_by_chef: '#9ca3af',
  cancelled_by_admin: '#9ca3af'
}

const initCharts = () => {
  // Status Pie Chart
  if (statusChartRef.value && props.bookingsByStatus) {
    const ctx = statusChartRef.value.getContext('2d')

    if (statusChart) {
      statusChart.destroy()
    }

    const labels = Object.keys(props.bookingsByStatus).map(s => statusLabels[s] || s)
    const data = Object.values(props.bookingsByStatus)
    const colors = Object.keys(props.bookingsByStatus).map(s => statusColors[s] || '#9ca3af')

    statusChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: colors,
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
              padding: 15
            }
          }
        }
      }
    })
  }

  // Earnings Line Chart
  if (earningsChartRef.value && props.earningsByMonth?.length) {
    const ctx = earningsChartRef.value.getContext('2d')

    if (earningsChart) {
      earningsChart.destroy()
    }

    const labels = props.earningsByMonth.map(e => arabicMonths[e.month - 1])
    const netData = props.earningsByMonth.map(e => e.net)
    const totalData = props.earningsByMonth.map(e => e.total)

    earningsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [
          {
            label: 'صافي الأرباح',
            data: netData,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4
          },
          {
            label: 'الإجمالي',
            data: totalData,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
          }
        ]
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
              padding: 15
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
              font: { family: 'Cairo' }
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

watch(() => props.bookingsByStatus, () => {
  initCharts()
}, { deep: true })
</script>
