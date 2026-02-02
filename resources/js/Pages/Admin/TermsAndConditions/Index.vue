<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="$t('terms_and_conditions.title')" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <Link
                    :href="route('admin.terms-and-conditions.create')"
                    class="flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    {{ $t("terms_and_conditions.create_new") }}
                </Link>
            </div>

            <!-- Terms List -->
            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("terms_and_conditions.version") }}
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("terms_and_conditions.title_ar") }}
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("terms_and_conditions.title_en") }}
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("terms_and_conditions.effective_date") }}
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("common.status") }}
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">
                                    {{ $t("common.actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="term in terms.data" :key="term.id" class="border-b border-gray-100 dark:border-gray-800">
                                <td class="px-4 py-3 text-gray-800 dark:text-white">
                                    {{ term.version }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-white">
                                    {{ term.title_ar }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-white">
                                    {{ term.title_en }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ formatDate(term.effective_date) }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :color="term.is_active ? 'success' : 'error'" size="sm">
                                        {{ term.is_active ? $t("common.active") : $t("common.inactive") }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <Link
                                            :href="route('admin.terms-and-conditions.edit', term.id)"
                                            class="text-brand-500 hover:text-brand-600"
                                        >
                                            {{ $t("common.edit") }}
                                        </Link>

                                        <button
                                            v-if="!term.is_active"
                                            @click="activate(term.id)"
                                            class="text-success-500 hover:text-success-600"
                                        >
                                            {{ $t("common.activate") }}
                                        </button>

                                        <button
                                            v-if="term.is_active"
                                            @click="deactivate(term.id)"
                                            class="text-warning-500 hover:text-warning-600"
                                        >
                                            {{ $t("common.deactivate") }}
                                        </button>

                                        <button
                                            @click="deleteTerms(term.id)"
                                            class="text-error-500 hover:text-error-600"
                                        >
                                            {{ $t("common.delete") }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!terms.data?.length">
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    {{ $t("common.no_data") }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="terms.last_page > 1" class="flex items-center justify-center gap-2 border-t border-gray-200 p-4 dark:border-gray-700">
                    <button
                        v-for="page in terms.last_page"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            'h-8 w-8 rounded-lg text-sm',
                            page === terms.current_page
                                ? 'bg-brand-500 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300',
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Components/layout/AdminLayout.vue";
import PageBreadcrumb from "@/Components/common/PageBreadcrumb.vue";
import Badge from "@/Components/ui/Badge.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps({
    terms: Object,
});

const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("ar-SA");
};

const goToPage = (page) => {
    router.get(
        route("admin.terms-and-conditions.index"),
        { page },
        { preserveState: true },
    );
};

const activate = (id) => {
    if (confirm(t("terms_and_conditions.confirm_activate"))) {
        router.patch(route("admin.terms-and-conditions.activate", id));
    }
};

const deactivate = (id) => {
    if (confirm(t("terms_and_conditions.confirm_deactivate"))) {
        router.patch(route("admin.terms-and-conditions.deactivate", id));
    }
};

const deleteTerms = (id) => {
    if (confirm(t("terms_and_conditions.confirm_delete"))) {
        router.delete(route("admin.terms-and-conditions.destroy", id));
    }
};
</script>
