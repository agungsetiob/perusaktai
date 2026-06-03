<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
}>()
</script>

<template>
  <div v-if="links.length > 3" 
       class="flex items-center justify-between border-t border-slate-100 bg-white px-6 py-4">
    
    <!-- Mobile Pagination -->
    <div class="flex flex-1 justify-between sm:hidden">
      <!-- Previous -->
      <template v-if="links[0].url">
        <Link :href="links[0].url!"
          class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 active:scale-95 transition-all">
          ← Sebelumnya
        </Link>
      </template>
      <div v-else
        class="inline-flex items-center rounded-full border border-slate-100 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-400 cursor-not-allowed">
        ← Sebelumnya
      </div>

      <!-- Next -->
      <template v-if="links[links.length - 1].url">
        <Link :href="links[links.length - 1].url!"
          class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 active:scale-95 transition-all">
          Selanjutnya →
        </Link>
      </template>
      <div v-else
        class="inline-flex items-center rounded-full border border-slate-100 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-400 cursor-not-allowed">
        Selanjutnya →
      </div>
    </div>

    <!-- Desktop Pagination -->
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center">
      <nav class="isolate inline-flex gap-1 rounded-full p-1 bg-slate-100/60 border border-slate-200/60 shadow-sm" aria-label="Pagination">
        <template v-for="(link, key) in links" :key="key">
          <div v-if="link.url === null"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-400 cursor-not-allowed select-none"
            v-html="link.label" />
          
          <Link v-else :href="link.url"
            class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-bold transition-all duration-200"
            :class="link.active 
              ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/30' 
              : 'text-slate-600 hover:bg-white hover:text-blue-600 hover:shadow-sm'"
            v-html="link.label" />
        </template>
      </nav>
    </div>
  </div>
</template>
