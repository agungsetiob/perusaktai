<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import type { ComplaintCategory } from '@/types/complaint'

// Import Heroicons untuk representasi visual alur pengaduan
import { 
    SparklesIcon,
    DocumentPlusIcon,
    MagnifyingGlassIcon,
    CpuChipIcon,
    CheckCircleIcon,
    ArrowRightIcon
} from '@heroicons/vue/24/outline'

defineProps<{
    categories: ComplaintCategory[]
}>()

// Struktur Data Alur Pengaduan (Step-by-Step)
const steps = [
    {
        id: '01',
        title: 'Kirim Aduan',
        description: 'Sampaikan laporan keluhan atau aspirasi pelayanan Anda secara rinci melalui formulir resmi.',
        icon: DocumentPlusIcon,
        color: 'text-blue-600 bg-blue-50 border-blue-100'
    },
    {
        id: '02',
        title: 'Verifikasi Data',
        description: 'Tim admin operasional memeriksa kelengkapan berkas serta mengklasifikasikan jenis aduan.',
        icon: CpuChipIcon,
        color: 'text-amber-600 bg-amber-50 border-amber-100'
    },
    {
        id: '03',
        title: 'Tindak Lanjut',
        description: 'Supervisor mengoordinasikan solusi ke unit terkait untuk perbaikan langsung di lapangan.',
        icon: MagnifyingGlassIcon,
        color: 'text-indigo-600 bg-indigo-50 border-indigo-100'
    },
    {
        id: '04',
        title: 'Selesai',
        description: 'Aduan dinyatakan tuntas, solusi diterbitkan, dan pelapor menerima riwayat hasil akhir.',
        icon: CheckCircleIcon,
        color: 'text-emerald-600 bg-emerald-50 border-emerald-100'
    }
]
</script>

