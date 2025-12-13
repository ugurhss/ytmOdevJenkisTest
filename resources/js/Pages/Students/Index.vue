<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  group: Object,
  students: Array,
})

const removeStudent = (studentId, studentName) => {
  if (confirm(`${studentName} adlı öğrenciyi gruptan çıkarmak istediğinizden emin misiniz?`)) {
    router.delete(route('students.destroy', [props.group.id, studentId]), {
      preserveScroll: true,
    })
  }
}

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
          <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            {{ groupData.name }}
          </h2>
          <p class="text-gray-500 mt-2 text-sm flex items-center">
            <span class="inline-block w-2 h-2 bg-purple-600 rounded-full mr-2"></span>
            Öğrenci Yönetimi
          </p>
        </div>
        <Link
          :href="route('students.create', props.group.id)"
          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg active:scale-95"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
          Öğrenci Ekle
        </Link>
      </div>
    </template>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Grup Bilgileri Kartı -->
      <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-lg p-8 mb-8 text-white relative overflow-hidden mb-8">
        <div class="absolute -right-40 -top-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-40 -bottom-40 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
          <div>
            <h3 class="text-3xl font-bold mb-2">{{ groupData.name }}</h3>
            <p class="text-white/80 text-sm">Öğrenci yönetim paneli</p>
          </div>
          <div class="text-center bg-white/20 backdrop-blur-sm rounded-xl px-8 py-4 border border-white/30">
            <div class="text-5xl font-bold">{{ studentCount }}</div>
            <div class="text-sm opacity-90 mt-1">Toplam Öğrenci</div>
          </div>
        </div>
      </div>

      <!-- Öğrenci Listesi -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
        <div class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            Öğrenci Listesi
          </h3>
          <Link
            :href="route('groups.show', props.group.id)"
            class="text-sm text-gray-600 hover:text-indigo-600 transition font-medium flex items-center"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Grup Detayına Dön
          </Link>
        </div>

        <!-- Boş Durum -->
        <div v-if="studentCount === 0" class="text-center py-20 px-4">
          <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-2">Henüz öğrenci yok</h3>
          <p class="text-gray-500 mb-6">Bu gruba henüz öğrenci eklenmemiş. Başlamak için aşağıdaki butonu tıklayın.</p>
          <Link
            :href="route('students.create', props.group.id)"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg active:scale-95"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            İlk Öğrenciyi Ekle
          </Link>
        </div>

        <!-- Öğrenci Tablosu -->
        <div v-else class="overflow-x-auto">
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
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg font-bold text-sm">
                    {{ index + 1 }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                      <span class="text-white font-bold text-lg">
                        {{ student.name.charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <div class="ml-3">
                      <p class="text-sm font-semibold text-gray-900">{{ student.name }}</p>
                      <p class="text-xs text-gray-500">Öğrenci</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center text-sm text-gray-600 font-medium">
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
  class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg transition text-sm font-semibold hover:shadow-md active:scale-95"
  title="Gruptan Çıkar"
>
  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
