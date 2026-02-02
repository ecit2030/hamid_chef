<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="$t('terms_and_conditions.create_new')" />

        <div class="space-y-6">
            <!-- Form -->
            <form @submit.prevent="submit" class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title Arabic -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("terms_and_conditions.title_ar") }} *
                        </label>
                        <input
                            v-model="form.title_ar"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.title_ar }"
                        />
                        <p v-if="form.errors.title_ar" class="mt-1 text-sm text-error-600">
                            {{ form.errors.title_ar }}
                        </p>
                    </div>

                    <!-- Title English -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("terms_and_conditions.title_en") }} *
                        </label>
                        <input
                            v-model="form.title_en"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.title_en }"
                        />
                        <p v-if="form.errors.title_en" class="mt-1 text-sm text-error-600">
                            {{ form.errors.title_en }}
                        </p>
                    </div>

                    <!-- Version -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("terms_and_conditions.version") }} *
                        </label>
                        <input
                            v-model="form.version"
                            type="text"
                            placeholder="1.0"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            :class="{ 'border-error-500': form.errors.version }"
                        />
                        <p v-if="form.errors.version" class="mt-1 text-sm text-error-600">
                            {{ form.errors.version }}
                        </p>
                    </div>

                    <!-- Effective Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $t("terms_and_conditions.effective_date") }}
                        </label>
                        <input
                            v-model="form.effective_date"
                            type="date"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        />
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
                                {{ $t("terms_and_conditions.set_as_active") }}
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Content Arabic -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $t("terms_and_conditions.content_ar") }} *
                    </label>
                    <textarea
                        v-model="form.content_ar"
                        rows="10"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        :class="{ 'border-error-500': form.errors.content_ar }"
                    ></textarea>
                    <p v-if="form.errors.content_ar" class="mt-1 text-sm text-error-600">
                        {{ form.errors.content_ar }}
                    </p>
                </div>

                <!-- Content English -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $t("terms_and_conditions.content_en") }} *
                    </label>
                    <textarea
                        v-model="form.content_en"
                        rows="10"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        :class="{ 'border-error-500': form.errors.content_en }"
                    ></textarea>
                    <p v-if="form.errors.content_en" class="mt-1 text-sm text-error-600">
                        {{ form.errors.content_en }}
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-start gap-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-brand-500 px-6 py-2 text-white hover:bg-brand-600 disabled:opacity-50"
                    >
                        {{ form.processing ? $t("common.saving") : $t("common.save") }}
                    </button>
                    <Link
                        :href="route('admin.terms-and-conditions.index')"
                        class="rounded-lg border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        {{ $t("common.cancel") }}
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Components/layout/AdminLayout.vue";
import PageBreadcrumb from "@/Components/common/PageBreadcrumb.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const form = useForm({
    title_ar: "",
    title_en: "",
    content_ar: "",
    content_en: "",
    version: "1.0",
    effective_date: new Date().toISOString().split("T")[0],
    is_active: true,
});

const submit = () => {
    form.post(route("admin.terms-and-conditions.store"));
};
</script>
