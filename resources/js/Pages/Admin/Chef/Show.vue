<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <!-- Tabs Navigation -->
      <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 dark:border-gray-800">
          <nav class="flex gap-2 px-4" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'relative px-4 py-4 text-sm font-medium transition-colors',
                activeTab === tab.id
                  ? 'text-brand-600 dark:text-brand-400'
                  : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'
              ]"
            >
              {{ tab.label }}
              <span
                v-if="activeTab === tab.id"
                class="absolute bottom-0 left-0 right-0 h-0.5 bg-brand-600 dark:bg-brand-400"
              ></span>
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Basic Information Tab -->
          <div v-show="activeTab === 'info'">
            <ShowChef :chef="chef" />
          </div>

          <!-- Working Hours Tab -->
          <div v-show="activeTab === 'working-hours'">
            <ChefWorkingHoursTab :workingHours="workingHours" />
          </div>

          <!-- Vacations Tab -->
          <div v-show="activeTab === 'vacations'">
            <ChefVacationsTab :vacations="vacations" />
          </div>

          <!-- Services Tab -->
          <div v-show="activeTab === 'services'">
            <ChefServicesTab :services="services" />
          </div>

          <!-- Bookings Tab -->
          <div v-show="activeTab === 'bookings'">
            <ChefBookingsTab :bookings="bookings" />
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
        <Link 
          :href="route('admin.chefs.index')" 
          class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]"
        >
          {{ t('buttons.backToList') }}
        </Link>

        <Link 
          :href="route('admin.chefs.edit', chef.id)" 
          class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition"
        >
          {{ t('buttons.edit') }}
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import ShowChef from '@/Components/admin/chef/ShowChef.vue'
import ChefWorkingHoursTab from '@/Components/admin/chef/ChefWorkingHoursTab.vue'
import ChefVacationsTab from '@/Components/admin/chef/ChefVacationsTab.vue'
import ChefServicesTab from '@/Components/admin/chef/ChefServicesTab.vue'
import ChefBookingsTab from '@/Components/admin/chef/ChefBookingsTab.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { computed, ref } from 'vue'

const { t } = useI18n()
const currentPageTitle = computed(() => t('chefs.showChef'))

const chef = computed(() => usePage().props.chef)
const workingHours = computed(() => usePage().props.workingHours)
const vacations = computed(() => usePage().props.vacations)
const services = computed(() => usePage().props.services)
const bookings = computed(() => usePage().props.bookings)

const activeTab = ref('info')

const tabs = computed(() => [
  { id: 'info', label: t('chefs.basicInformation') },
  { id: 'working-hours', label: t('chefs.workingHours') },
  { id: 'vacations', label: t('chefs.vacations') },
  { id: 'services', label: t('chefs.services') },
  { id: 'bookings', label: t('chefs.bookings') },
])

</script>
