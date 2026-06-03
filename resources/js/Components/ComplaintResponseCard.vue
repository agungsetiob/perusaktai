<script setup lang="ts">
defineProps<{
    response: any
}>()
</script>

<template>

    <div class="rounded-lg border bg-white p-5">

        <div class="mb-4 flex justify-between">

            <h3 class="font-semibold">
                Solusi
            </h3>

            <StatusBadge
                :status="response.approval_status"
            />

        </div>

        <p class="whitespace-pre-line">
            {{ response.solution }}
        </p>

        <div
            v-if="response.review_note"
            class="mt-4 rounded bg-gray-50 p-3"
        >
            <strong>Catatan Atasan:</strong>

            <p>
                {{ response.review_note }}
            </p>
        </div>

    </div>

</template>