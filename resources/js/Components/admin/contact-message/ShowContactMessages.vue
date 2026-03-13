<template>
  <div class="overflow-hidden">
    <div class="flex flex-col gap-2 px-4 py-4 border border-b-0 border-gray-200 rounded-b-none rounded-xl bg-white dark:border-gray-600 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-center gap-3">
        <span class="text-gray-700 dark:text-gray-300">{{ t('datatable.show') }}</span>
        <select
          v-model="perPage"
          @change="fetchPage"
          class="h-9 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-800 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-100 xl:w-20"
        >
          <option value="15">15</option>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <span class="text-gray-700 dark:text-gray-300">{{ t('datatable.entries') }}</span>
      </div>
      <div class="relative">
        <input
          v-model="search"
          type="text"
          :placeholder="t('datatable.searchPlaceholder')"
          class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-100 xl:w-[280px]"
          @input="debouncedSearch"
        />
      </div>
    </div>

    <div class="max-w-full overflow-x-auto">
      <table class="w-full min-w-full bg-white dark:bg-gray-900">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              #
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('contactMessages.name', 'الاسم') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('contactMessages.email', 'البريد') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('contactMessages.phone', 'الهاتف') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200 max-w-[200px]">
              {{ t('contactMessages.message', 'الرسالة') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('contactMessages.status', 'الحالة') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('contactMessages.date', 'التاريخ') }}
            </th>
            <th class="px-4 py-3 text-start border border-gray-200 dark:border-gray-600 text-xs font-medium text-gray-800 dark:text-gray-200">
              {{ t('common.actions') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(msg, idx) in contactMessages.data"
            :key="msg.id"
            :class="!msg.is_read ? 'bg-primary/5 dark:bg-primary/10' : ''"
            class="border-b border-gray-200 dark:border-gray-600"
          >
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200">
              {{ contactMessages.from + idx }}
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm font-medium text-gray-800 dark:text-gray-200">
              {{ msg.name }}
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200">
              <a :href="`mailto:${msg.email}`" class="text-primary hover:underline">{{ msg.email }}</a>
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200">
              <a v-if="msg.phone" :href="`tel:${msg.phone}`" class="text-primary hover:underline">{{ msg.phone }}</a>
              <span v-else class="text-gray-400">-</span>
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-800 dark:text-gray-200 max-w-[200px] truncate">
              {{ msg.message }}
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600">
              <span
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                :class="msg.is_read ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' : 'bg-primary/20 text-primary dark:bg-primary/30 dark:text-primary-300'"
              >
                {{ msg.is_read ? (t('contactMessages.read', 'مقروءة') || 'مقروءة') : (t('contactMessages.unread', 'غير مقروءة') || 'غير مقروءة') }}
              </span>
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400">
              {{ formatDate(msg.created_at) }}
            </td>
            <td class="px-4 py-3 border border-gray-200 dark:border-gray-600">
              <div class="flex items-center gap-2">
                <Link
                  :href="route('admin.contact-messages.show', msg.id)"
                  class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                  {{ t('common.view') }}
                </Link>
                <button
                  v-if="canDelete"
                  @click="confirmDelete(msg.id)"
                  type="button"
                  class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-white px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-800 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20"
                >
                  {{ t('common.delete') }}
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!contactMessages.data?.length">
            <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
              {{ t('contactMessages.noMessages', 'لا توجد رسائل') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="contactMessages.last_page > 1" class="flex items-center justify-between border border-t-0 rounded-b-xl border-gray-200 bg-white px-4 py-3 dark:border-gray-600 dark:bg-gray-800">
      <p class="text-sm text-gray-700 dark:text-gray-300">
        {{ t('datatable.showing', { start: contactMessages.from, end: contactMessages.to, total: contactMessages.total }) }}
      </p>
      <div class="flex gap-2">
        <Link
          v-if="contactMessages.current_page > 1"
          :href="buildUrl(contactMessages.current_page - 1)"
          class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
        >
          {{ t('datatable.previous') }}
        </Link>
        <Link
          v-if="contactMessages.current_page < contactMessages.last_page"
          :href="buildUrl(contactMessages.current_page + 1)"
          class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
        >
          {{ t('datatable.next') }}
        </Link>
      </div>
    </div>

    <DangerAlert
      :isOpen="isDeleteModalOpen"
      :title="t('messages.areYouSure')"
      :message="t('contactMessages.deleteConfirm', 'هل أنت متأكد من حذف هذه الرسالة؟')"
      @close="closeDeleteModal"
      @confirm="doDelete"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import DangerAlert from '@/Components/modals/DangerAlert.vue'
import { usePermissions } from '@/composables/usePermissions'

const { t } = useI18n()
const { hasAnyPermission } = usePermissions()

const props = defineProps({
  contactMessages: { type: Object, required: true }
})

const canDelete = hasAnyPermission(['contact-messages.delete', 'contact-messages.destroy'])

const perPage = ref(props.contactMessages.per_page ?? 15)
const search = ref('')
const isDeleteModalOpen = ref(false)
const idToDelete = ref(null)

let searchTimeout = null

watch(() => props.contactMessages, (val) => {
  perPage.value = val.per_page ?? 15
}, { deep: true })

function formatDate(dateStr) {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString('ar-SA', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function buildUrl(page) {
  const params = new URLSearchParams()
  params.set('page', page)
  if (perPage.value) params.set('per_page', perPage.value)
  if (search.value) params.set('search', search.value)
  return route('admin.contact-messages.index') + '?' + params.toString()
}

function fetchPage() {
  router.get(route('admin.contact-messages.index'), {
    page: 1,
    per_page: perPage.value,
    search: search.value || undefined
  }, { preserveState: true })
}

function debouncedSearch() {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('admin.contact-messages.index'), {
      page: 1,
      per_page: perPage.value,
      search: search.value || undefined
    }, { preserveState: true })
  }, 300)
}

function confirmDelete(id) {
  idToDelete.value = id
  isDeleteModalOpen.value = true
}

function closeDeleteModal() {
  isDeleteModalOpen.value = false
  idToDelete.value = null
}

function doDelete() {
  if (idToDelete.value) {
    router.delete(route('admin.contact-messages.destroy', idToDelete.value), {
      onSuccess: () => closeDeleteModal(),
      preserveScroll: true
    })
  }
}
</script>
