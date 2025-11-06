        <script setup>
        import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
        import { Head, Link, router } from '@inertiajs/vue3'
        import { computed } from 'vue'

        const props = defineProps({
        group: Object,
        })

        // Grup silme fonksiyonu
        const deleteGroup = () => {
        if (confirm('Bu grubu silmek istediğinizden emin misiniz?')) {
            router.delete(route('groups.destroy', props.group.id))
        }
        }

        // Güvenli veri erişimi için computed properties
        const groupData = computed(() => ({
        name: props.group?.groups_name || 'Bilinmiyor',
        city: props.group?.city?.name || props.group?.city_id || 'Bilinmiyor',
        university: props.group?.university?.name || props.group?.university_id || 'Bilinmiyor',
        faculty: props.group?.faculty?.name || props.group?.faculty_id || 'Bilinmiyor',
        department: props.group?.department?.name || props.group?.department_id || 'Bilinmiyor',
        class: props.group?.class_model?.name || props.group?.class_models_id || 'Bilinmiyor',
        }))
        </script>

        <template>
        <Head :title="`Grup: ${groupData.name}`" />

        <AuthenticatedLayout>
            <template #header>
            <h2 class="text-xl font-semibold text-gray-800">{{ groupData.name }}</h2>
            </template>

            <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl p-8 space-y-4">
                <div class="border-b pb-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Grup Bilgileri</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                    <p class="text-sm text-gray-500 mb-1">Şehir</p>
                    <p class="text-base font-medium text-gray-900">{{ groupData.city }}</p>
                    </div>

                    <div>
                    <p class="text-sm text-gray-500 mb-1">Üniversite</p>
                    <p class="text-base font-medium text-gray-900">{{ groupData.university }}</p>
                    </div>

                    <div>
                    <p class="text-sm text-gray-500 mb-1">Fakülte</p>
                    <p class="text-base font-medium text-gray-900">{{ groupData.faculty }}</p>
                    </div>

                    <div>
                    <p class="text-sm text-gray-500 mb-1">Bölüm</p>
                    <p class="text-base font-medium text-gray-900">{{ groupData.department }}</p>
                    </div>

                    <div>
                    <p class="text-sm text-gray-500 mb-1">Sınıf</p>
                    <p class="text-base font-medium text-gray-900">{{ groupData.class }}</p>
                    </div>
                </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-4">
                <Link
                    :href="route('groups.edit', props.group.id)"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Düzenle
                </Link>

                <button
                    @click="deleteGroup"
                    class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Sil
                </button>

                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Geri Dön
                </Link>
                </div>
            </div>

            </div>

        </AuthenticatedLayout>
        </template>
