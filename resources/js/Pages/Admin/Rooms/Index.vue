<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3'
import type { PageProps } from '@inertiajs/core'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import RoomFormModal from '@/Components/RoomFormModal.vue'
import Modal from '@/Components/Modal.vue'
import { ref, computed } from 'vue'
import type { User } from '@/types'
import { 
    PlusIcon, 
    PencilSquareIcon, 
    PowerIcon, 
    BuildingOfficeIcon,
    ExclamationTriangleIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

defineProps<{
    rooms: any[]
}>()

const page = usePage<PageProps & {
    auth: {
        user: User | null;
    }
}>()

const isSuperAdmin = computed(() => page.props.auth.user?.role === 'super_admin')
const isAdminOrSupervisor = computed(() => 
    page.props.auth.user?.role === 'super_admin' || 
    page.props.auth.user?.role === 'admin' || 
    page.props.auth.user?.role === 'supervisor'
)

const showFormModal = ref(false)
const selectedRoom = ref<any | null>(null)

const showConfirmModal = ref(false)
const roomIdToDeactivate = ref<number | null>(null)
const isSubmittingDeactivate = ref(false)

function createRoom() {
    selectedRoom.value = null
    showFormModal.value = true
}

function editRoom(room: any) {
    selectedRoom.value = room
    showFormModal.value = true
}

function confirmDeactivate(id: number) {
    roomIdToDeactivate.value = id
    showConfirmModal.value = true
}

function closeConfirmModal() {
    showConfirmModal.value = false
    roomIdToDeactivate.value = null
}

function handleDeactivate() {
    if (!roomIdToDeactivate.value) return

    isSubmittingDeactivate.value = true

    router.delete(
        route('admin.rooms.destroy', roomIdToDeactivate.value),
        {
            preserveScroll: true,
            onSuccess: () => {
                isSubmittingDeactivate.value = false
                closeConfirmModal()
            },
            onError: () => {
                isSubmittingDeactivate.value = false
            }
        }
    )
}
</script>

<template>
    <Head title="Manajemen Ruangan" />

    <AdminLayout>
        <div class="space-y-5 animate-fade-in">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <BuildingOfficeIcon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Manajemen Ruangan
                        </h1>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">
                            Kelola data ruangan atau unit pelayanan di rumah sakit
                        </p>
                    </div>
                </div>

                <button 
                    v-if="isSuperAdmin"
                    type="button"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700 active:scale-95"
                    @click="createRoom"
                >
                    <PlusIcon class="h-5 w-5 stroke-2" />
                    <span>Tambah Ruangan</span>
                </button>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 table-fixed sm:table-auto">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-7/12">
                                    Nama Ruangan
                                </th>
                                <th scope="col" class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400 w-3/12">
                                    Status
                                </th>
                                <th v-if="isSuperAdmin" scope="col" class="px-5 py-3 text-center text-xs font-bold uppercase tracking-wider text-slate-400 w-2/12">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="room in rooms"
                                :key="room.id"
                                class="hover:bg-slate-50/70 transition-colors"
                            >
                                <td class="whitespace-nowrap px-5 py-3.5 text-xs sm:text-sm font-semibold text-slate-700">
                                    {{ room.name }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3.5">
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-bold tracking-wide"
                                        :class="
                                            room.is_active
                                                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60'
                                                : 'bg-rose-50 text-rose-700 border border-rose-200/60'
                                        "
                                    >
                                        <span class="mr-1 h-1.5 w-1.5 rounded-full" :class="room.is_active ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                        {{ room.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td v-if="isSuperAdmin" class="whitespace-nowrap px-5 py-3.5 text-center text-xs font-medium">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-lg bg-slate-50 border border-slate-200 px-2.5 py-1 text-xs font-semibold text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors"
                                            @click="editRoom(room)"
                                        >
                                            <PencilSquareIcon class="h-4 w-4" />
                                            <span>Edit</span>
                                        </button>

                                        <button
                                            v-if="room.is_active"
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-lg bg-slate-50 border border-slate-200 px-2.5 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors"
                                            @click="confirmDeactivate(room.id)"
                                        >
                                            <PowerIcon class="h-4 w-4" />
                                            <span>Nonaktifkan</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="!rooms || rooms.length === 0">
                                <td :colspan="isSuperAdmin ? 3 : 2" class="px-5 py-10 text-center text-slate-400 text-xs sm:text-sm font-medium">
                                    <BuildingOfficeIcon class="h-8 w-8 mx-auto text-slate-300 mb-2" />
                                    Belum ada data ruangan yang tersimpan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <RoomFormModal
            :show="showFormModal"
            :room="selectedRoom"
            @close="showFormModal = false"
        />

        <Modal :show="showConfirmModal" max-width="sm" @close="closeConfirmModal()">
            <div class="relative bg-white rounded-xl overflow-hidden p-5">
                
                <button 
                    type="button" 
                    @click="closeConfirmModal()"
                    class="absolute top-4 right-4 rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>

                <div class="flex flex-col items-center text-center mt-2">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 border border-rose-100 shadow-sm mb-3.5">
                        <ExclamationTriangleIcon class="h-6 w-6 stroke-[1.5]" />
                    </div>

                    <h3 class="text-sm font-bold text-slate-900">
                        Nonaktifkan Ruangan?
                    </h3>
                    
                    <p class="text-xs text-slate-500 font-medium mt-2 leading-relaxed px-2">
                        Tindakan ini akan menyembunyikan ruangan ini dari formulir aduan publik masyarakat. Anda dapat mengaktifkannya kembali kapan saja.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-6 border-t border-slate-100 pt-4">
                    <button 
                        type="button" 
                        class="rounded-lg border border-slate-200 py-2 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 active:scale-95 transition-all" 
                        @click="closeConfirmModal()"
                        :disabled="isSubmittingDeactivate"
                    >
                        Batal
                    </button>

                    <button 
                        type="button" 
                        class="inline-flex items-center justify-center rounded-lg bg-rose-600 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-rose-700 active:scale-95 disabled:opacity-50"
                        @click="handleDeactivate"
                        :disabled="isSubmittingDeactivate"
                    >
                        <span>{{ isSubmittingDeactivate ? 'Memproses...' : 'Ya, Nonaktifkan' }}</span>
                    </button>
                </div>

            </div>
        </Modal>

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