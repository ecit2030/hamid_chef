<template>
  <admin-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        {{ t('menu.dashboard_short') }}
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <!-- Total Users -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.totalUsers') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ formatNumber(dashboard.total_users) }}</p>
                <p v-if="dashboard.users_growth_percentage !== 0" class="mt-1 text-sm" :class="dashboard.users_growth_percentage > 0 ? 'text-green-600' : 'text-red-600'">
                  {{ dashboard.users_growth_percentage > 0 ? '+' : '' }}{{ formatNumber(dashboard.users_growth_percentage) }}%
                </p>
              </div>
              <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Active Chefs -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.activeChefs') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ formatNumber(dashboard.active_chefs) }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ t('admin.dashboard.outOf') }} {{ formatNumber(dashboard.total_chefs) }}</p>
              </div>
              <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.5 1.5 0 003 15.546V19a2 2 0 002 2h14a2 2 0 002-2v-3.454z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Monthly Bookings -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.monthlyBookings') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ formatNumber(dashboard.monthly_bookings) }}</p>
                <p class="mt-1 text-sm text-yellow-600">{{ formatNumber(dashboard.pending_bookings) }} {{ t('admin.dashboard.pending') }}</p>
              </div>
              <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Monthly Revenue -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.monthlyRevenue') }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ formatCurrency(dashboard.monthly_revenue) }}</p>
                <p v-if="dashboard.revenue_growth_percentage !== 0" class="mt-1 text-sm" :class="dashboard.revenue_growth_percentage > 0 ? 'text-green-600' : 'text-red-600'">
                  {{ dashboard.revenue_growth_percentage > 0 ? '+' : '' }}{{ formatNumber(dashboard.revenue_growth_percentage) }}%
                </p>
              </div>
              <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- KYC Alert -->
        <div v-if="dashboard.pending_kyc_requests > 0" class="p-4 bg-orange-50 border border-orange-200 rounded-xl dark:bg-orange-900/20 dark:border-orange-800">
          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30">
              <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <p class="font-medium text-orange-800 dark:text-orange-200">{{ t('admin.dashboard.pendingKyc') }}</p>
              <p class="text-sm text-orange-600 dark:text-orange-300">{{ formatNumber(dashboard.pending_kyc_requests) }} {{ t('admin.dashboard.requestsWaiting') }}</p>
            </div>
          </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <!-- Bookings Chart -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.bookingsChart') }}</h3>
            <div class="h-64">
              <canvas ref="bookingsChartRef"></canvas>
            </div>
          </div>

          <!-- Revenue Chart -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.revenueChart') }}</h3>
            <div class="h-64">
              <canvas ref="revenueChartRef"></canvas>
            </div>
          </div>
        </div>

        <!-- Top Chefs and Recent Bookings -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <!-- Top Chefs -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.topChefs') }}</h3>
            <div v-if="dashboard.top_chefs.length > 0" class="space-y-4">
              <div v-for="chef in dashboard.top_chefs" :key="chef.id" class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center gap-3">
                  <div class="flex items-center justify-center w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-900/30">
                    <span class="text-sm font-medium text-brand-600 dark:text-brand-400">{{ chef.name.charAt(0) }}</span>
                  </div>
                  <div>
                    <p class="font-medium text-gray-900 dark:text-white">{{ chef.name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatNumber(chef.bookings_count) }} {{ t('admin.dashboard.bookings') }}</p>
                  </div>
                </div>
                <div class="flex items-center gap-1">
                  <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ formatNumber(chef.average_rating?.toFixed(1) || 0) }}</span>
                </div>
              </div>
            </div>
            <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
              {{ t('common.noData') }}
            </div>
          </div>

          <!-- Recent Bookings -->
          <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.recentBookings') }}</h3>
            <div v-if="dashboard.recent_bookings.length > 0" class="space-y-4">
              <div v-for="booking in dashboard.recent_bookings" :key="booking.id" class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ booking.service_name }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">{{ booking.customer_name }} → {{ booking.chef_name }}</p>
                </div>
                <div class="text-right">
                  <p class="font-medium text-gray-900 dark:text-white">{{ formatCurrency(booking.total_amount) }}</p>
                  <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="getStatusClass(booking.status)">
                    {{ t('booking.status.' + booking.status) }}
                  </span>
                </div>
              </div>
            </div>
            <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
              {{ t('common.noData') }}
            </div>
          </div>
        </div>

        <!-- Bookings by Status -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.bookingsByStatus') }}</h3>
          <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            <div v-for="(count, status) in dashboard.bookings_by_status" :key="status" class="p-4 text-center rounded-lg bg-gray-50 dark:bg-gray-700/50">
              <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatNumber(count) }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('booking.status.' + status) }}</p>
            </div>
          </div>
        </div>

        <!-- Comprehensive Reports Section -->
        <div class="p-6 bg-white rounded-xl shadow-sm dark:bg-gray-800">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.reportsSection') }}</h3>
          </div>
          
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Bookings Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                  <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('reports.bookings_report') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('reports.bookings_report_desc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.bookings')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.bookings.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.bookings.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>

            <!-- Customers Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30">
                  <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.customersReport') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.customersReportDesc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.customers')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.customers.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.customers.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>

            <!-- Chefs Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                  <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0A1.5 1.5 0 003 15.546V19a2 2 0 002 2h14a2 2 0 002-2v-3.454z" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.chefsReport') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.chefsReportDesc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.chefs')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.chefs.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.chefs.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>

            <!-- Services Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                  <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('reports.services_report') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('reports.services_report_desc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.services')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.services.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.services.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>

            <!-- Earnings Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                  <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('reports.earnings_report') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('reports.earnings_report_desc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.earnings')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.earnings.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.earnings.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>

            <!-- Transactions Report -->
            <div class="p-5 transition-all border border-gray-200 rounded-lg hover:shadow-md dark:border-gray-700 hover:border-brand-500 dark:hover:border-brand-500">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-pink-100 dark:bg-pink-900/30">
                  <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                </div>
              </div>
              <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">{{ t('admin.dashboard.transactionsReport') }}</h4>
              <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">{{ t('admin.dashboard.transactionsReportDesc') }}</p>
              <div class="flex gap-2">
                <a :href="route('admin.reports.transactions')" class="flex-1 px-3 py-2 text-sm font-medium text-center text-white transition-colors rounded-lg !bg-[#083064] hover:!bg-[#062347]">
                  {{ t('admin.dashboard.viewReport') }}
                </a>
                <a :href="route('admin.reports.transactions.export', { format: 'excel' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  Excel
                </a>
                <a :href="route('admin.reports.transactions.export', { format: 'pdf' })" class="px-3 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                  PDF
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </admin-layout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import { useI18n } from 'vue-i18n'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps<{
  dashboard: {
    total_users: number
    total_customers: number
    total_chefs: number
    active_chefs: number
    users_growth_percentage: number
    total_bookings: number
    monthly_bookings: number
    pending_bookings: number
    completed_bookings: number
    total_revenue: number
    monthly_revenue: number
    revenue_growth_percentage: number
    pending_kyc_requests: number
    bookings_chart: Array<{ date: string; count: number }>
    revenue_chart: Array<{ month: string; month_name: string; revenue: number }>
    top_chefs: Array<{ id: number; name: string; bookings_count: number; average_rating: number | null }>
    recent_bookings: Array<{ id: number; service_name: string; customer_name: string; chef_name: string; total_amount: number; status: string }>
    bookings_by_status: Record<string, number>
  }
}>()

