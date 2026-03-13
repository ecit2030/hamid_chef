<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="pageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="pageTitle" :desc="t('landing_page.banners.description')">
        <BannersSection :section="section" @refresh="refreshSection" />
      </ComponentCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import BannersSection from '@/Components/admin/landing-page-section/Banners.vue'

const { t } = useI18n()

defineProps({
  section: { type: Object, required: true },
})

const pageTitle = computed(() => t('menu.landing_banners') || 'البانرات')

function refreshSection() {
  router.reload()
}
</script>
