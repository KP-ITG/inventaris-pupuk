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

                    <!-- Pencarian Pupuk -->
                    <div>
                        <label for="pupuk" class="block text-sm font-medium text-gray-700">
                            Jenis Pupuk
                        </label>
                        <div class="mt-1 relative">
                            <input
                                id="pupuk"
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
                                            Stok: {{ pupuk.stok?.jumlah_stok || 0 }} kg
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="errors.pupuk_id" class="mt-1 text-sm text-red-600">{{ errors.pupuk_id }}</div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Jumlah Distribusi -->
                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">
                                Jumlah Distribusi (kg)
                            </label>
                            <div class="mt-1">
                                <input
                                    id="jumlah"
                                    v-model.number="form.jumlah_distribusi"
                                    type="number"
                                    min="1"
                                    :max="selectedPupuk?.stok?.jumlah_stok || 999999"
                                    required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="Masukkan jumlah"
                                >
                            </div>
                            <div v-if="selectedPupuk?.stok" class="mt-1 text-xs text-gray-500">
                                Stok tersedia: {{ selectedPupuk.stok.jumlah_stok }} kg
                            </div>
                            <div v-if="stockValidationMessage" class="mt-1 text-sm text-red-600">
                                {{ stockValidationMessage }}
                            </div>
                            <div v-if="errors.jumlah_distribusi" class="mt-1 text-sm text-red-600">{{ errors.jumlah_distribusi }}</div>
                        </div>

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
    pupuk_id: '',
    jumlah_distribusi: 0,
    tanggal_distribusi: new Date().toISOString().split('T')[0],
    status_distribusi: 'rencana',
    catatan: '',
    nama_penerima: '',
    jabatan_penerima: '',
    no_telepon_penerima: ''
})

const processing = ref(false)

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
    if (!selectedPupuk.value?.stok) return false
    return form.jumlah_distribusi <= selectedPupuk.value.stok.jumlah_stok
})

const stockValidationMessage = computed(() => {
    if (!selectedPupuk.value?.stok) return ''
    if (form.jumlah_distribusi > selectedPupuk.value.stok.jumlah_stok) {
        return `Jumlah distribusi melebihi stok tersedia (${selectedPupuk.value.stok.jumlah_stok} kg)`
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
        filteredPupuks.value = props.pupuks.slice(0, 10)
        return
    }

    const search = pupukSearch.value.toLowerCase()
    filteredPupuks.value = props.pupuks.filter(pupuk =>
        pupuk.nama_pupuk.toLowerCase().includes(search) ||
        pupuk.kategori?.nama_kategori.toLowerCase().includes(search)
    ).slice(0, 10)
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
    form.pupuk_id = pupuk.id
    pupukSearch.value = pupuk.nama_pupuk
    showPupukDropdown.value = false
}

// Submit form
const submitForm = () => {
    // Validasi stok sebelum submit
    if (!selectedPupuk.value?.stok) {
        alert('Pupuk yang dipilih tidak memiliki stok')
        return
    }

    if (form.jumlah_distribusi > selectedPupuk.value.stok.jumlah_stok) {
        alert(`Jumlah distribusi (${form.jumlah_distribusi} kg) melebihi stok tersedia (${selectedPupuk.value.stok.jumlah_stok} kg)`)
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
    filteredPupuks.value = props.pupuks.slice(0, 10)

    // Add click outside listener
    document.addEventListener('click', handleClickOutside)
})
</script>
