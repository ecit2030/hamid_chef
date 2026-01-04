<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('chef.edit_vacation')" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="t('chef.edit_vacation')">
        <form @submit.prevent="submit" class="space-y-6 max-w-xl">
          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.date') }} *
            </label>
            <input
              v-model="form.date"
              type="date"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            />
            <div v-if="form.errors.date" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
              <p class="text-sm text-red-600 dark:text-red-400 whitespace-pre-line">{{ form.errors.date }}</p>
            </div>
          </div>

          <!-- Note -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.note') }}
            </label>
            <textarea
              v-model="form.note"
              rows="3"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              :placeholder="t('chef.vacation_note_placeholder')"
            ></textarea>
            <p v-if="form.errors.note" class="mt-1 text-sm text-red-600">{{ form.errors.note }}</p>
          </div>

          <!-- Status -->
          <div>
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
              />
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ t('common.active') }}
              </span>
            </label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3">
            <Link
              :href="route('chef.vacations.index')"
              class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600"
            >
              {{ t('common.cancel') }}
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 disabled:opacity-50 transition-colors"
            >
              {{ form.processing ? t('common.saving') : t('common.save') }}
            </button>
          </div>
        </form>
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

const { t } = useI18n()
const page = usePage()

const vacation = computed(() => page.props.vacation)

const form = useForm({
  date: vacation.value?.date || '',
  note: vacation.value?.note || '',
  is_active: vacation.value?.is_active ?? true,
})

const submit = () => {
  form.put(route('chef.vacations.update', vacation.value.id))
}
</script>
