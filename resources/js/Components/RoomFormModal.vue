<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import { XMarkIcon, BuildingOfficeIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{
    show: boolean
    room?: any | null
}>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const form = ref({
    name: '',
    is_active: true
})

const isSubmitting = ref(false)
const errors = ref<Record<string, string>>({})

// Watch untuk reset form saat modal dibuka/tutup
watch(() => props.show, (newVal) => {
    if (newVal) {
        if (props.room) {
            form.value = {
                name: props.room.name,
                is_active: props.room.is_active
            }
        } else {
            form.value = {
                name: '',
                is_active: true
            }
        }
        errors.value = {}
    }
})

function closeModal() {
    emit('close')
}

function submitForm() {
    isSubmitting.value = true
    errors.value = {}

    const url = props.room
        ? route('admin.rooms.update', props.room.id)
        : route('admin.rooms.store')

    const method = props.room ? 'put' : 'post'

    router[method](url, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            isSubmitting.value = false
            closeModal()
        },
        onError: (error) => {
            isSubmitting.value = false

            // Handle berbagai bentuk error
            if (error.errors && typeof error.errors === 'object') {
                const errs: Record<string, unknown> = error.errors
                for (const key in errs) {
                    const val = errs[key]
                    if (Array.isArray(val)) {
                        errors.value[key] = val[0]
                    } else {
                        errors.value[key] = val as string
                    }
                }
            }
        }
    })
}
</script>

<template>
    <Modal :show="show" max-width="md" @close="closeModal()">
        <div class="relative bg-white rounded-xl overflow-hidden">
            <!-- Header -->
            <div
                class="flex items-center justify-between border-b border-slate-100 px-5 py-3.5 bg-gradient-to-r from-blue-50/50 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <BuildingOfficeIcon class="h-4 w-4" />
                    </div>
                    <h2 class="text-sm font-bold text-slate-800">
                        {{ room ? 'Edit Ruangan' : 'Tambah Ruangan Baru' }}
                    </h2>
                </div>
                <button type="button" @click="closeModal()"
                    class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>

            <!-- Form Body -->
            <form @submit.prevent="submitForm" class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                        Nama Ruangan <span class="text-rose-500">*</span>
                    </label>
                    <input v-model="form.name" type="text"
                        class="w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:outline-hidden transition-all"
                        placeholder="Contoh: Rawat Inap, ICU, IGD, Poli Umum"
                        :class="{ 'border-rose-400 focus:border-rose-400': errors.name }" />
                    <p v-if="errors.name" class="mt-1 text-xs text-rose-500 font-medium">
                        {{ errors.name }}
                    </p>
                </div>

                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox"
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-200" />
                        <span class="text-xs font-semibold text-slate-600">
                            Status Aktif
                        </span>
                    </label>
                    <p class="mt-1 text-[10px] text-slate-400">
                        Nonaktifkan jika ruangan ini tidak ingin ditampilkan dalam formulir pengaduan.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                    <button type="button"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors"
                        @click="closeModal()" :disabled="isSubmitting">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition-all active:scale-95 disabled:opacity-50"
                        :disabled="isSubmitting">
                        {{ isSubmitting ? 'Menyimpan...' : (room ? 'Update' : 'Simpan') }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>