<script setup>
import { usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

const page = usePage()
const show = ref(false)
const message = ref('')
const type = ref('success') // 'success' veya 'error'

// Sayfadan gelen flash verilerini dinle
watch(() => page.props.flash, (flash) => {
  if (flash?.success) {
    type.value = 'success'
    message.value = flash.success
    show.value = true
    setTimeout(() => show.value = false, 4000)
  }

  if (flash?.error) {
    type.value = 'error'
    message.value = flash.error
    show.value = true
    setTimeout(() => show.value = false, 5000)
  }
}, { deep: true })

const close = () => {
  show.value = false
}
</script>

<template>
  <transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div v-if="show" class="fixed top-20 right-4 z-50 w-full max-w-sm overflow-hidden rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
      <div class="p-4" :class="type === 'success' ? 'bg-white border-l-4 border-green-500' : 'bg-white border-l-4 border-red-500'">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg v-if="type === 'success'" class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-if="type === 'error'" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-gray-900">
              {{ type === 'success' ? 'İşlem Başarılı' : 'Bir Sorun Oluştu' }}
            </p>
            <p class="mt-1 text-sm text-gray-500">
              {{ message }}
            </p>
          </div>
          <div class="ml-4 flex flex-shrink-0">
            <button @click="close" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
              <span class="sr-only">Kapat</span>
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
