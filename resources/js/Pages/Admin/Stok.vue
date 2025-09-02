<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    stocks: Array,
    pupuks: Array,
    users: Array,
    user: Object,
});

const showModal = ref(false);
const editMode = ref(false);
const processing = ref(false);

const form = ref({
    id: null,
    pupuk_id: '',
    pengguna_id: '',
    jumlah_stok: '',
});

const openCreateModal = () => {
    editMode.value = false;
    form.value = {
        id: null,
        pupuk_id: '',
        pengguna_id: '',
        jumlah_stok: '',
    };
    showModal.value = true;
};

const openEditModal = (stock) => {
    editMode.value = true;
    form.value = {
        id: stock.id,
        pupuk_id: stock.pupuk_id,
        pengguna_id: stock.pengguna_id,
        jumlah_stok: stock.jumlah_stok,
    };
    showModal.value = true;
};

const submitForm = () => {
    if (processing.value) return;
    processing.value = true;

    const url = editMode.value ? `/admin/stok/${form.value.id}` : '/admin/stok';
    const method = editMode.value ? 'patch' : 'post';

    router[method](url, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            showModal.value = false;
        },
        onFinish: () => processing.value = false,
    });
};

const deleteStock = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus stok ini?')) {
        router.delete(`/admin/stok/${id}`, {
            preserveScroll: true,
        });
    }
};

const closeModal = () => {
    showModal.value = false;
};

const getStockStatus = (jumlah) => {
    if (jumlah < 10) {
        return { class: 'bg-red-100 text-red-800', text: 'Stok Rendah' };
    } else if (jumlah < 50) {
        return { class: 'bg-yellow-100 text-yellow-800', text: 'Stok Sedang' };
    }
    return { class: 'bg-green-100 text-green-800', text: 'Stok Aman' };
};
</script>

<template>
    <Head title="Manajemen Stok" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Manajemen Stok Pupuk</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ user.role === 'admin' ? 'Kelola stok pupuk semua distributor' : 'Kelola stok pupuk Anda' }}
                        </p>
                    </div>
                    <button
                        v-if="user.role === 'admin'"
                        @click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Stok
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pupuk
                                </th>
                                <th v-if="user.role === 'admin'" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Distributor
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Stok
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="stock in stocks" :key="stock.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ stock.pupuk?.nama_pupuk || 'Unknown' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ stock.pupuk?.kategori_pupuk?.nama_kategori || 'Unknown' }}
                                        </div>
                                    </div>
                                </td>
                                <td v-if="user.role === 'admin'" class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ stock.pengguna?.nama || 'Unknown' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ stock.pengguna?.email || 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ stock.jumlah_stok }} kg
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[getStockStatus(stock.jumlah_stok).class, 'inline-flex px-2 py-1 text-xs font-semibold rounded-full']">
                                        {{ getStockStatus(stock.jumlah_stok).text }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="openEditModal(stock)"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            v-if="user.role === 'admin'"
                                            @click="deleteStock(stock.id)"
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

                <div v-if="!stocks || stocks.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada stok</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan stok pupuk baru.</p>
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
                            {{ editMode ? 'Edit Stok' : 'Tambah Stok Baru' }}
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label for="pupuk_id" class="block text-sm font-medium text-gray-700">Pupuk</label>
                                <select
                                    v-model="form.pupuk_id"
                                    id="pupuk_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="">Pilih Pupuk</option>
                                    <option v-for="pupuk in pupuks" :key="pupuk.id" :value="pupuk.id">
                                        {{ pupuk.nama_pupuk }}
                                    </option>
                                </select>
                            </div>

                            <div v-if="user.role === 'admin'">
                                <label for="pengguna_id" class="block text-sm font-medium text-gray-700">Distributor</label>
                                <select
                                    v-model="form.pengguna_id"
                                    id="pengguna_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                >
                                    <option value="">Pilih Distributor</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.nama }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="jumlah_stok" class="block text-sm font-medium text-gray-700">Jumlah Stok (kg)</label>
                                <input
                                    v-model="form.jumlah_stok"
                                    type="number"
                                    id="jumlah_stok"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="0"
                                />
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
