<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const message = ref('')
const visible = ref(false)

watch(
  () => page.props?.flash?.success,
  (newMessage) => {
    if (newMessage) {
      message.value = newMessage
      visible.value = true
      setTimeout(() => (visible.value = false), 3000)
    }
  }
)
</script>
