<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

const props = defineProps({
    stokPupuk: Array,
    desa: Object,
});

const selectedItems = ref([]);

const permintaanForm = useForm({
    items: [],
    keterangan: '',
});

const addItem = () => {
    selectedItems.value.push({
        pupuk_id: '',
        kategori_id: '',
        jumlah_diminta: '',
    });
};

const removeItem = (index) => {
    selectedItems.value.splice(index, 1);
};

const updateKategori = (index) => {
    const pupukId = selectedItems.value[index].pupuk_id;
    const pupuk = props.stokPupuk.find(s => s.pupuk_id == pupukId);
    if (pupuk) {
        selectedItems.value[index].kategori_id = pupuk.kategori_id;
    }
};

const submitPermintaan = () => {
    permintaanForm.items = selectedItems.value;
    permintaanForm.post(route('kepala-desa.permintaan.store'));
};
</script>

<template>
    <Head title="Ajukan Permintaan" />

    <DashboardLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Ajukan Permintaan Distribusi</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ desa?.nama_desa }}</p>
                    </div>
                </div>

                <!-- Stok Pupuk Tersedia -->
                <!-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Stok Pupuk Tersedia</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pupuk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="stok in stokPupuk" :key="stok.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ stok.nama_pupuk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ stok.kategori }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ stok.jumlah }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ stok.satuan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->

                <!-- Form Permintaan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Form Permintaan Distribusi</h3>

                        <form @submit.prevent="submitPermintaan" class="space-y-6">
                            <!-- Items -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Pupuk yang diminta <span class="text-red-500">*</span>
                                    </label>
                                    <button
                                        type="button"
                                        @click="addItem"
                                        class="inline-flex items-center px-3 py-2 text-sm text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                                    >
                                        + Tambah Item
                                    </button>
                                </div>

                                <div v-if="selectedItems.length === 0" class="text-sm text-gray-500 p-4 bg-gray-50 rounded-md mb-2">
                                    Klik "Tambah Item" untuk menambah pupuk yang ingin diminta
                                </div>

                                <div v-for="(item, index) in selectedItems" :key="index" class="flex gap-2 mb-2">
                                    <select
                                        v-model="item.pupuk_id"
                                        @change="updateKategori(index)"
                                        required
                                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Pilih Pupuk</option>
                                        <option v-for="stok in stokPupuk" :key="stok.pupuk_id" :value="stok.pupuk_id">
                                            {{ stok.nama_pupuk }} ({{ stok.kategori }})
                                        </option>
                                    </select>
                                    <input
                                        v-model="item.jumlah_diminta"
                                        type="number"
                                        required
                                        min="1"
                                        placeholder="Jumlah"
                                        class="w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <button
                                        type="button"
                                        @click="removeItem(index)"
                                        class="px-3 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200"
                                    >
                                        Hapus
                                    </button>
                                </div>
                                <p v-if="permintaanForm.errors.items" class="mt-1 text-sm text-red-600">
                                    {{ permintaanForm.errors.items }}
                                </p>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan/Alasan <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    v-model="permintaanForm.keterangan"
                                    rows="4"
                                    required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Jelaskan alasan permintaan distribusi (minimal 20 karakter)"
                                    :class="{ 'border-red-300': permintaanForm.errors.keterangan }"
                                ></textarea>
                                <p v-if="permintaanForm.errors.keterangan" class="mt-1 text-sm text-red-600">
                                    {{ permintaanForm.errors.keterangan }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    @click="$inertia.visit(route('kepala-desa.dashboard'))"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    :disabled="permintaanForm.processing || selectedItems.length === 0"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                                >
                                    {{ permintaanForm.processing ? 'Mengirim...' : 'Kirim Permintaan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
