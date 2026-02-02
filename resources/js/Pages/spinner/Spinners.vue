<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard title="Logo Spinner - Light & Dark Mode">
        <div class="flex flex-col gap-8">
          <!-- Light Background -->
          <div class="p-8 bg-white dark:bg-gray-800 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Light Mode</h3>
            <LogoSpinner />
          </div>

          <!-- Dark Background -->
          <div class="p-8 bg-gray-900 rounded-lg">
            <h3 class="text-sm font-medium text-gray-300 mb-4">Dark Mode</h3>
            <LogoSpinner />
          </div>

          <!-- Different Sizes -->
          <div class="p-8 bg-white dark:bg-gray-800 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Different Sizes</h3>
            <div class="flex items-center gap-8 flex-wrap">
              <div class="flex flex-col items-center gap-2">
                <LogoSpinner :size="60" :stroke-width="3" :logo-padding="15" />
                <span class="text-xs text-gray-500">Small (60px)</span>
              </div>
              <div class="flex flex-col items-center gap-2">
                <LogoSpinner :size="80" :stroke-width="4" :logo-padding="20" />
                <span class="text-xs text-gray-500">Medium (80px)</span>
              </div>
              <div class="flex flex-col items-center gap-2">
                <LogoSpinner :size="120" :stroke-width="5" :logo-padding="30" />
                <span class="text-xs text-gray-500">Large (120px)</span>
              </div>
            </div>
          </div>
        </div>
      </ComponentCard>

      <ComponentCard title="Original Spinner 3">
        <SpinnerThree />
      </ComponentCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import AdminLayout from '@/Components/layout/AdminLayout.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'
import LogoSpinner from '@/Components/spinner/LogoSpinner.vue'
import SpinnerThree from '@/Components/spinner/SpinnerThree.vue'
const currentPageTitle = ref('Spinners')
</script>

<style></style>
