<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { 
    ChatBubbleLeftRightIcon, 
    PaperAirplaneIcon, 
    ArrowPathIcon,
    XMarkIcon 
} from '@heroicons/vue/24/outline'

const props = defineProps<{
    complaintId: number
}>()

// State untuk mengatur buka/tutup chat card
const isOpen = ref(false)

interface Message {
    sender: 'user' | 'ai'
    text: string
}

const messages = ref<Message[]>([
    { sender: 'ai', text: 'Halo! Ada yang bisa saya bantu untuk membedah atau menganalisis aduan ini?' }
])
const inputMessage = ref('')
const isLoading = ref(false)

async function sendMessage() {
    if (!inputMessage.value.trim() || isLoading.value) return

    const userText = inputMessage.value
    messages.value.push({ sender: 'user', text: userText })
    inputMessage.value = ''
    isLoading.value = true

    try {
        const response = await axios.post(`/admin/complaints/${props.complaintId}/ai-chat`, {
            message: userText
        })

        if (response.data.success) {
            messages.value.push({ sender: 'ai', text: response.data.reply })
        } else {
            messages.value.push({ sender: 'ai', text: 'Gagal mendapatkan jawaban dari AI.' })
        }
    } catch (error) {
        messages.value.push({ sender: 'ai', text: 'Terjadi kesalahan koneksi saat menghubungi server AI.' })
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50 font-sans flex flex-col items-end">
        
        <div 
            v-if="isOpen" 
            class="mb-4 rounded-2xl border border-slate-200 bg-white shadow-xl flex flex-col h-[450px] w-[360px] sm:w-[400px] overflow-hidden transition-all duration-300 transform scale-100 origin-bottom-right"
        >
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <ChatBubbleLeftRightIcon class="h-5 w-5 text-blue-600" />
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Asisten Analisis AI</h3>
                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">Diskusikan solusi & SOP aduan</p>
                    </div>
                </div>
                <button 
                    @click="isOpen = false" 
                    class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors"
                >
                    <XMarkIcon class="h-4 w-4" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/30">
                <div 
                    v-for="(msg, index) in messages" 
                    :key="index"
                    :class="['flex', msg.sender === 'user' ? 'justify-end' : 'justify-start']"
                >
                    <div 
                        :class="[
                            'max-w-[85%] rounded-xl px-3 py-2 text-xs leading-relaxed shadow-2xs font-medium',
                            msg.sender === 'user' 
                                ? 'bg-blue-600 text-white rounded-tr-none' 
                                : 'bg-white border border-slate-200 text-slate-600 rounded-tl-none'
                        ]"
                    >
                        {{ msg.text }}
                    </div>
                </div>

                <div v-if="isLoading" class="flex justify-start">
                    <div class="bg-white border border-slate-100 rounded-xl rounded-tl-none px-3 py-2 shadow-2xs flex items-center gap-2 text-slate-400 text-xs font-medium">
                        <ArrowPathIcon class="h-3.5 w-3.5 animate-spin text-blue-500" />
                        <span>Gemini sedang menganalisis...</span>
                    </div>
                </div>
            </div>

            <form @submit.prevent="sendMessage" class="p-3 border-t border-slate-100 bg-white flex gap-2">
                <input 
                    v-model="inputMessage"
                    type="text" 
                    placeholder="Tanya AI perihal komplain ini..."
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:outline-hidden focus:border-blue-500 focus:bg-white transition-all font-medium text-slate-700"
                    :disabled="isLoading"
                />
                <button 
                    type="submit"
                    :disabled="isLoading"
                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center shrink-0"
                >
                    <PaperAirplaneIcon class="h-3.5 w-3.5" />
                </button>
            </form>
        </div>

        <button 
            @click="isOpen = !isOpen"
            class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all duration-200 group relative"
            :class="{'rotate-90 bg-slate-700 hover:bg-slate-800': isOpen}"
        >
            <XMarkIcon v-if="isOpen" class="h-6 w-6" />
            <ChatBubbleLeftRightIcon v-else class="h-6 w-6 animate-pulse" />
            
            <span v-if="!isOpen" class="absolute right-16 scale-0 group-hover:scale-100 transition-all duration-150 bg-slate-800 text-white text-[10px] font-bold px-2.5 py-1 rounded-md whitespace-nowrap shadow-md uppercase tracking-wider">
                Tanya AI ✨
            </span>
        </button>

    </div>
</template>