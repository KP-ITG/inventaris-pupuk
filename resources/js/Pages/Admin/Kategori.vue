<template>
    <DashboardLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-medium text-gray-900">Manajemen Kategori Pupuk</h2>
                            <button @click="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Tambah Kategori
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pupuk</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in kategori" :key="item.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.nama_kategori }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.deskripsi || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ item.pupuk_count || 0 }} pupuk</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button @click="openModal(item)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                            <button @click="deleteKategori(item)" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </td>
                                    </tr>
                                    <tr v-if="kategori.length === 0">
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada kategori</td>
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
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    kategori: Array
})

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
</script>
