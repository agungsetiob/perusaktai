<script setup lang="ts">

import { Head, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import ComplaintStatusTimeline from '@/Components/ComplaintStatusTimeline.vue'
import SubmitSolutionForm from './Partials/SubmitSolutionForm.vue'
import ReviewSolutionCard from './Partials/ReviewSolutionCard.vue'
import SolveComplaintCard from './Partials/SolveComplaintCard.vue'
import RejectComplaintCard from './Partials/RejectComplaintCard.vue'
import AttachmentPreviewModal from '@/Components/AttachmentPreviewModal.vue'
import ComplaintAiChatCard from '@/Components/ComplaintAiChatCard.vue'
import { formatDateTime } from '@/utils/date.js'
import type { User } from '@/types'

const showAttachmentModal = ref(false)
const selectedAttachment = ref(null)

function openAttachment(file: any) {
    selectedAttachment.value = file
    showAttachmentModal.value = true
}

import {
    QrCodeIcon,
    UserIcon,
    IdentificationIcon,
    PhoneIcon,
    DocumentTextIcon,
    PaperClipIcon,
    ClockIcon,
    ChatBubbleLeftRightIcon,
    ShieldCheckIcon
} from '@heroicons/vue/24/outline'
import { ref } from 'vue'

const props = defineProps<{
    complaint: any
}>()

const page = usePage()
const user = page.props.auth.user as User
</script>

<template>

    <Head :title="`Detail #${complaint.tracking_code}`" />

    <AdminLayout>
        <div class="space-y-5">

            <!-- TOP HEADER PANEL -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-sm relative">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 to-indigo-500"></div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <QrCodeIcon class="h-5 w-5" />
                        </div>
                        <div>
                            <h1 class="text-lg font-bold font-mono tracking-tight text-slate-900">
                                {{ complaint.tracking_code }}
                            </h1>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                Kategori: <span class="text-blue-600 font-medium">{{ complaint.category.name }}</span>
                            </p>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                Ruangan: <span class="text-red-600 font-medium">{{ complaint.room_name }}</span>
                            </p>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                Pelapor: <span class="text-emerald-600 font-medium">{{
                                        complaint.reporter_type === 'patient'
                                            ? 'Pasien'
                                    : 'Keluarga / Pendamping'
                                    }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col items-start sm:items-end gap-1">
                        <StatusBadge :status="complaint.status" />
                        <span class="text-[11px] text-slate-400 font-medium">Masuk: {{
                            formatDateTime(complaint.created_at
                                || complaint.submitted_at) }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. GRID KONTEN UTAMA -->
            <div class="grid gap-5 lg:grid-cols-3 items-start">

                <!-- KOLOM KIRI (2/3 Porsi) -->
                <div class="space-y-5 lg:col-span-2">

                    <!-- KARTU DATA PELAPOR -->
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-3.5 bg-slate-50/50">
                            <UserIcon class="h-4 w-4 text-slate-400" />
                            <h2 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Identitas Pelapor</h2>
                        </div>

                        <div class="p-5">
                            <div v-if="complaint.is_anonymous"
                                class="inline-flex items-center gap-2 rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-500">
                                🕵️ Pengaduan Ini Bersifat Anonim
                            </div>

                            <div v-else class="grid gap-3 sm:grid-cols-3">
                                <div class="rounded-lg bg-slate-50/60 border border-slate-100 p-3">
                                    <span
                                        class="block text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-0.5">Nama
                                    </span>
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-800">
                                        <UserIcon class="h-3.5 w-3.5 text-slate-400" />
                                        <span>{{ complaint.name }}</span>
                                    </div>
                                </div>
                                <div class="rounded-lg bg-slate-50/60 border border-slate-100 p-3">
                                    <span
                                        class="block text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-0.5">No.
                                        WhatsApp</span>
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-800">
                                        <PhoneIcon class="h-3.5 w-3.5 text-slate-400" />
                                        <span>{{ complaint.phone }}</span>
                                    </div>
                                </div>
                                <div class="rounded-lg bg-slate-50/60 border border-slate-100 p-3">
                                    <span
                                        class="block text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-0.5">NIK</span>
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-800">
                                        <IdentificationIcon class="h-3.5 w-3.5 text-slate-400" />
                                        <span class="font-mono">{{ complaint.nik }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU DESKRIPSI KELUHAN -->
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-3.5 bg-slate-50/50">
                            <DocumentTextIcon class="h-4 w-4 text-slate-400" />
                            <h2 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Isi Pengaduan
                            </h2>
                        </div>
                        <div class="p-5">
                            <div
                                class="rounded-lg bg-slate-50/30 border border-slate-100 p-4 text-xs sm:text-sm leading-relaxed text-slate-600 whitespace-pre-line font-medium">
                                {{ complaint.description }}
                            </div>
                        </div>
                    </div>

                    <!-- KARTU FILE LAMPIRAN BUKTI -->
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-3.5 bg-slate-50/50">
                            <PaperClipIcon class="h-4 w-4 text-slate-400" />
                            <h2 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Lampiran Berkas Bukti
                            </h2>
                        </div>

                        <!-- Konten Utama -->
                        <div class="p-5">
                            <div v-if="complaint.attachments && complaint.attachments.length"
                                class="grid gap-2.5 sm:grid-cols-2">
                                <button v-for="file in complaint.attachments" :key="file.id" type="button"
                                    class="group flex items-center justify-between w-full text-left rounded-lg border border-slate-200 bg-white p-3 shadow-sm hover:border-blue-300 hover:bg-blue-50/20 active:scale-[0.99] transition-all duration-200"
                                    @click="openAttachment(file)">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <!-- Badge Ekstensi File Dinamis -->
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-slate-100 text-[10px] font-extrabold uppercase tracking-wider group-hover:bg-blue-100 transition-colors"
                                            :class="file.original_name.split('.').pop() === 'pdf' ? 'text-red-600' : 'text-blue-600'">
                                            {{ file.original_name.split('.').pop() }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate text-xs font-semibold text-slate-700 group-hover:text-blue-700 transition-colors"
                                                :title="file.original_name">
                                                {{ file.original_name }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div v-else class="flex items-center gap-2 text-slate-400 text-xs py-1">
                                <span class="text-slate-300">✕</span>
                                <span class="font-medium">Tidak ada dokumen atau lampiran.</span>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU RIWAYAT TANGGAPAN -->
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-3.5 bg-slate-50/50">
                            <ChatBubbleLeftRightIcon class="h-4 w-4 text-slate-400" />
                            <h2 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Tanggapan & Catatan
                                Internal</h2>
                        </div>
                        <div class="p-5">
                            <div v-if="complaint.responses?.length" class="space-y-3">
                                <ReviewSolutionCard v-for="response in complaint.responses" :key="response.id"
                                    :response="response" />
                            </div>
                            <div v-else class="text-left text-xs text-slate-400 font-medium py-1">
                                Belum ada tanggapan.
                            </div>
                        </div>
                    </div>

                </div>

                <!-- KOLOM KANAN (1/3 Porsi) -->
                <div class="space-y-5 lg:col-span-1">

                    <!-- KARTU ALUR LOG STATUS -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                            <ClockIcon class="h-4 w-4 text-slate-400" />
                            <h2 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Lini Masa Proses</h2>
                        </div>
                        <ComplaintStatusTimeline :logs="complaint.status_logs" />
                    </div>

                    <!-- KARTU PANEL AKSI OPERATOR -->
                    <div v-if="
                        (complaint.status === 'waiting' && (user.role === 'admin' || user.role === 'super_admin')) ||
                        (complaint.status === 'under_review' && (user.role === 'supervisor' || user.role === 'super_admin')) ||
                        (complaint.status === 'on_process' && (user.role === 'admin' || user.role === 'super_admin'))
                    "
                        class="rounded-xl border border-blue-100 bg-gradient-to-b from-blue-50/30 to-white p-4 shadow-sm border-t-2 border-t-blue-500">

                        <div class="flex items-center gap-1.5 pb-2 mb-3 border-b border-blue-100/50 text-blue-800">
                            <ShieldCheckIcon class="h-4 w-4" />
                            <h3 class="text-[10px] font-bold uppercase tracking-wider">Panel Otoritas Menu</h3>
                        </div>

                        <SubmitSolutionForm
                            v-if="complaint.status === 'waiting' && (user.role === 'admin' || user.role === 'super_admin')"
                            :complaint-id="complaint.id" />

                        <div v-if="
                            complaint.status === 'under_review'
                            && (
                                user.role === 'supervisor'
                                || user.role === 'super_admin'
                            )
                        " class="space-y-4">
                            <ReviewSolutionCard :response="complaint.latest_response" action-mode />

                            <RejectComplaintCard :complaint-id="complaint.id" />
                        </div>

                        <SolveComplaintCard
                            v-if="complaint.status === 'on_process' && (user.role === 'admin' || user.role === 'super_admin')"
                            :complaint-id="complaint.id" />
                    </div>

                </div>

            </div>
        </div>
        <AttachmentPreviewModal :show="showAttachmentModal" :file="selectedAttachment"
            @close="showAttachmentModal = false" />
        <!-- PENEMPATAN KARTU CHAT ASSISTANT AI INTERNAL -->
        <template #floating-chat>
            <ComplaintAiChatCard :complaint-id="complaint.id" />
        </template>
    </AdminLayout>
</template>