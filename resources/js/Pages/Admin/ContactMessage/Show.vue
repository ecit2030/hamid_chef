<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="currentPageTitle">
        <div class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ t('contactMessages.name', 'الاسم') }}</label>
              <p class="text-gray-900 dark:text-gray-100 font-medium">{{ message.name }}</p>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ t('contactMessages.email', 'البريد الإلكتروني') }}</label>
              <a :href="`mailto:${message.email}`" class="text-primary hover:underline">{{ message.email }}</a>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ t('contactMessages.phone', 'رقم الهاتف') }}</label>
              <a v-if="message.phone" :href="`tel:${message.phone}`" class="text-primary hover:underline">{{ message.phone }}</a>
              <span v-else class="text-gray-400">-</span>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ t('contactMessages.date', 'التاريخ') }}</label>
              <p class="text-gray-800 dark:text-gray-200">{{ formatDate(message.created_at) }}</p>
            </div>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">{{ t('contactMessages.message', 'الرسالة') }}</label>
            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 whitespace-pre-wrap">
              {{ message.message }}
            </div>
          </div>
          <div class="flex gap-3 pt-4">
            <Link
              :href="route('admin.contact-messages.index')"
              class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
              {{ t('common.back') }}
            </Link>
            <button
              v-if="canDelete"
              @click="confirmDelete"
              type="button"
              class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-800 dark:bg-gray-800 dark:text-red-400"
            >
              {{ t('common.delete') }}
            </button>
          </div>
        </div>
      </ComponentCard>
    </div>

    <DangerAlert
      :isOpen="isDeleteModalOpen"
      :title="t('messages.areYouSure')"
      :message="t('contactMessages.deleteConfirm', 'هل أنت متأكد من حذف هذه الرسالة؟')"
      @close="isDeleteModalOpen = false"
      @confirm="doDelete"
    />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'
import DangerAlert from '@/Components/modals/DangerAlert.vue'
import { usePermissions } from '@/composables/usePermissions'

const { t } = useI18n()
const { hasAnyPermission } = usePermissions()

const props = defineProps({
  message: { type: Object, required: true }
})

const canDelete = hasAnyPermission(['contact-messages.delete', 'contact-messages.destroy'])
const isDeleteModalOpen = ref(false)

const currentPageTitle = t('contactMessages.title', 'رسالة تواصل')

function formatDate(dateStr) {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleString('ar-SA', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

function confirmDelete() {
  isDeleteModalOpen.value = true
}

function doDelete() {
  router.delete(route('admin.contact-messages.destroy', props.message.id), {
    onSuccess: () => { isDeleteModalOpen.value = false },
    preserveScroll: false
  })
}
</script>
