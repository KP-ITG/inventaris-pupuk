<template>
    <DashboardLayout title="Distribusi Pupuk">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Distribusi Pupuk ke Desa</h1>
                <Link
                    :href="route('admin.distribusi-pupuk.create')"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Distribusi
                </Link>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Distribusi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ distribusi.length }}</p>
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
                            <p class="text-sm font-medium text-gray-600">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900">{{ distribusi.filter(d => d.status_distribusi === 'selesai').length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Dalam Perjalanan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ distribusi.filter(d => d.status_distribusi === 'dalam_perjalanan').length }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gray-100 text-gray-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Rencana</p>
                            <p class="text-2xl font-bold text-gray-900">{{ distribusi.filter(d => d.status_distribusi === 'rencana').length }}</p>
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
                                Nomor Distribusi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Desa Tujuan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pupuk
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
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
                        <tr v-for="item in distribusi" :key="item.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ item.nomor_distribusi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.desa?.nama_desa }}</div>
                                <div class="text-sm text-gray-500">{{ item.desa?.kecamatan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.pupuk?.nama_pupuk }}</div>
                                <div class="text-sm text-gray-500">{{ item.pupuk?.kategori?.nama_kategori }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ item.jumlah_distribusi }} kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ formatDate(item.tanggal_distribusi) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select
                                    :value="item.status_distribusi"
                                    :data-id="item.id"
                                    @change="updateStatus(item.id, $event.target.value)"
                                    :class="['appearance-none block w-full px-3 py-2 pr-8 text-sm font-medium border rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors cursor-pointer hover:shadow-sm', getStatusSelectClass(item.status_distribusi)]"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 4 5&quot;><path fill=&quot;%23666&quot; d=&quot;M2 0L0 2h4zm0 5L0 3h4z&quot;/></svg>'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px;"
                                >
                                    <option value="rencana">Rencana</option>
                                    <option value="dalam_perjalanan">Dalam Perjalanan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Batal</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <Link
                                    :href="route('admin.distribusi-pupuk.edit', item.id)"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteDistribusi(item)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="distribusi.length === 0">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data distribusi pupuk
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    distribusi: Array
})

const deleteDistribusi = (item) => {
    if (confirm(`Yakin ingin menghapus distribusi ${item.nomor_distribusi}?`)) {
        router.delete(route('admin.distribusi-pupuk.destroy', item.id))
    }
}

const updateStatus = (id, newStatus) => {
    // Tampilkan loading indicator (optional)
    const selectElement = document.querySelector(`select[data-id="${id}"]`);
    if (selectElement) {
        selectElement.disabled = true;
    }

    router.patch(route('admin.distribusi-pupuk.update-status', id), {
        status_distribusi: newStatus
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Status berhasil diupdate');
            // Re-enable select
            if (selectElement) {
                selectElement.disabled = false;
            }
        },
        onError: (errors) => {
            console.error('Error updating status:', errors);

            // Re-enable select
            if (selectElement) {
                selectElement.disabled = false;
            }

            // Show specific error message
            if (errors.error) {
                alert(errors.error);
            } else {
                alert('Gagal mengupdate status. Silakan coba lagi.');
            }
        },
        onFinish: () => {
            // Re-enable select in any case
            if (selectElement) {
                selectElement.disabled = false;
            }
        }
    })
}

const getStatusClass = (status) => {
    switch (status) {
        case 'selesai':
            return 'bg-green-100 text-green-800'
        case 'dalam_perjalanan':
            return 'bg-yellow-100 text-yellow-800'
        case 'rencana':
            return 'bg-gray-100 text-gray-800'
        case 'batal':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const getStatusSelectClass = (status) => {
    switch (status) {
        case 'selesai':
            return 'bg-green-100 text-green-800 border-green-200'
        case 'dalam_perjalanan':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200'
        case 'rencana':
            return 'bg-gray-100 text-gray-800 border-gray-200'
        case 'batal':
            return 'bg-red-100 text-red-800 border-red-200'
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200'
    }
}

const getStatusLabel = (status) => {
    switch (status) {
        case 'selesai':
            return 'Selesai'
        case 'dalam_perjalanan':
            return 'Dalam Perjalanan'
        case 'rencana':
            return 'Rencana'
        case 'batal':
            return 'Batal'
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
</script>
