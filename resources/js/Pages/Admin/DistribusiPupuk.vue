<template>
    <DashboardLayout title="Distribusi Pupuk">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Distribusi Pupuk ke Desa</h1>
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
                    <Link
                        :href="route('admin.distribusi-pupuk.create')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Distribusi
                    </Link>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Distribusi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'selesai').length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Dalam Perjalanan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'dalam_perjalanan').length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-100 text-gray-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Rencana</p>
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'rencana').length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div class="flex-1 max-w-md">
                            <input
                                v-model="searchQuery"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Cari nomor distribusi, pupuk, atau desa..."
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
                </div>

                <!-- Period Filter -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
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

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor Distribusi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Desa Tujuan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pupuk
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="item in distribusi.data" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ item.nomor_distribusi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.desa?.nama_desa }}</div>
                                <div class="text-sm text-gray-500">{{ item.desa?.kecamatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.pupuk?.nama_pupuk }}</div>
                                <div class="text-sm text-gray-500">{{ item.pupuk?.kategori?.nama_kategori }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.jumlah_distribusi }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ formatDate(item.tanggal_distribusi) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', getStatusClass(item.status_distribusi)]">
                                    {{ getStatusLabel(item.status_distribusi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <Link
                                    :href="route('admin.distribusi-pupuk.edit', item.id)"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteDistribusi(item)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="distribusi.data && distribusi.data.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data distribusi pupuk
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div v-if="distribusi.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ distribusi.from || 0 }} sampai {{ distribusi.to || 0 }} dari {{ distribusi.total || 0 }} data
                        </div>
                        <div class="flex space-x-1">
                            <button
                                @click="goToPage(distribusi.current_page - 1)"
                                :disabled="!distribusi.prev_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="distribusi.prev_page_url ? 'bg-white hover:bg-gray-50 text-gray-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            >
                                Sebelumnya
                            </button>
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="goToPage(page)"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="page === distribusi.current_page ? 'bg-green-50 text-green-700 border-green-500' : 'bg-white hover:bg-gray-50 text-gray-700'"
                            >
                                {{ page }}
                            </button>
                            <button
                                @click="goToPage(distribusi.current_page + 1)"
                                :disabled="!distribusi.next_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="distribusi.next_page_url ? 'bg-white hover:bg-gray-50 text-gray-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            >
                                Berikutnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    distribusi: Object,
    filters: Object
})

const filterMonth = ref(props.filters?.month || '')
const filterYear = ref(props.filters?.year || '')
const searchQuery = ref(props.filters?.search || '')
const perPageSelected = ref(props.filters?.per_page || 10)

const deleteDistribusi = (item) => {
    if (confirm(`Yakin ingin menghapus distribusi ${item.nomor_distribusi}?`)) {
        router.delete(route('admin.distribusi-pupuk.destroy', item.id))
    }
}

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
    if (page >= 1 && page <= props.distribusi.last_page) {
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

// Pagination computed
const visiblePages = computed(() => {
    const current = props.distribusi.current_page;
    const last = props.distribusi.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const getStatusClass = (status) => {
    switch (status) {
        case 'selesai':
            return 'bg-green-100 text-green-800'
        case 'dalam_perjalanan':
            return 'bg-yellow-100 text-yellow-800'
        case 'rencana':
            return 'bg-gray-100 text-gray-800'
        case 'batal':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getStatusLabel = (status) => {
    switch (status) {
        case 'selesai':
            return 'Selesai'
        case 'dalam_perjalanan':
            return 'Dalam Perjalanan'
        case 'rencana':
            return 'Rencana'
        case 'batal':
            return 'Batal'
        default:
            return 'Unknown'
    }
}

const exportPdf = () => {
    const params = new URLSearchParams();
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.location.href = route('admin.distribusi-pupuk.export.pdf') + (params.toString() ? '?' + params.toString() : '');
};

const exportExcel = () => {
    const params = new URLSearchParams();
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.location.href = route('admin.distribusi-pupuk.export.excel') + (params.toString() ? '?' + params.toString() : '');
};

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>
