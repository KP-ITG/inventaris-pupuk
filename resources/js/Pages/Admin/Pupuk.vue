<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    pupuks: Object,
    categories: Array,
    nutrisiList: Array,
    filters: Object,
});

const showModal = ref(false);
const showDetailModal = ref(false);
const showPreviewModal = ref(false);
const editMode = ref(false);
const processing = ref(false);
const selectedPupuk = ref(null);

// Search and pagination
const searchQuery = ref(props.filters?.search || '');
const perPageSelected = ref(props.filters?.per_page || 10);
const filterMonth = ref(props.filters?.month || '');
const filterYear = ref(props.filters?.year || '');

const form = ref({
    id: null,
    nama_pupuk: '',
    kategori_id: '',
    deskripsi: '',
    harga_jual: '',
    nutrisi: [],
});

const openCreateModal = () => {
    editMode.value = false;
    form.value = {
        id: null,
        nama_pupuk: '',
        kategori_id: '',
        deskripsi: '',
        harga_jual: '',
        nutrisi: [],
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
        nutrisi: pupuk.nutrisi ? pupuk.nutrisi.map(n => ({
            nutrisi_id: n.id,
            kandungan_persen: n.pivot.kandungan_persen
        })) : [],
    };
    showModal.value = true;
};

const openDetailModal = (pupuk) => {
    selectedPupuk.value = pupuk;
    showDetailModal.value = true;
};

const addNutrisi = () => {
    form.value.nutrisi.push({
        nutrisi_id: '',
        kandungan_persen: ''
    });
};

const removeNutrisi = (index) => {
    form.value.nutrisi.splice(index, 1);
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
        router.delete(route('admin.pupuk.destroy', id), {
            preserveScroll: false,
            preserveState: false,
            replace: true,
            onSuccess: () => {
                console.log('Pupuk berhasil dihapus');
            },
            onError: (errors) => {
                console.error('Error deleting pupuk:', errors);
                if (errors.error) {
                    alert(errors.error);
                } else {
                    alert('Gagal menghapus pupuk. Silakan coba lagi.');
                }
            }
        });
    }
};

const closeModal = () => {
    showModal.value = false;
};

// Search and pagination functions
const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

const debouncedSearch = debounce(() => {
    updateUrl({ search: searchQuery.value, page: 1 });
}, 300);

const changePerPage = () => {
    updateUrl({ per_page: perPageSelected.value, page: 1 });
};

const goToPage = (page) => {
    if (page >= 1 && page <= props.pupuks.last_page) {
        updateUrl({ page });
    }
};

const updateUrl = (params) => {
    const currentParams = new URLSearchParams(window.location.search);

    Object.entries(params).forEach(([key, value]) => {
        if (value) {
            currentParams.set(key, value);
        } else {
            currentParams.delete(key);
        }
    });

    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {
        preserveState: true,
        preserveScroll: true
    });
};

const applyFilter = () => {
    updateUrl({
        search: searchQuery.value,
        per_page: perPageSelected.value,
        month: filterMonth.value,
        year: filterYear.value,
        page: 1
    });
};

const resetFilter = () => {
    filterMonth.value = '';
    filterYear.value = '';
    searchQuery.value = '';
    updateUrl({
        search: '',
        per_page: perPageSelected.value,
        month: '',
        year: '',
        page: 1
    });
};

// Export functions
const exportPdf = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value,
        month: filterMonth.value || '',
        year: filterYear.value || ''
    });
    window.open(`/admin/pupuk/export/pdf?${params.toString()}`, '_blank');
    showPreviewModal.value = false;
};

const exportExcel = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value,
        month: filterMonth.value || '',
        year: filterYear.value || ''
    });
    window.location.href = `/admin/pupuk/export/excel?${params.toString()}`;
    showPreviewModal.value = false;
};

// Helper function
const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

