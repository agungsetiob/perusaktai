<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { onClickOutside } from '@vueuse/core'
import {
    Squares2X2Icon,
    DocumentTextIcon,
    FolderIcon,
    UsersIcon,
    ArrowLeftEndOnRectangleIcon,
    Bars3Icon,
    ChevronLeftIcon,
    ChevronRightIcon,
    BellIcon,
    ChevronDownIcon,
    UserIcon,
} from '@heroicons/vue/24/outline'

const page = usePage<any>()

const user = computed(
    () => page.props.auth.user
)

const notifications = computed(
    () => page.props.notifications ?? []
)

/*
|--------------------------------------------------------------------------
| Sidebar
|--------------------------------------------------------------------------
*/

const isSidebarOpen = ref(false)

const isSidebarCondensed = ref(false)

function toggleSidebarMobile() {
    isSidebarOpen.value =
        !isSidebarOpen.value
}

function toggleSidebarCondensed() {
    isSidebarCondensed.value =
        !isSidebarCondensed.value
}

/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/

const notificationRef = ref()

const showNotifications = ref(false)

const unreadNotifications = computed(
    () =>
        notifications.value.filter(
            (notification: any) =>
                !notification.read_at
        )
)

/*
|--------------------------------------------------------------------------
| User Menu
|--------------------------------------------------------------------------
*/

const userMenuRef = ref()

const isUserMenuOpen = ref(false)

/*
|--------------------------------------------------------------------------
| Dropdown Toggles
|--------------------------------------------------------------------------
*/

function toggleNotifications() {
    showNotifications.value =
        !showNotifications.value

    if (showNotifications.value) {
        isUserMenuOpen.value = false
    }
}

function toggleUserMenu() {
    isUserMenuOpen.value =
        !isUserMenuOpen.value

    if (isUserMenuOpen.value) {
        showNotifications.value = false
    }
}

/*
|--------------------------------------------------------------------------
| Click Outside
|--------------------------------------------------------------------------
*/

onClickOutside(
    notificationRef,
    () => {
        showNotifications.value = false
    }
)

