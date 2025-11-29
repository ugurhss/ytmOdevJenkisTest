<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FormErrorModal from '@/Components/FormErrorModal.vue'
import { Head, useForm, Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
  group: Object,
})

/* ---------------------------
| Import Type
| manual | csv | excel
----------------------------*/
const importType = ref("manual")

/* ---------------------------
| Form State
----------------------------*/
const form = useForm({
  import_type: 'manual',
  name: '',
  no: '', // Email ve Password yerine sadece No var
  file: null,
})

const submitForm = () => {
  form.import_type = importType.value

  form.post(route('students.store', props.group.id), {
    preserveScroll: true,
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

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-lg p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute -right-32 -top-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -left-32 -bottom-32 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>

        <div class="relative z-10">
          <div class="flex items-center space-x-3 mb-3">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/30">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold">{{ groupData.name }}</h3>
          </div>
          <p class="text-white/80 text-sm">Öğrenci Adı ve Numarası girilerek otomatik hesap oluşturulacaktır.</p>
        </div>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 hover:shadow-md transition duration-300">
        <label class="block text-sm font-bold text-gray-900 mb-4 flex items-center">
          <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          Öğrenci Ekleme Yöntemi
        </label>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <button
            type="button"
            @click="importType = 'manual'"
            :class="[
              'p-4 rounded-xl border-2 transition duration-300 text-left hover:shadow-md',
              importType === 'manual'
                ? 'border-indigo-500 bg-gradient-to-br from-indigo-50 to-purple-50 shadow-md'
                : 'border-gray-200 bg-white hover:border-indigo-200'
            ]"
          >
            <div class="flex items-start space-x-3">
              <div :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0',
                importType === 'manual' ? 'bg-gradient-to-br from-indigo-500 to-purple-600' : 'bg-gray-100'
              ]">
                <svg :class="importType === 'manual' ? 'text-white' : 'text-gray-500'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </div>
              <div>
                <p :class="['font-bold text-sm', importType === 'manual' ? 'text-indigo-700' : 'text-gray-700']">
                  Manuel Kayıt
                </p>
                <p class="text-xs text-gray-500 mt-1">Tek tek öğrenci ekle</p>
              </div>
            </div>
          </button>

          <button
            type="button"
            @click="importType = 'csv'"
            :class="[
              'p-4 rounded-xl border-2 transition duration-300 text-left hover:shadow-md',
              importType === 'csv'
                ? 'border-green-500 bg-gradient-to-br from-green-50 to-emerald-50 shadow-md'
                : 'border-gray-200 bg-white hover:border-green-200'
            ]"
          >
            <div class="flex items-start space-x-3">
              <div :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0',
                importType === 'csv' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gray-100'
              ]">
                <svg :class="importType === 'csv' ? 'text-white' : 'text-gray-500'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
              </div>
              <div>
                <p :class="['font-bold text-sm', importType === 'csv' ? 'text-green-700' : 'text-gray-700']">
                  CSV Dosyası
                </p>
                <p class="text-xs text-gray-500 mt-1">Toplu içe aktarma</p>
              </div>
            </div>
          </button>

          <button
            type="button"
            @click="importType = 'excel'"
            :class="[
              'p-4 rounded-xl border-2 transition duration-300 text-left hover:shadow-md',
              importType === 'excel'
                ? 'border-blue-500 bg-gradient-to-br from-blue-50 to-cyan-50 shadow-md'
                : 'border-gray-200 bg-white hover:border-blue-200'
            ]"
          >
            <div class="flex items-start space-x-3">
              <div :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0',
                importType === 'excel' ? 'bg-gradient-to-br from-blue-500 to-cyan-600' : 'bg-gray-100'
              ]">
                <svg :class="importType === 'excel' ? 'text-white' : 'text-gray-500'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <p :class="['font-bold text-sm', importType === 'excel' ? 'text-blue-700' : 'text-gray-700']">
                  Excel Dosyası
                </p>
                <p class="text-xs text-gray-500 mt-1">XLSX veya XLS</p>
              </div>
            </div>
          </button>
        </div>
      </div>

      <FormErrorModal :errors="form.errors" />

      <form @submit.prevent="submitForm" class="space-y-6">

        <div v-if="importType === 'manual'" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
          <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              Öğrenci Bilgileri
            </h3>
          </div>

          <div class="p-8 space-y-6">
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
                />
              </div>
              <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ form.errors.name }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-900 mb-3">
                <span class="flex items-center">
                  <span>Öğrenci Numarası (No)</span>
                  <span class="ml-2 text-red-500 font-bold">*</span>
                </span>
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                  </svg>
                </div>
                <input
                  v-model="form.no"
                  type="text"
                  class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 hover:bg-white transition"
                  :class="{ 'border-red-500 bg-red-50 focus:ring-red-500': form.errors.no }"
                  placeholder="101"
                />
              </div>
              <p v-if="form.errors.no" class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ form.errors.no }}
              </p>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start space-x-3">
              <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm text-blue-800">
                <p class="font-bold mb-1">Otomatik Hesap Oluşturma</p>
                <p>Girdiğiniz bilgilere göre sistem şunları atayacaktır:</p>
                <ul class="list-disc list-inside mt-1 space-y-1 text-blue-700 font-mono">
                  <li>Email: <strong>{{ form.no || 'no' }}@dgn.com</strong></li>
                  <li>Şifre: <strong>{{ form.name || 'name' }}passworddgn</strong></li>
                </ul>
              </div>
            </div>

          </div>
        </div>

        <div v-if="importType !== 'manual'" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
          <div :class="[
            'px-8 py-6 border-b border-gray-200',
            importType === 'csv' ? 'bg-gradient-to-r from-green-50 to-emerald-50' : 'bg-gradient-to-r from-blue-50 to-cyan-50'
          ]">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
              <div :class="[
                'w-10 h-10 rounded-lg flex items-center justify-center mr-3',
                importType === 'csv' ? 'bg-gradient-to-br from-green-500 to-emerald-600' : 'bg-gradient-to-br from-blue-500 to-cyan-600'
              ]">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              {{ importType === 'csv' ? 'CSV Dosyası Yükle' : 'Excel Dosyası Yükle' }}
            </h3>
          </div>

          <div class="p-8 space-y-6">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
              <div class="flex items-start">
                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="flex-1">
                  <p class="font-bold text-amber-900 mb-2">Önce örnek dosyayı indirin!</p>
                  <p class="text-sm text-amber-700 mb-3">
                    Dosya yapısı değişti. Sadece <strong>Name</strong> ve <strong>No</strong> alanları gereklidir.
                  </p>
                  <a
                    :href="route(importType === 'csv' ? 'students.sample.csv' : 'students.sample.excel')"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition shadow-md hover:shadow-lg active:scale-95"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Örnek {{ importType === 'csv' ? 'CSV' : 'Excel' }} Dosyasını İndir
                  </a>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-900 mb-3">
                {{ importType === 'csv' ? 'CSV Dosyası Seç' : 'Excel Dosyası Seç' }}
              </label>

              <div class="mt-3 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 transition bg-gray-50 hover:bg-indigo-50/30">
                <div class="space-y-2 text-center">
                  <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                  </svg>
                  <div class="flex text-sm text-gray-600">
                    <label class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                      <span class="text-base">Dosya seç</span>
                      <input
                        type="file"
                        class="sr-only"
                        @change="form.file = $event.target.files[0]"
                        accept=".csv,.xlsx,.xls"
                      />
                    </label>
                    <p class="pl-1">veya sürükle bırak</p>
                  </div>
                  <p class="text-xs text-gray-500">
                    {{ importType === 'csv' ? 'CSV (.csv)' : 'Excel (.xlsx, .xls)' }}
                  </p>
                </div>
              </div>

              <p v-if="form.file" class="mt-3 text-sm text-green-600 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Seçilen dosya: <strong class="ml-1">{{ form.file.name }}</strong>
              </p>

              <p v-if="form.errors.file" class="mt-2 text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ form.errors.file }}
              </p>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-6 rounded-xl border border-indigo-200">
              <div class="flex flex-col space-y-4">

                <div class="flex items-start">
                  <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-indigo-900 mb-2">Dosya Sütunları:</p>
                    <div class="flex space-x-3 text-sm text-indigo-700">
                      <p class="font-mono bg-white/60 px-3 py-1 rounded border border-indigo-100"><strong>Name:</strong> Ali Veli</p>
                      <p class="font-mono bg-white/60 px-3 py-1 rounded border border-indigo-100"><strong>No:</strong> 105</p>
                    </div>
                  </div>
                </div>

                <div class="flex items-start border-t border-indigo-200 pt-4">
                  <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-purple-900 mb-2">Otomatik İşlem:</p>
                    <p class="text-sm text-purple-800">Yüklediğiniz dosyadaki veriler kullanılarak kullanıcılar şöyle oluşturulur:</p>
                    <ul class="mt-2 space-y-1 text-sm text-purple-700 font-mono">
                      <li>• Email: <strong>[no]@dgn.com</strong></li>
                      <li>• Şifre: <strong>[name]passworddgn</strong></li>
                    </ul>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-3 pt-2">
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
            <span v-else>Öğrenciyi Kaydet</span>
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
