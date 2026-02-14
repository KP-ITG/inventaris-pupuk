<script setup>
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    permintaan: Object,
});

const processing = ref(false);
const showRejectModal = ref(false);
const selectedPermintaan = ref(null);

const rejectForm = useForm({
    alasan_penolakan: '',
});

const approvePermintaan = (id) => {
    if (!confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')) {
        return;
    }

    processing.value = true;
    router.patch(route('admin.permintaan.approve', id), {}, {
        preserveScroll: true,
        onFinish: () => {
            processing.value = false;
        },
    });
};

const openRejectModal = (permintaan) => {
    selectedPermintaan.value = permintaan;
    rejectForm.reset();
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedPermintaan.value = null;
    rejectForm.reset();
};

const submitReject = () => {
    rejectForm.patch(route('admin.permintaan.reject', selectedPermintaan.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeRejectModal();
        },
    });
};

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
    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Permintaan Distribusi</h2>

                        <div v-if="!permintaan.data || permintaan.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada permintaan</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada permintaan distribusi dari kepala desa.</p>
                        </div>

                        <div v-else class="space-y-4">
                            <div v-for="item in permintaan.data" :key="item.id" class="border rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ item.kepala_desa?.nama }}</h3>
                                        <p class="text-sm text-gray-600">{{ item.desa?.nama_desa }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ new Date(item.created_at).toLocaleString('id-ID') }}</p>
                                    </div>
                                    <span :class="[getStatusColor(item.status), 'inline-flex px-3 py-1 text-xs font-semibold rounded-full']">
                                        {{ getStatusLabel(item.status) }}
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-900 mb-2">Item yang diminta:</p>
                                    <div class="bg-gray-50 rounded-md p-3">
                                        <ul class="space-y-2">
                                            <li v-for="(pupuk, index) in item.items" :key="index" class="text-sm text-gray-700 flex justify-between">
                                                <span>{{ pupuk.nama_pupuk || `Pupuk ID: ${pupuk.pupuk_id}` }}</span>
                                                <span class="font-medium">{{ pupuk.jumlah_diminta }} unit</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Keterangan:</p>
                                    <p class="text-sm text-gray-700 bg-gray-50 rounded-md p-3">{{ item.keterangan }}</p>
                                </div>

                                <div v-if="item.status === 'approved' && item.approver" class="mb-4 text-sm text-gray-600 bg-green-50 rounded-md p-3">
                                    <p><strong>Disetujui oleh:</strong> {{ item.approver.nama }}</p>
                                    <p><strong>Waktu:</strong> {{ new Date(item.approved_at).toLocaleString('id-ID') }}</p>
                                </div>

                                <div v-if="item.status === 'rejected'" class="mb-4 bg-red-50 rounded-md p-3">
                                    <p class="text-sm font-medium text-red-900 mb-1">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-700">{{ item.alasan_penolakan }}</p>
                                    <p v-if="item.approver" class="text-xs text-gray-600 mt-2">Ditolak oleh: {{ item.approver.nama }} pada {{ new Date(item.approved_at).toLocaleString('id-ID') }}</p>
                                </div>

                                <div v-if="item.status === 'pending'" class="flex space-x-2">
                                    <button
                                        @click="approvePermintaan(item.id)"
                                        :disabled="processing"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                                    >
                                        Setujui
                                    </button>
                                    <button
                                        @click="openRejectModal(item)"
                                        :disabled="processing"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                                    >
                                        Tolak
                                    </button>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div v-if="permintaan.links && permintaan.links.length > 3" class="flex justify-center space-x-2 mt-6">
                                <template v-for="(link, index) in permintaan.links" :key="index">
                                    <button
                                        v-if="link.url"
                                        @click="router.visit(link.url)"
                                        :class="[
                                            'px-4 py-2 text-sm border rounded-md',
                                            link.active
                                                ? 'bg-indigo-600 text-white border-indigo-600'
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
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div v-if="showRejectModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="closeRejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Tolak Permintaan Distribusi
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Anda akan menolak permintaan dari <span class="font-semibold">{{ selectedPermintaan?.kepala_desa?.nama }}</span> ({{ selectedPermintaan?.desa?.nama_desa }}).
                                        Silakan berikan alasan penolakan.
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="alasan_penolakan"
                                        v-model="rejectForm.alasan_penolakan"
                                        rows="4"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Masukkan alasan penolakan (minimal 10 karakter)"
                                        :class="{ 'border-red-300': rejectForm.errors.alasan_penolakan }"
                                    ></textarea>
                                    <p v-if="rejectForm.errors.alasan_penolakan" class="mt-1 text-sm text-red-600">
                                        {{ rejectForm.errors.alasan_penolakan }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="submitReject"
                            :disabled="rejectForm.processing"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            {{ rejectForm.processing ? 'Menolak...' : 'Tolak Permintaan' }}
                        </button>
                        <button
                            type="button"
                            @click="closeRejectModal"
                            :disabled="rejectForm.processing"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
