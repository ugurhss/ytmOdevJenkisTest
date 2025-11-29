<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    group: Object,
    students: Array,
})

const showStudents = ref(false)

const deleteGroup = () => {
    if (confirm('Bu grubu silmek istediğinizden emin misiniz?')) {
        router.delete(route('groups.destroy', props.group.id))
    }
}

const removeStudent = (studentId, studentName) => {
    if (confirm(`${studentName} öğrencisini gruptan çıkarmak istediğinize emin misiniz?`)) {
        router.delete(route('groups.removeStudent', [props.group.id, studentId]))
    }
}

const groupData = computed(() => ({
    name: props.group?.groups_name || 'Bilinmiyor',
    city: props.group?.city?.name || props.group?.city_id || 'Bilinmiyor',
    university: props.group?.university?.name || props.group?.university_id || 'Bilinmiyor',
    faculty: props.group?.faculty?.name || props.group?.faculty_id || 'Bilinmiyor',
    department: props.group?.department?.name || props.group?.department_id || 'Bilinmiyor',
    class: props.group?.class_model?.name || props.group?.class_models_id || 'Bilinmiyor',
}))

const studentCount = computed(() => props.students?.length || 0)
</script>

<template>
<Head :title="`Grup: ${groupData.name}`" />

<AuthenticatedLayout>
    <template #header>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    {{ groupData.name }}
                </h2>
                <p class="text-gray-500 mt-1 text-sm">Grup Detayları ve Yönetimi</p>
            </div>
            <Link :href="route('dashboard')" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Geri Dön</span>
            </Link>
        </div>
    </template>

    <div class="py-12 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Grup Bilgileri -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6 hover:shadow-md transition duration-300">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m0 0h5.581m0 0a2.121 2.121 0 015.414 0M9 21h4a2 2 0 002-2v-2.382m0 0l2.055.381M21 7a8 8 0 11-16 0 8 8 0 0116 0z" />
                    </svg>
                </div>
                Grup Bilgileri
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mb-1">📍 Şehir</p>
                    <p class="text-lg font-bold text-gray-900">{{ groupData.city }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">🎓 Üniversite</p>
                    <p class="text-lg font-bold text-gray-900">{{ groupData.university }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-purple-600 uppercase tracking-wide mb-1">🏫 Fakülte</p>
                    <p class="text-lg font-bold text-gray-900">{{ groupData.faculty }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-green-50 to-green-100 border border-green-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">📚 Bölüm</p>
                    <p class="text-lg font-bold text-gray-900">{{ groupData.department }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-orange-600 uppercase tracking-wide mb-1">🎯 Sınıf</p>
                    <p class="text-lg font-bold text-gray-900">{{ groupData.class }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 hover:shadow-md transition">
                    <p class="text-xs font-semibold text-rose-600 uppercase tracking-wide mb-1">👥 Öğrenci</p>
                    <p class="text-lg font-bold text-gray-900">{{ studentCount }}</p>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6"></div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3 mt-6">
                <Link :href="route('groups.edit', props.group.id)" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg hover:shadow-blue-500/30 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Düzenle
                </Link>
                <Link :href="route('announcements.create')" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg hover:shadow-green-500/30 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Duyuru Ekle
                </Link>
                <button @click="deleteGroup" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg hover:shadow-red-500/30 active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Sil
                </button>
            </div>
        </div>

        <!-- Öğrenciler Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
            <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    Grup Öğrencileri
                </h3>
                <button @click="showStudents = !showStudents" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-lg transition shadow-md active:scale-95">
                    {{ showStudents ? '📖 Gizle' : '👁️ Göster' }}
                </button>
            </div>

            <!-- Öğrenci Tablosu -->
            <div v-if="showStudents && students && students.length > 0" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Öğrenci</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">E-posta</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kayıt Tarihi</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(student, index) in students" :key="student.id" class="hover:bg-indigo-50/50 transition duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                                        <span class="text-white font-bold text-lg">{{ student.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ student.name }}</p>
                                        <p class="text-xs text-gray-500">Öğrenci</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">{{ student.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new Date(student.created_at).toLocaleDateString('tr-TR', { day: '2-digit', month: 'long', year: 'numeric' }) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button @click="removeStudent(student.id, student.name)" class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg transition text-sm font-medium hover:shadow-md active:scale-95">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Çıkar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else-if="showStudents && (!students || students.length === 0)" class="p-8 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">Bu grupta henüz öğrenci bulunmamaktadır.</p>
            </div>

            <div v-else class="p-8 text-center text-gray-500">
                <p class="font-medium">Öğrencileri görmek için yukarıdaki butonu tıklayın</p>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
