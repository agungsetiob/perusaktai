<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import {
    PencilSquareIcon,
    MagnifyingGlassIcon,
    ArrowRightEndOnRectangleIcon,
    UserIcon,
    CalendarIcon
} from '@heroicons/vue/24/outline'

const page = usePage()
const isActive = (path: string) => page.url === path
const user = page.props.auth?.user
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50/30 text-slate-800 antialiased font-sans flex flex-col">

        <!-- Header / Navbar -->
        <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/80 backdrop-blur-md shadow-sm">
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">

                    <div className="flex items-center gap-3 min-w-0">
                        <div className="h-16 sm:h-18 w-auto flex items-center justify-center py-1.5 sm:py-2">
                            <Link href="/"
                                class="h-full block active:scale-95 transition-transform flex items-center gap-3">
                                <img src="/beraksi-logo.webp" alt="Logo"
                                    class="h-full w-auto object-contain block select-none" />
                                <span class="text-gray-400 font-light hidden sm:inline">|</span>
                                <span
                                    class="p-1 font-brand italic text-2xl font-extrabold tracking-tight bg-gradient-to-r from-amber-500 via-blue-500 to-blue-600 bg-clip-text text-transparent hidden sm:inline">
                                    RAJAPATEN AI
                                </span>
                            </Link>

                        </div>
                    </div>

                    <nav class="hidden sm:flex items-center gap-1.5">
                        <Link href="/pengaduan"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200" :class="isActive('/pengaduan')
                                ? 'bg-blue-50 text-blue-600 font-semibold'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            Buat Pengaduan
                        </Link>

                        <Link href="/tracking"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200" :class="isActive('/tracking')
                                ? 'bg-blue-50 text-blue-600 font-semibold'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            Tracking
                        </Link>

                        <Link href="/jadwal-dokter"
                            class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200" :class="isActive('/jadwal-dokter')
                                ? 'bg-blue-50 text-blue-600 font-semibold'
                                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            Jadwal Dokter
                        </Link>

                        <span class="h-4 w-px bg-slate-200 mx-1"></span>

                        <!-- Kondisi: jika user login -->
                        <Link v-if="user" href="/profile"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-slate-800 active:scale-95">
                            {{ user.name }}
                        </Link>

                        <!-- Jika belum login -->
                        <Link v-else href="/login"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-slate-800 active:scale-95">
                            Login
                        </Link>
                    </nav>

                    <div class="sm:hidden flex items-center">
                        <!-- Jika user login -->
                        <Link v-if="user" href="/profile"
                            class="inline-flex text-xxl font-semibold text-slate-600 rounded-lg border border-slate-300 p-1 active:scale-95">
                            <UserIcon class="h-7 w-7" />
                        </Link>

                        <!-- Jika belum login -->
                        <Link v-else href="/login"
                            class="inline-flex items-center justify-center gap-1 rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm active:scale-95">
                            <ArrowRightEndOnRectangleIcon class="h-3.5 w-3.5" />
                            <span>Login</span>
                        </Link>
                    </div>

                </div>
            </div>
        </header>

        <!-- Main Content Wrapper -->
        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:py-10 sm:px-6 lg:px-8 flex-1">
            <div class="animate-fade-in">
                <slot />
            </div>
        </main>

        <!-- FOOTER (Fleksibel: Menambahkan padding-bottom di mobile agar tidak tertutup Menu Bawah) -->
        <footer class="border-t border-slate-200 bg-white pb-16 sm:pb-6 mt-auto">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center text-center text-sm text-slate-500">
                    © 2026 <span class="font-semibold text-slate-700 mx-1">RAJAPATEN AI</span> Hak Cipta Dilindungi.
                </div>
            </div>
        </footer>

        <!-- NAVIGATION MOBILE (Sticky Bottom Bar - Hanya muncul di layar < 640px) -->
        <div
            class="sm:hidden fixed bottom-0 left-0 right-0 z-50 border-t border-slate-200/80 bg-white/95 backdrop-blur-lg px-6 py-2.5 shadow-[0_-4px_12px_rgba(0,0,0,0.03)] flex items-center justify-around">

            <!-- Tab: Buat Pengaduan -->
            <Link href="/pengaduan" class="flex flex-col items-center gap-1 px-3 py-1 transition-colors"
                :class="isActive('/pengaduan') ? 'text-blue-600 font-bold' : 'text-slate-400'">
                <PencilSquareIcon class="h-5 w-5" :class="isActive('/pengaduan') ? 'stroke-2' : ''" />
                <span class="text-[10px] tracking-tight">Buat Aduan</span>
            </Link>

            <!-- Tab: Tracking -->
            <Link href="/tracking" class="flex flex-col items-center gap-1 px-3 py-1 transition-colors"
                :class="isActive('/tracking') ? 'text-blue-600 font-bold' : 'text-slate-400'">
                <MagnifyingGlassIcon class="h-5 w-5" :class="isActive('/tracking') ? 'stroke-2' : ''" />
                <span class="text-[10px] tracking-tight">Lacak Status</span>
            </Link>

            <!-- Tab: Jadwal Dokter -->
            <Link href="/jadwal-dokter" class="flex flex-col items-center gap-1 px-3 py-1 transition-colors"
                :class="isActive('/jadwal-dokter') ? 'text-blue-600 font-bold' : 'text-slate-400'">
                <CalendarIcon class="h-5 w-5" :class="isActive('/jadwal-dokter') ? 'stroke-2' : ''" />
                <span class="text-[10px] tracking-tight">Jadwal Dokter</span>
            </Link>

        </div>

    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(4px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>