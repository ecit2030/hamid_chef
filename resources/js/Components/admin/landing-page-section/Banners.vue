<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
          {{ t('landing_page.banners.title') }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          {{ t('landing_page.banners.description') }}
        </p>
      </div>
      <button
        type="button"
        @click="addBanner"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        {{ t('landing_page.banners.add') }}
      </button>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="(banner, index) in banners"
        :key="index"
        class="relative rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-50 dark:bg-gray-700/50"
      >
        <div class="aspect-[16/9] flex items-center justify-center p-2">
          <img
            v-if="banner.imagePreview || getImageUrl(banner)"
            :src="banner.imagePreview || getImageUrl(banner)"
            alt="Banner"
            class="w-full h-full object-contain rounded-lg"
          />
          <div v-else class="text-center text-gray-400 dark:text-gray-500 py-8">
            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm">{{ t('landing_page.banners.select_image') }}</span>
          </div>
        </div>
        <div class="p-3 flex gap-2">
          <label class="flex-1 cursor-pointer">
            <input
              type="file"
              accept="image/jpeg,image/png,image/webp,image/gif"
              class="hidden"
              @change="handleImageUpload($event, index)"
            />
            <span class="inline-flex items-center justify-center gap-2 w-full rounded-lg border border-primary px-3 py-2 text-sm font-medium text-primary hover:bg-primary/10 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              {{ banner.image || banner.imagePreview ? t('common.changeImage') : t('common.uploadImage') }}
            </span>
          </label>
          <button
            type="button"
            @click="removeBanner(index)"
            class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div v-if="banners.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400">
      <p>{{ t('landing_page.banners.no_banners') }}</p>
      <button
        type="button"
        @click="addBanner"
        class="mt-4 text-primary hover:underline font-medium"
      >
        {{ t('landing_page.banners.add_first') }}
      </button>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
      <button
        type="button"
        @click="saveBanners"
        :disabled="saving"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90 disabled:opacity-50"
      >
        {{ saving ? t('landing_page.common.saving') : t('landing_page.common.save') }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { useNotifications, extractErrorMessage } from '@/composables/useNotifications'

const { t } = useI18n()
const { success, error } = useNotifications()

const props = defineProps({
  section: { type: Object, default: null },
})

const emit = defineEmits(['refresh'])

const banners = ref([])
const saving = ref(false)

function getImageUrl(banner) {
  if (!banner?.image) return null
  return banner.image.startsWith('http') || banner.image.startsWith('/') ? banner.image : `/storage/${banner.image}`
}

function addBanner() {
  banners.value.push({ image: '', imageFile: null, imagePreview: null })
}

function removeBanner(index) {
  const b = banners.value[index]
  if (b?.imagePreview) URL.revokeObjectURL(b.imagePreview)
  banners.value.splice(index, 1)
}

function handleImageUpload(event, index) {
  const file = event.target.files?.[0]
  if (!file) return
  if (banners.value[index]?.imagePreview) URL.revokeObjectURL(banners.value[index].imagePreview)
  banners.value[index] = {
    ...banners.value[index],
    imageFile: file,
    imagePreview: URL.createObjectURL(file),
  }
  event.target.value = ''
}

watch(
  () => props.section,
  (s) => {
    const images = s?.additional_data?.images || []
    banners.value = images.length
      ? images.map((img) => ({
          image: img?.image || '',
          imageFile: null,
          imagePreview: null,
        }))
      : []
  },
  { immediate: true }
)

function saveBanners() {
  if (!props.section) {
    error(t('landing_page.messages.save_error'))
    return
  }

  saving.value = true

  const imagesData = banners.value.map((b, i) => {
    const data = { image: b.image || '' }
    if (b.imageFile) data.has_new_image = true
    return data
  })

  const formData = {
    _method: 'PUT',
    section_key: props.section.section_key || 'banners',
    additional_data: JSON.stringify({ images: imagesData }),
  }

  banners.value.forEach((b, i) => {
    if (b.imageFile) formData[`banner_images[${i}]`] = b.imageFile
  })

  const form = useForm(formData)

  form.post(route('admin.landing-page-sections.update', props.section.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      success(t('landing_page.messages.save_success'))
      saving.value = false
      banners.value.forEach((b) => {
        if (b.imagePreview) URL.revokeObjectURL(b.imagePreview)
        b.imagePreview = null
        b.imageFile = null
      })
      emit('refresh')
    },
    onError: (err) => {
      error(extractErrorMessage(err, t('landing_page.messages.save_error')))
      saving.value = false
    },
  })
}
</script>
