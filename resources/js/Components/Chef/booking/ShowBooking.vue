<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Content - Booking Information -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Booking Information -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 flex items-center gap-4">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-200">
              <CalenderIcon class="h-8 w-8" />
            </div>
            <div>
              <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t('booking.booking_details') }}</h2>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ t('booking.booking') }} #{{ booking.id }}</p>
            </div>
          </div>

          <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 gap-x-5 gap-y-6 md:grid-cols-2">
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.date') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ formatDate(booking.date) }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.start_time') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ formatTime(booking.start_time) }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.hours_count') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.hours_count }} {{ t('booking.hours') }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.end_time') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ calculateEndTime(booking.start_time, booking.hours_count) }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.number_of_guests') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.number_of_guests }}</p>
              </div>

              <div v-if="booking.extra_guests_count > 0">
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.extra_guests_count') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.extra_guests_count }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.service_type') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ t(`booking.${booking.service_type}`) }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.booking_status') }}</label>
                <Badge :color="getStatusColor(booking.booking_status)" size="sm">
                  {{ t(`booking.${booking.booking_status}`) }}
                </Badge>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.payment_status') }}</label>
                <Badge :color="getPaymentStatusColor(booking.payment_status)" size="sm">
                  {{ t(`booking.${booking.payment_status}`) }}
                </Badge>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.created_at') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ formatDateTime(booking.created_at) }}</p>
              </div>

              <div v-if="booking.notes" class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.notes') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90 whitespace-pre-wrap">{{ booking.notes }}</p>
              </div>

              <div v-if="booking.rejection_reason && booking.booking_status === 'rejected'" class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.rejection_reason') }}</label>
                <div class="rounded-lg bg-error-50 dark:bg-error-500/10 border border-error-200 dark:border-error-500/20 p-4">
                  <p class="text-base text-error-800 dark:text-error-200 whitespace-pre-wrap">{{ booking.rejection_reason }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Address Information with Map -->
        <div v-if="booking.address" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t('booking.address_information') }}</h2>
          </div>

          <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 gap-x-5 gap-y-6 md:grid-cols-2 mb-6">
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('common.address') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.address?.address ?? '—' }}</p>
              </div>

              <div v-if="booking.address?.label">
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('addresses.label') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.address.label }}</p>
              </div>
            </div>

            <!-- Google Map -->
            <div class="mt-4">
              <label class="mb-2 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('booking.location_on_map') }}</label>
              <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <iframe
                  :src="googleMapsUrl"
                  width="100%"
                  height="300"
                  style="border:0;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  class="w-full"
                ></iframe>
              </div>
              <a
                :href="googleMapsDirectionsUrl"
                target="_blank"
                class="mt-3 inline-flex items-center gap-2 text-brand-500 hover:text-brand-600 text-sm font-medium"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ t('booking.open_in_google_maps') }}
              </a>
            </div>
          </div>
        </div>

        <!-- Pricing Details -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t('booking.pricing_details') }}</h2>
          </div>

          <div class="p-4 sm:p-6">
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ t('booking.unit_price') }}</span>
                <span class="text-sm text-gray-900 dark:text-white/90">{{ formatPrice(booking.unit_price) }}</span>
              </div>
              <div v-if="booking.service_type === 'hourly'" class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ t('booking.hours_count') }} ({{ booking.hours_count }})</span>
                <span class="text-sm text-gray-900 dark:text-white/90">{{ formatPrice(booking.unit_price * booking.hours_count) }}</span>
              </div>
              <div v-if="booking.extra_guests_amount > 0" class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ t('booking.extra_guests') }} ({{ booking.extra_guests_count }})</span>
                <span class="text-sm text-gray-900 dark:text-white/90">{{ formatPrice(booking.extra_guests_amount) }}</span>
              </div>
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between">
                <span class="text-base font-medium text-gray-900 dark:text-white/90">{{ t('booking.total_amount') }}</span>
                <span class="text-base font-medium text-gray-900 dark:text-white/90">{{ formatPrice(booking.total_amount) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar - Customer Info and Actions -->
      <div class="space-y-6">
        <!-- Customer Information -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 flex items-center gap-4">
            <div class="h-14 w-14 overflow-hidden rounded-full border border-gray-200 dark:border-gray-800">
              <img v-if="booking.customer?.image" :src="`/storage/${booking.customer.image}`" alt="" class="h-14 w-14 object-cover" />
              <div v-else class="h-14 w-14 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t('booking.customer_information') }}</h2>
          </div>

          <div class="p-4 sm:p-6">
            <div class="space-y-4">
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('common.name') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.customer?.first_name }} {{ booking.customer?.last_name }}</p>
              </div>

              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('common.phoneNumber') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90 dir-ltr">{{ booking.customer?.phone ?? '—' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Service Information -->
        <div v-if="booking.chef_service" class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h2 class="text-lg font-medium text-gray-800 dark:text-white">{{ t('booking.service_information') }}</h2>
          </div>

          <div class="p-4 sm:p-6">
            <div class="space-y-4">
              <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-500 dark:text-gray-400">{{ t('common.name') }}</label>
                <p class="text-base text-gray-800 dark:text-white/90">{{ booking.chef_service?.name ?? '—' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
          <Link :href="route('chef.bookings.index')" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
            {{ t('buttons.backToList') }}
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { computed } from 'vue'
import { CalenderIcon } from '@/icons'
import Badge from '@/Components/ui/Badge.vue'

const { t } = useI18n()

const props = defineProps({
  booking: {
    type: Object,
    required: true
  }
})

// Arabic month names
const arabicMonths = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر']
const arabicDays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت']

// Format date with Arabic text and English numbers
const formatDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  const dayName = arabicDays[date.getDay()]
  return `${dayName}، ${day} ${month} ${year}`
}

const formatDateTime = (dateTimeString) => {
  const date = new Date(dateTimeString)
  const day = date.getDate()
  const month = arabicMonths[date.getMonth()]
  const year = date.getFullYear()
  const hours = String(date.getHours()).padStart(2, '0')
  const minutes = String(date.getMinutes()).padStart(2, '0')
  return `${day} ${month} ${year} - ${hours}:${minutes}`
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(price) + ' ر.س'
}

const pad = (n) => String(n).padStart(2, '0')

const parseStartTime = (startTime) => {
  if (!startTime) return { hours: 0, minutes: 0 }
  if (typeof startTime !== 'string') startTime = String(startTime)
  if (startTime.includes('T')) {
    const d = new Date(startTime)
    if (!isNaN(d)) return { hours: d.getHours(), minutes: d.getMinutes() }
  }
  if (startTime.includes(':')) {
    const parts = startTime.split(':').map(Number)
    return { hours: parts[0] || 0, minutes: parts[1] || 0 }
  }
  return { hours: 0, minutes: 0 }
}

const calculateEndTime = (startTime, hoursCount) => {
  const { hours, minutes } = parseStartTime(startTime)
  const end = new Date()
  end.setHours(hours + (parseInt(hoursCount) || 0), minutes)
  return `${pad(end.getHours())}:${pad(end.getMinutes())}`
}

const formatTime = (val) => {
  if (!val) return ''
  if (typeof val !== 'string') return String(val)
  if (val.includes('T')) {
    const d = new Date(val)
    if (isNaN(d)) return val
    return `${pad(d.getHours())}:${pad(d.getMinutes())}`
  }
  if (val.includes(':')) {
    const parts = val.split(':')
    return `${pad(parts[0])}:${pad(parts[1] || '00')}`
  }
  return val
}

const getStatusColor = (status) => {
  const colors = {
    pending: 'warning',
    accepted: 'success',
    rejected: 'error',
    completed: 'info',
    cancelled_by_customer: 'light',
    cancelled_by_chef: 'light',
    cancelled_by_admin: 'light'
  }
  return colors[status] || 'light'
}

const getPaymentStatusColor = (status) => {
  const colors = {
    pending: 'warning',
    paid: 'success',
    refunded: 'info',
    failed: 'error'
  }
  return colors[status] || 'light'
}

// Map related computed properties
const hasCoordinates = computed(() => {
  return props.booking.address?.lat && props.booking.address?.lang
})

const getAddressQuery = computed(() => {
  if (hasCoordinates.value) {
    return `${props.booking.address.lat},${props.booking.address.lang}`
  }
  // Use text address if no coordinates
  const parts = []
  if (props.booking.address?.address) parts.push(props.booking.address.address)
  if (props.booking.address?.street) parts.push(props.booking.address.street)
  return encodeURIComponent(parts.join(', ') || 'Saudi Arabia')
})

const googleMapsUrl = computed(() => {
  if (hasCoordinates.value) {
    const lat = props.booking.address.lat
    const lng = props.booking.address.lang
    return `https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3000!2d${lng}!3d${lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM!5e0!3m2!1sen!2s!4v1600000000000!5m2!1sen!2s`
  }
  // Use text address search
  return `https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=${getAddressQuery.value}`
})

const googleMapsDirectionsUrl = computed(() => {
  if (hasCoordinates.value) {
    const lat = props.booking.address.lat
    const lng = props.booking.address.lang
    return `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`
  }
  return `https://www.google.com/maps/dir/?api=1&destination=${getAddressQuery.value}`
})
</script>
