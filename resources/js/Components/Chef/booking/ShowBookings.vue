<template>
    <div class="overflow-hidden">
        <!-- Tabs -->
        <div class="flex items-center gap-2 mb-4">
            <button
                @click="activeTab = 'cards'"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    activeTab === 'cards'
                        ? 'bg-brand-500 text-white'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                ]"
            >
                <span class="flex items-center gap-2">
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        />
                    </svg>
                    {{ t("booking.cards_view") }}
                </span>
            </button>
            <button
                @click="activeTab = 'map'"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
                    activeTab === 'map'
                        ? 'bg-brand-500 text-white'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700',
                ]"
            >
                <span class="flex items-center gap-2">
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    {{ t("booking.map_view") }}
                </span>
            </button>
        </div>

        <!-- Cards View -->
        <div v-show="activeTab === 'cards'">
            <div
                class="flex flex-col gap-2 px-4 py-4 border border-b-0 border-gray-200 rounded-b-none rounded-xl dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex items-center gap-3">
                    <span class="text-gray-500 dark:text-gray-400">{{
                        t("datatable.show")
                    }}</span>
                    <div class="relative z-20 bg-transparent">
                        <select
                            v-model="perPage"
                            class="w-full py-2 pl-3 pr-8 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none dark:bg-dark-900 h-9 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                        >
                            <option value="9">9</option>
                            <option value="6">6</option>
                            <option value="3">3</option>
                        </select>
                        <span
                            class="absolute z-30 text-gray-500 -translate-y-1/2 pointer-events-none right-2 top-1/2 dark:text-gray-400"
                        >
                            <svg
                                class="stroke-current"
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165"
                                    stroke=""
                                    stroke-width="1.2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </span>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400">{{
                        t("datatable.entries")
                    }}</span>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative">
                        <button
                            class="absolute text-gray-500 -translate-y-1/2 left-4 top-1/2 dark:text-gray-400"
                        >
                            <svg
                                class="fill-current"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M3.04199 9.37363C3.04199 5.87693 5.87735 3.04199 9.37533 3.04199C12.8733 3.04199 15.7087 5.87693 15.7087 9.37363C15.7087 12.8703 12.8733 15.7053 9.37533 15.7053C5.87735 15.7053 3.04199 12.8703 3.04199 9.37363ZM9.37533 1.54199C5.04926 1.54199 1.54199 5.04817 1.54199 9.37363C1.54199 13.6991 5.04926 17.2053 9.37533 17.2053C11.2676 17.2053 13.0032 16.5344 14.3572 15.4176L17.1773 18.238C17.4702 18.5309 17.945 18.5309 18.2379 18.238C18.5308 17.9451 18.5309 17.4703 18.238 17.1773L15.4182 14.3573C16.5367 13.0033 17.2087 11.2669 17.2087 9.37363C17.2087 5.04817 13.7014 1.54199 9.37533 1.54199Z"
                                    fill=""
                                />
                            </svg>
                        </button>
                        <input
                            v-model="search"
                            type="text"
                            :placeholder="t('datatable.searchPlaceholder')"
                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-11 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 xl:w-[300px]"
                        />
                    </div>
                </div>
            </div>

            <div
                v-if="paginatedData.length"
                class="grid grid-cols-1 gap-4 px-4 py-4 border border-t-0 border-gray-200 rounded-b-xl dark:border-gray-800 sm:grid-cols-2 xl:grid-cols-3"
            >
                <article
                    v-for="booking in paginatedData"
                    :key="booking.id"
                    class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/5"
                >
                    <div class="relative p-5 pb-9">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-200"
                            >
                                <CalenderIcon class="h-6 w-6" />
                            </div>
                            <div>
                                <h3
                                    class="mb-1 text-lg font-semibold text-gray-800 dark:text-white/90"
                                >
                                    {{ t("booking.booking") }} #{{ booking.id }}
                                </h3>
                                <p
                                    class="max-w-xs text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ t("booking.customer") }}:
                                    {{ booking.customer?.first_name }}
                                    {{ booking.customer?.last_name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between border-t border-gray-200 px-5 py-4 dark:border-gray-800"
                    >
                        <div>
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-gray-400"
                            >
                                {{ t("booking.date_time") }}
                            </p>
                            <p
                                class="mt-1 text-base font-semibold text-gray-800 dark:text-white/90"
                            >
                                {{ formatDate(booking.date) }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ formatTime(booking.start_time) }} ({{
                                    booking.hours_count
                                }}h)
                            </p>
                        </div>
                        <Badge
                            :color="getStatusColor(booking.booking_status)"
                            size="sm"
                        >
                            {{ t(`booking.${booking.booking_status}`) }}
                        </Badge>
                    </div>

                    <div
                        class="flex items-center justify-between border-t border-gray-200 px-5 py-4 dark:border-gray-800"
                    >
                        <div>
                            <p
                                class="text-xs font-medium uppercase tracking-wide text-gray-400"
                            >
                                {{ t("booking.total_amount") }}
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <p
                                    class="text-base font-semibold text-gray-800 dark:text-white/90"
                                >
                                    {{ formatPrice(booking.total_amount) }}
                                </p>
                                <span
                                    v-if="booking.discount_amount && booking.discount_amount > 0"
                                    class="inline-flex items-center rounded-md bg-success-50 dark:bg-success-500/10 px-2 py-0.5 text-xs font-medium text-success-700 dark:text-success-400"
                                    :title="t('booking.discount_applied')"
                                >
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    {{ t("booking.discount") }}
                                </span>
                            </div>
                        </div>
                        <Badge
                            :color="
                                getPaymentStatusColor(booking.payment_status)
                            "
                            size="sm"
                        >
                            {{ t(`booking.${booking.payment_status}`) }}
                        </Badge>
                    </div>

                    <div
                        v-if="
                            booking.rejection_reason &&
                            booking.booking_status === 'rejected'
                        "
                        class="border-t border-gray-200 px-5 py-4 dark:border-gray-800"
                    >
                        <p
                            class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-2"
                        >
                            {{ t("booking.rejection_reason") }}
                        </p>
                        <p
                            class="text-sm text-error-600 dark:text-error-400 line-clamp-2"
                        >
                            {{ booking.rejection_reason }}
                        </p>
                    </div>

                    <div
                        class="flex items-center justify-between border-t border-gray-200 px-5 py-4 dark:border-gray-800"
                    >
                        <div class="flex items-center gap-2">
                            <!-- View Button -->
                            <button
                                @click="handleViewClick(booking.id)"
                                class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90"
                            >
                                <svg
                                    class="fill-current"
                                    width="21"
                                    height="20"
                                    viewBox="0 0 21 20"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M10.8749 13.8619C8.10837 13.8619 5.74279 12.1372 4.79804 9.70241C5.74279 7.26761 8.10837 5.54297 10.8749 5.54297C13.6415 5.54297 16.0071 7.26762 16.9518 9.70243C16.0071 12.1372 13.6415 13.8619 10.8749 13.8619ZM10.8749 4.04297C7.35666 4.04297 4.36964 6.30917 3.29025 9.4593C3.23626 9.61687 3.23626 9.78794 3.29025 9.94552C4.36964 13.0957 7.35666 15.3619 10.8749 15.3619C14.3932 15.3619 17.3802 13.0957 18.4596 9.94555C18.5136 9.78797 18.5136 9.6169 18.4596 9.45932C17.3802 6.30919 14.3932 4.04297 10.8749 4.04297ZM10.8663 7.84413C9.84002 7.84413 9.00808 8.67606 9.00808 9.70231C9.00808 10.7286 9.84002 11.5605 10.8663 11.5605H10.8811C11.9074 11.5605 12.7393 10.7286 12.7393 9.70231C12.7393 8.67606 11.9074 7.84413 10.8811 7.84413H10.8663Z"
                                    />
                                </svg>
                            </button>

                            <!-- Accept Button (for pending bookings) -->
                            <button
                                v-if="booking.booking_status === 'pending'"
                                @click="handleAcceptClick(booking.id)"
                                class="text-gray-500 hover:text-success-500 dark:text-gray-400 dark:hover:text-success-500"
                                :title="t('booking.accept')"
                            >
                                <svg
                                    class="fill-current"
                                    width="21"
                                    height="21"
                                    viewBox="0 0 21 21"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M10.5 18.375C14.8492 18.375 18.375 14.8492 18.375 10.5C18.375 6.15076 14.8492 2.625 10.5 2.625C6.15076 2.625 2.625 6.15076 2.625 10.5C2.625 14.8492 6.15076 18.375 10.5 18.375ZM14.7803 8.28033C15.0732 7.98744 15.0732 7.51256 14.7803 7.21967C14.4874 6.92678 14.0126 6.92678 13.7197 7.21967L9.25 11.6893L7.28033 9.71967C6.98744 9.42678 6.51256 9.42678 6.21967 9.71967C5.92678 10.0126 5.92678 10.4874 6.21967 10.7803L8.71967 13.2803C9.01256 13.5732 9.48744 13.5732 9.78033 13.2803L14.7803 8.28033Z"
                                    />
                                </svg>
                            </button>

                            <!-- Reject Button (for pending bookings) -->
                            <button
                                v-if="booking.booking_status === 'pending'"
                                @click="handleRejectClick(booking.id)"
                                class="text-gray-500 hover:text-error-500 dark:text-gray-400 dark:hover:text-error-500"
                                :title="t('booking.reject')"
                            >
                                <svg
                                    class="fill-current"
                                    width="21"
                                    height="21"
                                    viewBox="0 0 21 21"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M10.5 18.375C14.8492 18.375 18.375 14.8492 18.375 10.5C18.375 6.15076 14.8492 2.625 10.5 2.625C6.15076 2.625 2.625 6.15076 2.625 10.5C2.625 14.8492 6.15076 18.375 10.5 18.375ZM8.21967 7.21967C7.92678 7.51256 7.92678 7.98744 8.21967 8.28033L9.43934 9.5L8.21967 10.7197C7.92678 11.0126 7.92678 11.4874 8.21967 11.7803C8.51256 12.0732 8.98744 12.0732 9.28033 11.7803L10.5 10.5607L11.7197 11.7803C12.0126 12.0732 12.4874 12.0732 12.7803 11.7803C13.0732 11.4874 13.0732 11.0126 12.7803 10.7197L11.5607 9.5L12.7803 8.28033C13.0732 7.98744 13.0732 7.51256 12.7803 7.21967C12.4874 6.92678 12.0126 6.92678 11.7197 7.21967L10.5 8.43934L9.28033 7.21967C8.98744 6.92678 8.51256 6.92678 8.21967 7.21967Z"
                                    />
                                </svg>
                            </button>

                            <!-- Complete Button (for accepted bookings) -->
                            <button
                                v-if="booking.booking_status === 'accepted'"
                                @click="handleCompleteClick(booking.id)"
                                class="text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                                :title="t('booking.complete')"
                            >
                                <svg
                                    class="fill-current"
                                    width="21"
                                    height="21"
                                    viewBox="0 0 21 21"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M10.5 2.625C6.15076 2.625 2.625 6.15076 2.625 10.5C2.625 14.8492 6.15076 18.375 10.5 18.375C14.8492 18.375 18.375 14.8492 18.375 10.5C18.375 6.15076 14.8492 2.625 10.5 2.625ZM13.2803 8.78033L9.78033 12.2803C9.48744 12.5732 9.01256 12.5732 8.71967 12.2803L7.21967 10.7803C6.92678 10.4874 6.92678 10.0126 7.21967 9.71967C7.51256 9.42678 7.98744 9.42678 8.28033 9.71967L9.25 10.6893L12.2197 7.71967C12.5126 7.42678 12.9874 7.42678 13.2803 7.71967C13.5732 8.01256 13.5732 8.48744 13.2803 8.78033Z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
            </div>
            <div
                v-else
                class="border border-t-0 rounded-b-xl border-gray-200 px-4 py-12 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400"
            >
                {{ t("booking.no_bookings") }}
            </div>

            <!-- Pagination Controls -->
            <div
                class="border border-t-0 rounded-b-xl border-gray-100 py-4 pl-[18px] pr-4 dark:border-gray-800"
            >
                <div
                    class="flex flex-col xl:flex-row xl:items-center xl:justify-between"
                >
                    <p
                        class="pb-3 text-sm font-medium text-center text-gray-500 border-b border-gray-100 dark:border-gray-800 dark:text-gray-400 xl:border-b-0 xl:pb-0 xl:text-left"
                    >
                        {{
                            t("datatable.showing", {
                                start: startEntry,
                                end: endEntry,
                                total: totalEntries,
                            })
                        }}
                    </p>
                    <div
                        class="flex items-center justify-center gap-0.5 pt-3 xl:justify-end xl:pt-0"
                    >
                        <button
                            @click="prevPage"
                            :disabled="currentPage === 1"
                            class="mr-2.5 flex items-center h-10 justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                        >
                            {{ t("datatable.previous") }}
                        </button>
                        <button
                            @click="goToPage(1)"
                            :class="
                                currentPage === 1
                                    ? 'bg-blue-500/[0.08] text-brand-500'
                                    : 'text-gray-700 dark:text-gray-400'
                            "
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500"
                        >
                            1
                        </button>
                        <span
                            v-if="currentPage > 3"
                            class="flex h-10 w-10 items-center justify-center rounded-lg hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500"
                            >...</span
                        >
                        <button
                            v-for="page in pagesAroundCurrent"
                            :key="page"
                            @click="goToPage(page)"
                            :class="
                                currentPage === page
                                    ? 'bg-blue-500/[0.08] text-brand-500'
                                    : 'text-gray-700 dark:text-gray-400'
                            "
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500"
                        >
                            {{ page }}
                        </button>
                        <span
                            v-if="currentPage < totalPages - 2"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium text-gray-700 hover:bg-blue-500/[0.08] hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                            >...</span
                        >
                        <button
                            v-if="totalPages > 1"
                            @click="goToPage(totalPages)"
                            :class="
                                currentPage === totalPages
                                    ? 'bg-blue-500/[0.08] text-brand-500'
                                    : 'text-gray-700 dark:text-gray-400'
                            "
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium hover:bg-blue-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500"
                        >
                            {{ totalPages }}
                        </button>
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="ml-2.5 flex items-center h-10 justify-center rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-gray-700 shadow-theme-xs hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                        >
                            {{ t("datatable.next") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map View -->
        <div
            v-show="activeTab === 'map'"
            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden"
        >
            <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                    {{ t("booking.bookings_map") }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ t("booking.map_legend") }}
                </p>
                <!-- Legend -->
                <div class="flex flex-wrap gap-4 mt-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400"
                            >{{ t("booking.urgent_today") }}</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400"
                            >{{ t("booking.soon_3_days") }}</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span
                            class="text-xs text-gray-600 dark:text-gray-400"
                            >{{ t("booking.later") }}</span
                        >
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="relative" style="height: 500px">
                <div id="bookings-map" class="w-full h-full"></div>

                <!-- No bookings with coordinates message -->
                <div
                    v-if="!bookingsWithCoordinates.length"
                    class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-900"
                >
                    <div class="text-center">
                        <svg
                            class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">
                            {{ t("booking.no_bookings_with_location") }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bookings List under map -->
            <div
                v-if="bookingsWithCoordinates.length"
                class="p-4 border-t border-gray-200 dark:border-gray-800"
            >
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"
                >
                    <div
                        v-for="booking in bookingsWithCoordinates"
                        :key="booking.id"
                        @click="handleViewClick(booking.id)"
                        class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800"
                        :class="getBookingBorderColor(booking.date)"
                    >
                        <div
                            class="w-3 h-3 rounded-full flex-shrink-0"
                            :class="getMarkerColorClass(booking.date)"
                        ></div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-medium text-gray-800 dark:text-white truncate"
                            >
                                {{ booking.customer?.first_name }}
                                {{ booking.customer?.last_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ booking.customer?.phone_number }}
                            </p>
                            <p
                                class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            >
                                {{ formatDate(booking.date) }} -
                                {{ formatTime(booking.start_time) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Reason Modal -->
        <RejectionReasonModal
            :isOpen="isRejectModalOpen"
            @close="closeRejectModal"
            @confirm="confirmReject"
        />
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import { route } from "@/route";
import { useI18n } from "vue-i18n";
import RejectionReasonModal from "@/Components/modals/RejectionReasonModal.vue";
import Badge from "@/Components/ui/Badge.vue";
import { useNotifications } from "@/composables/useNotifications";
import { CalenderIcon } from "@/icons";

const { t } = useI18n();
const { success, error } = useNotifications();

const props = defineProps({ bookings: Object });

const activeTab = ref("cards");
const search = ref("");
const sortColumn = ref("id");
const sortDirection = ref("desc");
const currentPage = ref(props.bookings?.current_page ?? 1);
const perPage = ref(props.bookings?.per_page ?? 10);
let map = null;
let markers = [];

function handleViewClick(id) {
    router.visit(route("chef.bookings.show", id));
}

function handleAcceptClick(id) {
    router.patch(
        route("chef.bookings.accept", id),
        {},
        {
            onSuccess: () => success(t("booking.accepted_successfully")),
            onError: () => error(t("booking.action_failed")),
            preserveScroll: true,
        },
    );
}

// Reject modal
const isRejectModalOpen = ref(false);
const bookingToRejectId = ref(null);

function handleRejectClick(id) {
    bookingToRejectId.value = id;
    isRejectModalOpen.value = true;
}

function closeRejectModal() {
    isRejectModalOpen.value = false;
    bookingToRejectId.value = null;
}

function confirmReject(reason) {
    if (bookingToRejectId.value) {
        router.patch(
            route("chef.bookings.reject", bookingToRejectId.value),
            { rejection_reason: reason },
            {
                onSuccess: () => {
                    success(t("booking.rejected_successfully"));
                    closeRejectModal();
                },
                onError: () => {
                    error(t("booking.action_failed"));
                    closeRejectModal();
                },
                preserveScroll: true,
            },
        );
    }
}

function handleCompleteClick(id) {
    router.patch(
        route("chef.bookings.complete", id),
        {},
        {
            onSuccess: () => success(t("booking.completed_successfully")),
            onError: () => error(t("booking.action_failed")),
            preserveScroll: true,
        },
    );
}

// Arabic month names
const arabicMonths = [
    "يناير",
    "فبراير",
    "مارس",
    "أبريل",
    "مايو",
    "يونيو",
    "يوليو",
    "أغسطس",
    "سبتمبر",
    "أكتوبر",
    "نوفمبر",
    "ديسمبر",
];

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = date.getDate();
    const month = arabicMonths[date.getMonth()];
    const year = date.getFullYear();
    return `${day} ${month} ${year}`;
};

const formatPrice = (price) => {
    return (
        new Intl.NumberFormat("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(price) + " ر.س"
    );
};

const formatTime = (timeString) => {
    if (!timeString) return "";
    if (timeString.includes("T")) {
        const d = new Date(timeString);
        if (!isNaN(d))
            return d.toLocaleTimeString("en-GB", {
                hour: "2-digit",
                minute: "2-digit",
            });
    }
    const simple = String(timeString).trim();
    const m = simple.match(/(\d{1,2}:\d{2})/);
    if (m) return m[1];
    return simple;
};

const getStatusColor = (status) => {
    const colors = {
        pending: "warning",
        accepted: "success",
        rejected: "error",
        completed: "info",
        cancelled_by_customer: "light",
        cancelled_by_chef: "light",
        cancelled_by_admin: "light",
    };
    return colors[status] || "light";
};

const getPaymentStatusColor = (status) => {
    const colors = {
        pending: "warning",
        paid: "success",
        refunded: "info",
        failed: "error",
    };
    return colors[status] || "light";
};

// Date proximity helpers for map markers
const getDaysUntilBooking = (dateString) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const bookingDate = new Date(dateString);
    bookingDate.setHours(0, 0, 0, 0);
    const diffTime = bookingDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
};

const getMarkerColor = (dateString) => {
    const days = getDaysUntilBooking(dateString);
    if (days <= 0) return "#EF4444"; // Red - today or overdue
    if (days <= 3) return "#F59E0B"; // Yellow/Orange - within 3 days
    return "#22C55E"; // Green - more than 3 days
};

const getMarkerColorClass = (dateString) => {
    const days = getDaysUntilBooking(dateString);
    if (days <= 0) return "bg-red-500";
    if (days <= 3) return "bg-yellow-500";
    return "bg-green-500";
};

const getBookingBorderColor = (dateString) => {
    const days = getDaysUntilBooking(dateString);
    if (days <= 0) return "border-red-300 dark:border-red-800";
    if (days <= 3) return "border-yellow-300 dark:border-yellow-800";
    return "border-green-300 dark:border-green-800";
};

// Bookings with valid coordinates
const bookingsWithCoordinates = computed(() => {
    return (props.bookings?.data || []).filter(
        (b) => b.address?.lat && b.address?.lang,
    );
});

// Initialize Google Maps
const initMap = () => {
    if (!bookingsWithCoordinates.value.length) return;

    const mapElement = document.getElementById("bookings-map");
    if (!mapElement || !window.google) return;

    // Calculate center from all bookings
    const bounds = new google.maps.LatLngBounds();
    bookingsWithCoordinates.value.forEach((booking) => {
        bounds.extend({
            lat: parseFloat(booking.address.lat),
            lng: parseFloat(booking.address.lang),
        });
    });

    map = new google.maps.Map(mapElement, {
        center: bounds.getCenter(),
        zoom: 10,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }],
            },
        ],
    });

    map.fitBounds(bounds);

    // Add markers
    bookingsWithCoordinates.value.forEach((booking) => {
        const marker = new google.maps.Marker({
            position: {
                lat: parseFloat(booking.address.lat),
                lng: parseFloat(booking.address.lang),
            },
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 12,
                fillColor: getMarkerColor(booking.date),
                fillOpacity: 1,
                strokeColor: "#ffffff",
                strokeWeight: 2,
            },
            title: `${booking.customer?.first_name} ${booking.customer?.last_name}`,
        });

        // Info window content
        const infoContent = `
      <div style="padding: 8px; min-width: 200px; font-family: 'Cairo', sans-serif;">
        <h4 style="margin: 0 0 8px; font-weight: 600; color: #1f2937;">
          ${booking.customer?.first_name || ""} ${booking.customer?.last_name || ""}
        </h4>
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 14px;">
          📞 ${booking.customer?.phone_number || "—"}
        </p>
        <p style="margin: 0 0 4px; color: #6b7280; font-size: 14px;">
          📅 ${formatDate(booking.date)}
        </p>
        <p style="margin: 0; color: #6b7280; font-size: 14px;">
          🕐 ${formatTime(booking.start_time)} (${booking.hours_count}h)
        </p>
        <a href="/chef/bookings/${booking.id}" style="display: inline-block; margin-top: 8px; color: #3b82f6; font-size: 14px; text-decoration: none;">
          عرض التفاصيل →
        </a>
      </div>
    `;

        const infoWindow = new google.maps.InfoWindow({ content: infoContent });

        marker.addListener("click", () => {
            infoWindow.open(map, marker);
        });

        markers.push(marker);
    });
};

