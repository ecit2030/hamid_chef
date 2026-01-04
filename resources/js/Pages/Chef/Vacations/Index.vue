<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('menu.vacations')" />
    
    <!-- Success/Error Messages -->
    <div v-if="$page.props.flash?.success" class="mb-6">
      <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <p class="text-sm text-green-600 dark:text-green-400">{{ $page.props.flash.success }}</p>
      </div>
    </div>

    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="t('menu.vacations')">
        <!-- Header with Add Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <p class="text-gray-600 dark:text-gray-400">{{ t('chef.vacations_description') }}</p>
          <button
            @click="showAddModal = true"
            class="px-4 py-2.5 !bg-[#083064] hover:!bg-[#062650] text-white rounded-lg transition-colors font-medium flex items-center gap-2"
          >
            <span class="text-lg">+</span>
            {{ t('chef.add_vacation') }}
          </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('common.status') }}
            </label>
            <select
              v-model="filters.status"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
            >
              <option value="all">{{ t('chef.all_statuses') }}</option>
              <option value="active">{{ t('common.active') }}</option>
              <option value="inactive">{{ t('common.inactive') }}</option>
            </select>
          </div>

          <!-- Date From Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.from_date') }}
            </label>
            <input
              v-model="filters.dateFrom"
              type="date"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
            />
          </div>

          <!-- Date To Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.to_date') }}
            </label>
            <input
              v-model="filters.dateTo"
              type="date"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
            />
          </div>
        </div>

        <!-- Clear Filters Button -->
        <div v-if="hasActiveFilters" class="mb-4">
          <button
            @click="clearFilters"
            class="text-sm text-gray-600 dark:text-gray-400 hover:text-[#083064] dark:hover:text-[#083064] flex items-center gap-2"
          >
            <span>✕</span>
            {{ t('chef.clear_filters') }}
          </button>
        </div>

        <!-- Vacations Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ t('chef.date') }}
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ t('chef.day') }}
                </th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ t('chef.note') }}
                </th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ t('common.status') }}
                </th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                  {{ t('common.actions') }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="vacation in filteredVacations"
                :key="vacation.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ getDateEmoji(vacation.date) }}</span>
                    <div>
                      <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ formatDate(vacation.date) }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                  {{ getDayName(vacation.date) }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                  <span v-if="vacation.note" class="line-clamp-2">{{ vacation.note }}</span>
                  <span v-else class="text-gray-400 dark:text-gray-500">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <span
                    :class="[
                      'inline-flex px-3 py-1 rounded-full text-xs font-medium',
                      vacation.is_active
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                    ]"
                  >
                    {{ vacation.is_active ? t('common.active') : t('common.inactive') }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button
                      @click="startEdit(vacation)"
                      class="px-3 py-1.5 !bg-[#083064] hover:!bg-[#062650] text-white rounded-lg text-xs font-medium transition-colors"
                    >
                      {{ t('common.edit') }}
                    </button>
                    <button
                      @click="confirmDelete(vacation)"
                      class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium transition-colors"
                    >
                      {{ t('common.delete') }}
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredVacations.length === 0">
                <td colspan="5" class="px-6 py-12 text-center">
                  <div class="text-6xl mb-4">🏖️</div>
                  <p class="text-gray-500 dark:text-gray-400 text-lg">
                    {{ hasActiveFilters ? t('chef.no_vacations_found') : t('chef.no_vacations') }}
                  </p>
                  <p v-if="!hasActiveFilters" class="text-gray-400 dark:text-gray-500 text-sm mt-2">
                    {{ t('chef.add_first_vacation') }}
                  </p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination Info -->
        <div v-if="filteredVacations.length > 0" class="mt-4 text-sm text-gray-600 dark:text-gray-400">
          {{ t('chef.showing_vacations', { count: filteredVacations.length, total: vacations.length }) }}
        </div>
      </ComponentCard>
    </div>

    <!-- Add Vacation Modal -->
    <div
      v-if="showAddModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
      @click.self="closeAddModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="text-2xl">➕</span>
            {{ t('chef.add_vacation') }}
          </h3>
          <button
            @click="closeAddModal"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl"
          >
            ✕
          </button>
        </div>

        <form @submit.prevent="submitNewVacation" class="space-y-4">
          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.date') }} *
            </label>
            <input
              v-model="newVacationForm.date"
              type="date"
              :min="minDate"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
              required
            />
            <div v-if="newVacationForm.errors.date" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
              <p class="text-sm text-red-600 dark:text-red-400 whitespace-pre-line">{{ newVacationForm.errors.date }}</p>
            </div>
          </div>

          <!-- Note -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.note') }}
            </label>
            <textarea
              v-model="newVacationForm.note"
              rows="3"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
              :placeholder="t('chef.vacation_note_placeholder')"
            ></textarea>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeAddModal"
              class="flex-1 px-4 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium"
            >
              {{ t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="newVacationForm.processing"
              class="flex-1 px-4 py-2.5 !bg-[#083064] hover:!bg-[#062650] text-white rounded-lg transition-colors disabled:opacity-50 font-medium"
            >
              {{ newVacationForm.processing ? t('common.saving') : t('chef.add_vacation') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Vacation Modal -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
      @click.self="closeEditModal"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-lg w-full shadow-2xl">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="text-2xl">✏️</span>
            {{ t('chef.edit_vacation') }}
          </h3>
          <button
            @click="closeEditModal"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-2xl"
          >
            ✕
          </button>
        </div>

        <form @submit.prevent="submitEdit" class="space-y-4">
          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.date') }} *
            </label>
            <input
              v-model="editForm.date"
              type="date"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
              required
            />
            <div v-if="editForm.errors.date" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
              <p class="text-sm text-red-600 dark:text-red-400 whitespace-pre-line">{{ editForm.errors.date }}</p>
            </div>
          </div>

          <!-- Note -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.note') }}
            </label>
            <textarea
              v-model="editForm.note"
              rows="3"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-[#083064] focus:ring-[#083064]"
              :placeholder="t('chef.vacation_note_placeholder')"
            ></textarea>
          </div>

          <!-- Status -->
          <div>
            <label class="flex items-center gap-3 cursor-pointer">
              <input
                v-model="editForm.is_active"
                type="checkbox"
                class="w-5 h-5 rounded border-gray-300 text-[#083064] focus:ring-[#083064]"
              />
              <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ t('common.active') }}
              </span>
            </label>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeEditModal"
              class="flex-1 px-4 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium"
            >
              {{ t('common.cancel') }}
            </button>
            <button
              type="submit"
              :disabled="editForm.processing"
              class="flex-1 px-4 py-2.5 !bg-[#083064] hover:!bg-[#062650] text-white rounded-lg transition-colors disabled:opacity-50 font-medium"
            >
              {{ editForm.processing ? t('common.saving') : t('common.save') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
      @click.self="showDeleteModal = false"
    >
      <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full shadow-2xl">
        <div class="text-center mb-4">
          <div class="text-5xl mb-3">⚠️</div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            {{ t('chef.confirm_delete_vacation') }}
          </h3>
          <p class="text-gray-600 dark:text-gray-400">
            {{ t('chef.delete_vacation_warning') }}
          </p>
        </div>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2.5 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium"
          >
            {{ t('common.cancel') }}
          </button>
          <button
            @click="deleteVacation"
            :disabled="deleteForm.processing"
            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 font-medium"
          >
            {{ t('common.delete') }}
          </button>
        </div>
      </div>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm, usePage } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'

const { t } = useI18n()
const page = usePage()

const vacations = computed(() => page.props.vacations?.data || [])

// Filters
const filters = ref({
  status: 'all',
  dateFrom: '',
  dateTo: '',
})

// Check if filters are active
const hasActiveFilters = computed(() => {
  return filters.value.status !== 'all' || filters.value.dateFrom || filters.value.dateTo
})

// Clear filters
const clearFilters = () => {
  filters.value = {
    status: 'all',
    dateFrom: '',
    dateTo: '',
  }
}

// Filtered vacations
const filteredVacations = computed(() => {
  let result = [...vacations.value]

  // Filter by status
  if (filters.value.status !== 'all') {
    result = result.filter(v => {
      if (filters.value.status === 'active') return v.is_active
      if (filters.value.status === 'inactive') return !v.is_active
      return true
    })
  }

  // Filter by date from
  if (filters.value.dateFrom) {
    result = result.filter(v => new Date(v.date) >= new Date(filters.value.dateFrom))
  }

  // Filter by date to
  if (filters.value.dateTo) {
    result = result.filter(v => new Date(v.date) <= new Date(filters.value.dateTo))
  }

  // Sort by date (upcoming first)
  return result.sort((a, b) => new Date(a.date) - new Date(b.date))
})

// Forms
const newVacationForm = useForm({
  date: '',
  note: '',
})

const editForm = useForm({
  date: '',
  note: '',
  is_active: true,
})

const deleteForm = useForm({})

// State
const showAddModal = ref(false)
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const editingVacationId = ref(null)
const vacationToDelete = ref(null)

// Min date for new vacations (today)
const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

// Arabic month and day names
const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']
const arabicDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']

const formatDate = (date) => {
  const d = new Date(date)
  const day = d.getDate()
  const month = arabicMonths[d.getMonth()]
  const year = d.getFullYear()
  return `${day} ${month} ${year}`
}

const getDayName = (date) => {
  const d = new Date(date)
  return arabicDays[d.getDay()]
}

const getDateEmoji = (date) => {
  const d = new Date(date)
  const day = d.getDay()
  const emojis = ['🌞', '🌙', '⭐', '🌟', '✨', '🌺', '🏖️']
  return emojis[day]
}

// Add vacation
const closeAddModal = () => {
  showAddModal.value = false
  newVacationForm.reset()
  newVacationForm.clearErrors()
}

const submitNewVacation = () => {
  newVacationForm.post(route('chef.vacations.store'), {
    preserveScroll: true,
    onSuccess: () => {
      closeAddModal()
    },
  })
}

// Edit vacation
const startEdit = (vacation) => {
  editingVacationId.value = vacation.id
  editForm.date = vacation.date
  editForm.note = vacation.note || ''
  editForm.is_active = vacation.is_active
  editForm.clearErrors()
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editingVacationId.value = null
  editForm.reset()
  editForm.clearErrors()
}

const submitEdit = () => {
  editForm.put(route('chef.vacations.update', editingVacationId.value), {
    preserveScroll: true,
    onSuccess: () => {
      closeEditModal()
    },
  })
}

// Delete vacation
const confirmDelete = (vacation) => {
  vacationToDelete.value = vacation
  showDeleteModal.value = true
}

const deleteVacation = () => {
  deleteForm.delete(route('chef.vacations.destroy', vacationToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
      vacationToDelete.value = null
    },
  })
}
</script>
