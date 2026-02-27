<template>
    <DashboardLayout title="Tambah Distribusi Pupuk">
        <div class="">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tambah Distribusi Pupuk</h1>
                        <p class="mt-2 text-sm text-gray-600">Buat distribusi pupuk baru ke desa tujuan</p>
                    </div>
                    <Link
                        :href="route('admin.distribusi-pupuk.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </Link>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-6 sm:p-6">
                    <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Pencarian Desa -->
                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700">
                            Desa Tujuan
                        </label>
                        <div class="mt-1 relative">
                            <input
                                id="desa"
                                v-model="desaSearch"
                                @input="filterDesas"
                                @focus="showDesaDropdown = true"
                                type="text"
                                placeholder="Cari nama desa..."
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                autocomplete="off"
                            >

                            <!-- Dropdown Desa -->
                            <div v-if="showDesaDropdown && filteredDesas.length > 0"
                                 class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                <div v-for="desa in filteredDesas"
                                     :key="desa.id"
                                     @click="selectDesa(desa)"
                                     class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-green-50">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900">{{ desa.nama_desa }}</span>
                                        <span class="text-gray-500 text-sm">{{ desa.kecamatan }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="errors.desa_id" class="mt-1 text-sm text-red-600">{{ errors.desa_id }}</div>
                    </div>

                    <!-- Daftar Pupuk yang Didistribusikan -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700">
                                Daftar Pupuk yang Didistribusikan
                            </label>
                            <button
                                type="button"
                                @click="openAddItemForm"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Pupuk
                            </button>
                        </div>

                        <!-- Tabel Items -->
                        <div v-if="form.items.length > 0" class="border border-gray-200 rounded-md overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pupuk</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (kg)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ getPupukName(item.pupuk_id) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ item.jumlah_distribusi }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ getPupukStok(item.pupuk_id) }} kg
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ item.catatan_item || '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button
                                                type="button"
                                                @click="removeItem(index)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-8 border-2 border-dashed border-gray-300 rounded-md">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada pupuk yang ditambahkan</p>
                            <p class="text-xs text-gray-400">Klik tombol "Tambah Pupuk" untuk menambah item</p>
                        </div>

                        <div v-if="errors.items" class="mt-1 text-sm text-red-600">{{ errors.items }}</div>
                    </div>

                    <!-- Modal Add Item -->
                    <div v-if="showAddItemForm" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="cancelAddItem"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                                Tambah Pupuk
                                            </h3>

                                            <div class="space-y-4">
                                                <!-- Pencarian Pupuk -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Jenis Pupuk
                                                    </label>
                                                    <div class="relative">
                                                        <input
                                                            v-model="pupukSearch"
                                                            @input="filterPupuks"
                                                            @focus="showPupukDropdown = true"
                                                            type="text"
                                                            placeholder="Cari nama pupuk..."
                                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                            autocomplete="off"
                                                        >

                                                        <!-- Dropdown Pupuk -->
                                                        <div v-if="showPupukDropdown && filteredPupuks.length > 0"
                                                             class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                                            <div v-for="pupuk in filteredPupuks"
                                                                 :key="pupuk.id"
                                                                 @click="selectPupuk(pupuk)"
                                                                 class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-green-50">
                                                                <div class="flex flex-col">
                                                                    <span class="font-medium text-gray-900">{{ pupuk.nama_pupuk }}</span>
                                                                    <span class="text-gray-500 text-sm">
                                                                        {{ pupuk.kategori?.nama_kategori }} |
                                                                        Stok: {{ pupuk.stok_gudang_pusat || 0 }} kg
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Jumlah -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Jumlah Distribusi (kg)
                                                    </label>
                                                    <input
                                                        v-model.number="newItem.jumlah_distribusi"
                                                        type="number"
                                                        min="1"
                                                        :max="selectedPupuk?.stok_gudang_pusat || 999999"
                                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                        placeholder="Masukkan jumlah"
                                                    >
                                                    <div v-if="selectedPupuk" class="mt-1 text-xs text-gray-500">
                                                        Stok tersedia: {{ selectedPupuk.stok_gudang_pusat }} kg
                                                    </div>
                                                    <div v-if="stockValidationMessage" class="mt-1 text-sm text-red-600">
                                                        {{ stockValidationMessage }}
                                                    </div>
                                                </div>

                                                <!-- Catatan Item -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Catatan
                                                    </label>
                                                    <textarea
                                                        v-model="newItem.catatan_item"
                                                        rows="2"
                                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                        placeholder="Catatan untuk item ini (opsional)"
                                                    ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button
                                        type="button"
                                        @click="addItem"
                                        :disabled="!newItem.pupuk_id || !newItem.jumlah_distribusi || newItem.jumlah_distribusi <= 0"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Tambahkan
                                    </button>
                                    <button
                                        type="button"
                                        @click="cancelAddItem"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    >
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Tanggal Distribusi -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">
                                Tanggal Distribusi
                            </label>
                            <div class="mt-1">
                                <input
                                    id="tanggal"
                                    v-model="form.tanggal_distribusi"
                                    type="date"
                                    required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                            </div>
                            <div v-if="errors.tanggal_distribusi" class="mt-1 text-sm text-red-600">{{ errors.tanggal_distribusi }}</div>
                        </div>

                            <!-- Status Distribusi -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status Distribusi
                            </label>
                            <div class="mt-1">
                                <select
                                    id="status"
                                    v-model="form.status_distribusi"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                >
                                    <option value="rencana">Rencana</option>
                                    <option value="dalam_perjalanan">Dalam Perjalanan</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div v-if="errors.status_distribusi" class="mt-1 text-sm text-red-600">{{ errors.status_distribusi }}</div>
                        </div>
                    </div>

                    <!-- Informasi Penerima -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penerima</h3>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="nama_penerima" class="block text-sm font-medium text-gray-700">
                                    Nama Penerima
                                </label>
                                <div class="mt-1">
                                    <input
                                        id="nama_penerima"
                                        v-model="form.nama_penerima"
                                        type="text"
                                        readonly
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 cursor-not-allowed sm:text-sm"
                                        placeholder="Pilih desa terlebih dahulu"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="jabatan_penerima" class="block text-sm font-medium text-gray-700">
                                    Jabatan
                                </label>
                                <div class="mt-1">
                                    <input
                                        id="jabatan_penerima"
                                        v-model="form.jabatan_penerima"
                                        type="text"
                                        readonly
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 cursor-not-allowed sm:text-sm"
                                        placeholder="Pilih desa terlebih dahulu"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="no_telepon_penerima" class="block text-sm font-medium text-gray-700">
                                No. Telepon Penerima
                            </label>
                            <div class="mt-1">
                                <input
                                    id="no_telepon_penerima"
                                    v-model="form.no_telepon_penerima"
                                    type="tel"
                                    readonly
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 cursor-not-allowed sm:text-sm"
                                    placeholder="Pilih desa terlebih dahulu"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700">
                            Catatan
                        </label>
                        <div class="mt-1">
                            <textarea
                                id="catatan"
                                v-model="form.catatan"
                                rows="3"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                placeholder="Catatan tambahan (opsional)"
                            ></textarea>
                        </div>
                        <div v-if="errors.catatan" class="mt-1 text-sm text-red-600">{{ errors.catatan }}</div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex space-x-4">
                        <Link
                            :href="route('admin.distribusi-pupuk.index')"
                            class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                        >
                            {{ processing ? 'Menyimpan...' : 'Simpan Distribusi' }}
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { router, Link, useForm, usePage } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    pupuks: Array,
    desas: Array
})

