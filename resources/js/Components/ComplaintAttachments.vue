<script setup lang="ts">
defineProps<{
    attachments: any[]
}>()
</script>

<template>

    <div class="space-y-2">

        <a
            v-for="file in attachments"
            :key="file.id"
            :href="`/storage/${file.file_path}`"
            target="_blank"
            class="block rounded border p-3 hover:bg-gray-50"
        >
            {{ file.original_name }}
        </a>

    </div>

</template>