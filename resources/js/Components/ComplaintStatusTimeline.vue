<script setup lang="ts">
// Impor StatusBadge agar logika label dan warna langsung sinkron otomatis
import StatusBadge from '@/Components/StatusBadge.vue' 
import { CalendarDaysIcon } from '@heroicons/vue/24/outline'
import { formatDateTime } from '@/utils/date'

defineProps<{
    logs: Array<{
        id: number
        old_status: string | null
        new_status: string
        note: string
        created_at: string
    }>
}>()
</script>

<template>
    <!-- Kontainer Lini Masa dengan Garis Halus Modern -->
    <ol class="relative border-l border-slate-200 pl-6 space-y-6">
        <!-- Loop data log pengaduan -->
        <li
            v-for="log in logs"
            :key="log.id"
            class="relative group"
        >
            <!-- Titik Penanda Status (Indicator Dot) dengan Efek Cincin Putih -->
            <div
                class="absolute -left-[30px] top-1 h-3.5 w-3.5 rounded-full border-2 border-white bg-blue-600 shadow-sm ring-4 ring-blue-50 transition-colors group-hover:bg-blue-700"
            />

            <!-- Blok Konten Log Ringan & Bersih -->
            <div class="space-y-1.5">
                <!-- Baris Informasi Waktu / Tanggal -->
                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                    <CalendarDaysIcon class="h-3.5 w-3.5 shrink-0" />
                    <span>{{ formatDateTime(log.created_at) }}</span>
                </div>

                <!-- SEKARANG MENGGUNAKAN STATUS BADGE SINKRON -->
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Status:</span>
                    <!-- Langsung panggil komponen StatusBadge, kirim status baru ke props -->
                    <StatusBadge :status="log.new_status" />
                </div>

                <!-- Catatan / Keterangan dari Admin / Sistem -->
                <div class="text-sm text-slate-600 bg-slate-50/60 border border-slate-100 rounded-xl p-3 inline-block max-w-full leading-relaxed mt-1 shadow-sm shadow-slate-50">
                    {{ log.note || 'Tidak ada catatan tambahan.' }}
                </div>
            </div>
        </li>
    </ol>
</template>