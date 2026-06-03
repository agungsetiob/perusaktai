<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'

import { SparklesIcon, PaperAirplaneIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{
    complaintId: number
}>()

const form = useForm({
    solution: '',
})

// State untuk Manajemen Pemrosesan AI
const isAiLoading = ref(false)
const aiError = ref('')

async function generateAiSuggestion(event: Event) {
    event.preventDefault()
    event.stopPropagation()

    isAiLoading.value = true
    aiError.value = ''
    
    try {
        const response = await axios.post(route('admin.complaints.ai-suggestion', props.complaintId))
        
        if (response.data && response.data.suggestion) {
            form.solution = response.data.suggestion
        } else {
            aiError.value = 'Sistem mengembalikan respons kosong. Pastikan API Key Gemini Anda valid.'
        }
    } catch (error: any) {
        aiError.value = error.response?.data?.message || 'Gagal terhubung ke server AI. Periksa logs/laravel.log.'
    } finally {
        isAiLoading.value = false
    }
}

function submit() {
    form.post(
        route(
            'admin.complaints.responses.store',
            props.complaintId
        ),
        {
            onSuccess: () => form.reset('solution')
        }
    )
}
</script>

<template>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                Tindak Lanjut
            </h2>
            
            <button
                type="button"
                @click.prevent.stop="generateAiSuggestion"
                :disabled="isAiLoading || form.processing"
                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-100 bg-blue-50/60 px-2.5 py-1.5 text-xs font-bold text-blue-600 transition-all hover:bg-blue-100 hover:text-blue-700 disabled:opacity-50 disabled:pointer-events-none active:scale-95 shadow-2xs"
            >
                <SparklesIcon class="h-3.5 w-3.5 text-blue-500" :class="{ 'animate-spin': isAiLoading }" />
                <span>{{ isAiLoading ? 'Merumuskan...' : 'Rekomendasi AI' }}</span>
            </button>
        </div>

        <div v-if="aiError" class="mb-4 flex items-start gap-2 rounded-lg bg-red-50 border border-red-100 p-2.5 text-xs text-red-600 font-medium animate-fade-in">
            <ExclamationTriangleIcon class="h-4 w-4 shrink-0 mt-0.5" />
            <div>
                <p class="font-bold">Sistem AI Mengalami Kendala:</p>
                <p class="opacity-90 font-mono text-[11px] mt-0.5">{{ aiError }}</p>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <textarea
                    v-model="form.solution"
                    rows="6"
                    class="w-full rounded-xl border border-slate-200 p-3.5 text-xs sm:text-sm shadow-2xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 font-medium transition-all"
                    placeholder="Tuliskan draf solusi penanganan atau klik tombol 'Rekomendasi AI' di atas untuk mengisi otomatis..."
                    :disabled="form.processing"
                    required
                />

                <div
                    v-if="form.errors.solution"
                    class="mt-1 text-xs font-semibold text-red-600"
                >
                    ⚠️ {{ form.errors.solution }}
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing || !form.solution.trim()"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-600/10 hover:bg-blue-500 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none transition-all active:scale-[0.98]"
            >
                <PaperAirplaneIcon class="h-3.5 w-3.5" />
                <span>{{ form.processing ? 'Memproses...' : 'Ajukan Solusi Resmi' }}</span>
            </button>
        </form>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.2s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-2px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>