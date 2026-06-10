<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue'
import { formatDateTime } from '@/utils/date'

import {
    ClockIcon,
    UserIcon,
    CpuChipIcon,
    DocumentMagnifyingGlassIcon,
    FunnelIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    logs: any
    filters: any
}>()

const module = ref(props.filters.module ?? '')
const action = ref(props.filters.action ?? '')

function filter() {
    router.get(
        route('admin.audit-logs.index'),
        {
            module: module.value,
            action: action.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}

// Helper untuk mempercantik badge Modul
function getModuleClass(mod: string) {
    const base = 'inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold border '
    switch (mod?.toLowerCase()) {
        case 'complaint':
            return base + 'bg-amber-50 text-amber-700 border-amber-200/60'
        case 'complaint_response':
            return base + 'bg-emerald-50 text-emerald-700 border-emerald-200/60'
        case 'user':
            return base + 'bg-blue-50 text-blue-700 border-blue-200/60'
        case 'category':
            return base + 'bg-purple-50 text-purple-700 border-purple-200/60'
        default:
            return base + 'bg-slate-50 text-slate-700 border-slate-200/60'
    }
}

function getActionClass(act: string) {
    const text = act?.toLowerCase() ?? ''
    if (text.includes('create') || text.includes('store') || text.includes('approve')) return 'text-emerald-600 font-bold'
    if (text.includes('delete') || text.includes('destroy') || text.includes('reject')) return 'text-rose-600 font-bold'
    if (text.includes('update') || text.includes('edit')) return 'text-amber-600 font-bold'
    return 'text-slate-600 font-semibold'
}
</script>

<template>

    <Head title="Audit Log Sistem" />

    <AdminLayout>
        <div class="space-y-5">

            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-4">
                <div class="flex items-center gap-2.5">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                        <ClockIcon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Audit Log Aktivitas
                        </h1>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">
                            Jejak riwayat transaksi dan manipulasi data pada sistem pengaduan
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col gap-3 sm:flex-row sm:items-center bg-slate-50 border border-slate-200 p-3.5 rounded-xl">
                <div
                    class="flex items-center gap-1.5 text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 mr-2">
                    <FunnelIcon class="h-4 w-4" />
                    <span>Filter</span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:flex sm:flex-1 sm:items-center">
                    <div class="relative sm:w-48">
                        <select v-model="module" @change="filter"
                            class="w-full rounded-xl border border-slate-300 bg-white pl-3 pr-8 py-2 text-sm text-slate-700 outline-none transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 appearance-none cursor-pointer font-medium">
                            <option value="">Semua Modul</option>
                            <option value="complaint">Complaint</option>
                            <option value="complaint_response">Response</option>
                            <option value="user">User</option>
                            <option value="category">Category</option>
                            <option value="whatsapp">Whatsapp</option>
                            <option value="room">Room</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative flex-1 max-w-xs">
                        <input v-model="action" @keyup.enter="filter" placeholder="Cari aksi (cth: create)..."
                            class="w-full rounded-xl border border-slate-300 bg-white pl-9 pr-4 py-2 text-sm text-slate-700 outline-none transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400">
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <MagnifyingGlassIcon class="h-4 w-4" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 table-fixed sm:table-auto">
                        <thead class="bg-slate-50/70">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-2/12">
                                    Waktu
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-2/12">
                                    Eksekutor
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-2/12">
                                    Modul
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-1.5/12">
                                    Aksi
                                </th>
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-4.5/12">
                                    Deskripsi Log
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-for="log in logs.data" :key="log.id" class="hover:bg-slate-50/60 transition-colors">
                                <td class="whitespace-nowrap px-4 py-3.5 text-xs text-slate-500 font-medium font-mono">
                                    {{ formatDateTime(log.created_at) }}
                                </td>

                                <td class="px-4 py-3.5 text-xs sm:text-sm font-semibold text-slate-700">
                                    <div class="flex items-center gap-1.5">
                                        <div
                                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                                            <UserIcon class="h-3 w-3" />
                                        </div>
                                        <span class="truncate max-w-[120px] sm:max-w-none">
                                            {{ log.user?.name ?? 'Sistem / Anonim' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5">
                                    <span :class="getModuleClass(log.module)">
                                        {{ log.module }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-4 py-3.5 text-xs font-mono tracking-tight">
                                    <span :class="getActionClass(log.action)">
                                        {{ log.action }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-xs sm:text-sm font-medium text-slate-500 leading-relaxed">
                                    {{ log.description }}
                                </td>
                            </tr>

                            <tr v-if="!logs.data || logs.data.length === 0">
                                <td colspan="5"
                                    class="px-4 py-12 text-center text-slate-400 text-xs sm:text-sm font-medium">
                                    <DocumentMagnifyingGlassIcon class="h-9 w-9 mx-auto text-slate-300 mb-2" />
                                    Tidak ditemukan catatan aktivitas pada log ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :links="logs.links" />
            </div>

        </div>
    </AdminLayout>
</template>