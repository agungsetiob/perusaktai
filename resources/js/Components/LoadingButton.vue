<script setup lang="ts">
defineProps<{
    loading?: boolean
}>()
</script>

<template>

    <button
        type="submit"
        :disabled="loading"
        class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
    >
        <slot />

        <span v-if="loading">
            ...
        </span>
    </button>

</template>