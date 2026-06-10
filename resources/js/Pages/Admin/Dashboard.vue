<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import { formatDateTime } from '@/utils/date'
import { computed } from 'vue'
import {
    Squares2X2Icon,
    ClockIcon,
    CheckCircleIcon,
    InboxIcon,
    ChartBarIcon,
    ArrowTopRightOnSquareIcon,
    ClipboardDocumentListIcon,
    DocumentMagnifyingGlassIcon,
    ArrowPathIcon,
    XCircleIcon,
    CalendarDaysIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    stats: {
        total: number
        waiting: number
        under_review: number
        on_process: number
        solved: number
        rejected: number
        avg_resolution_hours: string | number
        solved_this_month: number
        completion_rate: number
    }
    monthlyComplaints: Array<{
        month: string
        total: number
    }>
    statusDistribution: Array<{
        label: string
        value: number
    }>
    topCategories: Array<{
        total: number
        category: {
            name: string
        }
    }>
    latestComplaints: any[]
}>()

const monthlySeries = computed(() => [
    {
        name: 'Total Pengaduan',
        data: props.monthlyComplaints.map(item => item.total),
    },
])

const monthlyChartOptions = computed(() => ({
    chart: {
        fontFamily: 'Plus Jakarta Sans, Inter, sans-serif',
        toolbar: { show: false },
        sparkline: { enabled: false },
    },
    colors: ['#2563eb'],
    stroke: { curve: 'smooth', width: 3 },
    markers: { size: 4, colors: ['#2563eb'], strokeWidth: 2, hover: { size: 6 } },
    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
    xaxis: {
        categories: props.monthlyComplaints.map(item => item.month),
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: { style: { colors: '#94a3b8', fontSize: '12px' } }
    },
    yaxis: {
        labels: {
            style: { colors: '#94a3b8', fontSize: '12px' },
            formatter: (val: number) => {
                return val % 1 === 0 ? val.toFixed(0) : ''
            }
        },
        min: 0,
        forceNiceScale: true
    },
    tooltip: { y: { formatter: (val: number) => `${val} Pengaduan` } }
}))

const statusSeries = computed(() => props.statusDistribution.map(item => item.value))
const statusLabels = computed(() => props.statusDistribution.map(item => item.label))

const statusChartOptions = computed(() => ({
    chart: { fontFamily: 'Plus Jakarta Sans, Inter, sans-serif' },
    labels: statusLabels.value,
    colors: ['#eab308', '#f97316', '#3b82f6', '#10b981', '#ef4444'],
    legend: { position: 'bottom', fontSize: '12px', labels: { colors: '#475569' } },
    dataLabels: { enabled: true, dropShadow: { enabled: false } },
    plotOptions: {
        pie: {
            donut: {
                size: '75%',
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: 'Total Data',
                        fontSize: '12px',
                        color: '#64748b',
                        formatter: () => props.stats.total
                    }
                }
            }
        }
    }
}))
</script>

