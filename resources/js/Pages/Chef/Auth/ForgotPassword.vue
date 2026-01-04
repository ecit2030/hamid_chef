<template>
  <FullScreenLayout>
    <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0 font-cairo">
      <div class="relative flex flex-col justify-center w-full min-h-screen lg:flex-row dark:bg-gray-900">
        <div class="flex flex-col flex-1 w-full lg:w-1/2">
          <div class="w-full max-w-md pt-10 mx-auto">
            <div class="flex items-center justify-between">
              <Link
                :href="route('chef.login')"
                class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ t('common.back') }}
              </Link>
            </div>
          </div>
          <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
            <div>
              <div class="mb-5 sm:mb-8">
                <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                  {{ t('chef.auth.forgotPassword.title') }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ t('chef.auth.forgotPassword.subtitle') }}
                </p>
              </div>

              <!-- Status Message -->
              <div v-if="status" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400">
                {{ status }}
              </div>

              <form @submit.prevent="handleSubmit">
                <div class="space-y-5">
                  <!-- Email -->
                  <div>
                    <label
                      for="email"
                      class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                    >
                      {{ t('auth.login.email') }}<span class="text-error-500">*</span>
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      id="email"
                      name="email"
                      :placeholder="t('auth.login.emailPlaceholder')"
                      class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-error-500">
                      {{ form.errors.email }}
                    </p>
                  </div>

                  <!-- Button -->
                  <div>
                    <button
                      type="submit"
                      :disabled="form.processing"
                      class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      <svg v-if="form.processing" class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ form.processing ? t('common.loading') : t('chef.auth.forgotPassword.submit') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="relative items-center hidden w-full h-full lg:w-1/2 bg-brand-950 dark:bg-white/5 lg:grid">
          <div class="flex items-center justify-center z-1">
            <common-grid-shape />
            <div class="flex flex-col items-center max-w-xs">
              <Link href="/" class="block mb-4">
                <img width="231" height="48" src="/images/logo/auth-logo.svg" alt="Logo" />
              </Link>
              <p class="text-center text-gray-400 dark:text-white/60">
                {{ t('chef.auth.login.tagline') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </FullScreenLayout>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import { route } from '@/route'
import CommonGridShape from '@/Components/common/CommonGridShape.vue'
import FullScreenLayout from '@/Components/layout/FullScreenLayout.vue'
import { useI18n } from 'vue-i18n'

defineProps<{
  status?: string
}>()

const form = useForm({
  email: '',
})

const { t } = useI18n()

const handleSubmit = () => {
  form.post(route('chef.password.email'))
}
</script>
