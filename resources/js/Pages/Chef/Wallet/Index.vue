<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('menu.wallet')" />
    <div class="space-y-5 sm:space-y-6">
      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('chef.available_balance') }}</p>
              <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                {{ formatCurrency(statistics.available_balance) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
              <WalletIcon class="w-6 h-6 text-primary-600 dark:text-primary-400" />
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('chef.total_earnings') }}</p>
              <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                {{ formatCurrency(statistics.total_earnings) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('chef.total_withdrawn') }}</p>
              <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                {{ formatCurrency(statistics.total_withdrawn) }}
              </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Withdrawal Form -->
        <ComponentCard :title="t('chef.request_withdrawal')">
          <form @submit.prevent="submitWithdrawal" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.amount') }} *
              </label>
              <input
                v-model="withdrawForm.amount"
                type="number"
                step="0.01"
                min="10"
                :max="statistics.available_balance"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                required
              />
              <p v-if="withdrawForm.errors.amount" class="mt-1 text-sm text-red-600">{{ withdrawForm.errors.amount }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.withdrawal_method') }} *
              </label>
              <select
                v-model="withdrawForm.withdrawal_method_id"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                required
              >
                <option :value="null">{{ t('common.select') }}</option>
                <option v-for="method in withdrawal_methods" :key="method.id" :value="method.id">
                  {{ method.name }}
                </option>
              </select>
              <p v-if="withdrawForm.errors.withdrawal_method_id" class="mt-1 text-sm text-red-600">{{ withdrawForm.errors.withdrawal_method_id }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.payment_details') }} *
              </label>
              <textarea
                v-model="withdrawForm.payment_details"
                rows="3"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                :placeholder="t('chef.payment_details_placeholder')"
                required
              ></textarea>
              <p v-if="withdrawForm.errors.payment_details" class="mt-1 text-sm text-red-600">{{ withdrawForm.errors.payment_details }}</p>
            </div>

            <p v-if="withdrawForm.errors.wallet" class="text-sm text-red-600">{{ withdrawForm.errors.wallet }}</p>
            <p v-if="withdrawForm.errors.withdrawal" class="text-sm text-red-600">{{ withdrawForm.errors.withdrawal }}</p>

            <button
              type="submit"
              :disabled="withdrawForm.processing || statistics.available_balance < 10"
              class="w-full px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 disabled:opacity-50 transition-colors"
            >
              {{ withdrawForm.processing ? t('common.processing') : t('chef.submit_withdrawal') }}
            </button>
          </form>
        </ComponentCard>

        <!-- Pending Withdrawals -->
        <ComponentCard :title="t('chef.pending_withdrawals')">
          <div v-if="pending_withdrawals?.length" class="space-y-3">
            <div
              v-for="withdrawal in pending_withdrawals"
              :key="withdrawal.id"
              class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800"
            >
              <div class="flex justify-between items-start">
                <div>
                  <p class="font-semibold text-gray-900 dark:text-white">
                    {{ formatCurrency(withdrawal.amount) }}
                  </p>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ withdrawal.method?.name }}
                  </p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                  {{ t('chef.pending') }}
                </span>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                {{ formatDate(withdrawal.created_at) }}
              </p>
            </div>
          </div>
          <p v-else class="text-gray-500 dark:text-gray-400 text-center py-8">
            {{ t('chef.no_pending_withdrawals') }}
          </p>
        </ComponentCard>
      </div>

      <!-- Recent Transactions -->
      <ComponentCard :title="t('chef.recent_transactions')">
        <div class="flex justify-end mb-4">
          <Link
            :href="route('chef.wallet.transactions')"
            class="text-primary-600 hover:text-primary-700 text-sm"
          >
            {{ t('common.view_all') }}
          </Link>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th class="px-6 py-3">{{ t('chef.date') }}</th>
                <th class="px-6 py-3">{{ t('chef.description') }}</th>
                <th class="px-6 py-3">{{ t('chef.type') }}</th>
                <th class="px-6 py-3">{{ t('chef.amount') }}</th>
                <th class="px-6 py-3">{{ t('chef.balance') }}</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="transaction in recent_transactions"
                :key="transaction.id"
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
              >
                <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                  {{ formatDate(transaction.created_at) }}
                </td>
                <td class="px-6 py-4 text-gray-900 dark:text-white">
                  {{ transaction.description }}
                </td>
                <td class="px-6 py-4">
                  <span
                    :class="transaction.type === 'credit' 
                      ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' 
                      : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'"
                    class="px-2.5 py-0.5 rounded-full text-xs font-medium"
                  >
                    {{ transaction.type === 'credit' ? t('chef.credit') : t('chef.debit') }}
                  </span>
                </td>
                <td class="px-6 py-4" :class="transaction.type === 'credit' ? 'text-green-600' : 'text-red-600'">
                  {{ transaction.type === 'credit' ? '+' : '' }}{{ formatCurrency(transaction.amount) }}
                </td>
                <td class="px-6 py-4 text-gray-900 dark:text-white">
                  {{ formatCurrency(transaction.balance) }}
                </td>
              </tr>
              <tr v-if="!recent_transactions?.length">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                  {{ t('chef.no_transactions') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </ComponentCard>
    </div>
  </ChefLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'
import { WalletIcon } from '@/icons'

const { t, locale } = useI18n()
const page = usePage()

const wallet = computed(() => page.props.wallet || {})
const statistics = computed(() => page.props.statistics || {})
const recent_transactions = computed(() => page.props.recent_transactions || [])
const pending_withdrawals = computed(() => page.props.pending_withdrawals || [])
const withdrawal_methods = computed(() => page.props.withdrawal_methods || [])

const withdrawForm = useForm({
  amount: '',
  withdrawal_method_id: null,
  payment_details: '',
})

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-SA', {
    style: 'currency',
    currency: 'SAR',
  }).format(amount || 0)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-GB', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

const submitWithdrawal = () => {
  withdrawForm.post(route('chef.wallet.withdraw'), {
    onSuccess: () => {
      withdrawForm.reset()
    },
  })
}
</script>
