<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserFormModal from '@/Components/UserFormModal.vue'
import Modal from '@/Components/Modal.vue' // 1. Import komponen Modal bawaan project
import { 
    UserPlusIcon, 
    PencilSquareIcon, 
    UserMinusIcon,
    ShieldCheckIcon,
    UserIcon,
    EnvelopeIcon,
    ExclamationTriangleIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

interface User {
    id: number
    name: string
    email: string
    role: 'admin' | 'supervisor' | 'super_admin'
    is_active: boolean
}

const props = defineProps<{
    users: User[]
}>()

// State untuk Modal Form
const showModal = ref(false)
const selectedUser = ref<User | null>(null)

// State untuk Modal Konfirmasi Nonaktifkan
const showDeactivateModal = ref(false)
const userToDeactivate = ref<User | null>(null)
const isDeactivating = ref(false)

function createUser() {
    selectedUser.value = null
    showModal.value = true
}

function editUser(user: User) {
    selectedUser.value = user
    showModal.value = true
}

// Memicu modal konfirmasi muncul
function confirmDeactivate(user: User) {
    userToDeactivate.value = user
    showDeactivateModal.value = true
}

function closeDeactivateModal() {
    showDeactivateModal.value = false
    userToDeactivate.value = null
}

// Fungsi eksekusi final
function handleDeactivate() {
    if (!userToDeactivate.value) return

    isDeactivating.value = true
    router.delete(
        route('admin.users.destroy', userToDeactivate.value.id), 
        {
            preserveScroll: true,
            onSuccess: () => {
                isDeactivating.value = false
                closeDeactivateModal()
            },
            onError: () => {
                isDeactivating.value = false
            }
        }
    )
}

const formatRole = (role: string) => {
    return role.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
}
</script>

<template>
    <Head title="Manajemen User" />

    <AdminLayout>
        <div class="space-y-5 animate-fade-in">
            
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100 pb-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <ShieldCheckIcon class="h-5 w-5" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Manajemen Pengguna
                        </h1>
                        <p class="text-xs font-medium text-slate-400 mt-0.5">
                            Kelola data akun, hak akses, dan status keaktifan user dalam sistem.
                        </p>
                    </div>
                </div>

                <button
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-blue-600/10 transition-all hover:bg-blue-700 active:scale-95 shrink-0"
                    @click="createUser"
                >
                    <UserPlusIcon class="h-5 w-5" />
                    <span>Tambah User</span>
                </button>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm shadow-slate-100/80">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-4 text-left">Nama & Email</th>
                                <th class="px-6 py-4 text-left">Role</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data user yang terdaftar.
                                </td>
                            </tr>

                            <tr 
                                v-for="user in users" 
                                :key="user.id"
                                class="transition-colors hover:bg-slate-50/50"
                            >
                                <td class="whitespace-nowrap px-6 py-4.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                            <UserIcon class="h-5 w-5" />
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-semibold text-slate-900 truncate">{{ user.name }}</span>
                                            <span class="inline-flex items-center gap-1 text-xs text-slate-500 truncate mt-0.5">
                                                <EnvelopeIcon class="h-3.5 w-3.5 text-slate-400" />
                                                {{ user.email }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4.5">
                                    <span 
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold tracking-wide"
                                        :class="{
                                            'bg-purple-50 text-purple-700 ring-1 ring-purple-600/10': user.role === 'super_admin',
                                            'bg-blue-50 text-blue-700 ring-1 ring-blue-600/10': user.role === 'admin',
                                            'bg-amber-50 text-amber-700 ring-1 ring-amber-600/10': user.role === 'supervisor',
                                        }"
                                    >
                                        <ShieldCheckIcon class="h-3.5 w-3.5" />
                                        {{ formatRole(user.role) }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4.5 text-center">
                                    <span 
                                        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1"
                                        :class="user.is_active 
                                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/10' 
                                            : 'bg-slate-100 text-slate-600 ring-slate-500/10'"
                                    >
                                        <span class="mr-1 h-1.5 w-1.5 rounded-full" :class="user.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ user.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4.5 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition-colors bg-blue-50 hover:bg-blue-100 hover:text-blue-700"
                                            @click="editUser(user)"
                                        >
                                            <PencilSquareIcon class="h-4 w-4" />
                                            <span>Edit</span>
                                        </button>

                                        <button
                                            v-if="user.is_active"
                                            type="button"
                                            class="inline-flex items-center justify-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 transition-colors bg-red-50 hover:bg-red-100 hover:text-red-700"
                                            @click="confirmDeactivate(user)"
                                        >
                                            <UserMinusIcon class="h-4 w-4" />
                                            <span>Nonaktifkan</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <UserFormModal
            :show="showModal"
            :user="selectedUser"
            @close="showModal = false"
        />

        <Modal :show="showDeactivateModal" max-width="sm" @close="closeDeactivateModal()">
            <div class="relative bg-white rounded-xl overflow-hidden p-5">
                
                <button 
                    type="button" 
                    @click="closeDeactivateModal()"
                    class="absolute top-4 right-4 rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>

                <div class="flex flex-col items-center text-center mt-2">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 border border-rose-100 shadow-sm mb-3.5">
                        <ExclamationTriangleIcon class="h-6 w-6 stroke-[1.5]" />
                    </div>

                    <h3 class="text-sm font-bold text-slate-900">
                        Nonaktifkan Pengguna?
                    </h3>
                    
                    <p class="text-xs text-slate-500 font-medium mt-2 leading-relaxed px-2">
                        Tindakan ini akan mencabut izin login untuk akun <span class="font-bold text-slate-800">{{ userToDeactivate?.name }}</span> dari sistem secara sementara. Anda dapat mengaktifkannya kembali sewaktu-waktu.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-6 border-t border-slate-100 pt-4">
                    <button 
                        type="button" 
                        class="rounded-lg border border-slate-200 py-2 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 active:scale-95 transition-all" 
                        @click="closeDeactivateModal()"
                        :disabled="isDeactivating"
                    >
                        Batal
                    </button>

                    <button 
                        type="button" 
                        class="inline-flex items-center justify-center rounded-lg bg-rose-600 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-rose-700 active:scale-95 disabled:opacity-50"
                        @click="handleDeactivate"
                        :disabled="isDeactivating"
                    >
                        <span>{{ isDeactivating ? 'Memproses...' : 'Ya, Nonaktifkan' }}</span>
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
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>