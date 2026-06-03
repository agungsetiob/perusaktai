<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

import { 
    XCircleIcon, 
    ChevronDownIcon, 
    ChevronUpIcon,
    PaperAirplaneIcon,
    ExclamationTriangleIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    complaintId: number
}>()

const showForm = ref(false)

const form = useForm({
    note: '',
})

function submit() {
    form.post(
        route(
            'admin.complaints.reject',
            props.complaintId
        ),
        {
            onSuccess: () => {
                showForm.value = false
                form.reset()
            }
        }
    )
}
</script>

<template>
    <div class="mt-6 border-t border-slate-200/80 pt-5">
        
        <button
            type="button"
            @click="showForm = !showForm"
            class="inline-flex w-full justify-center items-center gap-2 rounded-xl border px-4 py-2.5 text-xs font-bold transition-all active:scale-[0.98] shadow-3xs"
            :class="showForm 
                ? 'bg-slate-800 border-slate-800 text-white hover:bg-slate-700' 
                : 'bg-rose-50 border-rose-100 text-rose-600 hover:bg-rose-100/70'"
        >
            <XCircleIcon class="h-4 w-4" />
            <span>Tolak Pengaduan</span>
            <component :is="showForm ? ChevronUpIcon : ChevronDownIcon" class="h-3.5 w-3.5 opacity-70 ml-1" />
        </button>

        <div
            v-if="showForm"
            class="mt-4 rounded-2xl border border-rose-100 bg-gradient-to-b from-rose-50/20 to-white p-4 space-y-4 shadow-sm animate-slide-down"
        >
            <div class="flex items-start gap-2.5 rounded-xl bg-rose-50/60 border border-rose-100/40 p-3 text-xs text-rose-700 font-medium">
                <ExclamationTriangleIcon class="h-4 w-4 shrink-0 mt-0.5 text-rose-500" />
                <div>
                    <p class="font-bold">Perhatian:</p>
                    <p class="opacity-90 mt-0.5 leading-relaxed">
                        Menolak pengaduan ini akan menghentikan alur kerja pengaduan.
                    </p>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Alasan Resmi Penolakan:</label>
                <textarea
                    v-model="form.note"
                    rows="4"
                    class="w-full rounded-xl border border-slate-200 bg-white p-3.5 text-xs sm:text-sm shadow-2xs focus:border-rose-500 focus:ring-1 focus:ring-rose-500 placeholder:text-slate-400 font-medium transition-all"
                    placeholder="Contoh: Pengaduan tidak disertai bukti yang valid / Salah alamat instansi penanganan..."
                    :disabled="form.processing"
                    required
                />

                <div
                    v-if="form.errors.note"
                    class="mt-1 text-xs font-semibold text-rose-600"
                >
                    ⚠️ {{ form.errors.note }}
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button
                    type="button"
                    @click="submit"
                    :disabled="form.processing || !form.note.trim()"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-rose-600/10 hover:bg-rose-500 disabled:bg-slate-100 disabled:text-slate-400 disabled:shadow-none transition-all active:scale-[0.98]"
                >
                    <PaperAirplaneIcon class="h-3.5 w-3.5" />
                    <span>{{ form.processing ? 'Memproses...' : 'Tolak Pengaduan' }}</span>
                </button>
            </div>
        </div>

    </div>
</template>

<style scoped>
.animate-slide-down {
    animation: slideDown 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>