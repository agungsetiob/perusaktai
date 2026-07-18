<script setup lang="ts">
import { onMounted, ref, watch, computed, onUnmounted } from 'vue'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import { Head, useForm } from '@inertiajs/vue3'
import type { ComplaintCategory } from '@/types/complaint'
import api from '@/utils/api';

const props = defineProps<{
    categories: ComplaintCategory[],
}>()

const installations = ref<any[]>([])
const rooms = ref<any[]>([])

const queryInstallation = ref('')
const queryRoom = ref('')

const isInstallationOpen = ref(false)
const isRoomOpen = ref(false)

const installationRef = ref<HTMLElement | null>(null)
const roomRef = ref<HTMLElement | null>(null)

const form = useForm({
    complaint_category_id: '',
    is_anonymous: false,
    installation_id: '',
    room_id: '',
    name: '',
    phone: '',
    nik: '',
    description: '',
    attachments: [] as File[],
    turnstile_token: '',
    reporter_type: 'patient',
})

const turnstileSiteKey = import.meta.env.VITE_TURNSTILE_SITE_KEY
const turnstileRef = ref<HTMLElement | null>(null)

// Computed untuk menyaring data instalasi
const filteredInstallations = computed(() => {
    return queryInstallation.value === ''
        ? installations.value
        : installations.value.filter((item) =>
            item.name.toLowerCase().includes(queryInstallation.value.toLowerCase())
        )
})

// Computed untuk menyaring data ruangan
const filteredRooms = computed(() => {
    return queryRoom.value === ''
        ? rooms.value
        : rooms.value.filter((item) =>
            item.name.toLowerCase().includes(queryRoom.value.toLowerCase())
        )
})

// Mengambil nama instalasi yang terpilih untuk ditampilkan di tombol input
const selectedInstallationName = computed(() => {
    const found = installations.value.find(i => i.id === form.installation_id)
    return found ? found.name : 'Pilih Instalasi'
})

// Mengambil nama ruangan yang terpilih untuk ditampilkan di tombol input
const selectedRoomName = computed(() => {
    const found = rooms.value.find(r => r.id === form.room_id)
    return found ? found.name : 'Pilih Ruangan Pelayanan'
})

// Fungsi menutup dropdown ketika mengklik di luar area select
const handleClickOutside = (event: MouseEvent) => {
    if (installationRef.value && !installationRef.value.contains(event.target as Node)) {
        isInstallationOpen.value = false
    }
    if (roomRef.value && !roomRef.value.contains(event.target as Node)) {
        isRoomOpen.value = false
    }
}

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

