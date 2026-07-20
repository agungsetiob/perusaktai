<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { reactive } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'

import {
    BuildingOfficeIcon,
    MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    rooms: any
    filters: {
        search?: string
        jenis?: string
        jenis_kunjungan?: string
    }
}>()

const filter = reactive({
    search: props.filters.search ?? '',
    jenis: props.filters.jenis ?? '',
    jenis_kunjungan: props.filters.jenis_kunjungan ?? '',
})

function submitFilter() {
    router.get(
        route('admin.simrs.rooms.index'),
        filter,
        {
            preserveState: true,
            replace: true,
        }
    )
}

function resetFilter() {
    filter.search = ''
    filter.jenis = ''
    filter.jenis_kunjungan = ''

    submitFilter()
}

function jenisName(jenis: number) {

    switch (jenis) {

        case 1:
            return 'Level 1'

        case 2:
            return 'Level 2'

        case 3:
            return 'Level 3'

        case 4:
            return 'Level 4'

        case 5:
            return 'Level 5'

        default:
            return jenis

    }

}

function visitType(jenis: number) {

    switch (jenis) {

        case 0:
            return 'Patologi Anatomi'

        case 1:
            return 'Rawat Jalan'

        case 2:
            return 'Gawat Darurat (Observasi)'

        case 3:
            return 'Rawat Inap'

        case 4:
            return 'Laboratorium'
        case 5:
            return 'Radiologi'
        case 6:
            return 'Kamar Operasi / Tindakan Invasif '

        case 7:
            return 'Hemodialisa'

        case 8:
            return 'Endoscopy'

        case 9:
            return 'Litotripsi'

        case 10:
            return 'Hiperbarik'
        case 11:
            return 'Farmasi'
        case 12:
            return 'Kamar Bersalin'

        case 14:
            return 'Radioterapi'

        case 18:
            return 'Bukan Ruangan Kunjungan / Pelayanan'
        default:
            return '-'

    }

}

function jenisBadge(jenis: number) {

    switch (jenis) {
        case 1:
            return 'bg-red-50 text-red-700 border-red-200'
        case 2:
            return 'bg-yellow-50 text-yellow-700 border-yellow-200'

        case 3:
            return 'bg-blue-50 text-blue-700 border-blue-200'

        case 4:
            return 'bg-purple-50 text-purple-700 border-purple-200'

        case 5:
            return 'bg-emerald-50 text-emerald-700 border-emerald-200'

        default:
            return 'bg-slate-50 text-slate-700 border-slate-200'

    }

}
</script>

<template>

    <Head title="Ruangan SIMRS" />

    <AdminLayout>

        <div class="space-y-5 animate-fade-in">

            <div class="border-b border-slate-200 pb-4">

                <div class="flex items-center gap-2.5">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">

                        <BuildingOfficeIcon class="h-5 w-5" />

                    </div>

                    <div>

                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Data Ruangan SIMRS
                        </h1>

                        <p class="mt-0.5 text-xs font-medium text-slate-400">
                            Data ruangan langsung dari database SIMRS
                        </p>

                    </div>

                </div>

            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="grid gap-3 md:grid-cols-4">

                    <div class="relative">

                        <MagnifyingGlassIcon class="absolute left-3 top-3.5 h-4 w-4 text-slate-400" />

                        <input v-model="filter.search" @keyup.enter="submitFilter" placeholder="Cari nama / ID"
                            class="w-full rounded-lg border border-slate-300 py-2.5 pl-9 pr-3 text-sm" />

                    </div>

                    <!-- Filter jenis -->
                    <select v-model="filter.jenis" class="rounded-lg border border-slate-300 text-sm">
                        <option value="">Semua Jenis</option>
                        <option value="1">Level 1</option>
                        <option value="2">Level 2</option>
                        <option value="3">Level 3</option>
                        <option value="4">Level 4</option>
                        <option value="5">Level 5</option>
                    </select>

                    <!-- Filter jenis_kunjungan -->
                    <select v-model="filter.jenis_kunjungan" class="rounded-lg border border-slate-300 text-sm">
                        <option value="">Semua Jenis Kunjungan</option>
                        <option value="0">Patologi Anatomi</option>
                        <option value="1">Rawat Jalan</option>
                        <option value="2">Gawat Darurat (Observasi)</option>
                        <option value="3">Rawat Inap</option>
                        <option value="4">Laboratorium</option>
                        <option value="5">Radiologi</option>
                        <option value="6">Kamar Operasi / Tindakan Invasif</option>
                        <option value="7">Hemodialisa</option>
                        <option value="8">Endoscopy</option>
                        <option value="9">Litotripsi</option>
                        <option value="10">Hiperbarik</option>
                        <option value="11">Farmasi</option>
                        <option value="12">Kamar Bersalin</option>
                        <option value="14">Radioterapi</option>
                        <option value="18">Bukan Ruangan Kunjungan / Pelayanan</option>
                    </select>

                    <div class="flex gap-2">

                        <button @click="submitFilter"
                            class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">

                            Filter

                        </button>

                        <button @click="resetFilter" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">

                            Reset

                        </button>

                    </div>

                </div>

            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-200">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-400">
                                    ID SIMRS
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-400">
                                    Nama Ruangan
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-400">
                                    Hierarki
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-400">
                                    Jenis Kunjungan
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-bold uppercase text-slate-400">
                                    Status
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <tr v-for="room in rooms.data" :key="room.ID" class="hover:bg-slate-50">

                                <td class="px-5 py-3 font-mono text-xs">

                                    {{ room.ID }}

                                </td>

                                <td class="px-5 py-3 font-semibold">

                                    {{ room.DESKRIPSI }}

                                </td>

                                <td class="px-5 py-3">

                                    <span class="rounded-full border px-2 py-1 text-xs font-semibold"
                                        :class="jenisBadge(room.JENIS)">

                                        {{ jenisName(room.JENIS) }}

                                    </span>

                                </td>

                                <td class="px-5 py-3 text-sm">

                                    {{ visitType(room.JENIS_KUNJUNGAN) }}

                                </td>

                                <td class="px-5 py-3">

                                    <span class="rounded-full px-2 py-1 text-xs font-bold" :class="room.STATUS == 1
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : 'bg-rose-50 text-rose-700'">

                                        {{ room.STATUS == 1 ? 'Aktif' : 'Nonaktif' }}

                                    </span>

                                </td>

                            </tr>

                            <tr v-if="rooms.data.length === 0">

                                <td colspan="5" class="py-10 text-center text-slate-400">

                                    Tidak ada data.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                    <Pagination :links="rooms.links" />

                </div>

            </div>

        </div>

    </AdminLayout>

</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn .4s ease-out forwards;
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