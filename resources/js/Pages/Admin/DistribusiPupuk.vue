<template>
    <DashboardLayout title="Distribusi Pupuk">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Distribusi Pupuk ke Desa</h1>
                <div class="flex items-center space-x-2">
                    <button
                        @click="showPreviewModal = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"
                    >
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Export
                    </button>
                    <Link
                        :href="route('admin.distribusi-pupuk.create')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Distribusi
                    </Link>
                </div>
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
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).length }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'selesai').length }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'dalam_perjalanan').length }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ (distribusi.data || distribusi).filter(d => d.status_distribusi === 'rencana').length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div class="flex-1 max-w-md">
                            <input
                                v-model="searchQuery"
                                @input="debouncedSearch"
                                type="text"
                                placeholder="Cari nomor distribusi, pupuk, atau desa..."
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"
                            />
                        </div>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <span class="text-sm text-gray-700">Tampilkan</span>
                            <select
                                v-model="perPageSelected"
                                @change="changePerPage"
                                class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-700">data</span>
                        </div>
                    </div>
                </div>

                <!-- Period Filter -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <span class="text-sm font-medium text-gray-700 whitespace-nowrap">Filter Periode:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        <select
                            v-model="filterMonth"
                            class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Semua Bulan</option>
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <select
                            v-model="filterYear"
                            class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-1.5 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Semua Tahun</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                        </select>
                        <button
                            @click="applyFilter"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-md text-sm font-medium shadow-sm"
                        >
                            Terapkan
                        </button>
                        <button
                            @click="resetFilter"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-1.5 rounded-md text-sm font-medium"
                        >
                            Reset
                        </button>
                    </div>
                </div>
            </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 140px;">
                                    Nomor Distribusi
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">
                                    Desa Tujuan
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 250px;">
                                    Pupuk
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 100px;">
                                    Jumlah
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 140px;">
                                    Tanggal
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 130px;">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 180px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in distribusi.data" :key="item.id" class="hover:bg-gray-50">
                                <td class="px-4 py-4 align-top">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ item.nomor_distribusi }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div class="text-sm font-medium text-gray-900">{{ item.desa?.nama_desa }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ item.desa?.kecamatan }}</div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div v-if="item.details && item.details.length > 0">
                                        <div v-if="item.details.length === 1" class="text-sm">
                                            <div class="font-medium text-gray-900">{{ item.details[0].pupuk?.nama_pupuk }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ item.details[0].pupuk?.kategori?.nama_kategori }}</div>
                                        </div>
                                        <div v-else class="text-sm">
                                            <div class="font-medium text-gray-900 mb-1">{{ item.details.length }} jenis pupuk:</div>
                                            <div class="text-xs text-gray-600 leading-relaxed">
                                                <span v-for="(detail, idx) in item.details.slice(0, 2)" :key="detail.id">
                                                    {{ detail.pupuk?.nama_pupuk }}<span v-if="idx < Math.min(item.details.length, 2) - 1">, </span>
                                                </span>
                                                <span v-if="item.details.length > 2" class="text-gray-400">...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else class="text-sm text-gray-400">-</div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div v-if="item.details && item.details.length > 0">
                                        <div class="text-sm font-semibold text-gray-900">{{ getTotalJumlah(item.details) }} kg</div>
                                        <div v-if="item.details.length > 1" class="text-xs text-gray-500 mt-0.5">({{ item.details.length }} item)</div>
                                    </div>
                                    <div v-else class="text-sm text-gray-400">-</div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div class="text-sm text-gray-900 whitespace-nowrap">{{ formatDate(item.tanggal_distribusi) }}</div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div class="flex flex-col space-y-1.5">
                                        <span :class="['px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full whitespace-nowrap', getStatusClass(item.status_distribusi)]">
                                            {{ getStatusLabel(item.status_distribusi) }}
                                        </span>
                                        <button
                                            v-if="item.status_logs && item.status_logs.length > 0"
                                            @click="showTimelineModal(item)"
                                            class="text-xs text-blue-600 hover:text-blue-800 hover:underline text-left"
                                        >
                                            Lihat Riwayat
                                        </button>
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-top">
                                    <div class="flex flex-col space-y-1.5">
                                        <button
                                            @click="showUpdateStatusModal(item)"
                                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline text-left"
                                            :disabled="item.status_distribusi === 'selesai' || item.status_distribusi === 'batal'"
                                            :class="{ 'opacity-50 cursor-not-allowed': item.status_distribusi === 'selesai' || item.status_distribusi === 'batal' }"
                                        >
                                            Update Status
                                        </button>
                                        <button
                                            @click="deleteDistribusi(item)"
                                            class="text-sm text-red-600 hover:text-red-800 hover:underline text-left"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="distribusi.data && distribusi.data.length === 0">
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data distribusi pupuk
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="distribusi.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ distribusi.from || 0 }} sampai {{ distribusi.to || 0 }} dari {{ distribusi.total || 0 }} data
                        </div>
                        <div class="flex space-x-1">
                            <button
                                @click="goToPage(distribusi.current_page - 1)"
                                :disabled="!distribusi.prev_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="distribusi.prev_page_url ? 'bg-white hover:bg-gray-50 text-gray-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            >
                                Sebelumnya
                            </button>
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="goToPage(page)"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="page === distribusi.current_page ? 'bg-green-50 text-green-700 border-green-500' : 'bg-white hover:bg-gray-50 text-gray-700'"
                            >
                                {{ page }}
                            </button>
                            <button
                                @click="goToPage(distribusi.current_page + 1)"
                                :disabled="!distribusi.next_page_url"
                                class="px-3 py-1 text-sm border rounded-md"
                                :class="distribusi.next_page_url ? 'bg-white hover:bg-gray-50 text-gray-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            >
                                Berikutnya
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Export Modal -->
        <div v-if="showPreviewModal" class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showPreviewModal = false"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Preview Data Export</h3>
                            <p class="text-sm text-gray-500 mt-1">Total: {{ (distribusi.data || distribusi).length }} data akan di-export</p>
                        </div>
                        <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nomor Distribusi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Desa Tujuan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pupuk</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Distribusi</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in (distribusi.data || distribusi)" :key="item.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.nomor_distribusi }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.desa?.nama_desa }} - {{ item.desa?.kecamatan }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <div v-if="item.details && item.details.length > 0">
                                                <div v-for="detail in item.details" :key="detail.id" class="mb-1">
                                                    {{ detail.pupuk?.nama_pupuk }}
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-400">-</div>
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <div v-if="item.details && item.details.length > 0">
                                                <div v-for="detail in item.details" :key="detail.id" class="mb-1 text-gray-900">
                                                    {{ detail.jumlah_distribusi }} kg
                                                </div>
                                                <div class="font-semibold text-gray-900 pt-1 border-t">
                                                    Total: {{ getTotalJumlah(item.details) }} kg
                                                </div>
                                            </div>
                                            <div v-else class="text-gray-400">-</div>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ formatDate(item.tanggal_distribusi) }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', getStatusClass(item.status_distribusi)]">
                                                {{ getStatusLabel(item.status_distribusi) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-2">
                        <button @click="showPreviewModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Tutup
                        </button>
                        <button @click="exportPdf" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </button>
                        <button @click="exportExcel" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Update Status -->
        <div v-if="showStatusModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeStatusModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Update Status Distribusi
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-4">
                                        {{ selectedDistribusi?.nomor_distribusi }}<br>
                                        <span class="text-xs">Desa: {{ selectedDistribusi?.desa?.nama_desa }}</span>
                                    </p>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini:</label>
                                        <span :class="['px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full', getStatusClass(selectedDistribusi?.status_distribusi)]">
                                            {{ getStatusLabel(selectedDistribusi?.status_distribusi) }}
                                        </span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Ke:</label>
                                        <select
                                            v-model="newStatus"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        >
                                            <option value="">-- Pilih Status Baru --</option>
                                            <option value="rencana" :disabled="!canChangeTo('rencana')">Rencana</option>
                                            <option value="dalam_perjalanan" :disabled="!canChangeTo('dalam_perjalanan')">Dalam Perjalanan</option>
                                            <option value="selesai" :disabled="!canChangeTo('selesai')">Selesai</option>
                                            <option value="batal" :disabled="!canChangeTo('batal')">Batal</option>
                                        </select>
                                        <p class="mt-2 text-xs text-gray-500">
                                            * Status hanya dapat berubah maju, tidak dapat dikembalikan
                                        </p>
                                    </div>

                                    <div v-if="statusUpdateError" class="mt-3 text-sm text-red-600">
                                        {{ statusUpdateError }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="updateStatus"
                            :disabled="!newStatus || updatingStatus"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ updatingStatus ? 'Memproses...' : 'Update Status' }}
                        </button>
                        <button
                            type="button"
                            @click="closeStatusModal"
                            :disabled="updatingStatus"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Timeline Status (Riwayat) -->
        <div v-if="showTimelineModalState" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeTimelineModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Riwayat Status Pengiriman
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        {{ timelineItem?.nomor_distribusi }}<br>
                                        <span class="text-xs">Desa: {{ timelineItem?.desa?.nama_desa }}</span>
                                    </p>

                                    <!-- Timeline -->
                                    <div class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            <li v-for="(log, idx) in timelineItem?.status_logs" :key="log.id">
                                                <div class="relative pb-8">
                                                    <span v-if="idx !== timelineItem.status_logs.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span :class="[
                                                                'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white',
                                                                log.status_baru === 'selesai' ? 'bg-green-500' :
                                                                log.status_baru === 'dalam_perjalanan' ? 'bg-yellow-500' :
                                                                log.status_baru === 'rencana' ? 'bg-gray-400' :
                                                                log.status_baru === 'batal' ? 'bg-red-500' : 'bg-gray-400'
                                                            ]">
                                                                <svg v-if="log.status_baru !== 'batal'" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                                </svg>
                                                                <svg v-else class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div>
                                                                <div class="text-sm">
                                                                    <span class="font-semibold text-gray-900">{{ getStatusLabel(log.status_baru) }}</span>
                                                                </div>
                                                                <p class="mt-0.5 text-xs text-gray-500">
                                                                    <svg class="inline h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    {{ formatDateTime(log.created_at) }}
                                                                </p>
                                                            </div>
                                                            <div class="mt-2 text-sm text-gray-700">
                                                                <p class="font-medium">{{ log.keterangan }}</p>
                                                                <p v-if="log.user" class="text-xs text-gray-500 mt-1">
                                                                    <svg class="inline h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                    </svg>
                                                                    {{ log.user.nama }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="closeTimelineModal"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    distribusi: Object,
    filters: Object
})

