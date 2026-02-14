<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    stokPupuk: Array,
    distribusi: Array,
    desa: Object,
});

const getStatusColor = (status) => {
    const colors = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Menunggu',
        'approved': 'Disetujui',
        'rejected': 'Ditolak',
    };
    return labels[status] || status;
};

const getDistribusiStatusColor = (status) => {
    const colors = {
        'rencana': 'bg-blue-100 text-blue-800',
        'dalam_perjalanan': 'bg-yellow-100 text-yellow-800',
        'selesai': 'bg-green-100 text-green-800',
        'batal': 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getDistribusiStatusLabel = (status) => {
    const labels = {
        'rencana': 'Direncanakan',
        'dalam_perjalanan': 'Dalam Perjalanan',
        'selesai': 'Selesai',
        'batal': 'Dibatalkan',
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Kepala Desa</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ desa?.nama_desa }}</p>
                    </div>
                </div>

                <!-- Stok Pupuk -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Stok Pupuk Tersedia</h3>

                        <div v-if="!stokPupuk || stokPupuk.length === 0" class="text-center py-8">
                            <p class="text-gray-500">Tidak ada data stok pupuk</p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="stok in stokPupuk" :key="stok.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ stok.nama_pupuk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ stok.kategori }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ stok.jumlah_stok }} kg</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="[
                                                stok.status_stok === 'aman' ? 'bg-green-100 text-green-800' :
                                                stok.status_stok === 'hampir_habis' ? 'bg-yellow-100 text-yellow-800' :
                                                'bg-red-100 text-red-800',
                                                'inline-flex px-2 py-1 text-xs font-semibold rounded-full'
                                            ]">
                                                {{ stok.status_stok === 'aman' ? 'Aman' : stok.status_stok === 'hampir_habis' ? 'Hampir Habis' : 'Habis' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Distribusi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Distribusi Pupuk</h3>

                        <div v-if="!distribusi || distribusi.length === 0" class="text-center py-8">
                            <p class="text-gray-500">Belum ada distribusi pupuk</p>
                        </div>

                        <div v-else class="space-y-6">
                            <div v-for="dist in distribusi" :key="dist.id" class="border rounded-lg p-4">
                                <!-- Header Distribusi -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ dist.nomor_distribusi }}</h4>
                                        <p class="text-sm text-gray-600">Tanggal: {{ new Date(dist.tanggal_distribusi).toLocaleDateString('id-ID') }}</p>
                                    </div>
                                    <span :class="[getDistribusiStatusColor(dist.status_distribusi), 'inline-flex px-3 py-1 text-xs font-semibold rounded-full']">
                                        {{ getDistribusiStatusLabel(dist.status_distribusi) }}
                                    </span>
                                </div>

                                <!-- Item Distribusi -->
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Pupuk yang didistribusikan:</p>
                                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                        <li v-for="(item, idx) in dist.items" :key="idx">
                                            {{ item.nama_pupuk }}: <strong>{{ item.jumlah }}</strong> kg
                                        </li>
                                    </ul>
                                </div>

                                <!-- Timeline Status -->
                                <div v-if="dist.status_logs && dist.status_logs.length > 0" class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-900 mb-3">Riwayat Status:</p>
                                    <ol class="relative border-l border-gray-300 ml-3 space-y-4">
                                        <li v-for="(log, idx) in dist.status_logs" :key="idx" class="ml-4">
                                            <div class="absolute w-3 h-3 bg-gray-300 rounded-full -left-1.5 border border-white"></div>
                                            <div class="mb-1">
                                                <span :class="[getDistribusiStatusColor(log.status_baru), 'inline-flex px-2 py-0.5 text-xs font-semibold rounded']">
                                                    {{ getDistribusiStatusLabel(log.status_baru) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-600">
                                                {{ new Date(log.created_at).toLocaleString('id-ID') }} - {{ log.user_nama }}
                                            </p>
                                            <p v-if="log.keterangan" class="text-sm text-gray-700 mt-1">{{ log.keterangan }}</p>
                                        </li>
                                    </ol>
                                </div>

                                <!-- Catatan -->
                                <div v-if="dist.catatan" class="mt-4 text-sm">
                                    <p class="font-medium text-gray-900">Catatan:</p>
                                    <p class="text-gray-700 mt-1">{{ dist.catatan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
