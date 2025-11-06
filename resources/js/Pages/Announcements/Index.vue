<template>
    <Head title="Duyurular" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Grup Duyuruları
                </h2>
                <Link
                    v-if="can.create"
                    :href="route('announcements.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Duyuru
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Başarı Mesajı -->
                <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.success }}
                </div>

                <!-- Hata Mesajı -->
                <div v-if="$page.props.flash?.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.error }}
                </div>

                <!-- Filtreler -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Arama -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ara
                                </label>
                                <input
                                    v-model="form.search"
                                    type="text"
                                    placeholder="Başlık veya içerik ara..."
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                    @input="searchDebounce"
                                />
                            </div>

                            <!-- Grup Filtresi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Grup
                                </label>
                                <select
                                    v-model="form.group_id"
                                    class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                    @change="filterByGroup"
                                >
                                    <option value="">Tüm Gruplar</option>
                                    <option v-for="group in groups" :key="group.id" :value="group.id">
                                        {{ group.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtreleri Temizle -->
                        <div v-if="hasFilters" class="mt-4">
                            <button
                                @click="clearFilters"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Filtreleri Temizle
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Duyuru Listesi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="announcements.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Duyuru bulunamadı</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ hasFilters ? 'Filtrelerinize uygun duyuru bulunmuyor.' : 'Henüz hiç duyuru eklenmemiş.' }}
                            </p>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="announcement in announcements.data"
                                :key="announcement.id"
                                class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
                            >
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <Link
                                            :href="route('announcements.show', announcement.id)"
                                            class="text-lg font-semibold text-gray-900 hover:text-blue-600"
                                        >
                                            {{ announcement.title }}
                                        </Link>

                                        <div class="mt-2 text-sm text-gray-600">
                                            <p class="line-clamp-2">{{ announcement.content }}</p>
                                        </div>

                                        <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ announcement.group.name }}
                                            </span>

                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ announcement.user.name }}
                                            </span>

                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ formatDate(announcement.created_at) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="ml-4 flex items-center space-x-2">
                                        <Link
                                            :href="route('announcements.show', announcement.id)"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                                        >
                                            Görüntüle
                                        </Link>
                                        <Link
                                            :href="route('announcements.edit', announcement.id)"
                                            class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50"
                                        >
                                            Düzenle
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="announcements.data.length > 0" class="mt-6">
                            <Pagination :links="announcements.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';

// Props
const props = defineProps({
    announcements: Object,
    groups: Array,
    filters: Object,
    can: Object
});

// Form state
const form = ref({
    search: props.filters.search || '',
    group_id: props.filters.group_id || ''
});

// Computed
const hasFilters = computed(() => {
    return form.value.search || form.value.group_id;
});

// Debounce timer
let searchTimeout = null;

// Methods
const searchDebounce = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
};

const filterByGroup = () => {
    applyFilters();
};

const applyFilters = () => {
    router.get(route('announcements.index'), {
        search: form.value.search,
        group_id: form.value.group_id
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

const clearFilters = () => {
    form.value.search = '';
    form.value.group_id = '';
    router.get(route('announcements.index'));
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>