// Pagination computed
const visiblePages = computed(() => {
    const current = props.pupuks.current_page;
    const last = props.pupuks.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>

<template>
    <Head title="Manajemen Pupuk" />

    <DashboardLayout>
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Manajemen Pupuk</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Kelola data pupuk dan kategorinya
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button
                            @click="showPreviewModal = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Export
                        </button>
                        <button
                            @click="openCreateModal"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Pupuk
                        </button>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex flex-col gap-4">
                        <!-- Search and Per Page -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex-1 max-w-md">
                                <input
                                    v-model="searchQuery"
                                    @input="debouncedSearch"
                                    type="text"
                                    placeholder="Cari pupuk..."
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"
                                />
                            </div>
                            <div class="flex items-center gap-2 whitespace-nowrap">
                                <span class="text-sm text-gray-700">Tampilkan</span>
                                <select
                                    v-model="perPageSelected"
                                    @change="changePerPage"
                                    class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="text-sm text-gray-700">data</span>
                            </div>
                        </div>

                        <!-- Period Filter -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 pt-3 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Periode:</span>
                            <div class="flex flex-wrap items-center gap-2">
                                <select
                                    v-model="filterMonth"
                                    class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="">Semua Bulan</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <select
                                    v-model="filterYear"
                                    class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="">Semua Tahun</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                </select>
                                <button
                                    @click="applyFilter"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md text-sm font-medium shadow-sm"
                                >
                                    Terapkan
                                </button>
                                <button
                                    @click="resetFilter"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1.5 rounded-md text-sm font-medium"
                                >
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
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
                                    Nutrisi
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="pupuk in pupuks.data" :key="pupuk.id" class="hover:bg-gray-50">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div v-if="pupuk.nutrisi && pupuk.nutrisi.length > 0" class="flex flex-wrap gap-1">
                                        <span v-for="nutrisi in pupuk.nutrisi.slice(0, 2)" :key="nutrisi.id"
                                              class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            {{ nutrisi.nama_nutrisi }} ({{ nutrisi.pivot.kandungan_persen }}%)
                                        </span>
                                        <span v-if="pupuk.nutrisi.length > 2" class="text-xs text-gray-400">
                                            +{{ pupuk.nutrisi.length - 2 }} lagi
                                        </span>
                                    </div>
                                    <span v-else class="text-gray-400">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="openDetailModal(pupuk)"
                                            class="text-green-600 hover:text-green-900"
                                        >
                                            Detail
                                        </button>
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

                <!-- Empty State -->
                <div v-if="!pupuks.data || pupuks.data.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.24 12.24a6 6 0 00-8.49-8.49L5 10.5V19h8.5z M16 8L2 22 M17.5 15H9" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pupuk</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pupuk baru.</p>
                </div>

                <!-- Pagination -->
                <div v-if="pupuks.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ pupuks.from || 0 }} sampai {{ pupuks.to || 0 }} dari {{ pupuks.total || 0 }} data
                        </div>
                        <div class="flex space-x-1">
                            <button
                                @click="goToPage(pupuks.current_page - 1)"
                                :disabled="!pupuks.prev_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="pupuks.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                            >
                                Prev
                            </button>
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="goToPage(page)"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="page === pupuks.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"
                            >
                                {{ page }}
                            </button>
                            <button
                                @click="goToPage(pupuks.current_page + 1)"
                                :disabled="!pupuks.next_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="pupuks.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                            >
                                Next
                            </button>
                        </div>
                    </div>
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="Masukkan nama pupuk"
                                />
                            </div>

                            <div>
                                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select
                                    v-model="form.kategori_id"
                                    id="kategori_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="0"
                                />
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea
                                    v-model="form.deskripsi"
                                    id="deskripsi"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="Deskripsi pupuk..."
                                ></textarea>
                            </div>

                            <!-- Nutrisi Section -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Kandungan Nutrisi</label>
                                    <button
                                        type="button"
                                        @click="addNutrisi"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200"
                                    >
                                        + Tambah Nutrisi
                                    </button>
                                </div>

                                <div v-if="form.nutrisi.length === 0" class="text-sm text-gray-500 italic">
                                    Belum ada nutrisi ditambahkan
                                </div>

                                <div v-for="(nutrisi, index) in form.nutrisi" :key="index" class="flex gap-2 mb-2">
                                    <select
                                        v-model="nutrisi.nutrisi_id"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
                                    >
                                        <option value="">Pilih Nutrisi</option>
                                        <option v-for="n in nutrisiList" :key="n.id" :value="n.id">
                                            {{ n.nama_nutrisi }} ({{ n.satuan }})
                                        </option>
                                    </select>
                                    <input
                                        v-model="nutrisi.kandungan_persen"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        placeholder="0.00"
                                        class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-sm"
                                    />
                                    <span class="self-center text-sm text-gray-500">%</span>
                                    <button
                                        type="button"
                                        @click="removeNutrisi(index)"
                                        class="text-red-600 hover:text-red-800 px-2"
                                    >
                                        ×
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            @click="submitForm"
                            :disabled="processing"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            {{ processing ? 'Loading...' : (editMode ? 'Update' : 'Simpan') }}
                        </button>
                        <button
                            @click="closeModal"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div v-if="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDetailModal = false"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Detail Pupuk
                            </h3>
                            <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div v-if="selectedPupuk" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Pupuk</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ selectedPupuk.nama_pupuk }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ selectedPupuk.kategori?.nama_kategori || '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Harga per Kg</label>
                                    <p class="mt-1 text-sm text-gray-900">Rp {{ Number(selectedPupuk.harga_jual).toLocaleString('id-ID') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ selectedPupuk.deskripsi || '-' }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kandungan Nutrisi</label>
                                <div v-if="selectedPupuk.nutrisi && selectedPupuk.nutrisi.length > 0" class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div v-for="nutrisi in selectedPupuk.nutrisi" :key="nutrisi.id"
                                             class="flex justify-between items-center bg-white p-3 rounded border">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ nutrisi.nama_nutrisi }}</div>
                                                <div class="text-sm text-gray-500">{{ nutrisi.deskripsi_nutrisi || 'Tidak ada deskripsi' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-lg font-semibold text-green-600">{{ nutrisi.pivot.kandungan_persen }}%</div>
                                                <div class="text-sm text-gray-500">{{ nutrisi.satuan }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-500 italic bg-gray-50 rounded-lg p-4">
                                    Belum ada kandungan nutrisi yang ditambahkan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            @click="showDetailModal = false"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Export Modal -->
        <div v-if="showPreviewModal" class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showPreviewModal = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Preview Data Export</h3>
                            <p class="text-sm text-gray-500 mt-1">Total: {{ pupuks.data?.length || 0 }} data akan di-export</p>
                        </div>
                        <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pupuk</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok Tersedia</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in pupuks.data" :key="item.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.nama_pupuk }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.kode_pupuk || '-' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.kategori?.nama_kategori || '-' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.supplier?.nama_supplier || '-' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">Rp {{ Number(item.harga_jual).toLocaleString('id-ID') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.stok?.jumlah_stok || 0 }} kg</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ formatDate(item.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-2">
                        <button @click="showPreviewModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Tutup
                        </button>
                        <button @click="exportPdf" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </button>
                        <button @click="exportExcel" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
