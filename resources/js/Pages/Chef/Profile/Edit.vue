<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('menu.profile')" />
    <div class="space-y-5 sm:space-y-6">
      <!-- Profile Information -->
      <ComponentCard :title="t('chef.profile_information')">
        <form @submit.prevent="submitProfile" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.name') }} *
              </label>
              <input
                v-model="profileForm.name"
                type="text"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                required
              />
              <p v-if="profileForm.errors.name" class="mt-1 text-sm text-red-600">{{ profileForm.errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.email') }} *
              </label>
              <input
                v-model="profileForm.email"
                type="email"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                required
              />
              <p v-if="profileForm.errors.email" class="mt-1 text-sm text-red-600">{{ profileForm.errors.email }}</p>
            </div>

            <!-- Phone -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.phone') }} *
              </label>
              <input
                v-model="profileForm.phone"
                type="tel"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
                required
              />
              <p v-if="profileForm.errors.phone" class="mt-1 text-sm text-red-600">{{ profileForm.errors.phone }}</p>
            </div>

            <!-- Base Hourly Rate -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.base_hourly_rate') }}
              </label>
              <input
                v-model="profileForm.base_hourly_rate"
                type="number"
                step="0.01"
                min="0"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              />
            </div>
          </div>

          <!-- Short Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.short_description') }}
            </label>
            <textarea
              v-model="profileForm.short_description"
              rows="2"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            ></textarea>
          </div>

          <!-- Long Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.long_description') }}
            </label>
            <textarea
              v-model="profileForm.long_description"
              rows="4"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            ></textarea>
          </div>

          <!-- Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.address') }}
            </label>
            <input
              v-model="profileForm.address"
              type="text"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            />
          </div>

          <!-- Location Selects -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.governorate') }}
              </label>
              <select
                v-model="profileForm.governorate_id"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              >
                <option :value="null">{{ t('common.select') }}</option>
                <option v-for="gov in governorates" :key="gov.id" :value="gov.id">{{ gov.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.district') }}
              </label>
              <select
                v-model="profileForm.district_id"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              >
                <option :value="null">{{ t('common.select') }}</option>
                <option v-for="dist in filteredDistricts" :key="dist.id" :value="dist.id">{{ dist.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ t('chef.area') }}
              </label>
              <select
                v-model="profileForm.area_id"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              >
                <option :value="null">{{ t('common.select') }}</option>
                <option v-for="area in filteredAreas" :key="area.id" :value="area.id">{{ area.name }}</option>
              </select>
            </div>
          </div>

          <!-- Categories -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.categories') }}
            </label>
            <div class="flex flex-wrap gap-2">
              <label
                v-for="cat in categories"
                :key="cat.id"
                class="inline-flex items-center px-3 py-2 rounded-lg border cursor-pointer transition-colors"
                :class="profileForm.categories.includes(cat.id) 
                  ? 'bg-primary-100 border-primary-500 text-primary-700 dark:bg-primary-900 dark:text-primary-300' 
                  : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
              >
                <input
                  type="checkbox"
                  :value="cat.id"
                  v-model="profileForm.categories"
                  class="sr-only"
                />
                {{ cat.name }}
              </label>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="profileForm.processing"
              class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 disabled:opacity-50 transition-colors"
            >
              {{ profileForm.processing ? t('common.saving') : t('common.save') }}
            </button>
          </div>
        </form>
      </ComponentCard>

      <!-- Change Password -->
      <ComponentCard :title="t('chef.change_password')">
        <form @submit.prevent="submitPassword" class="space-y-6 max-w-xl">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.current_password') }} *
            </label>
            <input
              v-model="passwordForm.current_password"
              type="password"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            />
            <p v-if="passwordForm.errors.current_password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.current_password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.new_password') }} *
            </label>
            <input
              v-model="passwordForm.password"
              type="password"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            />
            <p v-if="passwordForm.errors.password" class="mt-1 text-sm text-red-600">{{ passwordForm.errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef.confirm_password') }} *
            </label>
            <input
              v-model="passwordForm.password_confirmation"
              type="password"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            />
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="passwordForm.processing"
              class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 disabled:opacity-50 transition-colors"
            >
              {{ passwordForm.processing ? t('common.saving') : t('chef.update_password') }}
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
import { useForm, usePage } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'

const { t } = useI18n()
const page = usePage()

const chef = computed(() => page.props.chef)
const user = computed(() => page.props.user)
const governorates = computed(() => page.props.governorates || [])
const districts = computed(() => page.props.districts || [])
const areas = computed(() => page.props.areas || [])
const categories = computed(() => page.props.categories || [])

const profileForm = useForm({
  name: chef.value?.name || '',
  email: chef.value?.email || '',
  phone: chef.value?.phone || '',
  short_description: chef.value?.short_description || '',
  long_description: chef.value?.long_description || '',
  address: chef.value?.address || '',
  governorate_id: chef.value?.governorate_id || null,
  district_id: chef.value?.district_id || null,
  area_id: chef.value?.area_id || null,
  base_hourly_rate: chef.value?.base_hourly_rate || '',
  categories: chef.value?.categories || [],
})

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const filteredDistricts = computed(() => {
  if (!profileForm.governorate_id) return []
  return districts.value.filter(d => d.governorate_id === profileForm.governorate_id)
})

const filteredAreas = computed(() => {
  if (!profileForm.district_id) return []
  return areas.value.filter(a => a.district_id === profileForm.district_id)
})

const submitProfile = () => {
  profileForm.patch(route('chef.profile.update'))
}

const submitPassword = () => {
  passwordForm.put(route('chef.password.update'), {
    onSuccess: () => {
      passwordForm.reset()
    },
  })
}
</script>
