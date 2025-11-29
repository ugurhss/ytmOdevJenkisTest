<template>
    <Head :title="announcement.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <Link
                        :href="route('announcements.index')"
                        class="mr-4 text-gray-600 hover:text-indigo-600 transition font-medium flex items-center"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent leading-tight">
                        Duyuru Detayı
                    </h2>
                </div>

                <div class="flex items-center space-x-2">
                    <Link
                        v-if="can.update"
                        :href="route('announcements.edit', announcement.id)"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold text-sm rounded-lg transition shadow-md hover:shadow-lg active:scale-95"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Düzenle
                    </Link>
                    <button
                        v-if="can.delete"
                        @click="showDeleteModal = true"
                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold text-sm rounded-lg transition shadow-md hover:shadow-lg active:scale-95"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Sil
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Başlık Alanı -->
                    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-8 text-white relative overflow-hidden">
                        <div class="absolute -right-32 -top-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -left-32 -bottom-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <h1 class="text-4xl font-bold mb-4">
                                {{ announcement.title }}
                            </h1>
                            <p class="text-white/80 text-sm">Duyuru Detayları</p>
                        </div>
                    </div>

                    <!-- Meta Bilgiler -->
                    <div class="px-8 py-6 bg-gray-50 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2v-6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2m0 0h0" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Grup</p>
                                    <p class="text-sm font-bold text-gray-900">{{ announcement.group.groups_name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Yayınlayan</p>
                                    <p class="text-sm font-bold text-gray-900">{{ announcement.user.name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Tarih</p>
                                    <p class="text-sm font-bold text-gray-900">{{ formatDate(announcement.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- İçerik -->
                    <div class="p-8">
                        <div class="prose prose-sm max-w-none">
                            <div class="text-gray-700 leading-relaxed whitespace-pre-wrap font-medium text-base">
                                {{ announcement.content }}
                            </div>
                        </div>

                        <!-- Güncelleme Bilgisi -->
                        <div v-if="announcement.updated_at !== announcement.created_at" class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Son güncelleme: {{ formatDate(announcement.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Silme Onay Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full">
                <div class="bg-gradient-to-r from-red-600 to-rose-600 p-6 text-white">
                    <h2 class="text-xl font-bold">Duyuruyu Sil</h2>
                </div>

                <div class="p-6">
                    <p class="text-gray-700 mb-6">
                        Bu duyuruyu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                    </p>

                    <div class="flex justify-end space-x-3">
                        <button
                            @click="showDeleteModal = false"
                            class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition active:scale-95"
                        >
                            İptal
                        </button>
                        <button
                            @click="deleteAnnouncement"
                            :disabled="deleteForm.processing"
                            class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg active:scale-95 disabled:opacity-50"
                        >
                            {{ deleteForm.processing ? 'Siliniyor...' : 'Sil' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    announcement: Object,
    can: Object
});

const showDeleteModal = ref(false);

const deleteForm = useForm({});

const deleteAnnouncement = () => {
    deleteForm.delete(route('announcements.destroy', props.announcement.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
        }
    });
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