const filterMonth = ref(props.filters?.month || '')
const filterYear = ref(props.filters?.year || '')
const searchQuery = ref(props.filters?.search || '')
const perPageSelected = ref(props.filters?.per_page || 10)
const showPreviewModal = ref(false)

// Status modal state
const showStatusModal = ref(false)
const selectedDistribusi = ref(null)
const newStatus = ref('')
const updatingStatus = ref(false)
const statusUpdateError = ref('')

// Timeline modal state
const showTimelineModalState = ref(false)
const timelineItem = ref(null)

const deleteDistribusi = (item) => {
    if (confirm(`Yakin ingin menghapus distribusi ${item.nomor_distribusi}?`)) {
        router.delete(route('admin.distribusi-pupuk.destroy', item.id))
    }
}

const showUpdateStatusModal = (item) => {
    selectedDistribusi.value = item
    newStatus.value = ''
    statusUpdateError.value = ''
    showStatusModal.value = true
}

const closeStatusModal = () => {
    if (!updatingStatus.value) {
        showStatusModal.value = false
        selectedDistribusi.value = null
        newStatus.value = ''
        statusUpdateError.value = ''
    }
}

const showTimelineModal = (item) => {
    timelineItem.value = item
    showTimelineModalState.value = true
}

const closeTimelineModal = () => {
    showTimelineModalState.value = false
    timelineItem.value = null
}

