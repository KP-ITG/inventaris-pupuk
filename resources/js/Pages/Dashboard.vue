<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    user: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    statusStats: {
        type: Object,
        default: () => ({})
    },
    lowStock: {
        type: Array,
        default: () => []
    },
    recentDistribusi: {
        type: Array,
        default: () => []
    },
    topDesa: {
        type: Array,
        default: () => []
    },
});

const getStatusColor = (status) => {
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

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <div class="space-y-6">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 shadow rounded-lg p-6 text-white">
                <h2 class="text-2xl font-bold mb-2">
                    Selamat datang, {{ user?.nama || 'Admin' }}!
                </h2>
                <p class="text-green-100">
                    Sistem Inventaris Pupuk - Dinas Pertanian Garut
                </p>
                <p class="text-sm text-green-200 mt-2">
                    Dashboard untuk mengelola distribusi pupuk ke desa-desa
                </p>
            </div>

            <!-- Main Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded-full bg-blue-100">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c0 .621-.504 1.125-1.125 1.125H11.25a9 9 0 0 1-9-9V3.375c0-.621.504-1.125 1.125-1.125Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Jenis Pupuk
                                    </dt>
                                    <dd class="text-2xl font-bold text-gray-900">
                                        {{ stats?.total_pupuk || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded-full bg-green-100">
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Stok
                                    </dt>
                                    <dd class="text-2xl font-bold text-gray-900">
                                        {{ (stats?.total_stok || 0).toLocaleString() }} kg
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded-full bg-purple-100">
                                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 1-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m6 4.5V12a2.25 2.25 0 0 0-2.25-2.25H3" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Distribusi
                                    </dt>
                                    <dd class="text-2xl font-bold text-gray-900">
                                        {{ stats?.total_distribusi || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded-full bg-yellow-100">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Desa Terdaftar
                                    </dt>
                                    <dd class="text-2xl font-bold text-gray-900">
                                        {{ stats?.total_desa || 0 }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Status Distribusi Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rencana</p>
                                <p class="text-2xl font-bold text-gray-900">{{ statusStats?.rencana || 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Dalam Perjalanan</p>
                                <p class="text-2xl font-bold text-gray-900">{{ statusStats?.dalam_perjalanan || 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Selesai</p>
                                <p class="text-2xl font-bold text-gray-900">{{ statusStats?.selesai || 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Batal</p>
                                <p class="text-2xl font-bold text-gray-900">{{ statusStats?.batal || 0 }}</p>
                            </div>
                            <div class="p-3 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Stok Rendah Alert -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Stok Rendah
                            </h3>
                            <Link
                                :href="route('admin.stok.index')"
                                class="text-sm text-green-600 hover:text-green-900"
                            >
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="space-y-3">
                            <div v-for="stock in lowStock" :key="stock.id" class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ stock.pupuk?.nama_pupuk || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ stock.pupuk?.kategori?.nama_kategori || 'Unknown' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ stock.jumlah_stok }} kg
                                </span>
                            </div>
                            <div v-if="lowStock.length === 0" class="text-center py-4">
                                <p class="text-sm text-gray-500">Semua stok dalam kondisi baik</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Terbaru -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Distribusi Terbaru
                            </h3>
                            <Link
                                :href="route('admin.distribusi-pupuk.index')"
                                class="text-sm text-green-600 hover:text-green-900"
                            >
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="space-y-3">
                            <div v-for="distribusi in recentDistribusi" :key="distribusi.id" class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ distribusi.pupuk?.nama_pupuk || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ distribusi.desa?.nama_desa || 'Unknown' }} - {{ distribusi.jumlah_distribusi }} kg</p>
                                </div>
                                <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getStatusColor(distribusi.status_distribusi)]">
                                    {{ getStatusLabel(distribusi.status_distribusi) }}
                                </span>
                            </div>
                            <div v-if="recentDistribusi.length === 0" class="text-center py-4">
                                <p class="text-sm text-gray-500">Belum ada distribusi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Desa -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Top Desa Distribusi
                            </h3>
                            <Link
                                :href="route('admin.desa.index')"
                                class="text-sm text-green-600 hover:text-green-900"
                            >
                                Lihat Semua
                            </Link>
                        </div>
                        <div class="space-y-3">
                            <div v-for="desa in topDesa" :key="desa.desa_id" class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ desa.desa?.nama_desa || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ desa.total_distribusi }} distribusi</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ desa.total_pupuk }} kg
                                </span>
                            </div>
                            <div v-if="topDesa.length === 0" class="text-center py-4">
                                <p class="text-sm text-gray-500">Belum ada data distribusi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Aksi Cepat
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            :href="route('admin.distribusi-pupuk.create')"
                            class="flex items-center justify-center px-4 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Distribusi
                        </Link>

                        <Link
                            :href="route('admin.stok.index')"
                            class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Kelola Stok
                        </Link>

                        <Link
                            :href="route('admin.pupuk.index')"
                            class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c0 .621-.504 1.125-1.125 1.125H11.25a9 9 0 0 1-9-9V3.375c0-.621.504-1.125 1.125-1.125Z" />
                            </svg>
                            Data Pupuk
                        </Link>

                        <Link
                            :href="route('admin.desa.index')"
                            class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Data Desa
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
