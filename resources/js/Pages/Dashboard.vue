<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow rounded-2xl p-6 text-center border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-700 mb-2">
            Hoş geldiniz 👋
          </h3>
          <p class="text-gray-600">
            Henüz bir role sahip değilsiniz. Lütfen sistem yöneticinizle iletişime geçin.
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
