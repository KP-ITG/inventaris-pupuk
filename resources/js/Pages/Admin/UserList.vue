<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    users: Object,
    filters: {
        type: Object,
        default: () => ({})
    }
});

const getRoleColor = (role) => {
    switch (role) {
        case 'admin':
            return 'bg-red-100 text-red-800';
        case 'distributor':
            return 'bg-blue-100 text-blue-800';
        case 'kepala_desa':
            return 'bg-purple-100 text-purple-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getRoleLabel = (role) => {
    switch (role) {
        case 'admin':
            return 'Admin';
        case 'distributor':
            return 'Distributor';
        case 'kepala_desa':
            return 'Kepala Desa';
        default:
            return role;
    }
};

const getStatusColor = (status) => {
    switch (status) {
        case 'approved':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'rejected':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Daftar User" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Daftar User
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Daftar pengguna yang sudah disetujui dan aktif dalam sistem
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alamat
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ user.nama ? user.nama.charAt(0).toUpperCase() : 'U' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.nama }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ user.email }}
                                            </div>
                                            <div v-if="user.desa" class="text-xs text-gray-400 mt-0.5">
                                                {{ user.desa.nama_desa }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[getRoleColor(user.role), 'inline-flex px-2 py-1 text-xs font-semibold rounded-full']">
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[getStatusColor(user.status), 'inline-flex px-2 py-1 text-xs font-semibold rounded-full']">
                                        {{ user.status === 'approved' ? 'Approved' : user.status === 'pending' ? 'Pending' : 'Rejected' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ user.alamat || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ user.kontak || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div v-if="user.status === 'approved'" class="text-green-600 text-xs font-medium">
                                        ✓ Disetujui
                                    </div>
                                    <div v-else-if="user.status === 'rejected'" class="flex flex-col">
                                        <span class="text-red-600 text-xs font-medium">✗ Ditolak</span>
                                        <span v-if="user.alasan_penolakan" class="text-xs text-gray-500 mt-1">
                                            {{ user.alasan_penolakan }}
                                        </span>
                                    </div>
                                    <div v-else class="text-yellow-600 text-xs font-medium">
                                        ⏳ Pending
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="users.data && users.data.length > 0 && users.links && users.links.length > 3" class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ users.from }}</span> sampai <span class="font-medium">{{ users.to }}</span> dari <span class="font-medium">{{ users.total }}</span> hasil
                        </div>
                        <div class="flex space-x-2">
                            <template v-for="(link, index) in users.links" :key="index">
                                <button
                                    v-if="link.url"
                                    @click="router.visit(link.url)"
                                    :class="[
                                        'px-4 py-2 text-sm border rounded-md',
                                        link.active
                                            ? 'bg-green-600 text-white border-green-600'
                                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                    ]"
                                    v-html="link.label"
                                ></button>
                                <span
                                    v-else
                                    class="px-4 py-2 text-sm text-gray-400 border border-gray-300 rounded-md bg-gray-50"
                                    v-html="link.label"
                                ></span>
                            </template>
                        </div>
                    </div>
                </div>

                <div v-if="!users.data || users.data.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada user yang disetujui</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada user yang disetujui dalam sistem.</p>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
