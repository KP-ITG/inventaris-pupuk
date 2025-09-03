<template>
    <DashboardLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Manajemen Nutrisi</h2>
                            <button @click="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Tambah Nutrisi
                            </button>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-green-700">{{ $page.props.flash.success }}</p>
                    </div>
                    <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <p class="text-red-700">{{ $page.props.flash.error }}</p>
                    </div>

                    <!-- Table -->
                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Nutrisi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formula Kimia</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Digunakan pada</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in nutrisi" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.nama_nutrisi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.satuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.formula_kimia || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.deskripsi_nutrisi || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.pupuk_count || 0 }} pupuk</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openModal(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button @click="deleteNutrisi(item)" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr v-if="nutrisi.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada nutrisi</td>
                                    </tr>
                                </tbody>
                            </table>
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
                        {{ isEditing ? 'Edit Nutrisi' : 'Tambah Nutrisi' }}
                    </h3>

                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Nutrisi</label>
                            <input
                                v-model="form.nama_nutrisi"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: Nitrogen, Fosfor, Kalium"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                            <input
                                v-model="form.satuan"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: %, ppm, kg/ha"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Formula Kimia</label>
                            <input
                                v-model="form.formula_kimia"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Contoh: N, P2O5, K2O"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.deskripsi_nutrisi"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Deskripsi nutrisi dan fungsinya (opsional)"
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
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    nutrisi: Array
})

const showModal = ref(false)
const isEditing = ref(false)
const currentNutrisi = ref(null)
const processing = ref(false)

const form = reactive({
    nama_nutrisi: '',
    satuan: '',
    formula_kimia: '',
    deskripsi_nutrisi: ''
})

const openModal = (nutrisi = null) => {
    if (nutrisi) {
        isEditing.value = true
        currentNutrisi.value = nutrisi
        form.nama_nutrisi = nutrisi.nama_nutrisi
        form.satuan = nutrisi.satuan
        form.formula_kimia = nutrisi.formula_kimia || ''
        form.deskripsi_nutrisi = nutrisi.deskripsi_nutrisi || ''
    } else {
        isEditing.value = false
        currentNutrisi.value = null
        form.nama_nutrisi = ''
        form.satuan = ''
        form.formula_kimia = ''
        form.deskripsi_nutrisi = ''
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    isEditing.value = false
    currentNutrisi.value = null
    form.nama_nutrisi = ''
    form.satuan = ''
    form.formula_kimia = ''
    form.deskripsi_nutrisi = ''
}

const submitForm = () => {
    processing.value = true

    const formData = useForm(form)

    if (isEditing.value) {
        formData.put(`/admin/nutrisi/${currentNutrisi.value.id}`, {
            onSuccess: () => {
                closeModal()
                processing.value = false
            },
            onError: () => {
                processing.value = false
            }
        })
    } else {
        formData.post('/admin/nutrisi', {
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

const deleteNutrisi = (nutrisi) => {
    if (confirm(`Apakah Anda yakin ingin menghapus nutrisi "${nutrisi.nama_nutrisi}"?`)) {
        const formData = useForm({})
        formData.delete(`/admin/nutrisi/${nutrisi.id}`)
    }
}
</script>
