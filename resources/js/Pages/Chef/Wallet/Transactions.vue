<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('chef.transactions')" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="t('chef.transactions')">
        <!-- Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
          <select
            v-model="selectedType"
            @change="applyFilter"
            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
          >
            <option :value="null">{{ t('chef.all_types') }}</option>
            <option value="credit">{{ t('chef.credit') }}</option>
            <option value="debit">{{ t('chef.debit') }}</option>
          </select>
        </div>

        <!-- Transactions Table -->
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
                v-for="transaction in transactions.data"
                :key="transaction.id"
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
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
              <tr v-if="!transactions.data?.length">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                  {{ t('chef.no_transactions') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="transactions.links?.length > 3" class="mt-4 flex justify-center">
          <nav class="flex items-center gap-1">
            <template v-for="link in transactions.links" :key="link.label">
              <Link
                v-if="link.url"
                :href="link.url"
                :class="[
                  'px-3 py-2 rounded-lg text-sm',
                  link.active 
                    ? 'bg-primary-600 text-white' 
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                ]"
                v-html="link.label"
              />
              <span
                v-else
                class="px-3 py-2 text-sm text-gray-400"
                v-html="link.label"
              />
            </template>
          </nav>
        </div>
      </ComponentCard>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, router, usePage } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'

const { t, locale } = useI18n()
const page = usePage()

const transactions = computed(() => page.props.transactions || { data: [] })
const filters = computed(() => page.props.filters || {})

const selectedType = ref(filters.value.type || null)

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

const applyFilter = () => {
  router.get(route('chef.wallet.transactions'), {
    type: selectedType.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}
</script>
