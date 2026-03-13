<template>
  <div class="p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ t('landing_page.partners.title') }}
          </h3>
          <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
            {{ t('landing_page.partners.description') }}
          </p>
        </div>
        <button
          @click="addPartner"
          type="button"
          class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          {{ t('landing_page.common.add') }}
        </button>
      </div>

      <!-- Partners Grid -->
      <div class="grid md:grid-cols-2 gap-6 mb-6">
        <div
          v-for="(partner, index) in partners"
          :key="index"
          class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-5 shadow-sm"
        >
          <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-600 mb-4">
            <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
              {{ t('landing_page.partners.partner') }} #{{ index + 1 }}
            </span>
            <button
              @click="removePartner(index)"
              type="button"
              class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>

          <div class="space-y-3">
            <!-- Logo Upload -->
            <div>
              <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-gray-200">
                {{ t('landing_page.partners.logo') }}
              </label>
              <div class="flex items-center gap-4">
                <div class="relative w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-500 overflow-hidden bg-white dark:bg-gray-800 flex items-center justify-center">
                  <img
                    v-if="partner.logoPreview || getLogoUrl(partner)"
                    :src="partner.logoPreview || getLogoUrl(partner)"
                    :alt="partner.name_ar || partner.name_en"
                    class="w-full h-full object-contain p-1"
                  />
                  <div v-else class="text-center text-gray-400 dark:text-gray-500 p-2">
                    <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs">{{ t('common.selectImage') || 'اختر صورة' }}</span>
                  </div>
                  <button
                    v-if="partner.logo || partner.logoPreview"
                    @click="removePartnerLogo(index)"
                    type="button"
                    class="absolute top-1 end-1 p-1 rounded-full bg-red-500 text-white hover:bg-red-600 transition"
                  >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                <label class="cursor-pointer">
                  <input
                    type="file"
                    accept="image/jpeg,image/png,image/webp,image/gif"
                    class="hidden"
                    @change="handleLogoUpload($event, index)"
                  />
                  <span class="inline-flex items-center gap-2 rounded-lg border-2 border-primary px-3 py-2 text-sm font-medium text-primary hover:bg-primary/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    {{ partner.logo || partner.logoPreview ? (t('common.changeImage') || 'تغيير الصورة') : (t('common.uploadImage') || 'رفع صورة') }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Name Arabic -->
            <div>
              <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-gray-200">
                {{ t('landing_page.partners.name_ar') }}
              </label>
              <input 
                v-model="partner.name_ar" 
                type="text" 
                class="h-10 w-full rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 px-3 text-sm text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
              />
            </div>
            
            <!-- Name English -->
            <div>
              <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-gray-200">
                {{ t('landing_page.partners.name_en') }}
              </label>
              <input 
                v-model="partner.name_en" 
                type="text" 
                class="h-10 w-full rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 px-3 text-sm text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
              />
            </div>
            
            <!-- Description Arabic -->
            <div>
              <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-gray-200">
                {{ t('landing_page.common.description_ar') }}
              </label>
              <textarea 
                v-model="partner.description_ar" 
                rows="2"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
              ></textarea>
            </div>
            
            <!-- Description English -->
            <div>
              <label class="mb-1.5 block text-xs font-medium text-gray-800 dark:text-gray-200">
                {{ t('landing_page.common.description_en') }}
              </label>
              <textarea 
                v-model="partner.description_en" 
                rows="2"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 px-3 py-2 text-sm text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-600">
        <button
          @click="savePartners"
          :disabled="saving"
          type="button"
          class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90 disabled:opacity-50"
        >
          <svg v-if="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          {{ saving ? t('landing_page.common.saving') : t('landing_page.common.save') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useForm } from '@inertiajs/vue3';
import { useNotifications } from '@/composables/useNotifications';

const { t } = useI18n();
const { success, error } = useNotifications();

const props = defineProps({
  section: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['refresh']);

const partners = ref([]);
const saving = ref(false);

const getLogoUrl = (partner) => {
  const logo = partner?.logo;
  if (!logo || logo === 'null' || logo === 'undefined') return null;
  const path = String(logo).trim();
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  if (path.startsWith('/storage/')) return path;
  return path.startsWith('storage/') ? `/${path}` : `/storage/${path}`;
};

watch(() => props.section, (newSection) => {
  const raw = newSection?.additional_data?.partners || [];
  partners.value = raw.map((p) => ({
    ...p,
    logoPreview: null,
    logoFile: null
  }));
}, { immediate: true });

const addPartner = () => {
  partners.value.push({
    name_ar: '',
    name_en: '',
    logo: '',
    description_ar: '',
    description_en: '',
    logoPreview: null,
    logoFile: null
  });
};

const removePartner = (index) => {
  if (confirm(t('landing_page.partners.confirm_delete'))) {
    partners.value.splice(index, 1);
  }
};

const handleLogoUpload = (event, index) => {
  const file = event.target.files?.[0];
  if (!file || !file.type.startsWith('image/')) return;
  partners.value[index].logoFile = file;
  partners.value[index].logoPreview = URL.createObjectURL(file);
  partners.value[index].logo = ''; // Clear old path when uploading new
};

const removePartnerLogo = (index) => {
  partners.value[index].logo = '';
  partners.value[index].logoFile = null;
  if (partners.value[index].logoPreview) {
    URL.revokeObjectURL(partners.value[index].logoPreview);
  }
  partners.value[index].logoPreview = null;
};

const savePartners = () => {
  saving.value = true;

  const partnersData = partners.value.map((p, i) => {
    const { logoFile, logoPreview, ...rest } = p;
    const data = { ...rest };
    if (logoFile) data.has_new_logo = true;
    else if (p.logo) data.logo = p.logo;
    return data;
  });

  const formData = {
    _method: 'PUT',
    section_key: props.section.section_key || 'partners',
    additional_data: JSON.stringify({
      partners: partnersData,
      partnership_benefits: props.section?.additional_data?.partnership_benefits || []
    })
  };

  partners.value.forEach((p, i) => {
    if (p.logoFile) {
      formData[`partner_logos[${i}]`] = p.logoFile;
    }
  });

  const form = useForm(formData);

  form.post(route('admin.landing-page-sections.update', props.section.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      success(t('landing_page.messages.save_success'));
      saving.value = false;
      partners.value.forEach((p) => {
        if (p.logoPreview) URL.revokeObjectURL(p.logoPreview);
        p.logoPreview = null;
        p.logoFile = null;
      });
      emit('refresh');
    },
    onError: (err) => {
      error(t('landing_page.messages.save_error'));
      saving.value = false;
    }
  });
};
</script>
