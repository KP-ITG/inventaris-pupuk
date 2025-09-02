<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    nama: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'distributor',
    alamat: '',
    kontak: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen flex flex-col lg:flex-row">
        <Head title="Daftar - Sistem Inventaris Pupuk" />

        <!-- Mobile Banner - Top -->
        <div class="lg:hidden relative bg-gradient-to-r from-green-700 to-green-600 h-28 flex items-center justify-center">
            <div class="absolute inset-0">
                <!-- Placeholder image untuk mobile -->
                <!-- <img
                    src="https://via.placeholder.com/800x150/16a34a/ffffff?text=DINAS+PERTANIAN"
                    alt="Dinas Pertanian Banner"
                    class="w-full h-full object-cover opacity-20"
                /> -->
            </div>
            <div class="relative z-10 text-center text-white">
                <div class="w-10 h-10 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center mb-1 p-1">
                    <img
                        src="/assets/images/logo_dinas_pertanian.png"
                        alt="Logo Dinas Pertanian Garut"
                        class="w-6 h-6 object-contain"
                    />
                </div>
                <h2 class="text-base font-bold">Pendaftaran Distributor</h2>
                <p class="text-xs text-green-100">Dinas Pertanian</p>
            </div>
        </div>

        <!-- Left side - Banner Image (Desktop) -->
        <div class="hidden lg:flex lg:w-2/5 relative overflow-hidden">
            <!-- Banner Image -->
            <!-- <img
                src="https://via.placeholder.com/600x800/16a34a/ffffff?text=DINAS+PERTANIAN+KABUPATEN"
                alt="Dinas Pertanian Banner"
                class="w-full h-full object-cover"
            /> -->
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-green-800/70 to-green-600/70"></div>

            <!-- Content Overlay -->
            <div class="absolute inset-0 flex flex-col justify-center items-center p-8 text-white">
                <div class="text-center max-w-sm">
                    <!-- Logo -->
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm p-3">
                            <img
                                src="/assets/images/logo_dinas_pertanian.png"
                                alt="Logo Dinas Pertanian Garut"
                                class="w-full h-full object-contain"
                            />
                        </div>
                    </div>

                    <h2 class="text-3xl font-bold mb-4">Bergabung Bersama Kami</h2>
                    <p class="text-lg leading-relaxed text-green-100 mb-6">
                        Daftarkan diri Anda sebagai distributor pupuk resmi
                        untuk berpartisipasi dalam sistem inventaris Dinas Pertanian.
                    </p>

                    <!-- Features -->
                    <div class="space-y-3">
                        <div class="flex items-center text-green-100">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm">Kelola inventaris dengan mudah</span>
                        </div>
                        <div class="flex items-center text-green-100">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm">Pantau distribusi real-time</span>
                        </div>
                        <div class="flex items-center text-green-100">
                            <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 backdrop-blur-sm">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-sm">Akses data terintegrasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Register Form -->
        <div class="flex-1 lg:w-3/5 flex flex-col justify-center px-4 py-6 sm:px-6 lg:px-8 bg-gray-50 min-h-screen lg:min-h-0">
            <div class="w-full max-w-md lg:max-w-lg mx-auto">
                <div class="bg-white py-6 px-6 shadow-2xl rounded-2xl sm:px-8 border border-gray-100">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Pendaftaran Distributor</h2>
                        <p class="text-gray-600">
                            Isi formulir berikut untuk mendaftar sebagai distributor pupuk
                        </p>
                    </div>                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- Hidden field untuk role -->
                        <input type="hidden" v-model="form.role" />

                        <div class="space-y-4">
                            <div>
                                <InputLabel for="nama" value="Nama Lengkap *" class="text-gray-700 font-medium text-sm" />
                                <TextInput
                                    id="nama"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                    v-model="form.nama"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Masukkan nama lengkap"
                                />
                                <InputError class="mt-1" :message="form.errors.nama" />
                            </div>

                            <div>
                                <InputLabel for="email" value="Alamat Email *" class="text-gray-700 font-medium text-sm" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                    placeholder="contoh@email.com"
                                />
                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="password" value="Kata Sandi *" class="text-gray-700 font-medium text-sm" />
                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Minimal 8 karakter"
                                    />
                                    <InputError class="mt-1" :message="form.errors.password" />
                                </div>

                                <div>
                                    <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi *" class="text-gray-700 font-medium text-sm" />
                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Ulangi kata sandi"
                                    />
                                    <InputError class="mt-1" :message="form.errors.password_confirmation" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="alamat" value="Alamat Lengkap *" class="text-gray-700 font-medium text-sm" />
                                <textarea
                                    id="alamat"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                    v-model="form.alamat"
                                    rows="2"
                                    autocomplete="address"
                                    placeholder="Masukkan alamat lengkap"
                                    required
                                ></textarea>
                                <InputError class="mt-1" :message="form.errors.alamat" />
                            </div>

                            <div>
                                <InputLabel for="kontak" value="Nomor Telepon/WhatsApp *" class="text-gray-700 font-medium text-sm" />
                                <TextInput
                                    id="kontak"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm"
                                    v-model="form.kontak"
                                    required
                                    autocomplete="tel"
                                    placeholder="08xxxxxxxxxx"
                                />
                                <InputError class="mt-1" :message="form.errors.kontak" />
                            </div>
                        </div>

                        <div class="pt-4">
                            <PrimaryButton
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Mendaftarkan...' : 'Daftar Sekarang' }}
                            </PrimaryButton>
                        </div>

                        <div class="text-center pt-4">
                            <p class="text-sm text-gray-600">
                                Sudah memiliki akun?
                                <Link
                                    :href="route('login')"
                                    class="font-medium text-green-600 hover:text-green-500 ml-1"
                                >
                                    Masuk di sini
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>&copy; 2025 Dinas Pertanian. Sistem Inventaris Pupuk.</p>
                    <p class="mt-1">Dengan mendaftar, Anda menyetujui kebijakan penggunaan sistem.</p>
                </div>
            </div>
        </div>
    </div>
</template>
