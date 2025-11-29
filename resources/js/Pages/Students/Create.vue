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

const submitForm = () => {
  form.post(route('students.store', props.group.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Başarılı olursa listeye yönlendir
    },
  })
}

const cancelForm = () => {
  router.visit(route('students.index', props.group.id))
}

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
          <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Yeni Öğrenci Ekle
          </h2>
          <p class="text-gray-500 mt-2 text-sm flex items-center">
            <span class="inline-block w-2 h-2 bg-indigo-600 rounded-full mr-2"></span>
            {{ groupData.name }}
          </p>
        </div>
        <Link
          :href="route('students.index', props.group.id)"
          class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 transition font-medium"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Listeye Dön</span>
        </Link>
      </div>
    </template>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <!-- Grup Bilgileri Kartı -->
      <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-lg p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute -right-32 -top-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-32 -bottom-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative z-10">
          <div class="flex items-center space-x-3 mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2m2 2a2 2 0 002-2m-2 2v-6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2m0 0h0" />
            </svg>
            <h3 class="text-2xl font-bold">{{ groupData.name }}</h3>
          </div>
          <p class="text-white/80 text-sm">Yeni bir öğrenci profili oluşturmak üzeresiniz</p>
        </div>
      </div>

      <!-- Form Kartı -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
          <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            Öğrenci Bilgileri
          </h3>
        </div>

        <FormErrorModal :errors="form.errors" />

        <form @submit.prevent="submitForm" class="p-8 space-y-6">
          <!-- Ad Soyad -->
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">
              <span class="flex items-center">
                <span>Ad Soyad</span>
                <span class="ml-2 text-red-500 font-bold">*</span>
              </span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <input
                v-model="form.name"
                type="text"
                class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                :class="{ 'border-red-500 bg-red-50 focus:ring-red-500': form.errors.name }"
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
            <label class="block text-sm font-bold text-gray-900 mb-3">
              <span class="flex items-center">
                <span>E-posta Adresi</span>
                <span class="ml-2 text-red-500 font-bold">*</span>
              </span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <input
                v-model="form.email"
                type="email"
                class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                :class="{ 'border-red-500 bg-red-50 focus:ring-red-500': form.errors.email }"
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
            <p v-else class="mt-2 text-xs text-gray-600 flex items-center">
              <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Öğrenci bu e-posta adresiyle sisteme giriş yapacak
            </p>
          </div>

          <!-- Şifre -->
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-3">
              <span class="flex items-center">
                <span>Şifre</span>
                <span class="ml-2 text-red-500 font-bold">*</span>
              </span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input
                v-model="form.password"
                type="password"
                class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                :class="{ 'border-red-500 bg-red-50 focus:ring-red-500': form.errors.password }"
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
            <p v-else class="mt-2 text-xs text-gray-600 flex items-center">
              <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Minimum 8 karakter uzunluğunda olmalıdır
            </p>
          </div>

          <!-- Butonlar -->
          <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <button
              type="button"
              @click="cancelForm"
              class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition hover:shadow-md active:scale-95"
              :disabled="form.processing"
            >
              İptal
            </button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg hover:shadow-indigo-500/30 active:scale-95 disabled:opacity-50 flex items-center"
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
