<template>
    <DashboardLayout title="Edit Data Desa">
        <div class="space-y-6">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Data Desa</h1>
                        <p class="mt-2 text-sm text-gray-600">{{ desa.nama_desa }} - {{ desa.kecamatan }}</p>
                    </div>
                    <Link
                        :href="route('admin.desa.index')"
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
                        <!-- Informasi Dasar Desa -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar Desa</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="nama_desa" class="block text-sm font-medium text-gray-700">
                                        Nama Desa *
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="nama_desa"
                                            v-model="form.nama_desa"
                                            type="text"
                                            required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Masukkan nama desa"
                                        >
                                    </div>
                                    <div v-if="errors.nama_desa" class="mt-1 text-sm text-red-600">{{ errors.nama_desa }}</div>
                                </div>

                                <div>
                                    <label for="kecamatan" class="block text-sm font-medium text-gray-700">
                                        Kecamatan *
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="kecamatan"
                                            v-model="form.kecamatan"
                                            type="text"
                                            required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Masukkan nama kecamatan"
                                        >
                                    </div>
                                    <div v-if="errors.kecamatan" class="mt-1 text-sm text-red-600">{{ errors.kecamatan }}</div>
                                </div>

                                <div>
                                    <label for="kabupaten" class="block text-sm font-medium text-gray-700">
                                        Kabupaten *
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="kabupaten"
                                            v-model="form.kabupaten"
                                            type="text"
                                            required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Masukkan nama kabupaten"
                                        >
                                    </div>
                                    <div v-if="errors.kabupaten" class="mt-1 text-sm text-red-600">{{ errors.kabupaten }}</div>
                                </div>

                                <div>
                                    <label for="provinsi" class="block text-sm font-medium text-gray-700">
                                        Provinsi *
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="provinsi"
                                            v-model="form.provinsi"
                                            type="text"
                                            required
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Masukkan nama provinsi"
                                        >
                                    </div>
                                    <div v-if="errors.provinsi" class="mt-1 text-sm text-red-600">{{ errors.provinsi }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat dan Lokasi -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Alamat dan Lokasi</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">
                                        Alamat Lengkap
                                    </label>
                                    <div class="mt-1">
                                        <textarea
                                            id="alamat_lengkap"
                                            v-model="form.alamat_lengkap"
                                            rows="3"
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Masukkan alamat lengkap desa"
                                        ></textarea>
                                    </div>
                                    <div v-if="errors.alamat_lengkap" class="mt-1 text-sm text-red-600">{{ errors.alamat_lengkap }}</div>
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                    <div>
                                        <label for="kode_pos" class="block text-sm font-medium text-gray-700">
                                            Kode Pos
                                        </label>
                                        <div class="mt-1">
                                            <input
                                                id="kode_pos"
                                                v-model="form.kode_pos"
                                                type="text"
                                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                placeholder="Kode pos"
                                            >
                                        </div>
                                        <div v-if="errors.kode_pos" class="mt-1 text-sm text-red-600">{{ errors.kode_pos }}</div>
                                    </div>

                                    <div>
                                        <label for="jumlah_penduduk" class="block text-sm font-medium text-gray-700">
                                            Jumlah Penduduk
                                        </label>
                                        <div class="mt-1">
                                            <input
                                                id="jumlah_penduduk"
                                                v-model.number="form.jumlah_penduduk"
                                                type="number"
                                                min="0"
                                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                placeholder="Jumlah penduduk"
                                            >
                                        </div>
                                        <div v-if="errors.jumlah_penduduk" class="mt-1 text-sm text-red-600">{{ errors.jumlah_penduduk }}</div>
                                    </div>

                                    <div>
                                        <label for="luas_wilayah" class="block text-sm font-medium text-gray-700">
                                            Luas Wilayah (Ha)
                                        </label>
                                        <div class="mt-1">
                                            <input
                                                id="luas_wilayah"
                                                v-model.number="form.luas_wilayah"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                placeholder="Luas wilayah"
                                            >
                                        </div>
                                        <div v-if="errors.luas_wilayah" class="mt-1 text-sm text-red-600">{{ errors.luas_wilayah }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Kepala Desa -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kepala Desa</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="nama_kepala_desa" class="block text-sm font-medium text-gray-700">
                                        Nama Kepala Desa
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="nama_kepala_desa"
                                            v-model="form.nama_kepala_desa"
                                            type="text"
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="Nama kepala desa"
                                        >
                                    </div>
                                    <div v-if="errors.nama_kepala_desa" class="mt-1 text-sm text-red-600">{{ errors.nama_kepala_desa }}</div>
                                </div>

                                <div>
                                    <label for="no_telepon" class="block text-sm font-medium text-gray-700">
                                        No. Telepon
                                    </label>
                                    <div class="mt-1">
                                        <input
                                            id="no_telepon"
                                            v-model="form.no_telepon"
                                            type="text"
                                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                            placeholder="08xxxxxxxxxx"
                                        >
                                    </div>
                                    <div v-if="errors.no_telepon" class="mt-1 text-sm text-red-600">{{ errors.no_telepon }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="border-t border-gray-200 pt-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    Status Desa *
                                </label>
                                <div class="mt-1">
                                    <select
                                        id="status"
                                        v-model="form.status"
                                        required
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                    >
                                        <option value="aktif">Aktif</option>
                                        <option value="non_aktif">Non Aktif</option>
                                    </select>
                                </div>
                                <div v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Desa dengan status aktif dapat menerima distribusi pupuk
                                </p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex space-x-4">
                            <Link
                                :href="route('admin.desa.index')"
                                class="flex-1 flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="processing"
                                class="flex-1 flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                            >
                                {{ processing ? 'Menyimpan...' : 'Update Data Desa' }}
                            </button>
                            <button
                                type="button"
                                @click="deleteDesa"
                                :disabled="desa.distribusi_pupuk_count > 0"
                                :class="[
                                    'flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2',
                                    desa.distribusi_pupuk_count > 0
                                        ? 'text-gray-400 bg-gray-200 cursor-not-allowed'
                                        : 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500'
                                ]"
                            >
                                Hapus
                            </button>
                        </div>

                        <div v-if="desa.distribusi_pupuk_count > 0" class="mt-2">
                            <p class="text-sm text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Desa tidak dapat dihapus karena sudah memiliki {{ desa.distribusi_pupuk_count }} riwayat distribusi pupuk
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    desa: Object
})

const page = usePage()
const errors = computed(() => page.props.errors || {})

const form = useForm({
    nama_desa: props.desa.nama_desa,
    kecamatan: props.desa.kecamatan,
    kabupaten: props.desa.kabupaten,
    provinsi: props.desa.provinsi,
    alamat_lengkap: props.desa.alamat_lengkap || '',
    kode_pos: props.desa.kode_pos || '',
    nama_kepala_desa: props.desa.nama_kepala_desa || '',
    no_telepon: props.desa.no_telepon || '',
    jumlah_penduduk: props.desa.jumlah_penduduk,
    luas_wilayah: props.desa.luas_wilayah,
    status: props.desa.status
})

const processing = ref(false)

const submitForm = () => {
    processing.value = true

    form.patch(route('admin.desa.update', props.desa.id), {
        onFinish: () => {
            processing.value = false
        }
    })
}

const deleteDesa = () => {
    if (props.desa.distribusi_pupuk_count > 0) {
        alert('Tidak dapat menghapus desa yang sudah memiliki riwayat distribusi pupuk')
        return
    }

    if (confirm(`Yakin ingin menghapus desa ${props.desa.nama_desa}?`)) {
        router.delete(route('admin.desa.destroy', props.desa.id))
    }
}
</script>