const page = usePage()
const errors = computed(() => page.props.errors || {})

const form = useForm({
    desa_id: '',
    items: [],
    tanggal_distribusi: new Date().toISOString().split('T')[0],
    status_distribusi: 'rencana',
    catatan: '',
    nama_penerima: '',
    jabatan_penerima: '',
    no_telepon_penerima: ''
})

const processing = ref(false)
const showAddItemForm = ref(false)

const openAddItemForm = () => {
    showAddItemForm.value = true
    filteredPupuks.value = props.pupuks
    showPupukDropdown.value = true
}

// Item form
const newItem = ref({
    pupuk_id: null,
    jumlah_distribusi: 0,
    catatan_item: ''
})

// Search functionality
const desaSearch = ref('')
const pupukSearch = ref('')
const showDesaDropdown = ref(false)
const showPupukDropdown = ref(false)
const filteredDesas = ref([])
const filteredPupuks = ref([])
const selectedDesa = ref(null)
const selectedPupuk = ref(null)

// Computed properties for validation
const isStokCukup = computed(() => {
    if (!selectedPupuk.value) return false
    return newItem.value.jumlah_distribusi <= selectedPupuk.value.stok_gudang_pusat
})

const stockValidationMessage = computed(() => {
    if (!selectedPupuk.value) return ''
    if (newItem.value.jumlah_distribusi > selectedPupuk.value.stok_gudang_pusat) {
        return `Jumlah distribusi melebihi stok tersedia (${selectedPupuk.value.stok_gudang_pusat} kg)`
    }
    return ''
})