<template>

    <Head title="Dashboard Analitik" />

    <AdminLayout>
        <div class="space-y-5 animate-fade-in">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between border-b border-slate-100 pb-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <Squares2X2Icon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Dashboard
                        </h1>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">
                            Pantau statistik pengaduan, performa penyelesaian, dan laporan terbaru secara real-time.
                        </p>
                    </div>
                </div>

                <!-- Form Export PDF Bergaya Bar Horizontal Modern -->
                <form action="/admin/reports/complaints" method="GET" target="_blank"
                    class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm shadow-slate-100/50 w-full lg:w-auto">
                    <div class="flex flex-col sm:flex-row items-center gap-2.5">
                        
                        <!-- Input Tanggal Mulai -->
                        <div class="relative w-full sm:w-40">
                            <input 
                                type="date" 
                                name="start_date" 
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 outline-none transition-all focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10"
                            >
                            <span class="absolute right-3 top-2.5 pointer-events-none text-slate-400">
                                <CalendarDaysIcon class="h-4 w-4" />
                            </span>
                        </div>

                        <span class="text-slate-400 text-xs font-semibold hidden sm:inline">s/d</span>

                        <!-- Input Tanggal Selesai -->
                        <div class="relative w-full sm:w-40">
                            <input 
                                type="date" 
                                name="end_date" 
                                required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 outline-none transition-all focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10"
                            >
                            <span class="absolute right-3 top-2.5 pointer-events-none text-slate-400">
                                <CalendarDaysIcon class="h-4 w-4" />
                            </span>
                        </div>

                        <!-- Button Rekap -->
                        <button class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-red-600/10 hover:bg-red-700 transition-all active:scale-95 shrink-0">
                            <DocumentMagnifyingGlassIcon class="h-4 w-4" />
                            <span>Export PDF</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">

                <div class="lg:col-span-2 grid gap-4 sm:grid-cols-3">

                    <div
                        class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs flex flex-col justify-between hover:border-blue-200 transition-colors">
                        <div>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100/50">
                                <ClipboardDocumentListIcon class="h-5 w-5" />
                            </div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-4">Total Pengaduan
                            </p>
                            <p class="text-3xl font-black text-slate-900 mt-1">{{ stats.total }}</p>
                        </div>
                        <div class="text-[11px] font-medium text-slate-400 mt-4 pt-2 border-t border-slate-50">
                            Seluruh berkas aduan masuk
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs flex flex-col justify-between hover:border-emerald-200 transition-colors">
                        <div>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100/50">
                                <CheckCircleIcon class="h-5 w-5" />
                            </div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-4">Rasio Penyelesaian
                            </p>
                            <p class="text-3xl font-black text-emerald-600 mt-1">{{ stats.completion_rate }}%</p>
                        </div>
                        <div class="text-[11px] font-medium text-slate-400 mt-4 pt-2 border-t border-slate-50">
                            Berhasil diselesaikan penuh
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-slate-100 bg-white p-5 shadow-xs flex flex-col justify-between hover:border-indigo-200 transition-colors">
                        <div>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                                <ClockIcon class="h-5 w-5" />
                            </div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-4">Rerata Solusi</p>
                            <p class="text-lg font-extrabold text-slate-900 mt-2 truncate">{{ stats.avg_resolution_hours
                                }}</p>
                        </div>
                        <div class="text-[11px] font-medium text-slate-400 mt-4 pt-2 border-t border-slate-50">
                            Waktu respons penanganan
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-xs flex flex-col justify-between">
                    <div class="grid grid-cols-5 gap-1.5 h-full">

                        <div
                            class="flex flex-col items-center justify-center text-center rounded-xl bg-slate-50/50 border border-slate-100 p-2 hover:bg-yellow-50/40 hover:border-yellow-100 transition-colors">
                            <span
                                class="p-1.5 rounded-lg bg-yellow-50 text-yellow-600 border border-yellow-100/50 mb-1">
                                <InboxIcon class="h-4 w-4" />
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tunggu</span>
                            <span class="text-base font-extrabold text-slate-900 mt-0.5">{{ stats.waiting }}</span>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center text-center rounded-xl bg-slate-50/50 border border-slate-100 p-2 hover:bg-orange-50/40 hover:border-orange-100 transition-colors">
                            <span
                                class="p-1.5 rounded-lg bg-orange-50 text-orange-600 border border-orange-100/50 mb-1">
                                <DocumentMagnifyingGlassIcon class="h-4 w-4" />
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tinjau</span>
                            <span class="text-base font-extrabold text-slate-900 mt-0.5">{{ stats.under_review }}</span>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center text-center rounded-xl bg-slate-50/50 border border-slate-100 p-2 hover:bg-blue-50/40 hover:border-blue-100 transition-colors">
                            <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600 border border-blue-100/50 mb-1">
                                <ArrowPathIcon class="h-4 w-4" />
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Proses</span>
                            <span class="text-base font-extrabold text-slate-900 mt-0.5">{{ stats.on_process }}</span>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center text-center rounded-xl bg-slate-50/50 border border-slate-100 p-2 hover:bg-emerald-50/40 hover:border-emerald-100 transition-colors">
                            <span
                                class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100/50 mb-1">
                                <CheckCircleIcon class="h-4 w-4" />
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Selesai</span>
                            <span class="text-base font-extrabold text-emerald-700 mt-0.5">{{ stats.solved }}</span>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center text-center rounded-xl bg-slate-50/50 border border-slate-100 p-2 hover:bg-red-50/40 hover:border-red-100 transition-colors">
                            <span class="p-1.5 rounded-lg bg-red-50 text-red-600 border border-red-100/50 mb-1">
                                <XCircleIcon class="h-4 w-4" />
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Tolak</span>
                            <span class="text-base font-extrabold text-red-600 mt-0.5">{{ stats.rejected }}</span>
                        </div>

                    </div>
                </div>

            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm shadow-slate-100/50">
                    <div class="mb-4">
                        <h2 class="text-base font-bold text-slate-900">Tren Pengaduan per Bulan</h2>
                        <p class="text-xs text-slate-400">Total akumulasi laporan sepanjang tahun berjalan.</p>
                    </div>
                    <apexchart type="line" height="300" :options="monthlyChartOptions" :series="monthlySeries" />
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm shadow-slate-100/50">
                    <div class="mb-4">
                        <h2 class="text-base font-bold text-slate-900">Persentase Status Laporan</h2>
                        <p class="text-xs text-slate-400">Rasio pembagian penanganan</p>
                    </div>
                    <apexchart type="donut" height="300" :options="statusChartOptions" :series="statusSeries" />
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div
                    class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm shadow-slate-100/50 flex flex-col justify-between lg:col-span-1">
                    <div class="mb-4">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-1.5">
                            <ChartBarIcon class="h-5 w-5 text-slate-500" /> Kategori Terbanyak
                        </h2>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50/70 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama Kategori</th>
                                    <th class="px-4 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="item in topCategories" :key="item.category.name"
                                    class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-4 py-3 text-slate-700 font-medium">{{ item.category.name }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900">{{ item.total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-slate-100 bg-white shadow-sm shadow-slate-100/50 lg:col-span-2 overflow-hidden flex flex-col justify-between">
                    <div class="p-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-1.5">
                            <InboxIcon class="h-5 w-5 text-slate-500" /> Pengaduan Terbaru Masuk
                        </h2>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="bg-slate-50/70 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5 text-left">Kode Tracking</th>
                                    <th class="px-5 py-3.5 text-left">Kategori</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5 text-left">Tanggal</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <tr v-for="complaint in latestComplaints" :key="complaint.id"
                                    class="hover:bg-slate-50/50 transition-colors">
                                    <td
                                        class="px-5 py-3.5 font-mono font-semibold text-blue-600 text-xs tracking-wider">
                                        {{ complaint.tracking_code }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-700 font-medium">
                                        {{ complaint.category?.name || 'Umum' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                        <StatusBadge :status="complaint.status" />
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-500 text-xs">
                                        {{ formatDateTime(complaint.submitted_at) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <Link :href="route('admin.complaints.show', complaint.id)"
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 transition-all">
                                            <span>Detail</span>
                                            <ArrowTopRightOnSquareIcon class="h-3.5 w-3.5" />
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(6px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>