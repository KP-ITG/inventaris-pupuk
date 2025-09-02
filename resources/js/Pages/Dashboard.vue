<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4">Selamat datang, {{ user.nama }}!</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700">Informasi Akun</h4>
                                <div class="mt-2 space-y-2">
                                    <p class="text-sm"><span class="font-medium">Email:</span> {{ user.email }}</p>
                                    <p class="text-sm"><span class="font-medium">Role:</span>
                                        <span :class="{
                                            'text-red-600 font-medium': user.role === 'admin',
                                            'text-blue-600 font-medium': user.role === 'distributor'
                                        }">
                                            {{ user.role === 'admin' ? 'Administrator' : 'Distributor' }}
                                        </span>
                                    </p>
                                    <p class="text-sm" v-if="user.alamat"><span class="font-medium">Alamat:</span> {{ user.alamat }}</p>
                                    <p class="text-sm" v-if="user.kontak"><span class="font-medium">Kontak:</span> {{ user.kontak }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
