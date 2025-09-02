<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
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
    <div class="min-h-screen flex flex-col lg:flex-row">
        <Head title="Masuk - Sistem Inventaris Pupuk" />

        <!-- Mobile Banner - Top -->
        <div class="lg:hidden relative bg-gradient-to-r from-green-700 to-green-600 h-32 flex items-center justify-center">
            <div class="absolute inset-0">
                <!-- Placeholder image untuk mobile -->
                <!-- <img
                    src="https://via.placeholder.com/800x200/16a34a/ffffff?text=DINAS+PERTANIAN"
                    alt="Dinas Pertanian Banner"
                    class="w-full h-full object-cover opacity-20"
                /> -->
            </div>
            <div class="relative z-10 text-center text-white">
                <div class="w-12 h-12 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-2 p-1">
                    <img
                        src="/assets/images/logo_dinas_pertanian.png"
                        alt="Logo Dinas Pertanian Garut"
                        class="w-12 h-12 object-contain"
                    />
                </div>
                <h2 class="text-lg font-bold">Sistem Inventaris Pupuk</h2>
                <p class="text-sm text-green-100">Dinas Pertanian</p>
            </div>
        </div>

        <!-- Left side - Banner Image (Desktop) -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Banner Image -->
            <!-- <img
                src="https://via.placeholder.com/800x600/16a34a/ffffff?text=DINAS+PERTANIAN+KABUPATEN"
                alt="Dinas Pertanian Banner"
                class="w-full h-full object-cover"
            /> -->
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-green-800/70 to-green-600/70"></div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-center items-center p-12 text-white">
                <div class="text-center max-w-md">
                    <!-- Logo -->
                    <div class="mb-8">
                        <div class="w-24 h-24 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm p-3">
                            <img
                                src="/assets/images/logo_dinas_pertanian.png"
                                alt="Logo Dinas Pertanian Garut"
                                class="w-full h-full object-contain"
                            />
                        </div>
                    </div>

                    <h1 class="text-4xl font-bold mb-4">Sistem Inventaris Pupuk</h1>
                    <p class="text-xl mb-6 text-green-100">Dinas Pertanian Kabupaten Garut</p>
                    <p class="text-lg leading-relaxed text-green-100 mb-8">
                        Kelola inventaris pupuk dengan mudah dan efisien.
                        Pantau stok, distribusi, dan kebutuhan pupuk untuk mendukung
                        produktivitas pertanian daerah.
                    </p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white bg-opacity-15 backdrop-blur-sm rounded-lg p-4 border border-white border-opacity-20">
                            <div class="text-2xl font-bold">500+</div>
                            <div class="text-sm text-green-100">Stok Pupuk</div>
                        </div>
                        <div class="bg-white bg-opacity-15 backdrop-blur-sm rounded-lg p-4 border border-white border-opacity-20">
                            <div class="text-2xl font-bold">50+</div>
                            <div class="text-sm text-green-100">Distributor</div>
                        </div>
                        <div class="bg-white bg-opacity-15 backdrop-blur-sm rounded-lg p-4 border border-white border-opacity-20">
                            <div class="text-2xl font-bold">1000+</div>
                            <div class="text-sm text-green-100">Transaksi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Login Form -->
        <div class="flex-1 lg:w-1/2 flex flex-col justify-center px-4 py-8 sm:px-6 lg:px-8 bg-gray-50">
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-gray-100">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                        <p class="text-gray-600">
                            Masuk ke sistem inventaris pupuk
                        </p>
                    </div>

                    <div v-if="status" class="mb-6 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg p-4">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="email" value="Alamat Email" class="text-gray-700 font-medium mb-2" />
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Masukkan email Anda"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Kata Sandi" class="text-gray-700 font-medium mb-2" />
                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 px-4 py-3"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan kata sandi"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                />
                                <span class="ml-3 text-sm text-gray-600">Ingat saya</span>
                            </div>

                            <div class="text-sm">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="font-medium text-green-600 hover:text-green-500 transition-colors"
                                >
                                    Lupa kata sandi?
                                </Link>
                            </div>
                        </div>

                        <div>
                            <PrimaryButton
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Memproses...' : 'Masuk ke Sistem' }}
                            </PrimaryButton>
                        </div>

                        <div class="text-center border-t border-gray-200 pt-6">
                            <p class="text-sm text-gray-600">
                                Belum memiliki akun?
                                <Link
                                    :href="route('register')"
                                    class="font-medium text-green-600 hover:text-green-500 ml-1 transition-colors"
                                >
                                    Daftar sebagai distributor
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="mt-8 text-center text-xs text-gray-500">
                    <p>&copy; 2025 Dinas Pertanian. Sistem Inventaris Pupuk.</p>
                </div>
            </div>
        </div>
    </div>
</template>
