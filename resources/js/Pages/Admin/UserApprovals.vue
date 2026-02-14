<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    users: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
    isValidationPage: {
        type: Boolean,
        default: false
    }
});

const processing = ref(false);
const showRejectModal = ref(false);
const selectedUserId = ref(null);
const selectedUserName = ref('');

const rejectForm = useForm({
    alasan_penolakan: ''
});

const approveUser = (userId) => {
    if (processing.value) return;
    if (!confirm('Apakah Anda yakin ingin menyetujui user ini?')) return;

    processing.value = true;

    router.patch(`/admin/users/${userId}/approve`, {}, {
        preserveScroll: true,
        onFinish: () => processing.value = false,
    });
};

const openRejectModal = (user) => {
    selectedUserId.value = user.id;
    selectedUserName.value = user.nama;
    rejectForm.reset();
    showRejectModal.value = true;
};

const closeRejectModal = () => {
    showRejectModal.value = false;
    selectedUserId.value = null;
    selectedUserName.value = '';
    rejectForm.reset();
};

const submitReject = () => {
    rejectForm.patch(`/admin/users/${selectedUserId.value}/reject`, {
        preserveScroll: true,
        onSuccess: () => {
            closeRejectModal();
        }
    });
};

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
    <Head title="Approval User Baru" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Approval User Baru
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Review dan setujui pendaftaran user baru yang menunggu persetujuan
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
                                    <span class="bg-yellow-100 text-yellow-800 inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ user.alamat || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ user.kontak || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="approveUser(user.id)"
                                            :disabled="processing"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                                        >
                                            Approve
                                        </button>
                                        <button
                                            @click="openRejectModal(user)"
                                            :disabled="processing"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                                        >
                                            Reject
                                        </button>
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
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada user yang menunggu approval</h3>
                    <p class="mt-1 text-sm text-gray-500">Saat ini tidak ada user pending yang perlu diproses.</p>
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div v-if="showRejectModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div @click="closeRejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Center modal -->
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
                                    Tolak Pendaftaran User
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Anda akan menolak pendaftaran <span class="font-semibold">{{ selectedUserName }}</span>.
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
                            {{ rejectForm.processing ? 'Menolak...' : 'Tolak Pendaftaran' }}
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