const formatDateTime = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }) + ' WIB'
}

const canChangeTo = (targetStatus) => {
    if (!selectedDistribusi.value) return false

    const currentStatus = selectedDistribusi.value.status_distribusi
    const statusOrder = { 'rencana': 1, 'dalam_perjalanan': 2, 'selesai': 3, 'batal': 0 }

    // Tidak bisa ubah status jika sudah selesai atau batal
    if (currentStatus === 'selesai' || currentStatus === 'batal') {
        return false
    }

    // Batal selalu bisa dipilih (kecuali sudah selesai/batal)
    if (targetStatus === 'batal') {
        return true
    }

    // Status hanya bisa maju, tidak bisa mundur
    return statusOrder[targetStatus] > statusOrder[currentStatus]
}

const updateStatus = () => {
    if (!newStatus.value || !selectedDistribusi.value || updatingStatus.value) return

    updatingStatus.value = true
    statusUpdateError.value = ''

    router.patch(
        route('admin.distribusi-pupuk.update-status', selectedDistribusi.value.id),
        { status_distribusi: newStatus.value },
        {
            preserveScroll: true,
            onSuccess: () => {
                showStatusModal.value = false
                selectedDistribusi.value = null
                newStatus.value = ''
                statusUpdateError.value = ''
                router.reload({ only: ['distribusi'] })
            },
            onError: (errors) => {
                statusUpdateError.value = errors.status_distribusi || 'Terjadi kesalahan saat update status'
            },
            onFinish: () => {
                updatingStatus.value = false
            }
        }
    )
}

