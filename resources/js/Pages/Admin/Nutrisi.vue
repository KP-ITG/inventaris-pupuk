<template>
    <DashboardLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Manajemen Nutrisi</h2>
                            <div class="flex items-center space-x-2">
                                <button
                                    @click="exportPdf"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    PDF
                                </button>
                                <button
                                    @click="exportExcel"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel
                                </button>
                                <button
                                    @click="openModal()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Nutrisi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex-1 max-w-md">
                                <input
                                    v-model="searchQuery"
                                    @input="debouncedSearch"
                                    type="text"
                                    placeholder="Cari nutrisi..."
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
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 pt-3 border-t border-gray-200 mt-3">
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

                    <!-- Success/Error Messages -->
                    <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-green-700">{{ $page.props.flash.success }}</p>
                    </div>
                    <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-red-700">{{ $page.props.flash.error }}</p>
                    </div>

                    <!-- Table -->
                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Nutrisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formula Kimia</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Digunakan pada</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in nutrisi.data" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.nama_nutrisi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.satuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.formula_kimia || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.deskripsi_nutrisi || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.pupuk_count || 0 }} pupuk</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openModal(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button @click="deleteNutrisi(item)" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr v-if="nutrisi.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada nutrisi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="!nutrisi.data || nutrisi.data.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada nutrisi</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan nutrisi baru.</p>
                        </div>

                        <!-- Pagination -->
                        <div v-if="nutrisi.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Menampilkan {{ nutrisi.from || 0 }} sampai {{ nutrisi.to || 0 }} dari {{ nutrisi.total || 0 }} data
                                </div>
                                <div class="flex space-x-1">
                                    <button
                                        @click="goToPage(nutrisi.current_page - 1)"
                                        :disabled="!nutrisi.prev_page_url"
                                        class="px-3 py-1 text-sm border rounded-md"
                                        :class="nutrisi.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                                    >
                                        Prev
                                    </button>
                                    <button
                                        v-for="page in visiblePages"
                                        :key="page"
                                        @click="goToPage(page)"
                                        class="px-3 py-1 text-sm border rounded-md"
                                        :class="page === nutrisi.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"
                                    >
                                        {{ page }}
                                    </button>
                                    <button
                                        @click="goToPage(nutrisi.current_page + 1)"
                                        :disabled="!nutrisi.next_page_url"
                                        class="px-3 py-1 text-sm border rounded-md"
                                        :class="nutrisi.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ isEditing ? 'Edit Nutrisi' : 'Tambah Nutrisi' }}
                    </h3>

                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Nutrisi</label>
                            <input
                                v-model="form.nama_nutrisi"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: Nitrogen, Fosfor, Kalium"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                            <input
                                v-model="form.satuan"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: %, ppm, kg/ha"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Formula Kimia</label>
                            <input
                                v-model="form.formula_kimia"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: N, P2O5, K2O"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.deskripsi_nutrisi"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Deskripsi nutrisi dan fungsinya (opsional)"
                            ></textarea>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700"
                                :disabled="processing"
                            >
                                {{ processing ? 'Menyimpan...' : (isEditing ? 'Update' : 'Simpan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    nutrisi: Object,
    filters: Object,
})

const showModal = ref(false)
const isEditing = ref(false)
const currentNutrisi = ref(null)
const processing = ref(false)

// Search and pagination
const searchQuery = ref(props.filters?.search || '')
const perPageSelected = ref(props.filters?.per_page || 10)
const filterMonth = ref(props.filters?.month || '')
const filterYear = ref(props.filters?.year || '')

const form = reactive({
    nama_nutrisi: '',
    satuan: '',
    formula_kimia: '',
    deskripsi_nutrisi: ''
})

const openModal = (nutrisi = null) => {
    if (nutrisi) {
        isEditing.value = true
        currentNutrisi.value = nutrisi
        form.nama_nutrisi = nutrisi.nama_nutrisi
        form.satuan = nutrisi.satuan
        form.formula_kimia = nutrisi.formula_kimia || ''
        form.deskripsi_nutrisi = nutrisi.deskripsi_nutrisi || ''
    } else {
        isEditing.value = false
        currentNutrisi.value = null
        form.nama_nutrisi = ''
        form.satuan = ''
        form.formula_kimia = ''
        form.deskripsi_nutrisi = ''
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentNutrisi.value = null
    form.nama_nutrisi = ''
    form.satuan = ''
    form.formula_kimia = ''
    form.deskripsi_nutrisi = ''
}

const submitForm = () => {
    processing.value = true

    const formData = useForm(form)

    if (isEditing.value) {
        formData.put(`/admin/nutrisi/${currentNutrisi.value.id}`, {
            onSuccess: () => {
                closeModal()
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    } else {
        formData.post('/admin/nutrisi', {
            onSuccess: () => {
                closeModal()
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    }
}

const deleteNutrisi = (nutrisi) => {
    if (confirm(`Apakah Anda yakin ingin menghapus nutrisi "${nutrisi.nama_nutrisi}"?`)) {
        const formData = useForm({})
        formData.delete(`/admin/nutrisi/${nutrisi.id}`)
    }
}

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
    if (page >= 1 && page <= props.nutrisi.last_page) {
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

// Period filter functions
const applyFilter = () => {
    updateUrl({
        month: filterMonth.value,
        year: filterYear.value,
        search: searchQuery.value,
        per_page: perPageSelected.value,
        page: 1
    })
}

const resetFilter = () => {
    filterMonth.value = ''
    filterYear.value = ''
    updateUrl({
        month: '',
        year: '',
        search: searchQuery.value,
        per_page: perPageSelected.value,
        page: 1
    })
}

// Export functions
const exportPdf = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value
    });
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.open(`/admin/nutrisi/export/pdf?${params.toString()}`, '_blank');
};

const exportExcel = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value
    });
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.location.href = `/admin/nutrisi/export/excel?${params.toString()}`;
};

// Pagination computed
const visiblePages = computed(() => {
    const current = props.nutrisi.current_page;
    const last = props.nutrisi.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>
