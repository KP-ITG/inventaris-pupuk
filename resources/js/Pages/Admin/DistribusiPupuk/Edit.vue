<template>
    <DashboardLayout title="Edit Distribusi Pupuk">
        <div class="">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Distribusi Pupuk</h1>
                        <p class="mt-2 text-sm text-gray-600">{{ distribusi.nomor_distribusi }}</p>
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
                    <!-- Desa Tujuan (Readonly) -->
                    <div>
                        <label for="desa" class="block text-sm font-medium text-gray-700">
                            Desa Tujuan
                        </label>
                        <div class="mt-1">
                            <input
                                id="desa"
                                :value="props.distribusi.desa ? `${props.distribusi.desa.nama_desa} - ${props.distribusi.desa.kecamatan}` : 'Desa tidak ditemukan'"
                                readonly
                                type="text"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed focus:ring-green-500 focus:border-green-500 sm:text-sm"
                            >
                            <div class="mt-1 text-sm text-gray-500">
                                Desa tujuan tidak dapat diubah saat mengedit distribusi
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
                                    :value="formatDate(props.distribusi.tanggal_distribusi)"
                                    @input="form.tanggal_distribusi = $event.target.value"
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
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                        <div v-if="errors.status_distribusi" class="mt-1 text-sm text-red-600">{{ errors.status_distribusi }}</div>
                    </div>

                    <!-- Informasi Penerima -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penerima (Opsional)</h3>

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
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                        placeholder="Nama lengkap penerima"
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
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                        placeholder="Jabatan penerima"
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
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    placeholder="08xxxxxxxxxx"
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
                            {{ processing ? 'Menyimpan...' : 'Update Distribusi' }}
                        </button>
                        <button
                            type="button"
                            @click="deleteDistribusi"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            Hapus
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
    distribusi: Object,
    pupuks: Array,
    desas: Array
})

const page = usePage()
const errors = computed(() => page.props.errors || {})

// Helper function untuk memformat tanggal
const formatDate = (dateString) => {
    if (!dateString) return ''

    // Jika sudah dalam format yang benar
    if (typeof dateString === 'string' && dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return dateString
    }

    // Parse tanggal dari berbagai format
    const date = new Date(dateString)
    if (isNaN(date.getTime())) return ''

    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')

    return `${year}-${month}-${day}`
}

const form = useForm({
    desa_id: props.distribusi.desa_id,
    pupuk_id: props.distribusi.pupuk_id,
    jumlah_distribusi: props.distribusi.jumlah_distribusi,
    tanggal_distribusi: formatDate(props.distribusi.tanggal_distribusi),
    status_distribusi: props.distribusi.status_distribusi,
    catatan: props.distribusi.catatan || '',
    nama_penerima: props.distribusi.nama_penerima || '',
    jabatan_penerima: props.distribusi.jabatan_penerima || '',
    no_telepon_penerima: props.distribusi.no_telepon_penerima || ''
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

    form.patch(route('admin.distribusi-pupuk.update', props.distribusi.id), {
        onFinish: () => {
            processing.value = false
        }
    })
}

// Delete function
const deleteDistribusi = () => {
    if (confirm(`Yakin ingin menghapus distribusi ${props.distribusi.nomor_distribusi}?`)) {
        router.delete(route('admin.distribusi-pupuk.destroy', props.distribusi.id))
    }
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

    // Set initial values
    const currentDesa = props.desas.find(d => d.id === props.distribusi.desa_id)
    const currentPupuk = props.pupuks.find(p => p.id === props.distribusi.pupuk_id)

    if (currentDesa) {
        selectedDesa.value = currentDesa
        desaSearch.value = `${currentDesa.nama_desa} - ${currentDesa.kecamatan}`
    }

    if (currentPupuk) {
        selectedPupuk.value = currentPupuk
        pupukSearch.value = currentPupuk.nama_pupuk
    }

    // Add click outside listener
    document.addEventListener('click', handleClickOutside)
})
</script>
