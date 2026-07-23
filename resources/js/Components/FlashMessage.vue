<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const showSuccess = ref(false)
const showError = ref(false)
let autoHideTimer: ReturnType<typeof setTimeout> | null = null

watch(
  () => page.props.flash,
  (newFlash) => {
    if (autoHideTimer) clearTimeout(autoHideTimer)

    if (newFlash?.success) {
      showSuccess.value = true
      showError.value = false
      autoHideTimer = setTimeout(() => { showSuccess.value = false }, 5000)
    } else if (newFlash?.error) {
      showError.value = true
      showSuccess.value = false
      autoHideTimer = setTimeout(() => { showError.value = false }, 5000)
    }
  },
  { immediate: true, deep: true }
)

onBeforeUnmount(() => {
  if (autoHideTimer) clearTimeout(autoHideTimer)
})

const closeNotification = () => {
  if (autoHideTimer) clearTimeout(autoHideTimer)
  showSuccess.value = false
  showError.value = false
}
</script>

<template>
  <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-full max-w-sm">
    
    <!-- Notifikasi Sukses -->
    <transition name="toast-slide" mode="out-in">
      <div v-if="showSuccess" key="success" class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-lg flex items-start gap-3">
        <span class="flex-1">{{ page.props.flash?.success }}</span>
        <button @click="closeNotification" class="text-green-600 hover:text-green-800 focus:outline-none shrink-0 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </transition>

    <!-- Notifikasi Error -->
    <transition name="toast-slide" mode="out-in">
      <div v-if="showError" key="error" class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow-lg flex items-start gap-3">
        <span class="flex-1">{{ page.props.flash?.error }}</span>
        <button @click="closeNotification" class="text-red-600 hover:text-red-800 focus:outline-none shrink-0 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </transition>

  </div>
</template>

<style scoped>
.toast-slide-enter-active { transition: all 0.3s ease-out; }
.toast-slide-leave-active { transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1); }
.toast-slide-enter-from { opacity: 0; transform: translateX(30px) scale(0.95); }
.toast-slide-leave-to { opacity: 0; transform: translateX(30px) scale(0.95); }
</style>