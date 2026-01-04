<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="t('reports.bookings_report')" />
    
    <div class="space-y-6">
      <!-- Filters & Export -->
      <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4">
          <select 
            v-model="selectedPeriod" 
            @change="handlePeriodChange"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
          >
            <option value="week">{{ t('reports.this_week') }}</option>
            <option value="month">{{ t('reports.this_month') }}</option>
            <option value="quarter">{{ t('reports.this_quarter') }}</option>
            <option value="year">{{ t('reports.this_year') }}</option>
            <option value="all">{{ t('reports.all_time') }}</option>
            <option value="custom">{{ t('reports.custom_range') }}</option>
          </select>
          
          <template v-if="selectedPeriod === 'custom'">
            <input 
              v-model="startDate" 
              type="date"
              @change="applyFilters"
              class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
              :placeholder="t('reports.start_date')"
            />
            <input 
              v-model="endDate" 
              type="date"
              @change="applyFilters"
              class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
              :placeholder="t('reports.end_date')"
            />
          </template>
          
          <select 
            v-model="selectedStatus" 
            @change="applyFilters"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
          >
            <option value="">{{ t('reports.all_statuses') }}</option>
            <option value="pending">{{ t('booking.pending') }}</option>
            <option value="accepted">{{ t('booking.accepted') }}</option>
            <option value="completed">{{ t('booking.completed') }}</option>
            <option value="rejected">{{ t('booking.rejected') }}</option>
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
          <p class="text-sm text-gray-500">{{ t('reports.total_bookings') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(stats.total) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.completed_bookings') }}</p>
          <p class="text-2xl font-bold text-success-600">{{ formatNumber(stats.completed) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_amount') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatPrice(stats.total_amount) }}</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/5">
          <p class="text-sm text-gray-500">{{ t('reports.total_hours') }}</p>
          <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ formatNumber(stats.total_hours) }}</p>
        </div>
      </div>

      <!-- Bookings Chart -->
      <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/5">
        <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">{{ t('reports.bookings_overview') }}</h3>
        <div class="h-64">
          <canvas ref="bookingsChartRef"></canvas>
        </div>
      </div>

      <!-- Bookings Table -->
      <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                <th class="px-4 py-3 text-right font-medium text-gray-500">#</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.customer') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.chef') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.service') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.date') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.hours') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.amount') }}</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">{{ t('reports.status') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="booking in bookings.data" :key="booking.id" class="border-b border-gray-100 dark:border-gray-800">
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatNumber(booking.id) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">
                  {{ booking.customer?.first_name }} {{ booking.customer?.last_name }}
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                  {{ booking.chef?.user?.first_name }} {{ booking.chef?.user?.last_name }}
                </td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ booking.service?.name }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatDate(booking.date) }}</td>
                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ formatNumber(booking.hours_count) }}</td>
                <td class="px-4 py-3 text-gray-800 dark:text-white">{{ formatPrice(booking.total_amount) }}</td>
                <td class="px-4 py-3">
                  <Badge :color="getStatusColor(booking.booking_status)" size="sm">
                    {{ t(`booking.${booking.booking_status}`) }}
                  </Badge>
                </td>
              </tr>
              <tr v-if="!bookings.data?.length">
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                  {{ t('reports.no_data') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div v-if="bookings.last_page > 1" class="flex items-center justify-center gap-2 border-t border-gray-200 p-4 dark:border-gray-700">
          <button 
            v-for="page in bookings.last_page" 
            :key="page"
            @click="goToPage(page)"
            :class="[
              'h-8 w-8 rounded-lg text-sm',
              page === bookings.current_page 
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
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import Badge from '@/Components/ui/Badge.vue'

Chart.register(...registerables)

const DownloadIcon = { template: '<svg class="fill-current" viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>' }

const { t } = useI18n()

const props = defineProps({
  bookings: Object,
  stats: Object,
  period: String,
  status: String,
  start_date: String,
  end_date: String,
})

const selectedPeriod = ref(props.period || 'month')
const selectedStatus = ref(props.status || '')
const startDate = ref(props.start_date || '')
const endDate = ref(props.end_date || '')
const bookingsChartRef = ref(null)
let bookingsChart = null

const exportExcelUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'excel',
    period: selectedPeriod.value,
    status: selectedStatus.value || ''
  })
  if (selectedPeriod.value === 'custom' && startDate.value && endDate.value) {
    params.set('start_date', startDate.value)
    params.set('end_date', endDate.value)
  }
  return route('admin.reports.bookings.export') + '?' + params.toString()
})

const exportPdfUrl = computed(() => {
  const params = new URLSearchParams({
    format: 'pdf',
    period: selectedPeriod.value,
    status: selectedStatus.value || ''
  })
  if (selectedPeriod.value === 'custom' && startDate.value && endDate.value) {
    params.set('start_date', startDate.value)
    params.set('end_date', endDate.value)
  }
  return route('admin.reports.bookings.export') + '?' + params.toString()
})

const handlePeriodChange = () => {
  if (selectedPeriod.value !== 'custom') {
    applyFilters()
  }
}

const applyFilters = () => {
  const params = { 
    period: selectedPeriod.value,
    status: selectedStatus.value || undefined
  }
  
  if (selectedPeriod.value === 'custom' && startDate.value && endDate.value) {
    params.start_date = startDate.value
    params.end_date = endDate.value
  }
  
  router.get(route('admin.reports.bookings'), params, { preserveState: true })
}

const goToPage = (page) => {
  router.get(route('admin.reports.bookings'), { 
    period: selectedPeriod.value,
    status: selectedStatus.value || undefined,
    page
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

const getStatusColor = (status) => {
  const colors = { pending: 'warning', accepted: 'success', rejected: 'error', completed: 'info' }
  return colors[status] || 'light'
}

const initChart = () => {
  if (bookingsChartRef.value && props.stats) {
    const ctx = bookingsChartRef.value.getContext('2d')
    
    if (bookingsChart) {
      bookingsChart.destroy()
    }
    
    bookingsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['الإجمالي', 'مكتمل', 'قيد الانتظار', 'مقبول', 'ملغي'],
        datasets: [{
          label: 'عدد الحجوزات',
          data: [
            props.stats.total,
            props.stats.completed,
            props.stats.pending,
            props.stats.accepted,
            props.stats.cancelled
          ],
          backgroundColor: [
            '#083064',
            '#10b981',
            '#f59e0b',
            '#8b5cf6',
            '#ef4444'
          ],
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
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
  initChart()
})
</script>