const { t } = useI18n()

const bookingsChartRef = ref<HTMLCanvasElement | null>(null)
const revenueChartRef = ref<HTMLCanvasElement | null>(null)

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'SAR',
    minimumFractionDigits: 0,
  }).format(amount).replace('SAR', 'ر.س')
}

const formatNumber = (num: number) => {
  return new Intl.NumberFormat('en-US').format(num)
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    accepted: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
    completed: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
    rejected: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
    cancelled_by_customer: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
    cancelled_by_chef: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
  }
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
}

onMounted(() => {
  // Bookings Chart
  if (bookingsChartRef.value && props.dashboard.bookings_chart.length > 0) {
    new Chart(bookingsChartRef.value, {
      type: 'bar',
      data: {
        labels: props.dashboard.bookings_chart.map(item => item.date),
        datasets: [{
          label: t('admin.dashboard.dailyBookings'),
          data: props.dashboard.bookings_chart.map(item => item.count),
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    })
  }

  // Revenue Chart
  if (revenueChartRef.value && props.dashboard.revenue_chart.length > 0) {
    new Chart(revenueChartRef.value, {
      type: 'line',
      data: {
        labels: props.dashboard.revenue_chart.map(item => item.month_name),
        datasets: [{
          label: t('admin.dashboard.monthlyRevenue'),
          data: props.dashboard.revenue_chart.map(item => item.revenue),
          borderColor: 'rgb(139, 92, 246)',
          backgroundColor: 'rgba(139, 92, 246, 0.1)',
          fill: true,
          tension: 0.4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    })
  }
})
</script>