// Filter functions
const filterDesas = () => {
    if (!desaSearch.value) {
        filteredDesas.value = props.desas.slice(0, 10)
        return
    }

    const search = desaSearch.value.toLowerCase()
    filteredDesas.value = props.desas.filter(desa =>
        desa.nama_desa.toLowerCase().includes(search) ||
        desa.kecamatan.toLowerCase().includes(search)
    ).slice(0, 10)
}

const filterPupuks = () => {
    if (!pupukSearch.value) {
        filteredPupuks.value = props.pupuks
        return
    }

    const search = pupukSearch.value.toLowerCase()
    filteredPupuks.value = props.pupuks.filter(pupuk =>
        pupuk.nama_pupuk.toLowerCase().includes(search) ||
        pupuk.kategori?.nama_kategori.toLowerCase().includes(search)
    )
}

// Selection functions
const selectDesa = (desa) => {
    selectedDesa.value = desa
    form.desa_id = desa.id
    desaSearch.value = `${desa.nama_desa} - ${desa.kecamatan}`

    // Auto-fill penerima dengan nama kepala desa
    if (desa.nama_kepala_desa) {
        form.nama_penerima = desa.nama_kepala_desa
        form.jabatan_penerima = 'Kepala Desa'
        form.no_telepon_penerima = desa.no_telepon || ''
    }

    showDesaDropdown.value = false
}

const selectPupuk = (pupuk) => {
    selectedPupuk.value = pupuk
    newItem.value.pupuk_id = pupuk.id
    pupukSearch.value = pupuk.nama_pupuk
    showPupukDropdown.value = false
}

// Item management functions
const addItem = () => {
    // Validasi
    if (!newItem.value.pupuk_id || !newItem.value.jumlah_distribusi) {
        alert('Mohon lengkapi data pupuk dan jumlah distribusi')
        return
    }

    // Cek apakah pupuk sudah ada di list
    const exists = form.items.some(item => item.pupuk_id === newItem.value.pupuk_id)
    if (exists) {
        alert('Pupuk ini sudah ditambahkan. Silakan pilih pupuk yang lain atau hapus item yang lama.')
        return
    }

    // Validasi stok
    if (!selectedPupuk.value) {
        alert('Silakan pilih pupuk terlebih dahulu')
        return
    }

    if (newItem.value.jumlah_distribusi > selectedPupuk.value.stok_gudang_pusat) {
        alert(`Jumlah distribusi (${newItem.value.jumlah_distribusi} kg) melebihi stok tersedia (${selectedPupuk.value.stok_gudang_pusat} kg)`)
        return
    }

    // Tambahkan item
    form.items.push({
        pupuk_id: newItem.value.pupuk_id,
        jumlah_distribusi: newItem.value.jumlah_distribusi,
        catatan_item: newItem.value.catatan_item
    })

    // Reset form
    cancelAddItem()
}

const removeItem = (index) => {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        form.items.splice(index, 1)
    }
}

const cancelAddItem = () => {
    showAddItemForm.value = false
    newItem.value = {
        pupuk_id: null,
        jumlah_distribusi: 0,
        catatan_item: ''
    }
    selectedPupuk.value = null
    pupukSearch.value = ''
    showPupukDropdown.value = false
}

// Helper functions
const getPupukName = (pupukId) => {
    const pupuk = props.pupuks.find(p => p.id === pupukId)
    return pupuk ? pupuk.nama_pupuk : '-'
}

const getPupukStok = (pupukId) => {
    const pupuk = props.pupuks.find(p => p.id === pupukId)
    return pupuk?.stok_gudang_pusat || 0
}

// Submit form
const submitForm = () => {
    // Validasi items
    if (form.items.length === 0) {
        alert('Mohon tambahkan minimal 1 jenis pupuk untuk distribusi')
        return
    }

    processing.value = true

    form.post(route('admin.distribusi-pupuk.store'), {
        onFinish: () => {
            processing.value = false
        }
    })
}

// Click outside to close dropdowns
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        showDesaDropdown.value = false
        showPupukDropdown.value = false
    }
}

onMounted(() => {
    // Initialize filtered lists
    filteredDesas.value = props.desas.slice(0, 10)
    filteredPupuks.value = props.pupuks

    // Add click outside listener
    document.addEventListener('click', handleClickOutside)
})
</script>
