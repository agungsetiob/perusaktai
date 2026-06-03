<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { formatDateTime } from '@/utils/date'
import { 
    XCircleIcon, 
    UserIcon, 
    CalendarDaysIcon, 
    ChatBubbleBottomCenterTextIcon,
    CheckIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    response: any
    actionMode?: boolean
}>()

const showReject = ref(false)

const approveForm = useForm({
    note: '',
})

const rejectForm = useForm({
    note: '',
})

function approve() {
    approveForm.post(
        route(
            'admin.responses.approve',
            props.response.id
        )
    )
}

function reject() {
    rejectForm.post(
        route(
            'admin.responses.reject',
            props.response.id
        ),
        {
            onSuccess: () => {
                showReject.value = false
                rejectForm.reset()
            }
        }
    )
}
</script>

<template>
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 border border-slate-100 text-slate-600">
                    <UserIcon class="h-4 w-4" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800">{{ response.creator?.name }}</h4>
                </div>
            </div>
            
            <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                <CalendarDaysIcon class="h-3.5 w-3.5" />
                <span>{{ formatDateTime(response.created_at) }}</span>
            </div>
        </div>

        <div class="space-y-1.5">
            <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Usulan Solusi:</span>
            <div class="rounded-xl bg-slate-50/50 border border-slate-100 p-4 text-xs sm:text-sm leading-relaxed text-slate-700 whitespace-pre-line font-medium shadow-2xs">
                {{ response.solution }}
            </div>
        </div>

        <div v-if="response.review_note" class="rounded-xl border border-amber-100 bg-gradient-to-b from-amber-50/40 to-white p-4 shadow-3xs animate-fade-in">
            <div class="flex items-center justify-between border-b border-amber-100/60 pb-2 mb-2">
                <div class="flex items-center gap-1.5 text-amber-800">
                    <ChatBubbleBottomCenterTextIcon class="h-4 w-4 shrink-0" />
                    <span class="text-xs font-bold uppercase tracking-wider">Catatan Review Atasan</span>
                </div>
                <span class="text-[10px] font-semibold text-amber-600/80">{{ formatDateTime(response.reviewed_at) }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-2">Reviewer: <span class="text-slate-800">{{ response.reviewer?.name }}</span></p>
            <div class="text-xs font-medium leading-relaxed text-slate-700 font-serif italic bg-amber-50/20 rounded-lg p-2.5 border border-amber-100/30">
                " {{ response.review_note }} "
            </div>
        </div>

        <div v-if="actionMode" class="pt-2 border-t border-slate-100 space-y-4">
            
            <div v-if="!showReject" class="space-y-1.5 animate-fade-in">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Catatan Persetujuan (Opsional):</label>
                <input 
                    v-model="approveForm.note" 
                    type="text" 
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/30 px-3.5 py-2 text-xs sm:text-sm outline-none transition-all focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 font-medium shadow-2xs"
                    placeholder="Masukkan pesan/instruksi tambahan"
                    :disabled="approveForm.processing"
                >
            </div>

            <div v-if="!showReject" class="flex items-center gap-2.5 animate-fade-in">
                <button 
                    @click="approve" 
                    :disabled="approveForm.processing"
                    class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/10 hover:bg-emerald-500 disabled:opacity-50 transition-all active:scale-[0.98]"
                >
                    <CheckIcon class="h-4 w-4" />
                    <span>{{ approveForm.processing ? 'Menyimpan...' : 'Setuju' }}</span>
                </button>

                <button 
                    @click="showReject = true" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-100 transition-all active:scale-[0.98]"
                >
                    <XMarkIcon class="h-4 w-4" />
                    <span>Tolak</span>
                </button>
            </div>

            <div v-if="showReject" class="rounded-xl border border-red-100 bg-red-50/20 p-4 space-y-3 animate-slide-down">
                <div class="flex items-center justify-between border-b border-red-100 pb-2">
                    <div class="flex items-center gap-1.5 text-red-700">
                        <XCircleIcon class="h-4 w-4" />
                        <h5 class="text-xs font-bold uppercase tracking-wider">Form Alasan Penolakan</h5>
                    </div>
                    <button 
                        type="button"
                        @click="showReject = false" 
                        class="text-slate-400 hover:text-slate-600 text-xs font-bold p-1 hover:bg-slate-100 rounded-md transition-colors"
                    >
                        Batal
                    </button>
                </div>

                <div>
                    <textarea 
                        v-model="rejectForm.note" 
                        rows="4" 
                        class="w-full rounded-xl border border-red-200 bg-white p-3 text-xs sm:text-sm shadow-2xs focus:border-red-500 focus:ring-1 focus:ring-red-500 placeholder:text-slate-400 font-medium transition-all"
                        placeholder="Tuliskan alasan penolakan..."
                        :disabled="rejectForm.processing"
                        required
                    />

                    <div v-if="rejectForm.errors.note" class="mt-1 text-[11px] font-semibold text-red-600">
                        ⚠️ {{ rejectForm.errors.note }}
                    </div>
                </div>

                <button 
                    @click="reject" 
                    :disabled="rejectForm.processing || !rejectForm.note.trim()"
                    class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-red-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-red-600/10 hover:bg-red-500 disabled:opacity-50 transition-all active:scale-[0.98]"
                >
                    <XMarkIcon class="h-4 w-4" />
                    <span>{{ rejectForm.processing ? 'Mengirim...' : 'Kirim Penolakan' }}</span>
                </button>
            </div>

        </div>

    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.2s ease-out forwards;
}
.animate-slide-down {
    animation: slideDown 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>