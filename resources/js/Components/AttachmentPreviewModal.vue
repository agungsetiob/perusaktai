<script setup lang="ts">
const props = defineProps<{
    show: boolean
    file: any | null
}>()

const emit = defineEmits<{
    close: []
}>()

function isImage(type?: string) {
    return type?.startsWith('image/')
}

function isPdf(type?: string) {
    return type === 'application/pdf'
}
</script>

<template>
    <div
        v-if="show && file"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
    >
        <div
            class="relative max-h-[90vh] w-full max-w-5xl overflow-auto rounded-lg bg-white p-4"
        >
            <button
                class="absolute right-4 top-4 text-xl"
                @click="emit('close')"
            >
                ✕
            </button>

            <h2 class="mb-4 text-lg font-semibold">
                {{ file.original_name }}
            </h2>

            <img
                v-if="isImage(file.mime_type)"
                :src="file.url"
                class="mx-auto max-h-[75vh]"
            />

            <iframe
                v-else-if="isPdf(file.mime_type)"
                :src="file.url"
                class="h-[75vh] w-full"
            />

            <div
                v-else
                class="space-y-4 text-center"
            >
                <p>
                    Preview tidak tersedia untuk tipe file ini.
                </p>

                <a
                    :href="file.url"
                    target="_blank"
                    class="inline-block rounded bg-blue-600 px-4 py-2 text-white"
                >
                    Download File
                </a>
            </div>
        </div>
    </div>
</template>