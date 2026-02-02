<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="$t('discountCodes.create')" />

        <div class="space-y-6">
            <!-- Form -->
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.code") }} *
                        </label>
                        <input
                            v-model="form.code"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.code }"
                            required
                        />
                        <p v-if="form.errors.code" class="mt-1 text-sm text-error-600">
                            {{ form.errors.code }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.description") }}
                        </label>
                        <input
                            v-model="form.description"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.description }"
                        />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-error-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.type") }} *
                        </label>
                        <select
                            v-model="form.type"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.type }"
                            required
                        >
                            <option value="percentage">{{ $t('discountCodes.percentage') }}</option>
                            <option value="fixed">{{ $t('discountCodes.fixed') }}</option>
                        </select>
                        <p v-if="form.errors.type" class="mt-1 text-sm text-error-600">
                            {{ form.errors.type }}
                        </p>
                    </div>

                    <!-- Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.value") }} *
                        </label>
                        <input
                            v-model="form.value"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.value }"
                            required
                        />
                        <p v-if="form.errors.value" class="mt-1 text-sm text-error-600">
                            {{ form.errors.value }}
                        </p>
                    </div>

                    <!-- Min Order Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.minOrderAmount") }}
                        </label>
                        <input
                            v-model="form.min_order_amount"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.min_order_amount }"
                        />
                        <p v-if="form.errors.min_order_amount" class="mt-1 text-sm text-error-600">
                            {{ form.errors.min_order_amount }}
                        </p>
                    </div>

                    <!-- Max Discount Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.maxDiscountAmount") }}
                        </label>
                        <input
                            v-model="form.max_discount_amount"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.max_discount_amount }"
                        />
                        <p v-if="form.errors.max_discount_amount" class="mt-1 text-sm text-error-600">
                            {{ form.errors.max_discount_amount }}
                        </p>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.startDate") }} *
                        </label>
                        <input
                            v-model="form.start_date"
                            type="date"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.start_date }"
                            required
                        />
                        <p v-if="form.errors.start_date" class="mt-1 text-sm text-error-600">
                            {{ form.errors.start_date }}
                        </p>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.endDate") }} *
                        </label>
                        <input
                            v-model="form.end_date"
                            type="date"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.end_date }"
                            required
                        />
                        <p v-if="form.errors.end_date" class="mt-1 text-sm text-error-600">
                            {{ form.errors.end_date }}
                        </p>
                    </div>

                    <!-- Usage Limit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.usageLimit") }}
                        </label>
                        <input
                            v-model="form.usage_limit"
                            type="number"
                            min="1"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.usage_limit }"
                            placeholder="غير محدود"
                        />
                        <p v-if="form.errors.usage_limit" class="mt-1 text-sm text-error-600">
                            {{ form.errors.usage_limit }}
                        </p>
                    </div>

                    <!-- Per User Limit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("discountCodes.perUserLimit") }} *
                        </label>
                        <input
                            v-model="form.per_user_limit"
                            type="number"
                            min="1"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.per_user_limit }"
                            required
                        />
                        <p v-if="form.errors.per_user_limit" class="mt-1 text-sm text-error-600">
                            {{ form.errors.per_user_limit }}
                        </p>
                    </div>

                    <!-- Is Active -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input
                                v-model="form.is_active"
                                type="checkbox"
                                class="rounded border-gray-300 text-brand-600 focus:ring-brand-500"
                            />
                            <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $t("discountCodes.isActive") }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4 mt-6">
                    <Link
                        :href="route('admin.discount-codes.index')"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        {{ $t('common.cancel') }}
                    </Link>
                    <button
                        type="submit"
                        class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600"
                        :disabled="form.processing"
                    >
                        {{ $t('common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AdminLayout from '@/Components/layout/AdminLayout.vue';
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue';

const { t } = useI18n();

const form = useForm({
    code: '',
    description: '',
    type: 'percentage',
    value: 0,
    min_order_amount: null,
    max_discount_amount: null,
    start_date: '',
    end_date: '',
    usage_limit: null,
    per_user_limit: 1,
    is_active: true,
});

const submit = () => {
    form.post(route('admin.discount-codes.store'));
};
</script>
