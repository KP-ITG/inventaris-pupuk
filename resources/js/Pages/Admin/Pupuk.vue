<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    pupuks: Array,
    categories: Array,
});

const showModal = ref(false);
const editMode = ref(false);
const processing = ref(false);

const form = ref({
    id: null,
    nama_pupuk: '',
    kategori_id: '',
    deskripsi: '',
    harga_jual: '',
});

const openCreateModal = () => {
    editMode.value = false;
    form.value = {
        id: null,
        nama_pupuk: '',
        kategori_id: '',
        deskripsi: '',
        harga_jual: '',
    };
    showModal.value = true;
};

const openEditModal = (pupuk) => {
    editMode.value = true;
    form.value = {
        id: pupuk.id,
        nama_pupuk: pupuk.nama_pupuk,
        kategori_id: pupuk.kategori_id,
        deskripsi: pupuk.deskripsi,
        harga_jual: pupuk.harga_jual,
    };
    showModal.value = true;
};

const submitForm = () => {
    if (processing.value) return;
    processing.value = true;

    const url = editMode.value ? `/admin/pupuk/${form.value.id}` : '/admin/pupuk';
    const method = editMode.value ? 'patch' : 'post';

    router[method](url, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
        onFinish: () => processing.value = false,
    });
};

const deletePupuk = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus pupuk ini?')) {
        router.delete(`/admin/pupuk/${id}`, {
            preserveScroll: true,
        });
    }
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <Head title="Manajemen Pupuk" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Manajemen Pupuk</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Kelola data pupuk dan kategorinya
                        </p>
                    </div>
                    <button
                        @click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Pupuk
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Pupuk
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga/kg
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="pupuk in pupuks" :key="pupuk.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ pupuk.nama_pupuk }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ pupuk.kategori?.nama_kategori || '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ Number(pupuk.harga_jual).toLocaleString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate">
                                        {{ pupuk.deskripsi || '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="openEditModal(pupuk)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deletePupuk(pupuk.id)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="!pupuks || pupuks.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.24 12.24a6 6 0 00-8.49-8.49L5 10.5V19h8.5z M16 8L2 22 M17.5 15H9" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pupuk</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pupuk baru.</p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            {{ editMode ? 'Edit Pupuk' : 'Tambah Pupuk Baru' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="nama_pupuk" class="block text-sm font-medium text-gray-700">Nama Pupuk</label>
                                <input
                                    v-model="form.nama_pupuk"
                                    type="text"
                                    id="nama_pupuk"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Masukkan nama pupuk"
                                />
                            </div>

                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select
                                    v-model="form.kategori_id"
                                    id="kategori_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="">Pilih Kategori</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.nama_kategori }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga per Kg</label>
                                <input
                                    v-model="form.harga_jual"
                                    type="number"
                                    id="harga_jual"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="0"
                                />
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea
                                    v-model="form.deskripsi"
                                    id="deskripsi"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Deskripsi pupuk..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            @click="submitForm"
                            :disabled="processing"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            {{ processing ? 'Loading...' : (editMode ? 'Update' : 'Simpan') }}
                        </button>
                        <button
                            @click="closeModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
