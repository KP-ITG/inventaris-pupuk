<template>
    <DashboardLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Data Desa</h1>
                            <p class="mt-1 text-sm text-gray-600">Kelola data desa untuk distribusi pupuk</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button
                                @click="exportPdf"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium"
                            >
                                PDF
                            </button>
                            <button
                                @click="exportExcel"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium"
                            >
                                Excel
                            </button>
                            <Link
                                :href="route('admin.desa.create')"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Desa
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="mx-6 p-4 bg-green-50 border border-green-200 rounded-md">
                <p class="text-green-700">{{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="mx-6 p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-red-700">{{ $page.props.flash.error }}</p>
            </div>

            <!-- Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Desa ({{ desa.total || 0 }})</h3>
                </div>

                <!-- Search and Filters -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex-1 max-w-md">
                            <input
                                v-model="searchQuery"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Cari desa, kecamatan, atau kabupaten..."
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"
                            />
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <span class="text-sm text-gray-700">Tampilkan:</span>
                            <select
                                v-model="perPageSelected"
                                @change="changePerPage"
                                class="border border-gray-300 rounded-md text-sm px-2 py-1"
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kabupaten</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepala Desa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penduduk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distribusi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in desa.data" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ item.nama_desa }}</div>
                                    <div class="text-sm text-gray-500">{{ item.provinsi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.kecamatan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ item.kabupaten }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ item.nama_kepala_desa || '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ item.no_telepon || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ item.jumlah_penduduk ? item.jumlah_penduduk.toLocaleString() : '-' }} jiwa
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ item.distribusi_pupuk_count || 0 }} kali
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="item.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <Link
                                        :href="route('admin.desa.edit', item.id)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteItem(item)"
                                        class="text-red-600 hover:text-red-900"
                                        :disabled="item.distribusi_pupuk_count > 0"
                                        :class="{ 'opacity-50 cursor-not-allowed': item.distribusi_pupuk_count > 0 }"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="!desa.data || desa.data.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada desa</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan desa baru.</p>
                </div>

                <!-- Pagination -->
                <div v-if="desa.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ desa.from || 0 }} sampai {{ desa.to || 0 }} dari {{ desa.total || 0 }} data
                        </div>
                        <div class="flex space-x-1">
                            <button
                                @click="goToPage(desa.current_page - 1)"
                                :disabled="!desa.prev_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="desa.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                            >
                                Prev
                            </button>
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="goToPage(page)"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="page === desa.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"
                            >
                                {{ page }}
                            </button>
                            <button
                                @click="goToPage(desa.current_page + 1)"
                                :disabled="!desa.next_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="desa.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                            >
                                Next
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
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { ref, computed } from 'vue'

const props = defineProps({
    desa: Object,
    filters: Object,
})

// Search and pagination
const searchQuery = ref(props.filters?.search || '')
const perPageSelected = ref(props.filters?.per_page || 10)

const deleteItem = (item) => {
    if (item.distribusi_pupuk_count > 0) {
        alert('Tidak dapat menghapus desa yang sudah memiliki riwayat distribusi pupuk')
        return
    }

    if (confirm(`Yakin ingin menghapus desa ${item.nama_desa}?`)) {
        router.delete(route('admin.desa.destroy', item.id))
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
    if (page >= 1 && page <= props.desa.last_page) {
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

// Export functions
const exportPdf = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value
    });
    window.open(`/admin/desa/export/pdf?${params.toString()}`, '_blank');
};

const exportExcel = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value
    });
    window.location.href = `/admin/desa/export/excel?${params.toString()}`;
};

// Pagination computed
const visiblePages = computed(() => {
    const current = props.desa.current_page;
    const last = props.desa.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>
