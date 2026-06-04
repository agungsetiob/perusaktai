<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk ke Sistem" />

    <div class="flex min-h-screen bg-slate-50 font-sans">
        
        <div class="flex w-full flex-col justify-between p-8 sm:p-12 lg:w-[45%] xl:w-[40%] bg-white shadow-2xl z-10">
            
            <div class="flex w-full flex-col items-center justify-center gap-3">
                <img 
                    src="/beraksi-logo.webp" 
                    alt="Logo Beraksi" 
                    class="h-16 w-auto object-contain transition-transform duration-300 hover:scale-105"
                />
            </div>

            <div class="my-auto mx-auto w-full max-w-md py-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-black tracking-tight text-slate-800 sm:text-3xl">
                        Selamat Datang
                    </h2>
                    <p class="mt-2 text-sm font-medium text-slate-400">
                        Silakan masukkan akun Anda untuk mengakses dashboard manajemen.
                    </p>
                </div>

                <div v-if="status" class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-xs font-semibold text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel for="email" value="Email" class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5" />
                        <div class="relative">
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500/20 transition-all font-medium text-slate-700"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <InputError class="mt-1.5 text-xs font-medium" :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <InputLabel for="password" value="Kata Sandi" class="text-xs font-bold uppercase tracking-wide text-slate-500" />
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline focus:outline-none"
                            >
                                Lupa sandi?
                            </Link>
                        </div>
                        <TextInput
                            id="password"
                            type="password"
                            class="block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500/20 transition-all font-medium text-slate-700"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />
                        <InputError class="mt-1.5 text-xs font-medium" :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <Checkbox 
                                name="remember" 
                                v-model:checked="form.remember" 
                                class="rounded-md border-slate-300 text-blue-600 focus:ring-blue-500"
                            />
                            <span class="ms-2 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                                Ingat saya di perangkat ini
                            </span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <PrimaryButton
                            class="flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3.5 text-center text-xs font-bold uppercase tracking-wider text-white shadow-md hover:from-blue-700 hover:to-indigo-700 active:scale-[0.98] transition-all disabled:opacity-50"
                            :class="{ 'opacity-50 pointer-events-none': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing" class="flex items-center gap-2">
                                <svg class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                            <span v-else>Masuk</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>

            <div class="text-center lg:text-left">
                <p class="text-center text-[11px] font-semibold text-slate-400">
                    &copy; 2026 RAJAPATEN AI. <br class="sm:hidden" /> All Rights Reserved.
                </p>
            </div>
        </div>

        <div class="relative hidden flex-1 items-end justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-950 lg:flex">
            
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_var(--tw-gradient-stops))] from-blue-600/30 via-transparent to-transparent"></div>
            <div class="absolute -bottom-48 h-96 w-96 rounded-full bg-indigo-500/10 blur-3xl"></div>
            
            <div class="relative z-10 max-h-[85vh] w-full max-w-2xl px-6 flex justify-center">
                <img 
                    src="/bupati-dan-wakil.png" 
                    alt="Bupati dan Wakil Bupati" 
                    class="h-auto w-auto max-h-[80vh] object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.5)] select-none"
                />
            </div>
        </div>

    </div>
</template>