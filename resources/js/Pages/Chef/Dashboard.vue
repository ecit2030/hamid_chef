<template>
  <ChefLayout>
    <div class="space-y-6">
      <!-- Welcome Header -->
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ t('chef.dashboard.welcome') }}، {{ chef.name }} 👨‍🍳
          </h1>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ t('chef.dashboard.title') }}
          </p>
        </div>
        <div class="flex items-center gap-2">
          <span class="flex items-center gap-1 px-3 py-1 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-900/30 dark:text-yellow-400">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            {{ statistics.average_rating.toFixed(1) }}
          </span>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Monthly Bookings -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.monthlyBookings') }}</p>
              <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ statistics.monthly_bookings }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30">
              <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Pending Bookings -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.pendingBookings') }}</p>
              <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ statistics.pending_bookings }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
              <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Monthly Earnings -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.monthlyEarnings') }}</p>
              <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(statistics.monthly_earnings) }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Active Services -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.activeServices') }}</p>
              <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ statistics.active_services }}</p>
            </div>
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30">
              <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts and Lists Row -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Earnings Chart -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('chef.dashboard.earningsChart') }}</h3>
          <div class="h-64">
            <canvas ref="earningsChartRef"></canvas>
          </div>
        </div>

        <!-- Bookings by Status -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('chef.dashboard.bookingsByStatus') }}</h3>
          <div class="h-64">
            <canvas ref="statusChartRef"></canvas>
          </div>
        </div>
      </div>

      <!-- Upcoming Bookings and Recent Reviews -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Upcoming Bookings -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ t('chef.dashboard.upcomingBookings') }}</h3>
            <Link :href="route('chef.bookings.index')" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400">
              {{ t('chef.dashboard.viewAll') }} →
            </Link>
          </div>
          <div v-if="upcoming_bookings.length > 0" class="space-y-4">
            <div v-for="booking in upcoming_bookings" :key="booking.id" class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
              <div>
                <p class="font-medium text-gray-900 dark:text-white">{{ booking.service_name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ booking.customer_name }}</p>
              </div>
              <div class="text-right">
                <p class="font-medium text-gray-900 dark:text-white">{{ booking.date }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ booking.start_time }}</p>
              </div>
            </div>
          </div>
          <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
            {{ t('chef.dashboard.noUpcomingBookings') }}
          </div>
        </div>

        <!-- Recent Reviews -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('chef.dashboard.recentReviews') }}</h3>
          <div v-if="recent_reviews.length > 0" class="space-y-4">
            <div v-for="review in recent_reviews" :key="review.id" class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
              <div class="flex items-center justify-between mb-2">
                <p class="font-medium text-gray-900 dark:text-white">{{ review.customer_name }}</p>
                <div class="flex items-center gap-1">
                  <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
              </div>
              <p v-if="review.review" class="text-sm text-gray-600 dark:text-gray-300">{{ review.review }}</p>
              <p class="mt-2 text-xs text-gray-400">{{ review.service_name }} • {{ review.created_at }}</p>
            </div>
          </div>
          <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
            {{ t('chef.dashboard.noRecentReviews') }}
          </div>
        </div>
      </div>

      <!-- Services Performance -->
      <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('chef.dashboard.servicesPerformance') }}</h3>
        <div v-if="services_performance.length > 0" class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('common.name') }}</th>
                <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.totalBookings') }}</th>
                <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.totalReviews') }}</th>
                <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('chef.dashboard.averageRating') }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="service in services_performance" :key="service.id">
                <td class="py-3 text-gray-900 dark:text-white">{{ service.name }}</td>
                <td class="py-3 text-gray-600 dark:text-gray-300">{{ service.bookings_count }}</td>
                <td class="py-3 text-gray-600 dark:text-gray-300">{{ service.ratings_count }}</td>
                <td class="py-3">
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-gray-900 dark:text-white">{{ service.average_rating.toFixed(1) }}</span>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
          {{ t('common.noData') }}
        </div>
      </div>
    </div>
  </ChefLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps<{
  chef: {
    id: number
    name: string
    logo: string | null
    rating_avg: number
  }
  statistics: {
    total_bookings: number
    monthly_bookings: number
    pending_bookings: number
    confirmed_bookings: number
    completed_bookings: number
    total_earnings: number
    monthly_earnings: number
    average_rating: number
    total_reviews: number
    total_services: number
    active_services: number
    wallet_balance: number
  }
  earnings_chart: Array<{
    month: string
    month_name: string
    earnings: number
  }>
  bookings_by_status: {
    pending: number
    confirmed: number
    completed: number
    cancelled: number
  }
  upcoming_bookings: Array<{
    id: number
    date: string
    start_time: string
    hours_count: number
    status: string
    total_amount: number
    customer_name: string
    service_name: string
  }>
  recent_reviews: Array<{
    id: number
    rating: number
    review: string | null
    customer_name: string
    service_name: string
    created_at: string
  }>
  services_performance: Array<{
    id: number
    name: string
    bookings_count: number
    ratings_count: number
    average_rating: number
  }>
}>()

const { t } = useI18n()

const earningsChartRef = ref<HTMLCanvasElement | null>(null)
const statusChartRef = ref<HTMLCanvasElement | null>(null)

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-SA', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0,
  }).format(amount)
}

onMounted(() => {
  // Earnings Chart
  if (earningsChartRef.value) {
    new Chart(earningsChartRef.value, {
      type: 'line',
      data: {
        labels: props.earnings_chart.map(item => item.month_name),
        datasets: [{
          label: t('chef.dashboard.monthlyEarnings'),
          data: props.earnings_chart.map(item => item.earnings),
          borderColor: 'rgb(59, 130, 246)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
          }
        }
      }
    })
  }

  // Status Chart
  if (statusChartRef.value) {
    new Chart(statusChartRef.value, {
      type: 'doughnut',
      data: {
        labels: [
          t('chef.dashboard.pendingBookings'),
          t('chef.dashboard.confirmedBookings'),
          t('chef.dashboard.completedBookings'),
        ],
        datasets: [{
          data: [
            props.bookings_by_status.pending,
            props.bookings_by_status.confirmed,
            props.bookings_by_status.completed,
          ],
          backgroundColor: [
            'rgb(234, 179, 8)',
            'rgb(59, 130, 246)',
            'rgb(34, 197, 94)',
          ],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
          }
        }
      }
    })
  }
})
</script>
