<template>
    <AdminLayout>
        <div class="p-6 max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $t('discountCodes.details') }}
                </h1>
                <div class="flex gap-2">
                    <Link
                        :href="route('admin.discount-codes.edit', code.id)"
                        class="btn-primary"
                    >
                        {{ $t('common.edit') }}
                    </Link>
                    <Link
                        :href="route('admin.discount-codes.index')"
                        class="btn-secondary"
                    >
                        {{ $t('common.back') }}
                    </Link>
                </div>
            </div>

            <!-- Code Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $t('discountCodes.codeInfo') }}
                </h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.code') }}
                        </label>
                        <p class="mt-1 text-lg font-mono text-gray-900 dark:text-white">
                            {{ code.code }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.status') }}
                        </label>
                        <p class="mt-1">
                            <span v-if="code.status === 'active'" class="badge-success">
                                {{ $t('discountCodes.active') }}
                            </span>
                            <span v-else-if="code.status === 'expired'" class="badge-danger">
                                {{ $t('discountCodes.expired') }}
                            </span>
                            <span v-else-if="code.status === 'upcoming'" class="badge-warning">
                                {{ $t('discountCodes.upcoming') }}
                            </span>
                            <span v-else-if="code.status === 'exhausted'" class="badge-secondary">
                                {{ $t('discountCodes.exhausted') }}
                            </span>
                            <span v-else class="badge-secondary">
                                {{ $t('discountCodes.inactive') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.description') }}
                        </label>
                        <p class="mt-1 text-gray-900 dark:text-white">
                            {{ code.description || '-' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.type') }}
                        </label>
                        <p class="mt-1 text-gray-900 dark:text-white">
                            <span v-if="code.type === 'percentage'">
                                {{ $t('discountCodes.percentage') }} - {{ code.value }}%
                            </span>
                            <span v-else>
                                {{ $t('discountCodes.fixed') }} - {{ code.value }} {{ $t('common.currency') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.validity') }}
                        </label>
                        <p class="mt-1 text-gray-900 dark:text-white">
                            {{ code.start_date }} - {{ code.end_date }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.minOrderAmount') }}
                        </label>
                        <p class="mt-1 text-gray-900 dark:text-white">
                            {{ code.min_order_amount || '-' }} {{ code.min_order_amount ? $t('common.currency') : '' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $t('discountCodes.maxDiscountAmount') }}
                        </label>
                        <p class="mt-1 text-gray-900 dark:text-white">
                            {{ code.max_discount_amount || '-' }} {{ code.max_discount_amount ? $t('common.currency') : '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $t('discountCodes.statistics') }}
                </h2>
                <div class="grid grid-cols-4 gap-6">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-primary-600">
                            {{ statistics.total_usages }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $t('discountCodes.totalUsages') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">
                            {{ statistics.total_discount_amount }} {{ $t('common.currency') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $t('discountCodes.totalDiscount') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-blue-600">
                            {{ statistics.unique_users }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $t('discountCodes.uniqueUsers') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-orange-600">
                            {{ code.remaining_usages }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $t('discountCodes.remainingUsages') }}
                        </p>
                    </div>
                </div>

                <!-- Recent Usages -->
                <div v-if="statistics.recent_usages && statistics.recent_usages.length > 0" class="mt-8">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $t('discountCodes.recentUsages') }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ $t('discountCodes.user') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ $t('discountCodes.booking') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ $t('discountCodes.originalAmount') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ $t('discountCodes.discountAmount') }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        {{ $t('discountCodes.usedAt') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="usage in statistics.recent_usages" :key="usage.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ usage.user?.first_name }} {{ usage.user?.last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        #{{ usage.booking_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ usage.original_amount }} {{ $t('common.currency') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                        -{{ usage.discount_amount }} {{ $t('common.currency') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ usage.used_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AdminLayout from '@/Components/layout/AdminLayout.vue';

const { t: $t } = useI18n();

defineProps({
    code: Object,
    statistics: Object,
});
</script>
