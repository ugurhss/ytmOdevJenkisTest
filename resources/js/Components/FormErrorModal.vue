<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  errors: Object,
});

const isOpen = ref(false);

watch(
  () => props.errors,
  (val) => {
    if (val && Object.keys(val).length > 0) {
      isOpen.value = true;
    }
  },
  { deep: true }
);
</script>

<template>
  <transition name="fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
    >
      <div class="bg-white rounded-lg p-6 shadow-lg w-96">
        <h2 class="text-lg font-semibold mb-3 text-red-600">Form Hataları</h2>

        <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
          <li v-for="(msg, key) in errors" :key="key">{{ msg }}</li>
        </ul>

        <div class="mt-4 text-right">
          <button
            @click="isOpen = false"
            class="bg-red-600 text-white px-4 py-1 rounded-lg hover:bg-red-700"
          >
            Kapat
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
