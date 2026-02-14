<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    email: String,
    nama: String,
});
</script>

<template>
    <div class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
        <Head title="Menunggu Persetujuan - Sistem Inventaris Pupuk" />

        <!-- Left side - Banner Image (Desktop) -->
        <div class="hidden lg:flex lg:w-2/5 relative overflow-hidden bg-gradient-to-br from-green-800 to-green-600">
            <div class="absolute inset-0 flex flex-col justify-center items-center p-8 text-white">
                <div class="text-center max-w-sm">
                    <!-- Logo -->
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center backdrop-blur-sm p-3">
                            <img
                                src="/assets/images/logo_dinas_pertanian.png"
                                alt="Logo Dinas Pertanian"
                                class="w-full h-full object-contain"
                            />
                        </div>
                    </div>

                    <h1 class="text-3xl font-bold mb-4">Sistem Inventaris Pupuk</h1>
                    <p class="text-lg text-green-100 mb-2">Dinas Pertanian</p>
                    <p class="text-sm text-green-200">Mengelola distribusi pupuk dengan efisien dan transparan</p>
                </div>
            </div>
        </div>

        <!-- Right side - Pending Approval Content -->
        <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-lg">
                <!-- Mobile Banner -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-green-600 rounded-full flex items-center justify-center mb-3 p-2">
                        <img
                            src="/assets/images/logo_dinas_pertanian.png"
                            alt="Logo"
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Sistem Inventaris Pupuk</h2>
                    <p class="text-sm text-gray-600">Dinas Pertanian</p>
                </div>

                <!-- Success Icon -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-yellow-100 rounded-full mb-4">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h1>
                    <p class="text-lg text-gray-600">Akun Anda sedang ditinjau</p>
                </div>

                <!-- Info Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <div class="space-y-4">
                        <!-- User Info -->
                        <div class="pb-4 border-b border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Nama Pendaftar</p>
                            <p class="text-lg font-semibold text-gray-900">{{ nama }}</p>
                        </div>

                        <div class="pb-4 border-b border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Email Terdaftar</p>
                            <p class="text-lg font-medium text-gray-900">{{ email }}</p>
                        </div>

                        <!-- Status Info -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Status: Menunggu Persetujuan</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Akun Anda sedang dalam proses peninjauan oleh administrator.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Process Timeline -->
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Proses Selanjutnya:</h3>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Pendaftaran Berhasil</p>
                                        <p class="text-xs text-gray-500">Data Anda telah tersimpan</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Peninjauan Administrator</p>
                                        <p class="text-xs text-gray-500">Sedang berlangsung...</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Notifikasi Email</p>
                                        <p class="text-xs text-gray-500">Anda akan menerima email konfirmasi</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Akses Sistem</p>
                                        <p class="text-xs text-gray-500">Setelah disetujui, Anda dapat login</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-2">Penting untuk diketahui:</p>
                            <ul class="list-disc list-inside space-y-1 text-blue-700">
                                <li>Proses peninjauan biasanya memakan waktu 1-2 hari kerja</li>
                                <li>Anda akan menerima email notifikasi ke <strong>{{ email }}</strong></li>
                                <li>Periksa folder spam/junk jika email tidak masuk ke inbox</li>
                                <li>Jika ada pertanyaan, hubungi administrator</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <Link
                        :href="route('login')"
                        class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-lg"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Kembali ke Halaman Login
                    </Link>

                    <p class="text-center text-sm text-gray-600">
                        Sudah disetujui?
                        <Link :href="route('login')" class="font-medium text-green-600 hover:text-green-500 transition-colors">
                            Login sekarang
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
