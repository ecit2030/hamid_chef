<template>
  <ChefLayout>
    <PageBreadcrumb :pageTitle="t('chef_kyc.edit_kyc')" />
    <div class="space-y-5 sm:space-y-6">
      <ComponentCard :title="t('chef_kyc.edit_kyc')">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Document Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.document_type') }} *
            </label>
            <select
              v-model="form.document_type"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            >
              <option value="">{{ t('common.select') }}</option>
              <option v-for="type in documentTypes" :key="type.value" :value="type.value">
                {{ type.label }}
              </option>
            </select>
            <p v-if="form.errors.document_type" class="mt-1 text-sm text-red-600">{{ form.errors.document_type }}</p>
          </div>

          <!-- Full Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.full_name') }} *
            </label>
            <input
              v-model="form.full_name"
              type="text"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            />
            <p v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">{{ form.errors.full_name }}</p>
          </div>

          <!-- Gender -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.gender') }} *
            </label>
            <select
              v-model="form.gender"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
              required
            >
              <option value="">{{ t('common.select') }}</option>
              <option value="male">{{ t('common.male') }}</option>
              <option value="female">{{ t('common.female') }}</option>
            </select>
            <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">{{ form.errors.gender }}</p>
          </div>

          <!-- Date of Birth -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.date_of_birth') }}
            </label>
            <input
              v-model="form.date_of_birth"
              type="date"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            />
            <p v-if="form.errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ form.errors.date_of_birth }}</p>
          </div>

          <!-- Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.address') }}
            </label>
            <textarea
              v-model="form.address"
              rows="3"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            ></textarea>
            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">{{ form.errors.address }}</p>
          </div>

          <!-- Current Document Scan -->
          <div v-if="kyc.document_scan_copy" class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ t('chef_kyc.current_document') }}</p>
            <img :src="`/storage/${kyc.document_scan_copy}`" class="h-32 object-contain rounded-lg" />
          </div>

          <!-- Document Scan Copy -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.upload_new_document') }}
            </label>
            <input
              type="file"
              @change="handleFileChange($event, 'document_scan_copy')"
              accept="image/*"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            />
            <p class="mt-1 text-xs text-gray-500">{{ t('chef_kyc.allowed_formats') }}</p>
            <p v-if="form.errors.document_scan_copy" class="mt-1 text-sm text-red-600">{{ form.errors.document_scan_copy }}</p>
            <img v-if="previews.document_scan_copy" :src="previews.document_scan_copy" class="mt-2 h-32 object-contain rounded-lg" />
          </div>

          <!-- Current Certificates -->
          <div v-if="kyc.certificates && kyc.certificates.length > 0" class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ t('chef_kyc.current_certificates') }}</p>
            <div class="flex flex-wrap gap-2">
              <img v-for="(cert, index) in kyc.certificates" :key="index" :src="`/storage/${cert}`" class="h-20 object-contain rounded-lg" />
            </div>
          </div>

          <!-- Certificates (Optional) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              {{ t('chef_kyc.upload_new_certificates') }}
            </label>
            <input
              type="file"
              @change="handleCertificatesChange"
              accept="image/*"
              multiple
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-gray-900 dark:text-white focus:border-primary-500 focus:ring-primary-500"
            />
            <p class="mt-1 text-xs text-gray-500">{{ t('chef_kyc.certificates_hint') }}</p>
            <p v-if="form.errors.certificates" class="mt-1 text-sm text-red-600">{{ form.errors.certificates }}</p>
            <div v-if="previews.certificates.length > 0" class="mt-2 flex flex-wrap gap-2">
              <img v-for="(preview, index) in previews.certificates" :key="index" :src="preview" class="h-20 object-contain rounded-lg" />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3">
            <Link
              :href="route('chef.kyc.index')"
              class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
            >
              {{ t('common.cancel') }}
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 disabled:opacity-50 transition-colors"
            >
              {{ form.processing ? t('common.saving') : t('common.update') }}
            </button>
          </div>
        </form>
      </ComponentCard>
    </div>
  </ChefLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, usePage, useForm } from '@inertiajs/vue3'
import ChefLayout from '@/Components/layout/ChefLayout.vue'
import PageBreadcrumb from '@/Components/common/PageBreadcrumb.vue'
import ComponentCard from '@/Components/common/ComponentCard.vue'

const { t } = useI18n()
const page = usePage()

const kyc = computed(() => page.props.kyc || {})
const documentTypes = computed(() => page.props.documentTypes || [])

const form = useForm({
  document_type: kyc.value.document_type || '',
  full_name: kyc.value.full_name || '',
  gender: kyc.value.gender || '',
  date_of_birth: kyc.value.date_of_birth || '',
  address: kyc.value.address || '',
  document_scan_copy: null,
  certificates: [],
})

const previews = ref({
  document_scan_copy: null,
  certificates: [],
})

const handleFileChange = (event, field) => {
  const file = event.target.files[0]
  if (file) {
    form[field] = file
    if (file.type.startsWith('image/')) {
      previews.value[field] = URL.createObjectURL(file)
    } else {
      previews.value[field] = null
    }
  }
}

const handleCertificatesChange = (event) => {
  const files = Array.from(event.target.files)
  form.certificates = files
  previews.value.certificates = files
    .filter(file => file.type.startsWith('image/'))
    .map(file => URL.createObjectURL(file))
}

const submit = () => {
  form.post(route('chef.kyc.update', kyc.value.id), {
    forceFormData: true,
    _method: 'PUT',
  })
}
</script>
