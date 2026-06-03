<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    status: string
}>()

const classes = computed(() => {

    switch (props.status) {

        case 'waiting':
            return 'bg-yellow-100 text-yellow-800'

        case 'under_review':
            return 'bg-orange-100 text-orange-800'

        case 'on_process':
            return 'bg-blue-100 text-blue-800'

        case 'solved':
            return 'bg-green-100 text-green-800'

        case 'rejected':
            return 'bg-red-100 text-red-800'

        default:
            return 'bg-gray-100 text-gray-800'
    }
})

const label = computed(() => {

    switch (props.status) {

        case 'waiting':
            return 'Menunggu'

        case 'under_review':
            return 'Ditinjau'

        case 'on_process':
            return 'Diproses'

        case 'solved':
            return 'Selesai'

        case 'rejected':
            return 'Ditolak'

        default:
            return props.status
    }
})
</script>

<template>
    <span
        class="rounded-full px-3 py-1 text-xs font-medium"
        :class="classes"
    >
        {{ label }}
    </span>
</template>