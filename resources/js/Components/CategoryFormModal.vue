<script setup lang="ts">
import Modal from '@/Components/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

import { 
    FolderPlusIcon, 
    PencilSquareIcon, 
    XMarkIcon 
} from '@heroicons/vue/24/outline'

interface Category {
    id: number
    name: string
    is_active: boolean
}

const props = defineProps<{
    show: boolean
    category?: Category | null
}>()

const emit = defineEmits<{
    close: []
}>()

const form = useForm({
    name: '',
    is_active: true,
})

watch(
    () => props.category,
    (category) => {
        form.clearErrors()

        form.name = category?.name ?? ''
        form.is_active = category?.is_active ?? true
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

    if (props.category) {
        form.put(
            route('admin.categories.update', props.category.id),
            options
        )
        return
    }

    form.post(
        route('admin.categories.store'),
        options
    )
}
</script>

<template>
    <Modal :show="show" max-width="md" @close="closeModal()">
        <div class="relative overflow-hidden bg-white rounded-xl">
            
            <!-- MODAL HEADER -->
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <Component 
                            :is="category ? PencilSquareIcon : FolderPlusIcon" 
                            class="h-4.5 w-4.5" 
                        />
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">
                            {{ category ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        </h2>
                        <p class="text-[11px] text-slate-400 font-medium">
                            {{ category ? 'Perbarui data klasifikasi aduan' : 'Buat master data kategori baru' }}
                        </p>
                    </div>
                </div>

                <!-- Tombol Close Silang di Pojok -->
                <button 
                    type="button" 
                    @click="closeModal()"
                    class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>

            <!-- MODAL BODY & FORM -->
            <form @submit.prevent="submit" class="p-6 space-y-4">
                
                <!-- Input Nama Kategori -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                        Nama Kategori
                    </label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        placeholder="Contoh: Fasilitas Kamar Mandi, Pelayanan Medis"
                        class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 font-medium transition-all focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-50"
                        :class="form.errors.name ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-50' : ''"
                    >
                    <!-- Validasi Error -->
                    <div v-if="form.errors.name" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                        <span>⚠️</span> {{ form.errors.name }}
                    </div>
                </div>

                <div class="pt-1">
                    <label class="inline-flex items-center gap-2.5 cursor-pointer select-none group">
                        <div class="relative flex items-center">
                            <input 
                                v-model="form.is_active" 
                                type="checkbox" 
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 transition-all focus:ring-4 focus:ring-blue-50 focus:ring-offset-0"
                            >
                        </div>
                        <div class="text-xs sm:text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">
                            Aktifkan Kategori Ini
                            <span class="block text-[10px] font-normal text-slate-400 mt-0.5">
                                Kategori yang aktif langsung muncul di form pengaduan publik.
                            </span>
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-4 mt-6">
                    <button 
                        type="button" 
                        class="rounded-lg border border-slate-200 px-4 py-2 text-xs font-bold text-slate-500 bg-white hover:bg-slate-50 active:scale-95 transition-all" 
                        @click="closeModal()"
                    >
                        Batal
                    </button>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition-all hover:bg-blue-700 active:scale-95 disabled:opacity-50 disabled:pointer-events-none"
                    >
                        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                    </button>
                </div>
            </form>

        </div>
    </Modal>
</template>