<script setup lang="ts">
import Modal from '@/Components/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'
import { 
    XMarkIcon, 
    UserIcon, 
    EnvelopeIcon, 
    KeyIcon, 
    ShieldCheckIcon 
} from '@heroicons/vue/24/outline'

interface User {
    id: number
    name: string
    email: string
    role: 'admin' | 'supervisor' | 'super_admin'
    is_active: boolean
}

const props = defineProps<{
    show: boolean
    user?: User | null
}>()

const emit = defineEmits<{
    close: []
}>()

const form = useForm({
    name: '',
    email: '',
    role: 'admin',
    password: '',
    is_active: true,
})

watch(
    () => props.user,
    (user) => {
        form.clearErrors()

        form.name = user?.name ?? ''
        form.email = user?.email ?? ''
        form.role = user?.role ?? 'admin'
        form.password = ''
        form.is_active = user?.is_active ?? true
    },
    {
        immediate: true,
    }
)

function closeModal() {
    form.reset()
    form.clearErrors()
    emit('close')
}

function submit() {
    const options = {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    }

    if (props.user) {
        form.put(
            route('admin.users.update', props.user.id),
            options
        )
        return
    }

    form.post(
        route('admin.users.store'),
        options
    )
}
</script>

<template>
    <Modal
        :show="show"
        max-width="md"
        @close="closeModal"
    >
        <div class="relative bg-white rounded-2xl overflow-hidden shadow-xl">
            
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <div class="space-y-1">
                    <h2 class="text-lg font-bold text-slate-900">
                        {{ user ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ user ? 'Perbarui informasi detail akun user ini.' : 'Isi formulir di bawah untuk mendaftarkan akun baru.' }}
                    </p>
                </div>
                <button 
                    type="button" 
                    class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                    @click="closeModal"
                >
                    <XMarkIcon class="h-5 w-5" />
                </button>
            </div>

            <form @submit.prevent="submit" class="p-6 space-y-5">
                
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 tracking-wide flex items-center gap-1.5">
                        <UserIcon class="h-4 w-4 text-slate-400" />
                        <span>Nama Lengkap</span>
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Masukkan nama pengguna"
                        class="w-full rounded-xl border-slate-200 text-sm shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400"
                        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-500/10': form.errors.name }"
                    >
                    <div v-if="form.errors.name" class="text-xs font-medium text-red-600 mt-1">
                        {{ form.errors.name }}
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 tracking-wide flex items-center gap-1.5">
                        <EnvelopeIcon class="h-4 w-4 text-slate-400" />
                        <span>Alamat Email</span>
                    </label>
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="contoh@rumahsakit.com"
                        class="w-full rounded-xl border-slate-200 text-sm shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400"
                        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-500/10': form.errors.email }"
                    >
                    <div v-if="form.errors.email" class="text-xs font-medium text-red-600 mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 tracking-wide flex items-center gap-1.5">
                        <ShieldCheckIcon class="h-4 w-4 text-slate-400" />
                        <span>Role Akses</span>
                    </label>
                    <select
                        v-model="form.role"
                        class="w-full rounded-xl border-slate-200 text-sm shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="admin">Admin</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700 tracking-wide flex items-center gap-1.5">
                        <KeyIcon class="h-4 w-4 text-slate-400" />
                        <span>Kata Sandi (Password)</span>
                    </label>
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border-slate-200 text-sm shadow-sm transition-all focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 placeholder:text-slate-400"
                        :class="{ 'border-red-400 focus:border-red-500 focus:ring-red-500/10': form.errors.password }"
                    >
                    <p v-if="user" class="text-[11px] text-slate-400 mt-1 leading-normal">
                        💡 Kosongkan kolom ini jika Anda tidak ingin mengganti password lama.
                    </p>
                    <div v-if="form.errors.password" class="text-xs font-medium text-red-600 mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer group select-none">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/20 transition-all cursor-pointer"
                        >
                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">
                            Akun ini aktif dan dapat masuk sistem
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 active:scale-98"
                        @click="closeModal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-md shadow-blue-600/10 transition-all hover:bg-blue-700 active:scale-98 disabled:opacity-50 disabled:pointer-events-none"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Perubahan</span>
                    </button>
                </div>

            </form>

        </div>
    </Modal>
</template>