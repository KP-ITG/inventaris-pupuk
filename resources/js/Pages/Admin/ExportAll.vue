<template>
    <DashboardLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Card -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8">
                        <h1 class="text-3xl font-bold text-white">Export Semua Data</h1>
                        <p class="mt-2 text-green-100">Export seluruh data inventaris pupuk dalam satu file</p>
                    </div>
                </div>

                <!-- Export Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- PDF Export Card -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-red-100 rounded-lg mb-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Export ke PDF</h3>
                            <p class="text-gray-600 mb-4">Download semua data dalam format PDF yang siap untuk dicetak</p>
                            <button
                                @click="showPreviewModal('pdf')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview & Export PDF
                            </button>
                        </div>
                    </div>

                    <!-- Excel Export Card -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-lg mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Export ke Excel</h3>
                            <p class="text-gray-600 mb-4">Download semua data dalam format Excel untuk analisis lebih lanjut</p>
                            <button
                                @click="showPreviewModal('excel')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Preview & Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Periode -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Periode Waktu</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Bulan:</label>
                            <select
                                v-model="filterMonth"
                                class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-2 focus:ring-green-500 focus:border-green-500 min-w-[140px]"
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
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Tahun:</label>
                            <select
                                v-model="filterYear"
                                class="border border-gray-300 rounded-md text-sm pl-3 pr-8 py-2 focus:ring-green-500 focus:border-green-500 min-w-[120px]"
                            >
                                <option value="">Semua Tahun</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                                <option value="2027">2027</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="applyFilter"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors duration-200"
                            >
                                Terapkan Filter
                            </button>
                            <button
                                v-if="filterMonth || filterYear"
                                @click="clearFilter"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition-colors duration-200"
                            >
                                Reset
                            </button>
                        </div>
                    </div>
                    <div v-if="filterMonth || filterYear" class="mt-3 text-sm text-gray-600">
                        <span class="font-medium">Filter Aktif:</span>
                        <span v-if="filterMonth" class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded">
                            {{ getMonthName(filterMonth) }}
                        </span>
                        <span v-if="filterYear" class="ml-2 bg-green-100 text-green-800 px-2 py-1 rounded">
                            {{ filterYear }}
                        </span>
                    </div>
                </div>

                <!-- Data Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Data</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ summary.kategori }}</div>
                            <div class="text-sm text-gray-600 mt-1">Kategori</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ summary.nutrisi }}</div>
                            <div class="text-sm text-gray-600 mt-1">Nutrisi</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ summary.pupuk }}</div>
                            <div class="text-sm text-gray-600 mt-1">Pupuk</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">{{ summary.desa }}</div>
                            <div class="text-sm text-gray-600 mt-1">Desa</div>
                        </div>
                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <div class="text-2xl font-bold text-indigo-600">{{ summary.stok }}</div>
                            <div class="text-sm text-gray-600 mt-1">Stok</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ summary.distribusi }}</div>
                            <div class="text-sm text-gray-600 mt-1">Distribusi</div>
                        </div>
                    </div>
                </div>

                <!-- Preview Modal -->
                <div v-if="previewModal.show" class="fixed z-50 inset-0 overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closePreview"></div>

                        <div class="relative bg-white rounded-lg shadow-xl max-w-7xl w-full max-h-[90vh] overflow-hidden">
                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Preview Data Export</h3>
                                    <p class="text-sm text-gray-500 mt-1">Total: {{ getTotalData() }} data akan di-export</p>
                                </div>
                                <button @click="closePreview" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Preview Content -->
                            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
                                <div class="space-y-6">
                                    <!-- Kategori Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.kategori }}</span>
                                            Kategori Pupuk
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.kategori" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.nama_kategori }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-500">{{ item.deskripsi || '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Nutrisi Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.nutrisi }}</span>
                                            Nutrisi
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Simbol</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.nutrisi" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.nama_nutrisi }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ item.simbol_nutrisi }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-500">{{ item.deskripsi_nutrisi || '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Desa Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.desa }}</span>
                                            Data Desa
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Desa</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Luas</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Penduduk</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.desa" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.nama_desa }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.kecamatan }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.luas_wilayah }} ha</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.jumlah_penduduk }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pupuk Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.pupuk }}</span>
                                            Data Pupuk
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pupuk</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.pupuk" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.nama_pupuk }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.kode_pupuk }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.kategori?.nama_kategori || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.supplier?.nama_supplier || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">Rp {{ formatNumber(item.harga) }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ formatNumber(item.stok_tersedia) }} kg</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Stok Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.stok }}</span>
                                            Data Stok
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama Pupuk</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Stok</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Min/Max</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.stok" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.pupuk?.nama_pupuk || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.pupuk?.kode_pupuk || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ formatNumber(item.jumlah_stok) }} kg</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.stok_minimum }} / {{ item.stok_maksimum }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.lokasi_gudang || '-' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Distribusi Section -->
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm mr-2">{{ summary.distribusi }}</span>
                                            Data Distribusi Pupuk
                                        </h4>
                                        <div class="overflow-x-auto border rounded-lg">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nomor Distribusi</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Desa</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Pupuk</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <tr v-for="(item, index) in data.distribusi" :key="item.id">
                                                        <td class="px-4 py-2 text-sm text-gray-900">{{ index + 1 }}</td>
                                                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ item.nomor_distribusi }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.desa?.nama_desa || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.desa?.kecamatan || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ item.pupuk?.nama_pupuk || '-' }}</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ formatNumber(item.jumlah_distribusi) }} kg</td>
                                                        <td class="px-4 py-2 text-sm text-gray-600">{{ formatDate(item.tanggal_distribusi) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-2">
                                <button
                                    @click="closePreview"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    Tutup
                                </button>
                                <button
                                    @click="executeExport"
                                    :class="[
                                        previewModal.type === 'pdf' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700',
                                        'px-4 py-2 text-sm font-medium text-white rounded-md flex items-center'
                                    ]"
                                >
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export {{ previewModal.type === 'pdf' ? 'PDF' : 'Excel' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps({
    summary: Object,
    data: Object,
    filters: Object
})

const previewModal = reactive({
    show: false,
    type: null
})

const filterMonth = ref(props.filters?.month || '')
const filterYear = ref(props.filters?.year || '')

const showPreviewModal = (type) => {
    previewModal.type = type
    previewModal.show = true
}

const closePreview = () => {
    previewModal.show = false
    previewModal.type = null
}

const getTotalData = () => {
    return props.summary.kategori + props.summary.nutrisi + props.summary.pupuk +
           props.summary.desa + props.summary.stok + props.summary.distribusi
}

const executeExport = () => {
    const params = new URLSearchParams()
    if (filterMonth.value) params.append('month', filterMonth.value)
    if (filterYear.value) params.append('year', filterYear.value)

    const queryString = params.toString()

    if (previewModal.type === 'pdf') {
        window.open(`/admin/export-all/pdf${queryString ? '?' + queryString : ''}`, '_blank')
    } else {
        window.location.href = `/admin/export-all/excel${queryString ? '?' + queryString : ''}`
    }
    closePreview()
}

const applyFilter = () => {
    router.get('/admin/export-all', {
        month: filterMonth.value,
        year: filterYear.value
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

const clearFilter = () => {
    filterMonth.value = ''
    filterYear.value = ''
    router.get('/admin/export-all', {}, {
        preserveState: true,
        preserveScroll: true
    })
}

const getMonthName = (month) => {
    const months = {
        '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
        '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
        '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
    }
    return months[month] || month
}

const formatNumber = (number) => {
    return new Intl.NumberFormat('id-ID').format(number)
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}
</script>