<template>
    <Head title="Pusat Pengaduan Pelayanan" />

    <PublicLayout>
        <!-- MASTER CONTAINER: TRIK BREAKOUT UNTUK MENJEBOL MAX-W-7X PUBLIC LAYOUT AGAR BISA FULL LAYAR -->
        <div class="relative w-[100vw] left-1/2 right-1/2 -mx-[50vw] -mt-6 sm:-mt-10 bg-slate-950 text-slate-100 overflow-hidden border-b border-slate-900">
            
            <!-- ORNAMEN KOTAK-KOTAK (GRID MESH) FULL SCREN LEBAR LAYAR -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,#000_80%,transparent_100%)] opacity-30 pointer-events-none"></div>
            
            <!-- AURA GLOWING WARNA BRANDING -->
            <div class="absolute top-0 left-1/4 -translate-y-1/2 h-[450px] w-[600px] rounded-full bg-blue-500/10 blur-[130px] pointer-events-none"></div>
            <div class="absolute bottom-0 right-1/4 h-[350px] w-[500px] rounded-full bg-emerald-500/5 blur-[120px] pointer-events-none"></div>

            <!-- PEMBATAS KONTEN HERO (KEMBALI KE UKURAN STANDAR AGAR SEJAJAR DENGAN NAVBAR) -->
            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid gap-12 pt-16 md:grid-cols-12 md:pt-24 items-end">
                
                <!-- Sisi Narasi & Tombol (Kiri) -->
                <div class="pb-16 md:col-span-7 md:pb-24">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-950/80 border border-blue-800/40 backdrop-blur px-3.5 py-1.5 text-xs font-semibold text-blue-300 tracking-wide uppercase mb-6 shadow-sm">
                        <SparklesIcon class="h-3.5 w-3.5 text-blue-400" />
                        Layanan Aspirasi & Pengaduan
                    </span>
                    
                    <h1 class="text-[36px] font-extrabold leading-[1.1] tracking-tight text-white sm:text-[50px] lg:text-[60px]">
                        Suara Anda <span class="bg-gradient-to-r from-blue-400 via-blue-200 to-emerald-300 bg-clip-text text-transparent">Pedoman Kami</span> Untuk Berbenah.
                    </h1>
                    
                    <p class="mt-6 max-w-xl text-sm leading-[1.65] text-slate-400 sm:text-base md:text-lg">
                        Komitmen kami dalam memberikan transparansi dan kecepatan merespon keluhan demi peningkatan standar mutu operasional pelayanan.
                    </p>

                    <!-- Tombol CTA Navigasi -->
                    <div class="mt-10 flex flex-wrap gap-4">
                        <Link 
                            href="/pengaduan"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition-all hover:bg-blue-500 hover:shadow-blue-500/30 active:scale-95"
                        >
                            <span>Sampaikan Pengaduan</span>
                            <ArrowRightIcon class="h-4 w-4 stroke-[2.5]" />
                        </Link>

                        <Link 
                            href="/tracking"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 border border-slate-800 px-5 py-3 text-sm font-bold text-slate-300 transition-all hover:bg-slate-800 hover:text-white active:scale-95"
                        >
                            <span>Lacak Status Aduan</span>
                        </Link>
                    </div>
                </div>
                
                <!-- Sisi Gambar Pejabat (Kanan) -->
                <div class="flex justify-center md:col-span-5 md:self-end">
                    <img
                        src="/bupati-dan-wakil.png"
                        alt="Foto Bupati dan Wakil Bupati"
                        loading="eager"
                        fetchpriority="high"
                        class="max-h-[35vh] w-auto scale-105 md:scale-110 object-contain md:max-h-none md:w-full drop-shadow-[0_20px_50px_rgba(0,0,0,0.7)]"
                    />
                </div>

            </div>
        </div>

        <!-- ========================================== -->
        <!-- SECTION 2: ALUR PENGADUAN DI BAWAHNYA       -->
        <!-- ========================================== -->
        <div class="py-16 md:py-24">
            
            <!-- Judul Alur -->
            <div class="text-center max-w-2xl mx-auto mb-14 md:mb-18">
                <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl md:text-3xl">
                    4 Langkah Mudah Alur Penanganan
                </h2>
                <p class="mt-2 text-xs md:text-sm text-slate-500 leading-relaxed font-medium">
                    Prosedur transparansi pemrosesan laporan sejak pertama kali dikirimkan hingga selesai ditindaklanjuti oleh manajemen.
                </p>
            </div>

            <!-- Grid Pemetaan Alur Kartu -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 relative">
                
                <div 
                    v-for="(step, index) in steps" 
                    :key="step.id"
                    class="relative flex flex-col p-6 rounded-2xl border border-slate-200/80 bg-white shadow-sm shadow-slate-100/50 transition-all duration-300 hover:shadow-md hover:border-slate-300 hover:-translate-y-1 group"
                >
                    <!-- Garis Penghubung Alur (Hanya Muncul di Desktop Skala Besar) -->
                    <div 
                        v-if="index < 3"
                        class="hidden lg:block absolute top-12 left-[85%] w-[40%] h-[1px] border-t border-dashed border-slate-300 z-0 pointer-events-none"
                    ></div>

                    <!-- Nomor & Ikon Atas -->
                    <div class="flex items-center justify-between mb-4 z-10">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl border" :class="step.color">
                            <component :is="step.icon" class="h-5 w-5 stroke-[2]" />
                        </div>
                        <span class="text-2xl font-black text-slate-200 select-none group-hover:text-slate-300 transition-colors font-mono">
                            {{ step.id }}
                        </span>
                    </div>

                    <!-- Teks Deskripsi Alur -->
                    <div class="flex-1 z-10">
                        <h3 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                            {{ step.title }}
                        </h3>
                        <p class="mt-2 text-xs md:text-sm font-medium text-slate-500 leading-relaxed">
                            {{ step.description }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </PublicLayout>
</template>