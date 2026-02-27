<script setup>
import { ref, computed } from 'vue';
import { useForm, Head, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    stokPupuk: Array,
    riwayat: Array,
    desa: Object,
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const flash = computed(() => page.props.flash || {});

const showForm = ref(false);

const form = useForm({
    pupuk_id: '',
    jumlah_digunakan: '',
    keterangan: '',
    tanggal_penggunaan: new Date().toISOString().split('T')[0],
});

const selectedStok = computed(() =>
    props.stokPupuk.find(s => s.pupuk_id == form.pupuk_id) || null
);

const submitForm = () => {
    form.post(route('kepala-desa.kelola-pupuk.store'), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
        },
    });
};

const getStatusColor = (status) => {
    const colors = {
        aman: 'bg-green-100 text-green-800',
        hampir_habis: 'bg-yellow-100 text-yellow-800',
        habis: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        aman: 'Aman',
        hampir_habis: 'Hampir Habis',
        habis: 'Habis',
    };
    return labels[status] || status;
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric',
    });
};
</script>

<template>
    <Head title="Kelola Pupuk" />

    <DashboardLayout>
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Pupuk</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ desa?.nama_desa }} — Catat penggunaan pupuk so stok berkurang</p>
                </div>
                <button
                    @click="showForm = !showForm"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Penggunaan
                </button>
            </div>

            <!-- Flash message -->
            <div v-if="flash.success" class="bg-green-50 border border-green-200 rounded-md p-4 text-sm text-green-800">
                {{ flash.success }}
            </div>

            <!-- Form Catat Penggunaan -->
            <div v-if="showForm" class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Form Catat Penggunaan Pupuk</h3>
                    <form @submit.prevent="submitForm" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Jenis Pupuk -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Pupuk <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.pupuk_id"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                    :class="{ 'border-red-300': errors.pupuk_id }"
                                >
                                    <option value="">Pilih Pupuk</option>
                                    <option
                                        v-for="stok in stokPupuk"
                                        :key="stok.pupuk_id"
                                        :value="stok.pupuk_id"
                                        :disabled="stok.jumlah_stok <= 0"
                                    >
                                        {{ stok.nama_pupuk }} ({{ stok.kategori }}) — Stok: {{ stok.jumlah_stok }} kg
                                    </option>
                                </select>
                                <p v-if="errors.pupuk_id" class="mt-1 text-sm text-red-600">{{ errors.pupuk_id }}</p>
                                <p v-if="selectedStok" class="mt-1 text-xs text-gray-500">
                                    Stok tersedia: <strong>{{ selectedStok.jumlah_stok }} kg</strong>
                                </p>
                            </div>

                            <!-- Jumlah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Digunakan (kg) <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model.number="form.jumlah_digunakan"
                                    type="number"
                                    min="1"
                                    :max="selectedStok?.jumlah_stok || undefined"
                                    required
                                    placeholder="Jumlah dalam kg"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                    :class="{ 'border-red-300': errors.jumlah_digunakan }"
                                >
                                <p v-if="errors.jumlah_digunakan" class="mt-1 text-sm text-red-600">{{ errors.jumlah_digunakan }}</p>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Penggunaan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.tanggal_penggunaan"
                                    type="date"
                                    required
                                    :max="new Date().toISOString().split('T')[0]"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                    :class="{ 'border-red-300': errors.tanggal_penggunaan }"
                                >
                                <p v-if="errors.tanggal_penggunaan" class="mt-1 text-sm text-red-600">{{ errors.tanggal_penggunaan }}</p>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Keterangan
                                </label>
                                <input
                                    v-model="form.keterangan"
                                    type="text"
                                    placeholder="Contoh: Digunakan untuk lahan sawah RT 01"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                                >
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-2">
                            <button
                                type="button"
                                @click="showForm = false; form.reset()"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stok Pupuk Desa -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Stok Pupuk Desa</h3>

                    <div v-if="stokPupuk.length === 0" class="text-center py-8 text-gray-500 text-sm">
                        Belum ada stok pupuk yang diterima dari distribusi.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Masuk</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Digunakan</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Stok</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="stok in stokPupuk" :key="stok.pupuk_id">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ stok.nama_pupuk }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ stok.kategori }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right">{{ stok.total_masuk }} kg</td>
                                    <td class="px-4 py-3 text-sm text-red-600 text-right">{{ stok.total_digunakan }} kg</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">{{ stok.jumlah_stok }} kg</td>
                                    <td class="px-4 py-3 text-center">
                                        <span :class="['inline-flex px-2 py-0.5 rounded-full text-xs font-medium', getStatusColor(stok.status_stok)]">
                                            {{ getStatusLabel(stok.status_stok) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Riwayat Penggunaan -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Penggunaan</h3>

                    <div v-if="riwayat.length === 0" class="text-center py-8 text-gray-500 text-sm">
                        Belum ada catatan penggunaan pupuk.
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in riwayat" :key="item.id">
                                    <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">{{ formatDate(item.tanggal_penggunaan) }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ item.nama_pupuk }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 text-right whitespace-nowrap">{{ item.jumlah_digunakan }} kg</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ item.keterangan || '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