onClickOutside(
    userMenuRef,
    () => {
        isUserMenuOpen.value = false
    }
)

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function isRouteActive(
    routeName: string
) {
    return typeof route !== 'undefined'
        ? route().current(routeName)
        : false
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

        <!-- BACKDROP MOBILE (Muncul saat sidebar mobile terbuka) -->
        <div v-if="isSidebarOpen" @click="toggleSidebarMobile"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm transition-opacity lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside
            class="fixed left-0 top-0 z-50 h-screen border-r border-slate-200 bg-slate-900 text-slate-300 shadow-xl transition-all duration-300"
            :class="[
                // Responsive classes
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                // Mode Ringkas vs Lebar di Desktop
                isSidebarCondensed ? 'lg:w-20' : 'lg:w-64 w-64'
            ]">
            <!-- Sidebar Header / Brand -->
            <div class="flex h-16 items-center transition-all duration-300 border-b border-slate-800"
                :class="isSidebarCondensed ? 'justify-center px-4' : 'px-6'">
                <img src="/beraksi-logo.webp" alt="Logo" class="h-full w-auto object-contain select-none">
            </div>


            <!-- Sidebar Navigation -->
            <nav class="p-3 space-y-1.5">

                <!-- Menu: Dashboard -->
                <Link :href="route('admin.dashboard')"
                    class="flex items-center rounded-lg py-2.5 text-sm font-medium transition-all duration-200 group"
                    :class="[
                        isRouteActive('admin.dashboard')
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10'
                            : 'hover:bg-slate-800 hover:text-slate-100',
                        isSidebarCondensed ? 'justify-center px-0' : 'px-4 gap-3'
                    ]" :title="isSidebarCondensed ? 'Dashboard' : ''">
                    <Squares2X2Icon class="h-5 w-5 shrink-0 transition-colors duration-200"
                        :class="isRouteActive('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'" />
                    <span v-if="!isSidebarCondensed" class="truncate animate-fade-in-quick">Dashboard</span>
                </Link>

                <!-- Menu: Pengaduan -->
                <Link :href="route('admin.complaints.index')"
                    class="flex items-center rounded-lg py-2.5 text-sm font-medium transition-all duration-200 group"
                    :class="[
                        isRouteActive('admin.complaints.*')
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10'
                            : 'hover:bg-slate-800 hover:text-slate-100',
                        isSidebarCondensed ? 'justify-center px-0' : 'px-4 gap-3'
                    ]" :title="isSidebarCondensed ? 'Daftar Pengaduan' : ''">
                    <DocumentTextIcon class="h-5 w-5 shrink-0 transition-colors duration-200"
                        :class="isRouteActive('admin.complaints.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'" />
                    <span v-if="!isSidebarCondensed" class="truncate animate-fade-in-quick">Daftar Pengaduan</span>
                </Link>

                <!-- Divider Super Admin -->
                <div v-if="user?.role === 'super_admin'" class="transition-all duration-300">
                    <div v-if="!isSidebarCondensed"
                        class="pt-4 pb-2 px-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider truncate animate-fade-in-quick">
                        Manajemen Sistem
                    </div>
                    <div v-else class="border-t border-slate-800 my-4"></div>
                </div>

                <!-- Menu: Kategori -->
                <Link v-if="user?.role === 'super_admin' || user?.role === 'admin' || user?.role === 'supervisor'" :href="route('admin.categories.index')"
                    class="flex items-center rounded-lg py-2.5 text-sm font-medium transition-all duration-200 group"
                    :class="[
                        isRouteActive('admin.categories.*')
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10'
                            : 'hover:bg-slate-800 hover:text-slate-100',
                        isSidebarCondensed ? 'justify-center px-0' : 'px-4 gap-3'
                    ]" :title="isSidebarCondensed ? 'Kategori Pengaduan' : ''">
                    <FolderIcon class="h-5 w-5 shrink-0 transition-colors duration-200"
                        :class="isRouteActive('admin.categories.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'" />
                    <span v-if="!isSidebarCondensed" class="truncate animate-fade-in-quick">Kategori Pengaduan</span>
                </Link>

                <!-- Menu: User (Super Admin Only) -->
                <Link v-if="user?.role === 'super_admin'" :href="route('admin.users.index')"
                    class="flex items-center rounded-lg py-2.5 text-sm font-medium transition-all duration-200 group"
                    :class="[
                        isRouteActive('admin.users.*')
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10'
                            : 'hover:bg-slate-800 hover:text-slate-100',
                        isSidebarCondensed ? 'justify-center px-0' : 'px-4 gap-3'
                    ]" :title="isSidebarCondensed ? 'Manajemen User' : ''">
                    <UsersIcon class="h-5 w-5 shrink-0 transition-colors duration-200"
                        :class="isRouteActive('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'" />
                    <span v-if="!isSidebarCondensed" class="truncate animate-fade-in-quick">Manajemen User</span>
                </Link>

                <!-- Menu: Audit Logs (Super Admin & Supervisor) -->
                <Link v-if="user?.role === 'super_admin' || user?.role === 'supervisor'" :href="route('admin.audit-logs.index')"
                    class="flex items-center rounded-lg py-2.5 text-sm font-medium transition-all duration-200 group"
                    :class="[
                        isRouteActive('admin.audit-logs.*')
                            ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10'
                            : 'hover:bg-slate-800 hover:text-slate-100',
                        isSidebarCondensed ? 'justify-center px-0' : 'px-4 gap-3'
                    ]" :title="isSidebarCondensed ? 'Audit Logs' : ''">
                    <DocumentTextIcon class="h-5 w-5 shrink-0 transition-colors duration-200"
                        :class="isRouteActive('admin.audit-logs.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'" />
                    <span v-if="!isSidebarCondensed" class="truncate animate-fade-in-quick">Audit Logs</span>
                </Link>

            </nav>

            <!-- TOMBOL MINIMIZE SIDEBAR (Hanya Desktop) -->
            <button @click="toggleSidebarCondensed"
                class="hidden lg:flex absolute bottom-4 right-0 left-0 mx-4 h-9 items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition-colors"
                :title="isSidebarCondensed ? 'Perluas' : 'Sembunyikan'">
                <ChevronLeftIcon v-if="!isSidebarCondensed" class="h-4 w-4" />
                <ChevronRightIcon v-else class="h-4 w-4" />
            </button>
        </aside>

        <!-- MAIN KONTEN KANAN -->
        <div class="transition-all duration-300 min-h-screen flex flex-col" :class="[
            isSidebarCondensed ? 'lg:ml-20' : 'lg:ml-64',
            'ml-0'
        ]">
            <!-- HEADER -->
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-8 shadow-sm">

                <!-- Sisi Kiri: Trigger Mobile & Info Judul -->
                <div class="flex items-center gap-3">
                    <!-- Tombol Hamburger (Hanya Mobile/Tablet) -->
                    <button @click="toggleSidebarMobile"
                        class="rounded-lg p-1.5 text-slate-600 hover:bg-slate-100 lg:hidden">
                        <Bars3Icon class="h-6 w-6" />
                    </button>
                </div>

                <!-- Sisi Kanan Profile & Logout (Gaya Modern) -->
                <div class="flex items-center gap-2 sm:gap-5">

                    <!-- NOTIFIKASI INTERAKTIF -->
                    <div ref="notificationRef" class="relative">
                        <button @click="toggleNotifications"
                            class="relative flex h-10 w-auto items-center justify-center bg-white text-slate-600 transition-all duration-200 focus:outline-none">
                            <BellIcon class="h-6 w-6 hover:text-blue-600" />

                            <!-- Badges Indikator dengan Efek Animasi Ping -->
                            <span v-if="unreadNotifications.length" class="absolute -right-0.5 -top-0.5 flex h-4 w-4">
                                <span
                                    class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[9px] font-bold text-white shadow-sm">
                                    {{ unreadNotifications.length }}
                                </span>
                            </span>
                        </button>
                        <div v-if="showNotifications"
                            class="absolute right-0 top-12 z-50 w-96 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <h3 class="font-semibold">
                                    Notifikasi
                                </h3>

                                <button class="text-xs text-blue-600" @click="
                                    router.post(
                                        route(
                                            'admin.notifications.readAll'
                                        )
                                    )
                                    ">
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <div v-if="notifications.length" class="max-h-96 overflow-y-auto">

                                <button v-for="notification in notifications" :key="notification.id"
                                    class="w-full border-b border-slate-100 px-4 py-3 text-left transition hover:bg-slate-50"
                                    @click="
                                        router.post(
                                            route(
                                                'admin.notifications.read',
                                                notification.id
                                            )
                                        )
                                        ">
                                    <div class="flex items-start gap-3">
                                        <div v-if="!notification.read_at"
                                            class="mt-2 h-2 w-2 rounded-full bg-blue-600" />

                                        <div>
                                            <div class="font-medium">
                                                {{
                                                    notification.data.title
                                                }}
                                            </div>

                                            <div class="text-sm text-slate-600">
                                                {{
                                                    notification.data.message
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </button>

                            </div>

                            <div v-else class="p-6 text-center text-sm text-slate-500">
                                Tidak ada notifikasi
                            </div>
                        </div>
                    </div>

                    <!-- GARIS BATAS MIKRO -->
                    <span class="h-6 w-px bg-slate-200 hidden sm:block"></span>

                    <!-- DROPDOWN USER ELEGAN -->
                    <div ref="userMenuRef" class="relative">
                        <!-- Tombol Pemicu Dropdown -->
                        <button @click="toggleUserMenu"
                            class="group inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1.5 pr-1 shadow-sm transition-all duration-200 hover:border-slate-300 hover:bg-slate-50/80 focus:outline-none">
                            <!-- Avatar / Inisial User Lingkaran -->
                            <div
                                class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-50 font-bold text-blue-600 text-xs uppercase border border-blue-100 group-hover:bg-blue-100/50 transition-colors">
                                {{ user?.name?.charAt(0) || 'U' }}
                            </div>

                            <ChevronDownIcon
                                class="h-4 w-4 text-slate-400 transition-transform duration-200 group-hover:text-slate-600"
                                :class="{ 'rotate-180': isUserMenuOpen }" />
                        </button>

                        <!-- Menu Dropdown Popover -->
                        <div v-if="isUserMenuOpen"
                            class="absolute right-0 mt-2.5 w-56 origin-top-right rounded-2xl border border-slate-100 bg-white p-1.5 shadow-xl shadow-slate-200/50 z-50 animate-fade-in-popover">
                            <!-- Header Dropdown: Identitas Role -->
                            <div class="px-3.5 py-2.5 border-b border-slate-50 mb-1">
                                <p
                                    class="text-xs font-bold text-slate-800 capitalize bg-blue-50/60 border border-blue-50 text-blue-700 px-2.5 py-1 rounded-lg mt-1.5 inline-block">
                                    {{ user?.role?.replace('_', ' ') }}
                                </p>
                            </div>

                            <!-- Item: Profil -->
                            <Link href="/profile"
                                class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-slate-600 font-medium transition-colors hover:bg-slate-50 hover:text-slate-900">
                                <UserIcon class="h-4 w-4 text-slate-400" />
                                <span>{{ user?.name }}</span>
                            </Link>

                            <!-- Item: Keluar / Logout -->
                            <Link method="post" as="button" :href="route('logout')"
                                class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-sm text-red-600 font-semibold transition-colors hover:bg-red-50/80">
                                <ArrowLeftEndOnRectangleIcon class="h-4 w-4 text-red-500" />
                                <span>Keluar</span>
                            </Link>
                        </div>
                    </div>

                </div>
            </header>

            <!-- ISI UTAMA HALAMAN -->
            <main class="flex-1 p-4 sm:p-8 w-full max-w-8xl mx-auto">
                <div class="animate-fade-in">
                    <slot />
                </div>
            </main>

        </div>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}

/* Animasi mikro agar teks tidak langsung patah pecah saat di-expand/collapse */
.animate-fade-in-quick {
    animation: fadeIn 0.15s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(2px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>