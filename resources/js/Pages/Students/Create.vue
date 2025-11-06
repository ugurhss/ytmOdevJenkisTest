<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FormErrorModal from '@/Components/FormErrorModal.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  group: Object,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
})

// Form submit
const submitForm = () => {
  form.post(route('students.store', props.group.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Başarılı olursa listeye yönlendir
    },
  })
}

// İptal
const cancelForm = () => {
  router.visit(route('students.index', props.group.id))
}

// Güvenli veri erişimi
const groupData = computed(() => ({
  name: props.group?.groups_name || 'Bilinmiyor',
}))
</script>

<template>
  <Head :title="`${groupData.name} - Yeni Öğrenci Ekle`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">Yeni Öğrenci Ekle</h2>
          <p class="text-sm text-gray-600 mt-1">{{ groupData.name }}</p>
        </div>
        <Link
          :href="route('students.index', props.group.id)"
          class="text-sm text-gray-600 hover:text-gray-900 flex items-center"
        >
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Listeye Dön
        </Link>
      </div>
    </template>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <!-- Grup Bilgileri Kartı -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h3 class="text-xl font-bold mb-3">{{ groupData.name }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm opacity-90">
          <div class="flex items-center">
            <span class="mr-2">📍</span>
            <span>{{ groupData.city }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">🎓</span>
            <span>{{ groupData.university }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">🏫</span>
            <span>{{ groupData.faculty }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">📚</span>
            <span>{{ groupData.department }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">🎯</span>
            <span>{{ groupData.class }}</span>
          </div>
        </div>
      </div>

      <!-- Form Kartı -->
      <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            Öğrenci Bilgileri
          </h3>
        </div>

        <FormErrorModal :errors="form.errors" />

        <form @submit.prevent="submitForm" class="p-8 space-y-6">
          <!-- Ad Soyad -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Ad Soyad <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <input
                v-model="form.name"
                type="text"
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                :class="{ 'border-red-500 focus:ring-red-500': form.errors.name }"
                placeholder="Ahmet Yılmaz"
                required
              />
            </div>
            <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ form.errors.name }}
            </p>
          </div>

          <!-- E-posta -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              E-posta Adresi <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <input
                v-model="form.email"
                type="email"
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                :class="{ 'border-red-500 focus:ring-red-500': form.errors.email }"
                placeholder="ornek@email.com"
                required
              />
            </div>
            <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ form.errors.email }}
            </p>
            <p v-else class="mt-2 text-xs text-gray-500">
              Öğrenci bu e-posta adresiyle sisteme giriş yapacak
            </p>
          </div>

          <!-- Şifre -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Şifre <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input
                v-model="form.password"
                type="password"
                class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                :class="{ 'border-red-500 focus:ring-red-500': form.errors.password }"
                placeholder="••••••••"
                required
              />
            </div>
            <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 flex items-center">
              <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ form.errors.password }}
            </p>
            <p v-else class="mt-2 text-xs text-gray-500">
              En az 8 karakter uzunluğunda olmalıdır
            </p>
          </div>

          <!-- Butonlar -->
          <div class="flex justify-end space-x-4 pt-6 border-t">
            <button
              type="button"
              @click="cancelForm"
              class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition font-medium"
              :disabled="form.processing"
            >
              İptal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
            >
              <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-if="form.processing">Kaydediliyor...</span>
              <span v-else>Öğrenciyi Ekle</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
