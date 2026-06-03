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
    ArrowTopRightOnSquareIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    stats: {
        total: number
        waiting: number
        under_review: number
        on_process: number
        solved: number
        rejected: number
        avg_resolution_hours: number
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
    colors: ['#2563eb'], // Blue 600
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
        labels: { style: { colors: '#94a3b8', fontSize: '12px' } }
    },
    tooltip: { y: { formatter: (val: number) => `${val} Pengaduan` } }
}))

const statusSeries = computed(() => props.statusDistribution.map(item => item.value))
const statusLabels = computed(() => props.statusDistribution.map(item => item.label))

const statusChartOptions = computed(() => ({
    chart: { fontFamily: 'Plus Jakarta Sans, Inter, sans-serif' },
    labels: statusLabels.value,
    colors: ['#eab308', '#f97316', '#3b82f6', '#10b981', '#ef4444', '#64748b'], 
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
        <div class="space-y-6 max-w-7xl mx-auto px-1 animate-fade-in">
            
            <div class="border-b border-slate-100 pb-5">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl flex items-center gap-2.5">
                    <Squares2X2Icon class="h-8 w-8 text-blue-600" />
                    <span>Ringkasan Dashboard</span>
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Pantau statistik pengaduan, performa penyelesaian, dan laporan terbaru secara real-time.
                </p>
            </div>

            <div class="grid gap-4 grid-cols-2 md:grid-cols-4 lg:grid-cols-8">
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.total }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 border-l-4 border-l-yellow-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-yellow-600">Menunggu</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.waiting }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 border-l-4 border-l-orange-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-orange-600">Ditinjau</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.under_review }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 border-l-4 border-l-blue-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Diproses</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.on_process }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 border-l-4 border-l-emerald-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Selesai</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.solved }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 border-l-4 border-l-red-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-red-600">Ditolak</p>
                    <p class="text-2xl font-bold text-slate-900 mt-1">{{ stats.rejected }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 flex flex-col justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <ClockIcon class="h-3.5 w-3.5" /> Rerata Solusi
                    </p>
                    <p class="text-xl font-bold text-slate-900 mt-1">{{ stats.avg_resolution_hours }}<span class="text-xs font-normal text-slate-500 ml-0.5">jam</span></p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm shadow-slate-100/50 flex flex-col justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <CheckCircleIcon class="h-3.5 w-3.5" /> Penyelesaian
                    </p>
                    <p class="text-xl font-bold text-emerald-600 mt-1">{{ stats.completion_rate }}%</p>
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
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm shadow-slate-100/50 flex flex-col justify-between lg:col-span-1">
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
                                <tr v-for="item in topCategories" :key="item.category.name" class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-4 py-3 text-slate-700 font-medium">{{ item.category.name }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900">{{ item.total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white shadow-sm shadow-slate-100/50 lg:col-span-2 overflow-hidden flex flex-col justify-between">
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
                                <tr v-for="complaint in latestComplaints" :key="complaint.id" class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3.5 font-mono font-semibold text-blue-600 text-xs tracking-wider">
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
                                        <Link 
                                            :href="route('admin.complaints.show', complaint.id)"
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 transition-all"
                                        >
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
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>