const applyFilter = () => {
    updateUrl({
        month: filterMonth.value,
        year: filterYear.value,
        search: searchQuery.value,
        per_page: perPageSelected.value,
        page: 1
    })
}

const resetFilter = () => {
    filterMonth.value = ''
    filterYear.value = ''
    updateUrl({
        month: '',
        year: '',
        search: searchQuery.value,
        per_page: perPageSelected.value,
        page: 1
    })
}

// Search and pagination functions
const debounce = (func, wait) => {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
};

const debouncedSearch = debounce(() => {
    updateUrl({ search: searchQuery.value, page: 1 });
}, 300);

const changePerPage = () => {
    updateUrl({ per_page: perPageSelected.value, page: 1 });
};

const goToPage = (page) => {
    if (page >= 1 && page <= props.distribusi.last_page) {
        updateUrl({ page });
    }
};

const updateUrl = (params) => {
    const currentParams = new URLSearchParams(window.location.search);

    Object.entries(params).forEach(([key, value]) => {
        if (value) {
            currentParams.set(key, value);
        } else {
            currentParams.delete(key);
        }
    });

    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {
        preserveState: true,
        preserveScroll: true
    });
};

// Pagination computed
const visiblePages = computed(() => {
    const current = props.distribusi.current_page;
    const last = props.distribusi.last_page;
    const pages = [];

    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

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

const exportPdf = () => {
    const params = new URLSearchParams();
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.location.href = route('admin.distribusi-pupuk.export.pdf') + (params.toString() ? '?' + params.toString() : '');
    showPreviewModal.value = false;
};

const exportExcel = () => {
    const params = new URLSearchParams();
    if (filterMonth.value) params.append('month', filterMonth.value);
    if (filterYear.value) params.append('year', filterYear.value);
    window.location.href = route('admin.distribusi-pupuk.export.excel') + (params.toString() ? '?' + params.toString() : '');
    showPreviewModal.value = false;
};

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

const getTotalJumlah = (details) => {
    if (!details || details.length === 0) return 0
    return details.reduce((total, detail) => total + (detail.jumlah_distribusi || 0), 0)
}

</script>
