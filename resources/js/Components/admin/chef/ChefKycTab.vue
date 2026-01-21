<template>
    <div class="space-y-6">
        <!-- No KYC Data -->
        <div
            v-if="!kyc"
            class="rounded-lg border border-gray-200 bg-gray-50 p-6 text-center dark:border-gray-700 dark:bg-gray-800/50"
        >
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ t("kyc.noKycData") }}
            </p>
        </div>

        <!-- KYC Data -->
        <div v-else class="space-y-6">
            <!-- KYC Status -->
            <div
                class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
            >
                <h3
                    class="mb-4 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    {{ t("kyc.status") }}
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.currentStatus") }}
                        </label>
                        <span
                            :class="[
                                'mt-1 inline-flex rounded-full px-3 py-1 text-xs font-medium',
                                kyc.status === 'approved'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                    : kyc.status === 'rejected'
                                      ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                      : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                            ]"
                        >
                            {{ t(`kyc.statuses.${kyc.status}`) }}
                        </span>
                    </div>
                    <div v-if="kyc.verified_at">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.verifiedAt") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ kyc.verified_at }}
                        </p>
                    </div>
                </div>
                <div v-if="kyc.rejected_reason" class="mt-4">
                    <label
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ t("kyc.rejectedReason") }}
                    </label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ kyc.rejected_reason }}
                    </p>
                </div>
            </div>

            <!-- Personal Information -->
            <div
                class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
            >
                <h3
                    class="mb-4 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    {{ t("kyc.personalInformation") }}
                </h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.fullName") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ kyc.full_name || "-" }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.gender") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{
                                kyc.gender
                                    ? t(`kyc.genders.${kyc.gender}`)
                                    : "-"
                            }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.dateOfBirth") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ kyc.date_of_birth || "-" }}
                        </p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.documentType") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ kyc.document_type || "-" }}
                        </p>
                    </div>
                    <div class="sm:col-span-2">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ t("kyc.address") }}
                        </label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ kyc.address || "-" }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Certificates -->
            <div
                class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
            >
                <h3
                    class="mb-4 text-lg font-semibold text-gray-900 dark:text-white"
                >
                    {{ t("kyc.certificates") }}
                </h3>

                <div
                    v-if="!hasCertificates"
                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-center dark:border-gray-700 dark:bg-gray-800/50"
                >
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ t("kyc.noCertificates") }}
                    </p>
                </div>

                <div v-else class="space-y-4">
                    <!-- Identity Document -->
                    <div
                        v-if="kyc.certificates.identity_document?.path"
                        class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{
                                        t(
                                            "kyc.certificateTypes.identity_document",
                                        )
                                    }}
                                </h4>
                                <p
                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.uploadedAt") }}:
                                    {{
                                        formatDate(
                                            kyc.certificates.identity_document
                                                .uploaded_at,
                                        )
                                    }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.fileType") }}:
                                    {{
                                        kyc.certificates.identity_document.file_type?.toUpperCase()
                                    }}
                                </p>
                            </div>
                            <a
                                :href="
                                    kyc.certificates.identity_document
                                        .download_url
                                "
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                {{ t("buttons.download") }}
                            </a>
                        </div>
                    </div>

                    <!-- Health Certificate -->
                    <div
                        v-if="kyc.certificates.health_certificate?.path"
                        class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{
                                        t(
                                            "kyc.certificateTypes.health_certificate",
                                        )
                                    }}
                                </h4>
                                <p
                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.uploadedAt") }}:
                                    {{
                                        formatDate(
                                            kyc.certificates.health_certificate
                                                .uploaded_at,
                                        )
                                    }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.fileType") }}:
                                    {{
                                        kyc.certificates.health_certificate.file_type?.toUpperCase()
                                    }}
                                </p>
                            </div>
                            <a
                                :href="
                                    kyc.certificates.health_certificate
                                        .download_url
                                "
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                {{ t("buttons.download") }}
                            </a>
                        </div>
                    </div>

                    <!-- Professional Certificate -->
                    <div
                        v-if="kyc.certificates.professional_certificate?.path"
                        class="rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4
                                    class="font-medium text-gray-900 dark:text-white"
                                >
                                    {{
                                        t(
                                            "kyc.certificateTypes.professional_certificate",
                                        )
                                    }}
                                </h4>
                                <p
                                    class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.uploadedAt") }}:
                                    {{
                                        formatDate(
                                            kyc.certificates
                                                .professional_certificate
                                                .uploaded_at,
                                        )
                                    }}
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("kyc.fileType") }}:
                                    {{
                                        kyc.certificates.professional_certificate.file_type?.toUpperCase()
                                    }}
                                </p>
                            </div>
                            <a
                                :href="
                                    kyc.certificates.professional_certificate
                                        .download_url
                                "
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-3 py-2 text-sm font-medium text-white hover:bg-brand-700 dark:bg-brand-500 dark:hover:bg-brand-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                    />
                                </svg>
                                {{ t("buttons.download") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    kyc: {
        type: Object,
        default: null,
    },
});

const hasCertificates = computed(() => {
    if (!props.kyc?.certificates) return false;

    return Object.values(props.kyc.certificates).some((cert) => cert?.path);
});

const formatDate = (dateString) => {
    if (!dateString) return "-";

    try {
        const date = new Date(dateString);
        return date.toLocaleString();
    } catch (e) {
        return dateString;
    }
};
</script>
