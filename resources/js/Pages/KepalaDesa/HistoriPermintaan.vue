<script setup>
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    permintaan: Array,
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
</script>

<template>
    <Head title="Histori Permintaan" />

    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Histori Permintaan Distribusi</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ desa?.nama_desa }} - Riwayat permintaan distribusi pupuk</p>
                    </div>
                </div>

                <!-- Histori Permintaan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="!permintaan || permintaan.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada permintaan</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada permintaan distribusi yang diajukan.</p>
                            <div class="mt-6">
                                <a
                                    :href="route('kepala-desa.ajukan-permintaan')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                >
                                    Ajukan Permintaan Baru
                                </a>
                            </div>
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="item in permintaan" :key="item.id" class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                <!-- Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span :class="[getStatusColor(item.status), 'inline-flex px-3 py-1 text-xs font-semibold rounded-full']">
                                                {{ getStatusLabel(item.status) }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ new Date(item.created_at).toLocaleString('id-ID', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                }) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item yang diminta -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Pupuk yang Diminta:</h4>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <ul class="space-y-2">
                                            <li v-for="(pupuk, index) in item.items" :key="index" class="flex justify-between items-center text-sm">
                                                <span class="text-gray-700">{{ pupuk.nama_pupuk || `Pupuk ID: ${pupuk.pupuk_id}` }}</span>
                                                <span class="font-medium text-gray-900">{{ pupuk.jumlah_diminta }} kg</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2">Keterangan:</h4>
                                    <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ item.keterangan }}</p>
                                </div>

                                <!-- Status Approval -->
                                <div v-if="item.status === 'approved' && item.approver" class="border-t pt-4">
                                    <div class="flex items-start gap-2 text-sm text-green-700">
                                        <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-medium">Disetujui oleh {{ item.approver.nama }}</p>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ new Date(item.approved_at).toLocaleString('id-ID') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Rejection -->
                                <div v-if="item.status === 'rejected'" class="border-t pt-4">
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-red-900 mb-1">Alasan Penolakan:</h4>
                                                <p class="text-sm text-red-800">{{ item.alasan_penolakan }}</p>
                                                <p v-if="item.approver" class="text-xs text-red-700 mt-2">
                                                    Ditolak oleh {{ item.approver.nama }} pada {{ new Date(item.approved_at).toLocaleString('id-ID') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
