<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import ComplaintStatusTimeline from '@/Components/ComplaintStatusTimeline.vue'
import { formatDateTime } from '@/utils/date'

import {
    QrCodeIcon,
    CalendarDaysIcon,
    TagIcon,
    DocumentTextIcon,
    ClockIcon
} from '@heroicons/vue/24/outline'

defineProps<{
    complaint: {
        tracking_code: string
        status: string
        description: string
        submitted_at: string
        category: {
            name: string
        }
        status_logs: Array<{
            id: number
            old_status: string | null
            new_status: string
            note: string
            created_at: string
        }>
        responses: Array<{
            id: number
            solution: string
            approval_status: string
            created_at: string
        }>
    }
}>()

</script>

<template>

    <Head title="Hasil Tracking Pengaduan" />

    <PublicLayout>
        <div class="mx-auto max-w-5xl px-4">

            <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Progres Penanganan Aduan</h1>
                    <p class="text-sm text-slate-500">Lembar transparansi pelacakan keluhan yang diajukan oleh
                        masyarakat.</p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-3 items-start">

                <div class="space-y-6 md:col-span-2">
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm relative">
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-600"></div>

                        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 mt-1">
                            <div class="flex items-center gap-2">
                                <DocumentTextIcon class="h-5 w-5 text-blue-600" />
                                <h2 class="text-lg font-bold text-slate-900">Detail Laporan</h2>
                            </div>
                            <StatusBadge :status="complaint.status" />
                        </div>

                        <div class="p-6 space-y-5">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5">
                                    <div
                                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        <QrCodeIcon class="h-4 w-4 text-slate-400" />
                                        Kode Tracking
                                    </div>
                                    <span class="font-mono text-sm font-bold text-blue-600">
                                        #{{ complaint.tracking_code }}
                                    </span>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5">
                                    <div
                                        class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        <TagIcon class="h-4 w-4 text-slate-400" />
                                        Kategori / Jenis aduan
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700">
                                        {{ complaint.category.name }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-2.5 text-sm text-slate-600 border-b border-slate-100 pb-4">
                                <CalendarDaysIcon class="h-5 w-5 text-slate-400" />
                                <div>
                                    <span class="text-xs text-slate-400 block font-medium leading-none mb-0.5">Waktu
                                        Pengiriman</span>
                                    <span class="font-medium text-slate-700">{{ formatDateTime(complaint.submitted_at)
                                        }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Isi Deskripsi / Kronologi
                                </label>
                                <div
                                    class="rounded-xl bg-slate-50/40 border border-slate-100 p-4 text-sm leading-relaxed text-slate-700 whitespace-pre-line">
                                    {{ complaint.description }}
                                </div>
                            </div>

                            <div v-if="complaint.responses?.length"
                                class="rounded-xl border border-green-200 bg-green-50 p-4">
                                <h3 class="font-semibold text-green-800 mb-2">
                                    Solusi / Tindak Lanjut
                                </h3>

                                <p class="whitespace-pre-line text-slate-700">
                                    {{ complaint.responses[0].solution }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-2 pb-4 mb-5 border-b border-slate-100">
                            <ClockIcon class="h-5 w-5 text-slate-500" />
                            <h2 class="font-bold text-slate-900 text-md">
                                Riwayat Status Laporan
                            </h2>
                        </div>

                        <ComplaintStatusTimeline :logs="complaint.status_logs" />
                    </div>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>