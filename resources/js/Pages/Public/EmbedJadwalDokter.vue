<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'

defineProps<{
    targetUrl: string
}>()
</script>

<template>
    <Head title="Informasi Layanan Terintegrasi" />

    <PublicLayout>
        <div class="relative w-[100vw] left-1/2 right-1/2 -mx-[50vw] animate-fade-in flex flex-col -mt-9">

            <div class="w-full h-[calc(100vh-200px)] min-h-[500px] overflow-hidden shadow-xs relative">
                <iframe 
                    :src="targetUrl" 
                    class="absolute inset-0 w-full h-full border-none"
                    sandbox="allow-scripts allow-same-origin allow-forms"
                    loading="lazy"
                >
                    <div class="flex flex-col items-center justify-center h-full p-6 text-center">
                        <p class="text-sm font-medium text-slate-500">
                            Browser Anda tidak mendukung penayangan langsung halaman ini.
                        </p>
                        <a :href="targetUrl" target="_blank" class="mt-2 text-xs font-bold text-blue-600 underline">
                            Klik di sini untuk membuka halaman secara langsung.
                        </a>
                    </div>
                </iframe>
            </div>

        </div>
    </PublicLayout>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>