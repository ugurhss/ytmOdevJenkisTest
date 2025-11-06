<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  group: Object,
  students: Array,
})

// Öğrenci silme
const removeStudent = (studentId, studentName) => {
  if (confirm(`${studentName} adlı öğrenciyi gruptan çıkarmak istediğinizden emin misiniz?`)) {
    router.delete(route('students.destroy', [props.group.id, studentId]), {
      preserveScroll: true,
    })
  }
}

// Güvenli veri erişimi
const groupData = computed(() => ({
  name: props.group?.groups_name || 'Bilinmiyor',
  city: props.group?.city?.name || 'Bilinmiyor',
  university: props.group?.university?.name || 'Bilinmiyor',
  faculty: props.group?.faculty?.name || 'Bilinmiyor',
  department: props.group?.department?.name || 'Bilinmiyor',
  class: props.group?.class_model?.name || 'Bilinmiyor',
}))

const studentCount = computed(() => props.students?.length || 0)
</script>

<template>
  <Head :title="`${groupData.name} - Öğrenciler`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">{{ groupData.name }}</h2>
          <p class="text-sm text-gray-600 mt-1">Öğrenci Listesi</p>
        </div>
        <Link
          :href="route('students.create', props.group.id)"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition shadow-sm"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Öğrenci Ekle
        </Link>
      </div>
    </template>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Grup Bilgileri Kartı -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
          <div class="flex-1 min-w-[250px]">
            <h3 class="text-2xl font-bold">{{ groupData.name }}</h3>
            <p class="text-sm opacity-90 mt-2">Grup Öğrenci Listesi</p>
          </div>
          <div class="text-center bg-white/20 rounded-lg px-8 py-4 backdrop-blur-sm">
            <div class="text-5xl font-bold">{{ studentCount }}</div>
            <div class="text-sm opacity-90 mt-1">Öğrenci</div>
          </div>
        </div>
      </div>

      <!-- Öğrenci Listesi -->
      <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">Öğrenci Listesi</h3>
          <Link
            :href="route('groups.show', props.group.id)"
            class="text-sm text-gray-600 hover:text-gray-900 flex items-center"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Grup Detayına Dön
          </Link>
        </div>

        <!-- Öğrenci yoksa -->
        <div v-if="studentCount === 0" class="text-center py-16 px-4">
          <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz öğrenci yok</h3>
          <p class="text-gray-500 mb-6">Bu gruba henüz öğrenci eklenmemiş</p>
          <Link
            :href="route('students.create', props.group.id)"
            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            İlk Öğrenciyi Ekle
          </Link>
        </div>

        <!-- Öğrenci Tablosu -->
        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Öğrenci</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(student, index) in students" :key="student.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                  {{ index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md">
                      <span class="text-white font-bold text-lg">
                        {{ student.name.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-semibold text-gray-900">{{ student.name }}</div>
                      <div class="text-xs text-gray-500">Öğrenci</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center text-sm text-gray-900">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ student.email }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ new Date(student.created_at).toLocaleDateString('tr-TR', {
                      day: '2-digit',
                      month: 'long',
                      year: 'numeric'
                    }) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <button
                    @click="removeStudent(student.id, student.name)"
                    class="inline-flex items-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg transition text-sm font-medium"
                    title="Gruptan Çıkar"
                  >
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
      </div>
    </div>
  </AuthenticatedLayout>
</template>