// Load Google Maps script
const loadGoogleMaps = () => {
    return new Promise((resolve, reject) => {
        if (window.google && window.google.maps) {
            resolve();
            return;
        }

        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
};

// Watch for tab change to initialize map
watch(activeTab, async (newTab) => {
    if (newTab === "map" && bookingsWithCoordinates.value.length) {
        await nextTick();
        try {
            await loadGoogleMaps();
            initMap();
        } catch (e) {
            console.error("Failed to load Google Maps:", e);
        }
    }
});

const filteredData = computed(() => {
    const searchLower = search.value.toLowerCase();
    return (props.bookings?.data || [])
        .filter(
            (booking) =>
                booking.id?.toString().includes(searchLower) ||
                booking.customer?.first_name
                    ?.toLowerCase()
                    .includes(searchLower) ||
                booking.customer?.last_name
                    ?.toLowerCase()
                    .includes(searchLower) ||
                booking.booking_status?.toLowerCase().includes(searchLower),
        )
        .sort((a, b) => {
            const modifier = sortDirection.value === "asc" ? 1 : -1;
            if ((a[sortColumn.value] ?? "") < (b[sortColumn.value] ?? ""))
                return -1 * modifier;
            if ((a[sortColumn.value] ?? "") > (b[sortColumn.value] ?? ""))
                return 1 * modifier;
            return 0;
        });
});

const paginatedData = computed(() => filteredData.value);
const totalEntries = computed(
    () => props.bookings?.total || filteredData.value.length,
);
const startEntry = computed(() => props.bookings?.from || 1);
const endEntry = computed(
    () => props.bookings?.to || filteredData.value.length,
);
const totalPages = computed(() => props.bookings?.last_page || 1);

const pagesAroundCurrent = computed(() => {
    let pages = [];
    const startPage = Math.max(2, currentPage.value - 2);
    const endPage = Math.min(totalPages.value - 1, currentPage.value + 2);
    for (let i = startPage; i <= endPage; i++) pages.push(i);
    return pages;
});

watch(
    () => props.bookings?.current_page,
    (val) => {
        currentPage.value = typeof val === "number" ? val : 1;
    },
);
watch(
    () => props.bookings?.per_page,
    (val) => {
        if (typeof val === "number") perPage.value = val;
    },
);

const fetchPage = (page) => {
    const targetPage = page ?? currentPage.value;
    router.get(
        window.location.pathname,
        {
            page: targetPage,
            per_page: perPage.value,
            search: search.value || undefined,
            sort: sortColumn.value,
            direction: sortDirection.value,
        },
        { preserveState: true, preserveScroll: true, replace: true },
    );
};
const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) fetchPage(page);
};
const nextPage = () => {
    if (currentPage.value < totalPages.value) fetchPage(currentPage.value + 1);
};
const prevPage = () => {
    if (currentPage.value > 1) fetchPage(currentPage.value - 1);
};

watch(perPage, (val, oldVal) => {
    if (val !== oldVal) fetchPage(1);
});
</script>
