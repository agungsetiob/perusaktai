<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { CheckBadgeIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{
    complaintId: number
}>()

const form = useForm({})

function submit() {
    form.post(
        route(
            'admin.complaints.solve',
            props.complaintId
        )
    )
}
</script>

<template>
    <div class="rounded-2xl border border-emerald-100 bg-gradient-to-b from-emerald-50/30 to-white p-5 shadow-sm space-y-4">
        
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 shadow-2xs">
                <CheckBadgeIcon class="h-5 w-5" />
            </div>
            <div class="space-y-0.5">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                    Selesaikan Pengaduan
                </h2>
                <p class="text-xs font-medium text-slate-500 leading-relaxed">
                    Pastikan seluruh draf solusi telah disetujui oleh atasan dan tindakan operasional di lapangan telah selesai dilaksanakan.
                </p>
            </div>
        </div>

        <div class="pt-2 border-t border-slate-100">
            <button
                type="button"
                @click="submit"
                :disabled="form.processing"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/10 hover:bg-emerald-500 disabled:bg-slate-100 disabled:text-slate-400 disabled:shadow-none transition-all active:scale-[0.98]"
            >
                <svg v-if="form.processing" class="h-3.5 w-3.5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                <CheckIcon v-else class="h-4 w-4" />
                
                <span>{{ form.processing ? 'Menyimpan...' : 'Tandai Selesai' }}</span>
            </button>
        </div>

    </div>
</template>