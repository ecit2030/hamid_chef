<template>
  <Modal v-if="isOpen" :fullScreenBackdrop="true" @close="handleClose">
    <template #body>
      <div class="relative w-full max-w-md rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <div class="text-center">
          <div class="relative flex items-center justify-center z-1 mb-7">
            <svg
              class="fill-error-50 dark:fill-error-500/15"
              width="90"
              height="90"
              viewBox="0 0 90 90"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z"
                fill=""
                fill-opacity=""
              />
            </svg>

            <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
              <svg
                class="fill-error-600 dark:fill-error-500"
                width="38"
                height="38"
                viewBox="0 0 38 38"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M19 4.75C18.3096 4.75 17.75 5.30964 17.75 6V20C17.75 20.6904 18.3096 21.25 19 21.25C19.6904 21.25 20.25 20.6904 20.25 20V6C20.25 5.30964 19.6904 4.75 19 4.75ZM19 26.125C18.3096 26.125 17.75 26.6846 17.75 27.375V28.5C17.75 29.1904 18.3096 29.75 19 29.75C19.6904 29.75 20.25 29.1904 20.25 28.5V27.375C20.25 26.6846 19.6904 26.125 19 26.125Z"
                  fill=""
                />
              </svg>
            </span>
          </div>

          <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90 sm:text-title-sm">
            {{ t('booking.rejectionReason') }}
          </h4>

          <div class="mt-4 text-right">
            <p class="text-sm leading-6 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
              {{ rejectionReason }}
            </p>
          </div>

          <div class="mt-7 flex w-full items-center justify-center gap-3">
            <button
              @click="handleClose"
              type="button"
              class="flex w-full justify-center rounded-lg bg-gray-100 px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:w-auto"
            >
              {{ t('buttons.close') }}
            </button>
          </div>
        </div>
      </div>
    </template>
  </Modal>
</template>

<script setup>
import Modal from '@/Components/ui/Modal.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
  rejectionReason: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['close'])

const handleClose = () => {
  emit('close')
}
</script>
