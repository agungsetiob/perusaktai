<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { MagnifyingGlassIcon, TicketIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{
  errorMessage?: string
  old?: {
    tracking_code?: string
  }
}>()

const tracking_code = ref(props.old?.tracking_code ?? '')

function submit() {
  router.get(route('tracking.show', tracking_code.value))
}
</script>

<template>
  <Head title="Tracking Pengaduan" />

  <PublicLayout>
    <div class="mx-auto max-w-xl px-4 py-6">
      <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-md sm:p-8 relative">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 to-indigo-500"></div>

        <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 mt-2">
          <TicketIcon class="h-6 w-6" />
        </div>

        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
          Lacak Status Pengaduan
        </h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">
          Masukkan kode unik pelacakan (*tracking code*) yang Anda dapatkan setelah mengirim laporan untuk melihat perkembangan status penanganan saat ini.
        </p>

        <!-- Pesan error -->
        <div v-if="props.errorMessage" class="mt-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">
          ⚠ {{ props.errorMessage }}
        </div>

        <form @submit.prevent="submit" class="mt-6 space-y-4">
          <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-400">
              Kode Tracking
            </label>
            <div class="relative">
              <input
                v-model="tracking_code"
                type="text"
                placeholder="Contoh: RS-20260601-ABC123"
                class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 pl-11 text-center font-mono text-base font-bold tracking-wider text-slate-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
              >
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <MagnifyingGlassIcon class="h-5 w-5 text-slate-400" />
              </div>
            </div>
          </div>

          <button
            type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-500/10 transition-all hover:bg-blue-700 active:scale-[0.99]"
          >
            Cari Laporan
          </button>
        </form>
      </div>
    </div>
  </PublicLayout>
</template>
