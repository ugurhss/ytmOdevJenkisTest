<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  user: Object,
});
</script>

<template>
  <Head title="Öğrenci Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Hoş geldin, {{ user.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Gruplar -->
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Grupların
          </h3>

          <div v-if="user.groups.length > 0" class="grid gap-6 md:grid-cols-2">
            <div
              v-for="group in user.groups"
              :key="group.id"
              class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition"
            >
              <h4 class="text-lg font-semibold text-indigo-600 mb-2">
                {{ group.name }}
              </h4>

              <div v-if="group.announcements.length > 0">
                <h5 class="text-sm font-semibold text-gray-600 mb-1">
                  Duyurular:
                </h5>
                <ul class="text-gray-700 text-sm space-y-1">
                  <li
                    v-for="ann in group.announcements"
                    :key="ann.id"
                    class="p-2 bg-gray-50 rounded-lg border hover:bg-indigo-50 transition"
                  >
                    <p class="font-medium">{{ ann.title }}</p>
                    <p class="text-xs text-gray-500">
                      Yazan: {{ ann.user.name }}
                    </p>
                  </li>
                </ul>
              </div>
              <div v-else class="text-gray-500 text-sm italic">
                Henüz duyuru bulunmuyor.
              </div>
            </div>
          </div>

          <div v-else class="text-gray-500 text-sm italic">
            Henüz bir gruba üye değilsin.
          </div>
        </div>

        <!-- Duyuru Ekranı -->
        <div class="bg-white shadow rounded-2xl p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Genel Bilgilendirme
          </h3>
          <p class="text-gray-600">
            Buradan üye olduğun grupların duyurularını takip edebilirsin.
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
