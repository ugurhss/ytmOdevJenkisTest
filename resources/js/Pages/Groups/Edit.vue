<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import FormErrorModal from '@/Components/FormErrorModal.vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  group: Object,
  cities: Array,
})

const form = useForm({
  groups_name: props.group.groups_name,
  city_id: props.group.city_id,
  university_id: props.group.university_id,
  faculty_id: props.group.faculty_id,
  department_id: props.group.department_id,
  class_models_id: props.group.class_models_id,
})

const universities = ref([])
const faculties = ref([])
const departments = ref([])
const classes = ref([])
const loading = ref(false)

// Başlangıçta mevcut grup verisine göre dropdown'ları doldur
onMounted(async () => {
  loading.value = true
  try {
    if (props.group.city_id) {
      const uniRes = await axios.get(`/api/universities/${props.group.city_id}`)
      universities.value = uniRes.data
    }

    if (props.group.university_id) {
      const facRes = await axios.get(`/api/faculties/${props.group.university_id}`)
      faculties.value = facRes.data
    }

    if (props.group.faculty_id) {
      const depRes = await axios.get(`/api/departments/${props.group.faculty_id}`)
      departments.value = depRes.data
    }

    if (props.group.department_id) {
      const clsRes = await axios.get(`/api/classes/${props.group.department_id}`)
      classes.value = clsRes.data
    }
  } catch (error) {
    console.error('Veriler yüklenirken hata:', error)
  } finally {
    loading.value = false
  }
})

// Şehir değiştiğinde
watch(() => form.city_id, async (cityId) => {
  if (cityId && cityId !== props.group.city_id) {
    form.university_id = ''
    form.faculty_id = ''
    form.department_id = ''
    form.class_models_id = ''
    universities.value = []
    faculties.value = []
    departments.value = []
    classes.value = []

    try {
      const res = await axios.get(`/api/universities/${cityId}`)
      universities.value = res.data
    } catch (error) {
      console.error('Üniversiteler yüklenemedi:', error)
    }
  }
})

// Üniversite değiştiğinde
watch(() => form.university_id, async (universityId) => {
  if (universityId && universityId !== props.group.university_id) {
    form.faculty_id = ''
    form.department_id = ''
    form.class_models_id = ''
    faculties.value = []
    departments.value = []
    classes.value = []

    try {
      const res = await axios.get(`/api/faculties/${universityId}`)
      faculties.value = res.data
    } catch (error) {
      console.error('Fakülteler yüklenemedi:', error)
    }
  }
})

// Fakülte değiştiğinde
watch(() => form.faculty_id, async (facultyId) => {
  if (facultyId && facultyId !== props.group.faculty_id) {
    form.department_id = ''
    form.class_models_id = ''
    departments.value = []
    classes.value = []

    try {
      const res = await axios.get(`/api/departments/${facultyId}`)
      departments.value = res.data
    } catch (error) {
      console.error('Bölümler yüklenemedi:', error)
    }
  }
})

// Bölüm değiştiğinde
watch(() => form.department_id, async (departmentId) => {
  if (departmentId && departmentId !== props.group.department_id) {
    form.class_models_id = ''
    classes.value = []

    try {
      const res = await axios.get(`/api/classes/${departmentId}`)
      classes.value = res.data
    } catch (error) {
      console.error('Sınıflar yüklenemedi:', error)
    }
  }
})

const submitForm = () => {
  form.put(route('groups.update', props.group.id), {
    preserveScroll: true,
    onSuccess: () => {
      // Başarılı olursa bir şey yapılabilir
    },
  })
}

const cancelForm = () => {
  router.visit(route('groups.show', props.group.id))
}
</script>

<template>
  <Head title="Grup Düzenle" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Grup Düzenle</h2>
    </template>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <FormErrorModal :errors="form.errors" />

      <div v-if="loading" class="bg-white shadow rounded-xl p-8 text-center">
        <p class="text-gray-600">Yükleniyor...</p>
      </div>

      <form
        v-else
        @submit.prevent="submitForm"
        class="bg-white shadow rounded-xl p-8 space-y-6"
      >
        <!-- Grup Adı -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Grup Adı <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.groups_name"
            type="text"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            :class="{ 'border-red-500': form.errors.groups_name }"
            placeholder="Grup adını giriniz"
            required
          />
          <p v-if="form.errors.groups_name" class="mt-1 text-sm text-red-600">
            {{ form.errors.groups_name }}
          </p>
        </div>

        <!-- Şehir -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Şehir <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.city_id"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
            :class="{ 'border-red-500': form.errors.city_id }"
            required
          >
            <option value="">Seçiniz</option>
            <option v-for="city in props.cities" :key="city.id" :value="city.id">
              {{ city.name }}
            </option>
          </select>
          <p v-if="form.errors.city_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.city_id }}
          </p>
        </div>

        <!-- Üniversite -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Üniversite <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.university_id"
            :disabled="!universities.length"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{ 'border-red-500': form.errors.university_id }"
            required
          >
            <option value="">{{ universities.length ? 'Seçiniz' : 'Önce şehir seçiniz' }}</option>
            <option v-for="uni in universities" :key="uni.id" :value="uni.id">
              {{ uni.name }}
            </option>
          </select>
          <p v-if="form.errors.university_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.university_id }}
          </p>
        </div>

        <!-- Fakülte -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Fakülte <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.faculty_id"
            :disabled="!faculties.length"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{ 'border-red-500': form.errors.faculty_id }"
            required
          >
            <option value="">{{ faculties.length ? 'Seçiniz' : 'Önce üniversite seçiniz' }}</option>
            <option v-for="fac in faculties" :key="fac.id" :value="fac.id">
              {{ fac.name }}
            </option>
          </select>
          <p v-if="form.errors.faculty_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.faculty_id }}
          </p>
        </div>

        <!-- Bölüm -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Bölüm <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.department_id"
            :disabled="!departments.length"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{ 'border-red-500': form.errors.department_id }"
            required
          >
            <option value="">{{ departments.length ? 'Seçiniz' : 'Önce fakülte seçiniz' }}</option>
            <option v-for="dep in departments" :key="dep.id" :value="dep.id">
              {{ dep.name }}
            </option>
          </select>
          <p v-if="form.errors.department_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.department_id }}
          </p>
        </div>

        <!-- Sınıf -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Sınıf <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.class_models_id"
            :disabled="!classes.length"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{ 'border-red-500': form.errors.class_models_id }"
            required
          >
            <option value="">{{ classes.length ? 'Seçiniz' : 'Önce bölüm seçiniz' }}</option>
            <option v-for="cls in classes" :key="cls.id" :value="cls.id">
              {{ cls.name }}
            </option>
          </select>
          <p v-if="form.errors.class_models_id" class="mt-1 text-sm text-red-600">
            {{ form.errors.class_models_id }}
          </p>
        </div>

        <!-- Butonlar -->
        <div class="flex justify-end space-x-3 pt-4">
          <button
            type="button"
            @click="cancelForm"
            class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
            :disabled="form.processing"
          >
            İptal
          </button>

          <button
            type="submit"
            :disabled="form.processing"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Güncelleniyor...</span>
            <span v-else>Güncelle</span>
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
