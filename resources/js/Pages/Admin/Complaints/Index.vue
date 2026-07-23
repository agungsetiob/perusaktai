<script setup lang="ts">
import { router, Link, Head, useForm } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import { formatDateTime } from '@/utils/date'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import Pagination from '@/Components/Pagination.vue'
import EditSubmittedAtModal from './Partials/EditSubmittedAtModal.vue'
import {
    FunnelIcon,
    MagnifyingGlassIcon,
    InboxIcon,
    EyeIcon,
    CalendarDaysIcon,
    DocumentArrowDownIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    complaints: any
    // rooms:any
    categories: any[]
    filters: {
        search: string | null
        status: string | null
        category_id: string | null
    }
}>()

const filter = reactive({
    search: props.filters.search ?? '',
    status: props.filters.status ?? '',
    category_id: props.filters.category_id ?? '',
})

const showSubmittedAtModal = ref(false)
const selectedComplaint = ref<any>(null)

const submittedAtForm = useForm({
    submitted_at: '',
})

function openSubmittedAtModal(complaint: any) {

    selectedComplaint.value = complaint

    submittedAtForm.submitted_at =
        complaint.submitted_at.substring(0, 16)

    showSubmittedAtModal.value = true

}

function updateSubmittedAt() {

    submittedAtForm.patch(

        route(
            'admin.complaints.update-submitted-at',
            selectedComplaint.value.id
        ),

        {
            preserveScroll: true,

            onSuccess() {

                showSubmittedAtModal.value = false

            }

        }

    )

}

function applyFilter() {
    router.get(
        route('admin.complaints.index'),
        filter,
        {
            preserveState: true,
            replace: true,
        }
    )
}

function resetFilter() {
    filter.search = ''
    filter.status = ''
    filter.category_id = ''
    applyFilter()
}
</script>

<template>

    <Head title="Daftar Pengaduan" />

    <AdminLayout>
        <div class="space-y-5 animate-fade-in">

            <!-- PAGE HEADER & PANEL EXPORT PDF -->
            <div
                class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between border-b border-slate-100 pb-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <DocumentArrowDownIcon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Daftar Pengaduan
                        </h1>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">
                            Kelola, filter, dan unduh berkas laporan pengaduan masuk dari pasien & publik.
                        </p>
                    </div>
                </div>

                <!-- Form Export PDF Bergaya Bar Horizontal Modern -->
                <form action="/admin/reports/rooms" method="GET" target="_blank"
                    class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm shadow-slate-100/50 w-full lg:w-auto">
                    <div class="flex flex-col sm:flex-row items-center gap-2.5">

                        <!-- Input Tanggal Mulai -->
                        <div class="relative w-full sm:w-40">
                            <input type="date" name="start_date" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 outline-none transition-all focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10">
                            <span class="absolute right-3 top-2.5 pointer-events-none text-slate-400">
                                <CalendarDaysIcon class="h-4 w-4" />
                            </span>
                        </div>

                        <span class="text-slate-400 text-xs font-semibold hidden sm:inline">s/d</span>

                        <!-- Input Tanggal Selesai -->
                        <div class="relative w-full sm:w-40">
                            <input type="date" name="end_date" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-medium text-slate-700 outline-none transition-all focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10">
                            <span class="absolute right-3 top-2.5 pointer-events-none text-slate-400">
                                <CalendarDaysIcon class="h-4 w-4" />
                            </span>
                        </div>

                        <!-- Button Rekap -->
                        <button
                            class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-red-600/10 hover:bg-red-700 transition-all active:scale-95 shrink-0">
                            <DocumentArrowDownIcon class="h-4 w-4" />
                            <span>Export PDF</span>
                        </button>
                    </div>
                </form>
            </div>


            <!-- PANEL FILTER -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm shadow-slate-100/50">
                <div
                    class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400">
                    <FunnelIcon class="h-4 w-4" />
                    Filter Pencarian
                </div>

                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <!-- Pencarian -->
                    <div class="relative">
                        <input v-model="filter.search" type="text" placeholder="Cari Kode Tracking..."
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 pl-10 text-sm text-slate-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            @keyup.enter="applyFilter">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <MagnifyingGlassIcon class="h-4 w-4 text-slate-400" />
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <select v-model="filter.status"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        <option value="">Semua Status</option>
                        <option value="waiting">Waiting</option>
                        <option value="on_process">On Process</option>
                        <option value="solved">Solved</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <!-- Filter Kategori -->
                    <select v-model="filter.category_id"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        <option value="">Semua Kategori</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <!-- Aksi Filter -->
                <div class="mt-4 flex items-center justify-end gap-2">
                    <button @click="resetFilter"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Reset
                    </button>
                    <button @click="applyFilter"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-blue-500/10 hover:bg-blue-700 transition-all active:scale-95">
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <!-- PANEL TABEL DATA -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm shadow-slate-100/50">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left text-sm">
                        <thead
                            class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Tracking Code</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Ruangan</th>
                                <th class="px-6 py-4">Pelapor</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal Masuk</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <!-- Kondisi Jika Data Kosong -->
                            <tr v-if="complaints.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <InboxIcon class="h-8 w-8 text-slate-300 animate-pulse" />
                                        <span class="text-sm font-medium">Tidak ada data pengaduan yang
                                            ditemukan.</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Looping Data -->
                            <tr v-for="complaint in complaints.data" :key="complaint.id"
                                class="transition-colors hover:bg-slate-50/40">
                                <!-- Kode Tracking -->
                                <td
                                    class="whitespace-nowrap px-6 py-4 font-mono font-bold text-blue-600 text-xs tracking-wider">
                                    {{ complaint.tracking_code }}
                                </td>

                                <!-- Nama Kategori -->
                                <td class="px-6 py-4 text-slate-700 font-medium">
                                    {{ complaint.category.name }}
                                </td>

                                <td class="px-6 py-4 text-slate-700 font-medium">
                                    {{ complaint.room_name }}
                                </td>

                                <!-- Identitas Pelapor -->
                                <td class="px-6 py-4">
                                    <span v-if="complaint.is_anonymous"
                                        class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 border border-slate-200">
                                        Anonim
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-600 border border-blue-200">
                                        {{ complaint.name }}
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="whitespace-nowrap px-6 py-4">
                                    <StatusBadge :status="complaint.status" />
                                </td>

                                <!-- Tanggal -->
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500 text-xs">
                                    {{ formatDateTime(complaint.submitted_at) }}
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="whitespace-nowrap px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">

                                        <button type="button" @click="openSubmittedAtModal(complaint)"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 transition hover:bg-amber-100">

                                            <CalendarDaysIcon class="h-3.5 w-3.5" />

                                            <span>Tanggal</span>

                                        </button>

                                        <Link :href="route('admin.complaints.show', complaint.id)"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-700 shadow-sm hover:text-blue-600">

                                            <EyeIcon class="h-3.5 w-3.5" />

                                            Detail

                                        </Link>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="complaints.links" />
            </div>

        </div>

        <EditSubmittedAtModal :show="showSubmittedAtModal" :complaint="selectedComplaint"
            @close="showSubmittedAtModal = false"/>
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