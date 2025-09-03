<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    recentUsers: {
        type: Array,
        default: () => []
    },
    lowStock: {
        type: Array,
        default: () => []
    },
    myStocks: {
        type: Array,
        default: () => []
    },
    myTransactions: {
        type: Array,
        default: () => []
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <DashboardLayout>
        <div class="space-y-6">
            <!-- Welcome Section -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Selamat datang, {{ user?.nama || 'User' }}!
                </h2>
                <p class="text-gray-600">
                    Sistem Inventaris Pupuk - Dinas Pertanian Garut
                </p>
            </div>

            <!-- Stats Cards -->
            <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ user?.role === 'admin' ? 'Total Users' : 'Total Stok' }}
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ user?.role === 'admin' ? (stats?.total_users || 0) : (stats?.total_stok || 0) }}
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
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ user?.role === 'admin' ? 'Pending Users' : 'Jenis Pupuk' }}
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ user?.role === 'admin' ? (stats?.pending_users || 0) : (stats?.jenis_pupuk || 0) }}
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
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.24 12.24a6 6 0 00-8.49-8.49L5 10.5V19h8.5z M16 8L2 22 M17.5 15H9" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ user?.role === 'admin' ? 'Total Pupuk' : 'Total Transaksi' }}
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ user?.role === 'admin' ? (stats?.total_pupuk || 0) : (stats?.total_transaksi || 0) }}
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
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ user?.role === 'admin' ? 'Total Stok' : 'Stok Rendah' }}
                                    </dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ user?.role === 'admin' ? (stats?.total_stok || 0) : (stats?.stok_rendah || 0) }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Specific Content -->
            <div v-if="user?.role === 'admin'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Pending Users -->
                <div v-if="recentUsers" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            User Menunggu Persetujuan
                        </h3>
                        <div class="space-y-3">
                            <div v-for="user in recentUsers" :key="user.id" class="flex items-center justify-between py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ user.nama }}</p>
                                    <p class="text-xs text-gray-500">{{ user.email }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div v-if="lowStock" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Stok Rendah
                        </h3>
                        <div class="space-y-3">
                            <div v-for="stock in lowStock" :key="stock.id" class="flex items-center justify-between py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ stock.pupuk?.nama_pupuk || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ stock.pengguna?.nama || 'Unknown' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ stock.jumlah_stok }} tersisa
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distributor Specific Content -->
            <div v-if="user?.role === 'distributor'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- My Stocks -->
                <div v-if="myStocks" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Stok Pupuk Saya
                        </h3>
                        <div class="space-y-3">
                            <div v-for="stock in myStocks" :key="stock.id" class="flex items-center justify-between py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ stock.pupuk?.nama_pupuk || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ stock.pupuk?.kategori?.nama_kategori || 'Unknown' }}</p>
                                </div>
                                <span :class="[
                                    stock.jumlah_stok < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800',
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                                ]">
                                    {{ stock.jumlah_stok }} kg
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div v-if="myTransactions" class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Transaksi Terbaru
                        </h3>
                        <div class="space-y-3">
                            <div v-for="transaction in myTransactions" :key="transaction.id" class="flex items-center justify-between py-2 border-b border-gray-200">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ transaction.pupuk?.nama_pupuk || 'Unknown' }}</p>
                                    <p class="text-xs text-gray-500">{{ new Date(transaction.tanggal_distribusi).toLocaleDateString() }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ transaction.jumlah_distribusi }} kg
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