onMounted(async () => {
    window.addEventListener('click', handleClickOutside)

    const { data } = await api.get('/simrs/installations');
    installations.value = data;
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

onUnmounted(() => {
    window.removeEventListener('click', handleClickOutside)
})

watch(
    () => form.installation_id,
    async (installationId) => {
        form.room_id = '';
        rooms.value = [];
        queryRoom.value = '';
        if (!installationId) return;

        const { data } = await api.get(`/simrs/installations/${installationId}/rooms`);
        rooms.value = data;
    }
);
</script>

<template>

    <Head title="Buat Pengaduan" />

    <PublicLayout>
        <div class="mx-auto max-w-4xl px-4">

            <div class="mb-6 text-center sm:text-left">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Form Pengaduan
                </h1>
                <p class="mt-1.5 text-sm text-gray-500">
                    Silakan isi form di bawah ini dengan data yang valid demi kenyamanan dan kecepatan proses
                    penanganan.
                </p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8 mb-4">
                <form @submit.prevent="submit" class="space-y-6">

                    <div class="grid gap-4 md:grid-cols-3">

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

                        <!-- COMPONENT: Kustom Searchable Select - Instalasi -->
                        <div class="relative" ref="installationRef">
                            <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                                Instalasi
                            </label>
                            <div>
                                <!-- Tombol Utama yang menggantikan Select, ketika diklik langsung memunculkan dropdown -->
                                <button type="button"
                                    @click="isInstallationOpen = !isInstallationOpen; if (isInstallationOpen) queryInstallation = ''"
                                    class="flex w-full items-center justify-between rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                    <span :class="form.installation_id ? 'text-gray-800' : 'text-gray-400'">
                                        {{ selectedInstallationName }}
                                    </span>
                                    <!-- Icon Panah -->
                                    <svg class="h-4 w-4 text-gray-400 transition-transform"
                                        :class="{ 'rotate-180': isInstallationOpen }" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div v-if="isInstallationOpen"
                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg ring-1 ring-black/5">
                                    <!-- Input Kolom Pencarian -->
                                    <div class="border-b border-gray-100 p-2 bg-gray-50">
                                        <input v-model="queryInstallation" type="text"
                                            placeholder="Ketik untuk mencari..."
                                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                            @click.stop />
                                    </div>
                                    <!-- Daftar Pilihan -->
                                    <ul class="max-h-44 overflow-y-auto p-1 text-sm">
                                        <li v-if="filteredInstallations.length === 0"
                                            class="cursor-default select-none px-4 py-2 text-xs text-gray-500">
                                            Instalasi tidak ditemukan.
                                        </li>
                                        <li v-for="installation in filteredInstallations" :key="installation.id"
                                            @click="form.installation_id = installation.id; isInstallationOpen = false"
                                            :class="['cursor-pointer select-none rounded-lg py-2 px-4 transition-colors hover:bg-blue-600 hover:text-white', form.installation_id === installation.id ? 'bg-blue-50 font-semibold text-blue-600 hover:bg-blue-600 hover:text-white' : 'text-gray-900']">
                                            {{ installation.name }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- COMPONENT: Kustom Searchable Select - Ruangan Pelayanan -->
                        <div class="relative" ref="roomRef">
                            <label class="mb-1.5 block text-sm font-semibold text-gray-700">
                                Ruangan Pelayanan
                            </label>
                            <div>
                                <button type="button" :disabled="!form.installation_id"
                                    @click="isRoomOpen = !isRoomOpen; if (isRoomOpen) queryRoom = ''"
                                    class="flex w-full items-center justify-between rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-800 outline-none transition-all disabled:opacity-60 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                                    :class="{ 'border-red-500 bg-red-50/30': form.errors.room_id }">
                                    <span :class="form.room_id ? 'text-gray-800' : 'text-gray-400'">
                                        {{ form.installation_id ? selectedRoomName : 'Pilih Instalasi dahulu'
                                        }}
                                    </span>
                                    <svg class="h-4 w-4 text-gray-400 transition-transform"
                                        :class="{ 'rotate-180': isRoomOpen }" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div v-if="isRoomOpen && form.installation_id"
                                    class="absolute z-50 mt-1 max-h-60 w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg ring-1 ring-black/5">
                                    <!-- Input Kolom Pencarian -->
                                    <div class="border-b border-gray-100 p-2 bg-gray-50">
                                        <input v-model="queryRoom" type="text" placeholder="Ketik untuk mencari..."
                                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                            @click.stop />
                                    </div>
                                    <!-- Daftar Pilihan -->
                                    <ul class="max-h-44 overflow-y-auto p-1 text-sm">
                                        <li v-if="filteredRooms.length === 0"
                                            class="cursor-default select-none px-4 py-2 text-xs text-gray-500">
                                            Ruangan tidak ditemukan.
                                        </li>
                                        <li v-for="room in filteredRooms" :key="room.id"
                                            @click="form.room_id = room.id; isRoomOpen = false"
                                            :class="['cursor-pointer select-none rounded-lg py-2 px-4 transition-colors hover:bg-blue-600 hover:text-white', form.room_id === room.id ? 'bg-blue-50 font-semibold text-blue-600 hover:bg-blue-600 hover:text-white' : 'text-gray-900']">
                                            {{ room.name }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="form.errors.room_id" class="mt-1 text-xs text-red-600">
                                ⚠ {{ form.errors.room_id }}
                            </div>
                        </div>

                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Pelapor
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-300 p-3 hover:border-blue-500"
                                :class="form.reporter_type === 'patient'
                                    ? 'border-blue-500 bg-blue-50'
                                    : ''">
                                <input type="radio" value="patient" v-model="form.reporter_type">

                                <span class="font-medium">
                                    Pasien
                                </span>
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-xl border border-gray-300 p-3 hover:border-blue-500"
                                :class="form.reporter_type === 'family'
                                    ? 'border-blue-500 bg-blue-50'
                                    : ''">
                                <input type="radio" value="family" v-model="form.reporter_type">

                                <span class="font-medium">
                                    Keluarga / Pendamping
                                </span>
                            </label>

                        </div>

                        <div v-if="form.errors.reporter_type" class="mt-1 text-xs text-red-600">
                            {{ form.errors.reporter_type }}
                        </div>
                    </div>

                    <div class="rounded-xl bg-blue-50/50 p-4 border border-blue-100/50">
                        <label class="flex cursor-pointer items-start gap-3 select-none">
                            <input v-model="form.is_anonymous" type="checkbox"
                                class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="text-sm">
                                <span class="font-semibold text-blue-900">Kirim sebagai anonim</span>
                                <p class="text-xs text-green-600 mt-0.5">
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
                                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">NIK <span
                                            class="text-xs font-normal text-gray-400">(Opsional)</span></label>
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
                            Bukti Pendukung <span class="text-xs font-normal text-gray-400">(Opsional)</span>
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
                        <div ref="turnstileRef" class="scale-90 sm:scale-100 origin-top-left"></div>
                    </div>

                    <div v-if="form.errors.turnstile_token" class="text-start text-sm text-red-600">
                        {{ form.errors.turnstile_token }}
                    </div>

                    <div class="flex justify-end">
                        <LoadingButton :loading="form.processing"
                            class="w-full sm:w-auto shadow-md shadow-blue-500/10 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition-all hover:bg-blue-700 mb-2">
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