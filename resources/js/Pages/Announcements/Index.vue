<template>
    <Head title="Duyurular" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Grup Duyuruları
                    </h2>
                    <p class="text-gray-500 mt-2 text-sm">Tüm duyuruları buradan yönetebilirsiniz</p>
                </div>
                <Link
                    v-if="can.create"
                    :href="route('announcements.create')"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg active:scale-95"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Yeni Duyuru
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Başarı Mesajı -->
                <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ $page.props.flash.success }}</span>
                </div>

                <!-- Hata Mesajı -->
                <div v-if="$page.props.flash?.error" class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ $page.props.flash.error }}</span>
                </div>

                <!-- Filtreler -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-md transition duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Arama -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                🔍 Ara
                            </label>
                            <input
                                v-model="form.search"
                                type="text"
                                placeholder="Başlık veya içerik ara..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                                @input="searchDebounce"
                            />
                        </div>

                        <!-- Grup Filtresi -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-3">
                                🏢 Grup
                            </label>
                            <select
                                v-model="form.group_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                                @change="filterByGroup"
                            >
                                <option value="">Tüm Gruplar</option>
                                <option v-for="group in groups" :key="group.id" :value="group.id">
                                    {{ group.groups_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Filtreleri Temizle -->
                    <div v-if="hasFilters">
                        <button
                            @click="clearFilters"
                            class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold flex items-center transition"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Filtreleri Temizle
                        </button>
                    </div>
                </div>

                <!-- Duyuru Listesi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                    <div v-if="announcements.data.length === 0" class="text-center py-20 px-4">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Duyuru bulunamadı</h3>
                        <p class="text-gray-500">
                            {{ hasFilters ? 'Filtrelerinize uygun duyuru bulunmuyor.' : 'Henüz hiç duyuru eklenmemiş.' }}
                        </p>
                    </div>

                    <div v-else class="space-y-4 p-6">
                        <div
                            v-for="announcement in announcements.data"
                            :key="announcement.id"
                            class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-indigo-200 transition duration-300 hover:-translate-y-0.5"
                        >
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <!-- Başlık -->
                                    <Link
                                        :href="route('announcements.show', announcement.id)"
                                        class="text-xl font-bold text-gray-900 hover:text-indigo-600 transition mb-3 block"
                                    >
                                        {{ announcement.title }}
                                    </Link>

                                    <!-- İçerik Özeti -->
                                    <div class="mt-2 text-gray-600 line-clamp-2 text-sm">
                                        {{ announcement.content }}
                                    </div>

                                    <!-- Meta Bilgiler -->
                                    <div class="mt-4 flex items-center space-x-3 flex-wrap gap-3 text-sm text-gray-500">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2v-6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2m0 0h0" />
                                            </svg>
                                            <span class="font-semibold text-gray-700">{{ announcement.group.groups_name }}</span>
                                        </span>

                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ announcement.user.name }}
                                        </span>

                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ formatDate(announcement.created_at) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Aksiyonlar -->
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <Link
                                        :href="route('announcements.show', announcement.id)"
                                        class="inline-flex items-center px-4 py-2.5 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold text-sm rounded-lg transition hover:shadow-md active:scale-95"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Görüntüle
                                    </Link>
                                    <Link
                                        :href="route('announcements.edit', announcement.id)"
                                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-lg transition shadow-md hover:shadow-lg active:scale-95"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Düzenle
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="announcements.data.length > 0" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <Pagination :links="announcements.links" />
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

const props = defineProps({
    announcements: Object,
    groups: Array,
    filters: Object,
    can: Object
});

const form = ref({
    search: props.filters.search || '',
    group_id: props.filters.group_id || ''
});

const hasFilters = computed(() => {
    return form.value.search || form.value.group_id;
});

let searchTimeout = null;

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
