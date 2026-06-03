<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import CopyTrackingCode from '@/Components/CopyTrackingCode.vue'

// Import Heroicons untuk tampilan ikon sukses yang modern
import { CheckCircleIcon, ArrowRightIcon, PlusIcon } from '@heroicons/vue/24/outline'

// Mengambil data tracking_code dari Backend (Laravel/Inertia)
defineProps<{
    tracking_code: string
}>()
</script>

<template>
    <Head title="Pengaduan Berhasil" />

    <PublicLayout>
        <div class="mx-auto max-w-xl px-4 py-6">
            
            <!-- Card Container Utama -->
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 shadow-md text-center relative">
                
                <!-- Dekonstruksi Visual/Aksen Atas -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></div>
                
                <!-- Status Icon & Title -->
                <div class="mb-8 mt-2">
                    <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 ring-8 ring-emerald-50/50 animate-pulse-subtle">
                        <CheckCircleIcon class="h-10 w-10" />
                    </div>
                    
                    <h1 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">
                        Laporan Berhasil Terkirim
                    </h1>
                    
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">
                        Terima kasih telah berkontribusi dalam perbaikan layanan kami. Pengaduan Anda telah tercatat dengan aman di dalam sistem.
                    </p>
                </div>

                <!-- Box Kode Tracking Premium -->
                <div class="mb-6 rounded-2xl border border-amber-200/80 bg-gradient-to-b from-amber-50/70 to-amber-50/30 p-5 shadow-inner">
                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-amber-800">
                        Simpan Kode Pelacakan Anda
                    </p>
                    
                    <!-- Mempassing data ke komponen anak -->
                    <CopyTrackingCode :code="tracking_code" />
                </div>

                <!-- Warning/Catatan Kecil -->
                <div class="mb-8 rounded-xl bg-slate-50 p-3.5 text-xs text-slate-500 text-left border border-slate-100/80">
                    <span class="font-semibold text-slate-700 block mb-0.5">⚠️ Catatan Penting:</span>
                    Kode di atas digunakan untuk memantau status perkembangan aduan secara berkala.
                </div>

                <!-- Kelompok Tombol Navigasi/Aksi -->
                <div class="grid gap-3 sm:grid-cols-2">
                    <Link
                        :href="route('tracking.index')"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-500/10 transition-all hover:bg-blue-700 active:scale-95"
                    >
                        Pantau Status
                        <ArrowRightIcon class="h-4 w-4" />
                    </Link>
                    
                    <Link
                        :href="route('complaints.create')"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-95"
                    >
                        <PlusIcon class="h-4 w-4 text-slate-500" />
                        Aduan Baru
                    </Link>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* Animasi ring berdenyut halus agar tidak terlalu agresif */
.animate-pulse-subtle {
    animation: pulseSubtle 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulseSubtle {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.2);
    }
    50% {
        transform: scale(1.03);
        box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
    }
}
</style>