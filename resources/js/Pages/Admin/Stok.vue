<template>
    <DashboardLayout title="Manajemen Stok">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Stok Pusat Pupuk</h1>
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
                        @click="showModal = true; isEditing = false; resetForm()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="availablePupuks.length === 0"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ availablePupuks.length === 0 ? 'Semua Pupuk Sudah Terdaftar' : 'Tambah Stok Pupuk' }}
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Jenis Pupuk</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stocks.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600">Stok Aman</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stocks.filter(s => s.status_stok === 'aman').length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Perlu Perhatian</p>
                            <p class="text-2xl font-bold text-gray-900">{{ stocks.filter(s => s.status_stok !== 'aman').length }}</p>
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
                                Pupuk
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Stok
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok Min/Max
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi Gudang
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terakhir Restock
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
                        <tr v-for="stock in stocks" :key="stock.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ stock.pupuk?.nama_pupuk }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ stock.pupuk?.kategori?.nama_kategori }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ stock.jumlah_stok }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ stock.stok_minimum || 0 }} / {{ stock.stok_maksimum || 0 }} kg
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ stock.lokasi_gudang || '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ formatDate(stock.updated_at) }}</div>
                                <div class="text-sm text-gray-500">{{ formatRelativeTime(stock.updated_at) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', getStatusClass(stock.status_stok)]">
                                    {{ getStatusLabel(stock.status_stok) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button
                                    @click="editStock(stock)"
                                    class="text-green-600 hover:text-green-900 mr-3"
                                >
                                    Edit
                                </button>
                                <button
                                    @click="deleteStock(stock)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="stocks.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data stok
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ isEditing ? 'Edit Stok Pupuk' : 'Tambah Stok Pupuk Baru' }}
                    </h3>

                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pupuk</label>

                            <!-- Saat edit: tampilkan nama pupuk readonly -->
                            <div v-if="isEditing">
                                <input
                                    type="text"
                                    :value="currentPupukName"
                                    readonly
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed focus:ring-green-500 focus:border-green-500"
                                >
                                <div class="mt-1 text-sm text-gray-500">
                                    Pupuk tidak dapat diubah saat mengedit stok
                                </div>
                            </div>

                            <!-- Saat tambah baru: tampilkan dropdown -->
                            <div v-else>
                                <select v-model="form.pupuk_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <option value="">Pilih Pupuk</option>
                                    <option v-for="pupuk in availablePupuks" :key="pupuk.id" :value="pupuk.id">
                                        {{ pupuk.nama_pupuk }} - {{ pupuk.kategori?.nama_kategori }}
                                    </option>
                                </select>
                                <div v-if="availablePupuks.length === 0" class="mt-1 text-sm text-gray-500">
                                    Semua pupuk sudah memiliki stok. Gunakan fitur edit untuk mengubah stok yang sudah ada.
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok (kg)</label>
                            <input
                                v-model.number="form.jumlah_stok"
                                type="number"
                                required
                                min="0"
                                step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Masukkan jumlah stok"
                            >
                        </div>

                        <!-- Hanya tampilkan field tambahan saat edit -->
                        <div v-if="isEditing">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Minimum</label>
                                    <input
                                        v-model.number="form.stok_minimum"
                                        type="number"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="Min"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Maksimum</label>
                                    <input
                                        v-model.number="form.stok_maksimum"
                                        type="number"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="Max"
                                    >
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Gudang</label>
                                <input
                                    v-model="form.lokasi_gudang"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="Contoh: Gudang A, Rak 1"
                                >
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
                            >
                                {{ isEditing ? 'Update' : 'Simpan' }}
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
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    stocks: Array,
    pupuks: Array
})

const showModal = ref(false)
const isEditing = ref(false)
const editingId = ref(null)

const form = reactive({
    pupuk_id: '',
    jumlah_stok: 0,
    stok_minimum: 0,
    stok_maksimum: 0,
    lokasi_gudang: ''
})

// Computed property untuk menentukan pupuk yang ditampilkan
const availablePupuks = computed(() => {
    if (isEditing.value) {
        // Saat edit, tampilkan semua pupuk
        return props.pupuks
    } else {
        // Saat tambah baru, hanya tampilkan pupuk yang belum ada di stok
        const existingPupukIds = props.stocks.map(stock => stock.pupuk_id)
        return props.pupuks.filter(pupuk => !existingPupukIds.includes(pupuk.id))
    }
})

// Computed property untuk mendapatkan nama pupuk yang sedang diedit
const currentPupukName = computed(() => {
    if (isEditing.value && form.pupuk_id) {
        const pupuk = props.pupuks.find(p => p.id === form.pupuk_id)
        return pupuk ? `${pupuk.nama_pupuk} - ${pupuk.kategori?.nama_kategori}` : ''
    }
    return ''
})

const resetForm = () => {
    Object.assign(form, {
        pupuk_id: '',
        jumlah_stok: 0,
        stok_minimum: 0,
        stok_maksimum: 0,
        lokasi_gudang: ''
    })
}

const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    editingId.value = null
    resetForm()
}

const editStock = (stock) => {
    isEditing.value = true
    editingId.value = stock.id
    Object.assign(form, {
        pupuk_id: stock.pupuk_id,
        jumlah_stok: stock.jumlah_stok,
        stok_minimum: stock.stok_minimum || 0,
        stok_maksimum: stock.stok_maksimum || 0,
        lokasi_gudang: stock.lokasi_gudang || ''
    })
    showModal.value = true
}

const deleteStock = (stock) => {
    if (confirm(`Yakin ingin menghapus stok ${stock.pupuk?.nama_pupuk}?`)) {
        router.delete(`/admin/stok/${stock.id}`)
    }
}

const submitForm = () => {
    if (isEditing.value) {
        router.patch(`/admin/stok/${editingId.value}`, form, {
            onSuccess: () => closeModal()
        })
    } else {
        router.post('/admin/stok', form, {
            onSuccess: () => closeModal()
        })
    }
}

const getStatusClass = (status) => {
    switch (status) {
        case 'aman':
            return 'bg-green-100 text-green-800'
        case 'hampir_habis':
            return 'bg-yellow-100 text-yellow-800'
        case 'habis':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getStatusLabel = (status) => {
    switch (status) {
        case 'aman':
            return 'Aman'
        case 'hampir_habis':
            return 'Hampir Habis'
        case 'habis':
            return 'Habis'
        default:
            return 'Unknown'
    }
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const formatRelativeTime = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    const now = new Date()
    const diffInMs = now - date
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24))

    if (diffInDays === 0) return 'Hari ini'
    if (diffInDays === 1) return 'Kemarin'
    if (diffInDays < 7) return `${diffInDays} hari lalu`
    if (diffInDays < 30) return `${Math.floor(diffInDays / 7)} minggu lalu`
    return `${Math.floor(diffInDays / 30)} bulan lalu`
}

const exportPdf = () => {
    window.location.href = route('admin.stok.export.pdf');
};

const exportExcel = () => {
    window.location.href = route('admin.stok.export.excel');
};
</script>
