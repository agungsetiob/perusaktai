<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import { watch } from 'vue'
import { ClockIcon } from "@heroicons/vue/24/outline"
const props = defineProps<{
    show: boolean
    complaint: any | null
}>()

const emit = defineEmits<{
    (e: 'close'): void
    (e: 'updated'): void
}>()

const form = useForm({
    submitted_at: '',
})

function updateForm() {
    if (props.complaint) {
        form.submitted_at = props.complaint.submitted_at.substring(0, 16)
    }
}
watch(() => props.complaint, updateForm, { immediate: true })

function updateSubmittedAt() {
    if (!props.complaint) return
    form.patch(
        route('admin.complaints.update-submitted-at', props.complaint.id),
        {
            preserveScroll: true,
            onSuccess() {
                emit('updated')
                emit('close')
            }
        }
    )
}
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="lg">
        <div class="p-6">
            <h2 class="text-lg font-bold text-slate-800">
                Edit Tanggal Pengaduan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Tracking Code: <span class="text-blue-700">{{ complaint?.tracking_code }}</span>
            </p>
            <p class="mt-1 text-sm text-slate-500">
                Pelapor: <span v-if="complaint.is_anonymous"
                    class="text-red-600">
                    Anonim
                </span>
                <span v-else
                    class="text-blue-700">
                    {{ complaint.name }}
                </span>
            </p>

            <div class="mt-5">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                    Tanggal Pengaduan
                </label>
                <input type="datetime-local" v-model="form.submitted_at"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 disabled:opacity-60"
                    :disabled="form.processing" />
                <div v-if="form.errors.submitted_at" class="mt-1 text-xs text-red-600">
                    {{ form.errors.submitted_at }}
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button @click="emit('close')"
                    class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50"
                    :disabled="form.processing">
                    Batal
                </button>
                <button @click="updateSubmittedAt" :disabled="form.processing"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 disabled:opacity-70">
                    <ClockIcon v-if="form.processing" class="h-4 w-4 animate-spin text-white" />
                    <span>Simpan</span>
                </button>
            </div>
        </div>
    </Modal>
</template>