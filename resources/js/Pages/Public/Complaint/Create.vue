<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

import { Head, useForm } from '@inertiajs/vue3'
import type { ComplaintCategory } from '@/types/complaint'

const props = defineProps<{
    categories: ComplaintCategory[],
    rooms: any[],
}>()

const form = useForm({
    complaint_category_id: '',
    is_anonymous: false,
    room_id: '',
    name: '',
    phone: '',
    nik: '',
    description: '',
    attachments: [] as File[],
    turnstile_token: '',
})
const turnstileSiteKey = import.meta.env.VITE_TURNSTILE_SITE_KEY
const turnstileRef = ref<HTMLElement | null>(null)

function submit() {
    form.post(route('complaints.store'))
}

function handleFiles(event: Event) {
    const target = event.target as HTMLInputElement
    form.attachments = Array.from(target.files ?? [])
}

declare global {
    interface Window {
        turnstile: any
    }
}

onMounted(() => {
    const interval = setInterval(() => {
        if (window.turnstile && turnstileRef.value) {
            window.turnstile.render(turnstileRef.value, {
                sitekey: turnstileSiteKey,
                theme: 'light',
                callback: (token: string) => {
                    form.turnstile_token = token
                },
            })

            clearInterval(interval)
        }
    }, 300)
})

</script>

<template>

    <Head title="Buat Pengaduan" />

    <PublicLayout>
        <div class="mx-auto max-w-3xl px-4">

            <div class="mb-6 text-center sm:text-left">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Form Pengaduan
                </h1>
                <p class="mt-1.5 text-sm text-gray-500">
                    Silakan isi form di bawah ini dengan data yang valid demi kenyamanan dan kecepatan proses
                    penanganan.
                </p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">

                    <div class="grid gap-4 md:grid-cols-2">

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                                Jenis Pengaduan <span class="text-red-500">*</span>
                            </label>

                            <div class="relative">
                                <select v-model="form.complaint_category_id"
                                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500 bg-red-50/30': form.errors.complaint_category_id }">
                                    <option value="">
                                        Pilih Kategori Pengaduan
                                    </option>

                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="form.errors.complaint_category_id"
                                class="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-600">
                                ⚠ {{ form.errors.complaint_category_id }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                                Ruang Perawatan
                            </label>

                            <div class="relative">
                                <select v-model="form.room_id"
                                    class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500 bg-red-50/30': form.errors.room_id }">
                                    <option value="">
                                        Pilih Ruang Perawatan
                                    </option>

                                    <option v-for="room in rooms" :key="room.id" :value="room.id">
                                        {{ room.name }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="form.errors.room_id"
                                class="mt-1.5 flex items-center gap-1 text-xs font-medium text-red-600">
                                ⚠ {{ form.errors.room_id }}
                            </div>
                        </div>

                    </div>

                    <div class="rounded-xl bg-blue-50/50 p-4 border border-blue-100/50">
                        <label class="flex cursor-pointer items-start gap-3 select-none">
                            <input v-model="form.is_anonymous" type="checkbox"
                                class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="text-sm">
                                <span class="font-semibold text-blue-900">Kirim sebagai anonim</span>
                                <p class="text-xs text-red-500 mt-0.5">
                                    Anda tidak menerima notifikasi whatsapp saat aduan selesai ditindaklanjuti jika
                                    memilih anonim.
                                </p>
                            </div>
                        </label>
                    </div>

                    <transition name="fade-slide">
                        <div v-if="!form.is_anonymous"
                            class="space-y-5 rounded-xl border border-gray-100 bg-gray-50/30 p-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Informasi Pelapor
                            </h3>

                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                <input v-model="form.name" type="text" placeholder="Masukkan nama lengkap Anda"
                                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500': form.errors.name }">
                                <div v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">⚠ {{
                                    form.errors.name }}</div>
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Nomor HP /
                                        WhatsApp</label>
                                    <input v-model="form.phone" type="text" placeholder="Contoh: 08123456xxx"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        :class="{ 'border-red-500': form.errors.phone }">
                                    <div v-if="form.errors.phone" class="mt-1.5 text-xs font-medium text-red-600">⚠ {{
                                        form.errors.phone }}</div>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">NIK (KTP)</label>
                                    <input v-model="form.nik" type="text"
                                        placeholder="16 digit Nomor Induk Kependudukan"
                                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none transition-all focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        :class="{ 'border-red-500': form.errors.nik }">
                                    <div v-if="form.errors.nik" class="mt-1.5 text-xs font-medium text-red-600">⚠ {{
                                        form.errors.nik }}</div>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Deskripsi / Kronologi Pengaduan <span class="text-red-500">*</span>
                        </label>
                        <textarea v-model="form.description" rows="5"
                            placeholder="Ceritakan kronologi kejadian secara detail, mencakup waktu, lokasi, dan pihak yang terlibat..."
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                            :class="{ 'border-red-500 bg-red-50/30': form.errors.description }" />
                        <div v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                            ⚠ {{ form.errors.description }}
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Dokumen Pendukung <span class="text-xs font-normal text-gray-400">(Opsional)</span>
                        </label>

                        <div
                            class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/50 px-6 py-5 text-center transition-all hover:bg-gray-50 hover:border-gray-400">
                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>

                            <div class="flex text-sm text-gray-600">
                                <label
                                    class="relative cursor-pointer rounded-md font-semibold text-blue-600 focus-within:outline-none hover:text-blue-500">
                                    <span>Pilih berkas file</span>
                                    <input type="file" multiple @change="handleFiles" class="sr-only">
                                </label>
                                <p class="pl-1">atau seret ke sini</p>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, PDF hingga 5MB (Bisa pilih beberapa file)
                            </p>

                            <div v-if="form.attachments.length > 0"
                                class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 border border-green-200">
                                📁 {{ form.attachments.length }} File siap diunggah
                            </div>
                        </div>

                        <div v-if="form.errors.attachments" class="mt-1.5 text-xs font-medium text-red-600">
                            ⚠ {{ form.errors.attachments }}
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <div ref="turnstileRef"></div>
                    </div>

                    <div
                        v-if="form.errors.turnstile_token"
                        class="text-center text-sm text-red-600"
                    >
                        {{ form.errors.turnstile_token }}
                    </div>

                    <div class="flex justify-end">
                        <LoadingButton :loading="form.processing"
                            class="w-full sm:w-auto shadow-md shadow-blue-500/10 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition-all hover:bg-blue-700">
                            Kirim Pengaduan
                        </LoadingButton>
                    </div>

                </form>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 400px;
    opacity: 1;
    overflow: hidden;
}

.fade-slide-enter-from,
.fade-slide-leave-to {
    max-height: 0;
    opacity: 0;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    border-color: transparent;
}
</style>