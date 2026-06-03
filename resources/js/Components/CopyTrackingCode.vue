<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
    code: string
}>()

const copied = ref(false)

async function copy() {
    // Validasi jika code kosong
    if (!props.code) return

    try {
        // Cara utama menggunakan Clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(props.code)
        } else {
            // Fallback untuk HTTP / Browser lama
            const textArea = document.createElement("textarea")
            textArea.value = props.code
            textArea.style.position = "fixed" // hindari scrolling
            document.body.appendChild(textArea)
            textArea.focus()
            textArea.select()
            document.execCommand('copy')
            document.body.removeChild(textArea)
        }

        // Efek visual tombol berhasil diganti
        copied.value = true
        setTimeout(() => {
            copied.value = false
        }, 2000)

    } catch (err) {
        console.error('Gagal menyalin teks: ', err)
        alert('Gagal menyalin kode otomatis. Silakan salin secara manual.')
    }
}
</script>

<template>
    <div class="flex gap-2">
        <input
            readonly
            :value="code"
            class="flex-1 rounded border bg-gray-50 px-3 py-2 text-center font-mono font-semibold text-gray-800"
            @click="(e) => (e.target as HTMLInputElement).select()"
        >
        <button
            type="button"
            @click="copy"
            class="transition-colors duration-200 rounded px-4 py-2 text-white font-medium"
            :class="copied ? 'bg-green-600' : 'bg-blue-600 hover:bg-blue-700'"
        >
            {{ copied ? '✓ Tersalin' : 'Copy' }}
        </button>
    </div>
</template>