<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  stats: Object,
});
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- İstatistik Kartları -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            class="bg-white overflow-hidden shadow rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition"
          >
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">
                  Aktif Öğrenciler
                </h3>
                <p class="text-3xl font-bold text-indigo-600 mt-1">
                  {{ stats.active_students }}
                </p>
              </div>
              <div
                class="p-3 bg-indigo-100 text-indigo-600 rounded-full shadow-inner"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5V4H2v16h5m10 0V10m0 10H7m10 0v-8"
                  />
                </svg>
              </div>
            </div>
          </div>

          <div
            class="bg-white overflow-hidden shadow rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition"
          >
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">
                  Bekleyen Onaylar
                </h3>
                <p class="text-3xl font-bold text-yellow-500 mt-1">
                  {{ stats.pending_approvals }}
                </p>
              </div>
              <div
                class="p-3 bg-yellow-100 text-yellow-600 rounded-full shadow-inner"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6 5H3a2 2 0 01-2-2V7a2 2 0 012-2h18a2 2 0 012 2v11a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
            </div>
          </div>

          <div
            class="bg-white overflow-hidden shadow rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition"
          >
            <div class="flex items-center justify-between">
              <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase">
                  Son Aktiviteler
                </h3>
                <p class="text-3xl font-bold text-emerald-600 mt-1">
                  {{ stats.recent_activity }}
                </p>
              </div>
              <div
                class="p-3 bg-emerald-100 text-emerald-600 rounded-full shadow-inner"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m2 4H7m5-8h2m4-2H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2z"
                  />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Son Aktivite Listesi -->
        <div class="bg-white overflow-hidden shadow rounded-2xl p-6 border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">
            Son Aktiviteler
          </h3>
          <ul class="divide-y divide-gray-200">
            <li class="py-3 text-gray-600">Henüz aktivite bulunmuyor.</li>
          </ul>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
