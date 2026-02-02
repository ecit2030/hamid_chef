<template>
    <AdminLayout>
        <PageBreadcrumb :pageTitle="currentPageTitle" />
        <div class="space-y-5 sm:space-y-6">
            <ComponentCard :title="currentPageTitle">
                <ShowTermsAndConditions :terms="terms" />
            </ComponentCard>
        </div>
    </AdminLayout>
</template>

<script setup>
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'
import ShowTermsAndConditions from '@/Components/admin/terms-and-conditions/ShowTermsAndConditions.vue'

import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const currentPageTitle = computed(() => t('terms_and_conditions.title'))

const terms = computed(() => usePage().props.terms)
</script>
