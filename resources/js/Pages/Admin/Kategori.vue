<template>
    <DashboardLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Manajemen Kategori Pupuk</h2>
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
                                <button
                                    @click="openModal()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                >
                                    Tambah Kategori
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="flex-1 max-w-md">
                                <input
                                    v-model="searchQuery"
                                    @input="debouncedSearch"
                                    type="text"
                                    placeholder="Cari kategori atau deskripsi..."
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

                    <!-- Flash Messages -->
                    <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-green-700">{{ $page.props.flash.success }}</p>
                    </div>
                    <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-red-700">{{ $page.props.flash.error }}</p>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pupuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in kategori.data" :key="item.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.nama_kategori }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ item.deskripsi || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.pupuk_count || 0 }} pupuk</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button
                                                @click="openModal(item)"
                                                class="text-green-600 hover:text-green-900 text-sm font-medium"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="deleteKategori(item)"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!kategori.data || kategori.data.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada kategori
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="kategori.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ kategori.from || 0 }} sampai {{ kategori.to || 0 }} dari {{ kategori.total || 0 }} data
                            </div>
                            <div class="flex space-x-1">
                                <button
                                    @click="goToPage(kategori.current_page - 1)"
                                    :disabled="!kategori.prev_page_url"
                                    class="px-3 py-1 text-sm border rounded-md"
                                    :class="kategori.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                                >
                                    Prev
                                </button>
                                <button
                                    v-for="page in visiblePages"
                                    :key="page"
                                    @click="goToPage(page)"
                                    class="px-3 py-1 text-sm border rounded-md"
                                    :class="page === kategori.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"
                                >
                                    {{ page }}
                                </button>
                                <button
                                    @click="goToPage(kategori.current_page + 1)"
                                    :disabled="!kategori.next_page_url"
                                    class="px-3 py-1 text-sm border rounded-md"
                                    :class="kategori.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"
                                >
                                    Next
                                </button>
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
                        {{ isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}
                    </h3>

                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                            <input
                                v-model="form.nama_kategori"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Masukkan nama kategori"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.deskripsi"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Deskripsi kategori (opsional)"
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
    kategori: Object,
    filters: Object
})

// Search and pagination
const searchQuery = ref(props.filters?.search || '')
const perPageSelected = ref(props.filters?.per_page || 10)

const showModal = ref(false)
const isEditing = ref(false)
const currentKategori = ref(null)
const processing = ref(false)

const form = reactive({
    nama_kategori: '',
    deskripsi: ''
})

const openModal = (kategori = null) => {
    if (kategori) {
        isEditing.value = true
        currentKategori.value = kategori
        form.nama_kategori = kategori.nama_kategori
        form.deskripsi = kategori.deskripsi || ''
    } else {
        isEditing.value = false
        currentKategori.value = null
        form.nama_kategori = ''
        form.deskripsi = ''
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentKategori.value = null
    form.nama_kategori = ''
    form.deskripsi = ''
}

const submitForm = () => {
    processing.value = true

    const formData = useForm(form)

    if (isEditing.value) {
        formData.put(`/admin/kategori/${currentKategori.value.id}`, {
            onSuccess: () => {
                closeModal()
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    } else {
        formData.post('/admin/kategori', {
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

const deleteKategori = (kategori) => {
    if (confirm(`Apakah Anda yakin ingin menghapus kategori "${kategori.nama_kategori}"?`)) {
        const formData = useForm({})
        formData.delete(`/admin/kategori/${kategori.id}`)
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
    if (page >= 1 && page <= props.kategori.last_page) {
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
    window.open(`/admin/kategori/export/pdf?${params.toString()}`, '_blank');
};

const exportExcel = () => {
    const params = new URLSearchParams({
        search: searchQuery.value || '',
        per_page: perPageSelected.value
    });
    window.location.href = `/admin/kategori/export/excel?${params.toString()}`;
};

// Pagination computed
const visiblePages = computed(() => {
    const current = props.kategori.current_page;
    const last = props.kategori.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>
