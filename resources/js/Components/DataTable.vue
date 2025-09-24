<template><template><template><template><template><template>

    <div class="bg-white rounded-lg shadow">

        <!-- Header with Title and Actions -->    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200">

            <div class="flex justify-between items-center">        <!-- Header -->    <div class="bg-white rounded-lg shadow">

                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                <div class="flex items-center space-x-2">        <div class="px-6 py-4 border-b border-gray-200">

                    <button

                        v-if="exportPdfUrl"            <div class="flex justify-between items-center">        <!-- Header -->    <div class="bg-white rounded-lg shadow">

                        @click="exportPdf"

                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium"                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                    >

                        PDF                <div class="flex items-center space-x-2">        <div class="px-6 py-4 border-b border-gray-200">

                    </button>

                    <button                     <button

                        v-if="exportExcelUrl"

                        @click="exportExcel"                         v-if="exportPdfUrl"            <div class="flex justify-between items-center">        <!-- Header with Title and Actions -->    <div class="bg-white rounded-lg shadow">    <div class="bg-white rounded-lg shadow">

                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium"

                    >                        @click="exportPdf"

                        Excel

                    </button>                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium"                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                    <slot name="actions"></slot>

                </div>                    >

            </div>

        </div>                        PDF                <div class="flex items-center space-x-2">        <div class="px-6 py-4 border-b border-gray-200">



        <!-- Search and Filter Controls -->                    </button>

        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">

            <div class="flex justify-between items-center">                    <button                     <button

                <div class="flex-1 max-w-md">

                    <input                        v-if="exportExcelUrl"

                        v-model="searchQuery"

                        @input="handleSearch"                        @click="exportExcel"                         v-if="exportPdfUrl"            <div class="flex justify-between items-center">        <!-- Header with Title and Actions -->        <!-- Header with Title and Actions -->

                        type="text"

                        :placeholder="searchPlaceholder"                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium"

                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"

                    />                    >                        @click="exportPdf"

                </div>

                <div class="flex items-center space-x-2 ml-4">                        Excel

                    <span class="text-sm text-gray-700">Tampilkan:</span>

                    <select                     </button>                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium"                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                        v-model="perPageSelected"

                        @change="handlePerPageChange"                    <slot name="actions"></slot>

                        class="border border-gray-300 rounded-md text-sm px-2 py-1"

                    >                </div>                    >

                        <option v-for="option in perPageOptions" :key="option" :value="option">

                            {{ option }}            </div>

                        </option>

                    </select>        </div>                        PDF                <div class="flex items-center space-x-2">        <div class="px-6 py-4 border-b border-gray-200">        <div class="px-6 py-4 border-b border-gray-200">

                    <span class="text-sm text-gray-700">data</span>

                </div>

            </div>

        </div>        <!-- Search and Filters -->                    </button>



        <!-- Flash Messages -->        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">

        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">

            <p class="text-green-700">{{ $page.props.flash.success }}</p>            <div class="flex justify-between items-center">                    <button                     <!-- Export Buttons -->

        </div>

        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">                <div class="flex-1 max-w-md">

            <p class="text-red-700">{{ $page.props.flash.error }}</p>

        </div>                    <input                        v-if="exportExcelUrl"



        <!-- Table -->                        v-model="searchQuery"

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">                        @input="debouncedSearch"                        @click="exportExcel"                     <button             <div class="flex justify-between items-center">            <div class="flex justify-between items-center">

                <thead class="bg-gray-50">

                    <tr>                        type="text"

                        <th

                            v-for="column in columns"                         :placeholder="searchPlaceholder"                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium"

                            :key="column.key"

                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"

                        >

                            {{ column.label }}                    />                    >                        v-if="exportPdfUrl"

                        </th>

                    </tr>                </div>

                </thead>

                <tbody class="bg-white divide-y divide-gray-200">                <div class="flex items-center space-x-2 ml-4">                        Excel

                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">

                        <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">                    <span class="text-sm text-gray-700">Tampilkan:</span>

                            <slot :name="`cell-${column.key}`" :item="item" :value="getColumnValue(item, column.key)">

                                <span :class="column.class || 'text-gray-500'">                    <select                     </button>                        @click="exportPdf"                 <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>                <h2 class="text-lg font-medium text-gray-900">{{ title }}</h2>

                                    {{ getColumnValue(item, column.key) || column.defaultValue || '-' }}

                                </span>                        v-model="perPageSelected"

                            </slot>

                        </td>                        @change="changePerPage"                    <slot name="actions"></slot>

                    </tr>

                    <tr v-if="!data.data || data.data.length === 0">                        class="border border-gray-300 rounded-md text-sm px-2 py-1"

                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">

                            {{ emptyMessage }}                    >                </div>                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"

                        </td>

                    </tr>                        <option v-for="option in perPageOptions" :key="option" :value="option">

                </tbody>

            </table>                            {{ option }}            </div>

        </div>

                        </option>

        <!-- Pagination -->

        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">                    </select>        </div>                    >                <div class="flex items-center space-x-2">                <div class="flex items-center space-x-2">

            <div class="flex items-center justify-between">

                <div class="text-sm text-gray-700">                    <span class="text-sm text-gray-700">data</span>

                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data

                </div>                </div>

                <div class="flex space-x-1">

                    <button            </div>

                        @click="goToPage(data.current_page - 1)"

                        :disabled="!data.prev_page_url"        </div>        <!-- Search and Filters -->                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        class="px-3 py-1 text-sm border rounded-md"

                        :class="data.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

                    >

                        Prev        <!-- Flash Messages -->        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">

                    </button>

                    <button        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">

                        v-for="page in visiblePages"

                        :key="page"            <p class="text-green-700">{{ $page.props.flash.success }}</p>            <div class="flex justify-between items-center">                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>                    <!-- Export Buttons -->                    <!-- Export Buttons -->

                        @click="goToPage(page)"

                        class="px-3 py-1 text-sm border rounded-md"        </div>

                        :class="page === data.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"

                    >        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">                <div class="flex-1 max-w-md">

                        {{ page }}

                    </button>            <p class="text-red-700">{{ $page.props.flash.error }}</p>

                    <button

                        @click="goToPage(data.current_page + 1)"        </div>                    <input                        </svg>

                        :disabled="!data.next_page_url"

                        class="px-3 py-1 text-sm border rounded-md"

                        :class="data.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

                    >        <!-- Table -->                        v-model="searchQuery"

                        Next

                    </button>        <div class="overflow-x-auto">

                </div>

            </div>            <table class="min-w-full divide-y divide-gray-200">                        @input="debouncedSearch"                        PDF                    <button                     <button

        </div>

    </div>                <thead class="bg-gray-50">

</template>

                    <tr>                        type="text"

<script setup>

import { ref, computed, watch } from 'vue'                        <th

import { router } from '@inertiajs/vue3'

                            v-for="column in columns"                         :placeholder="searchPlaceholder"                    </button>

const props = defineProps({

    title: String,                            :key="column.key"

    data: Object,

    columns: Array,                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm"

    searchPlaceholder: { type: String, default: 'Cari data...' },

    emptyMessage: { type: String, default: 'Tidak ada data' },                        >

    perPageOptions: { type: Array, default: () => [10, 25, 50, 100] },

    exportPdfUrl: String,                            {{ column.label }}                    />                    <button                         v-if="exportPdfUrl"                        v-if="exportPdfUrl"

    exportExcelUrl: String,

    filters: { type: Object, default: () => ({}) }                        </th>

})

                    </tr>                </div>

const searchQuery = ref(props.filters.search || '')

const perPageSelected = ref(props.filters.per_page || 10)                </thead>



const visiblePages = computed(() => {                <tbody class="bg-white divide-y divide-gray-200">                <div class="flex items-center space-x-2 ml-4">                        v-if="exportExcelUrl"

    const current = props.data.current_page

    const last = props.data.last_page                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">

    const pages = []

                            <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">                    <span class="text-sm text-gray-700">Tampilkan:</span>

    const start = Math.max(1, current - 2)

    const end = Math.min(last, current + 2)                            <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">



    for (let i = start; i <= end; i++) {                                <span :class="column.class || 'text-gray-500'">                    <select                         @click="exportExcel"                         @click="exportPdf"                         @click="exportPdf"

        pages.push(i)

    }                                    {{ item[column.key] || column.defaultValue || '-' }}



    return pages                                </span>                        v-model="perPageSelected"

})

                            </slot>

const getColumnValue = (item, key) => {

    return key.split('.').reduce((obj, k) => obj?.[k], item)                        </td>                        @change="changePerPage"                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"

}

                    </tr>

let searchTimeout

const handleSearch = () => {                    <tr v-if="data.data && data.data.length === 0">                        class="border border-gray-300 rounded-md text-sm px-2 py-1"

    clearTimeout(searchTimeout)

    searchTimeout = setTimeout(() => {                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">

        updateUrl({ search: searchQuery.value, page: 1 })

    }, 300)                            {{ emptyMessage }}                    >                    >                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"

}

                        </td>

const handlePerPageChange = () => {

    updateUrl({ per_page: perPageSelected.value, page: 1 })                    </tr>                        <option v-for="option in perPageOptions" :key="option" :value="option">

}

                </tbody>

const goToPage = (page) => {

    if (page >= 1 && page <= props.data.last_page) {            </table>                            {{ option }}                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        updateUrl({ page })

    }        </div>

}

                        </option>

const updateUrl = (params) => {

    const currentParams = new URLSearchParams(window.location.search)        <!-- Pagination -->



    Object.entries(params).forEach(([key, value]) => {        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">                    </select>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>                    >                    >

        if (value) {

            currentParams.set(key, value)            <div class="flex items-center justify-between">

        } else {

            currentParams.delete(key)                <div class="text-sm text-gray-700">                    <span class="text-sm text-gray-700">data</span>

        }

    })                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data



    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {                </div>                </div>                        </svg>

        preserveState: true,

        preserveScroll: true                <div class="flex space-x-1">

    })

}                    <button            </div>



const exportPdf = () => {                        @click="goToPage(data.current_page - 1)"

    if (props.exportPdfUrl) {

        window.open(`${props.exportPdfUrl}?search=${searchQuery.value}`, '_blank')                        :disabled="!data.prev_page_url"        </div>                        Excel                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">

    }

}                        class="px-3 py-1 text-sm border rounded-md"



const exportExcel = () => {                        :class="data.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

    if (props.exportExcelUrl) {

        window.open(`${props.exportExcelUrl}?search=${searchQuery.value}`, '_blank')                    >

    }

}                        Prev        <!-- Flash Messages -->                    </button>



watch(() => props.filters, (newFilters) => {                    </button>

    searchQuery.value = newFilters.search || ''

    perPageSelected.value = newFilters.per_page || 10                    <button        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">

}, { immediate: true })

</script>                        v-for="page in visiblePages"

                        :key="page"            <p class="text-green-700">{{ $page.props.flash.success }}</p>                    <slot name="actions"></slot>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

                        @click="goToPage(page)"

                        class="px-3 py-1 text-sm border rounded-md"        </div>

                        :class="page === data.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"

                    >        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">                </div>

                        {{ page }}

                    </button>            <p class="text-red-700">{{ $page.props.flash.error }}</p>

                    <button

                        @click="goToPage(data.current_page + 1)"        </div>            </div>                        </svg>                        </svg>

                        :disabled="!data.next_page_url"

                        class="px-3 py-1 text-sm border rounded-md"

                        :class="data.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

                    >        <!-- Table -->        </div>

                        Next

                    </button>        <div class="overflow-x-auto">

                </div>

            </div>            <table class="min-w-full divide-y divide-gray-200">                        PDF                        PDF

        </div>

    </div>                <thead class="bg-gray-50">

</template>

                    <tr>        <!-- Search and Filters -->

<script setup>

import { ref, computed, watch } from 'vue'                        <th

import { router } from '@inertiajs/vue3'

                            v-for="column in columns"         <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">                    </button>                    </button>

const props = defineProps({

    title: String,                            :key="column.key"

    data: Object,

    columns: Array,                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">

    searchPlaceholder: { type: String, default: 'Cari data...' },

    emptyMessage: { type: String, default: 'Tidak ada data' },                        >

    perPageOptions: { type: Array, default: () => [10, 25, 50, 100] },

    exportPdfUrl: String,                            {{ column.label }}                <!-- Search Input -->                    <button                     <button

    exportExcelUrl: String,

    filters: { type: Object, default: () => ({}) }                        </th>

})

                    </tr>                <div class="flex-1 max-w-md">

const searchQuery = ref(props.filters.search || '')

const perPageSelected = ref(props.filters.per_page || 10)                </thead>



const visiblePages = computed(() => {                <tbody class="bg-white divide-y divide-gray-200">                    <div class="relative">                        v-if="exportExcelUrl"                        v-if="exportExcelUrl"

    const current = props.data.current_page

    const last = props.data.last_page                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">

    const pages = []

                            <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

    const start = Math.max(1, current - 2)

    const end = Math.min(last, current + 2)                            <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">



    for (let i = start; i <= end; i++) {                                <span :class="column.class || 'text-gray-500'">                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        @click="exportExcel"                         @click="exportExcel"

        pages.push(i)

    }                                    {{ item[column.key] || column.defaultValue || '-' }}



    return pages                                </span>                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>

})

                            </slot>

const debounce = (func, wait) => {

    let timeout                        </td>                            </svg>                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium flex items-center"

    return (...args) => {

        clearTimeout(timeout)                    </tr>

        timeout = setTimeout(() => func(...args), wait)

    }                    <tr v-if="data.data && data.data.length === 0">                        </div>

}

                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">

const debouncedSearch = debounce(() => {

    updateUrl({ search: searchQuery.value, page: 1 })                            {{ emptyMessage }}                        <input                    >                    >

}, 300)

                        </td>

const changePerPage = () => {

    updateUrl({ per_page: perPageSelected.value, page: 1 })                    </tr>                            v-model="searchQuery"

}

                </tbody>

const goToPage = (page) => {

    if (page >= 1 && page <= props.data.last_page) {            </table>                            @input="debouncedSearch"                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        updateUrl({ page })

    }        </div>

}

                            type="text"

const updateUrl = (params) => {

    const currentParams = new URLSearchParams(window.location.search)        <!-- Pagination -->



    Object.entries(params).forEach(([key, value]) => {        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">                            :placeholder="searchPlaceholder"                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>

        if (value) {

            currentParams.set(key, value)            <div class="flex items-center justify-between">

        } else {

            currentParams.delete(key)                <div class="text-sm text-gray-700">                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 text-sm"

        }

    })                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data



    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {                </div>                        />                        </svg>                        </svg>

        preserveState: true,

        preserveScroll: true                <div class="flex space-x-1">

    })

}                    <button                    </div>



const exportPdf = () => {                        @click="goToPage(data.current_page - 1)"

    if (props.exportPdfUrl) {

        window.open(`${props.exportPdfUrl}?search=${searchQuery.value}`, '_blank')                        :disabled="!data.prev_page_url"                </div>                        Excel                        CSV

    }

}                        class="px-3 py-1 text-sm border rounded-md"



const exportExcel = () => {                        :class="data.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

    if (props.exportExcelUrl) {

        window.open(`${props.exportExcelUrl}?search=${searchQuery.value}`, '_blank')                    >

    }

}                        Prev                <!-- Per Page Selector -->                    </button>                    </button>



watch(() => props.filters, (newFilters) => {                    </button>

    searchQuery.value = newFilters.search || ''

    perPageSelected.value = newFilters.per_page || 10                    <button                <div class="flex items-center space-x-2">

}, { immediate: true })

</script>                        v-for="page in visiblePages"

                        :key="page"                    <span class="text-sm text-gray-700">Tampilkan:</span>                    <slot name="actions"></slot>                    <slot name="actions"></slot>

                        @click="goToPage(page)"

                        class="px-3 py-1 text-sm border rounded-md"                    <select

                        :class="page === data.current_page ? 'bg-green-50 border-green-500 text-green-600' : 'hover:bg-gray-50'"

                    >                        v-model="perPageSelected"                </div>                </div>

                        {{ page }}

                    </button>                        @change="changePerPage"

                    <button

                        @click="goToPage(data.current_page + 1)"                        class="border border-gray-300 rounded-md text-sm px-2 py-1 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"            </div>            </div>

                        :disabled="!data.next_page_url"

                        class="px-3 py-1 text-sm border rounded-md"                    >

                        :class="data.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'"

                    >                        <option v-for="option in perPageOptions" :key="option" :value="option">        </div>        </div>

                        Next

                    </button>                            {{ option }}

                </div>

            </div>                        </option>

        </div>

    </div>                    </select>

</template>

                    <span class="text-sm text-gray-700">data</span>        <!-- Search and Filters -->        <!-- Search and Filters -->

<script setup>

import { ref, computed, watch } from 'vue'                </div>

import { router } from '@inertiajs/vue3'

            </div>        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">

const props = defineProps({

    title: String,        </div>

    data: Object,

    columns: Array,            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">

    searchPlaceholder: { type: String, default: 'Cari data...' },

    emptyMessage: { type: String, default: 'Tidak ada data' },        <!-- Success/Error Messages -->

    perPageOptions: { type: Array, default: () => [10, 25, 50, 100] },

    exportPdfUrl: String,        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">                <!-- Search Input -->                <!-- Search Input -->

    exportExcelUrl: String,

    filters: { type: Object, default: () => ({}) }            <p class="text-green-700">{{ $page.props.flash.success }}</p>

})

        </div>                <div class="flex-1 max-w-md">                <div class="flex-1 max-w-md">

const searchQuery = ref(props.filters.search || '')

const perPageSelected = ref(props.filters.per_page || 10)        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">



const visiblePages = computed(() => {            <p class="text-red-700">{{ $page.props.flash.error }}</p>                    <div class="relative">                    <div class="relative">

    const current = props.data.current_page

    const last = props.data.last_page        </div>

    const pages = []

                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

    const start = Math.max(1, current - 2)

    const end = Math.min(last, current + 2)        <!-- Loading State -->



    for (let i = start; i <= end; i++) {        <div v-if="loading" class="px-6 py-8 text-center">                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        pages.push(i)

    }            <div class="inline-flex items-center">



    return pages                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>

})

                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>

const debounce = (func, wait) => {

    let timeout                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>                            </svg>                            </svg>

    return (...args) => {

        clearTimeout(timeout)                </svg>

        timeout = setTimeout(() => func(...args), wait)

    }                <span class="text-gray-500">Memuat data...</span>                        </div>                        </div>

}

            </div>

const debouncedSearch = debounce(() => {

    updateUrl({ search: searchQuery.value, page: 1 })        </div>                        <input                        <input

}, 300)



const changePerPage = () => {

    updateUrl({ per_page: perPageSelected.value, page: 1 })        <!-- Table -->                            v-model="searchQuery"                            v-model="searchQuery"

}

        <div v-else class="overflow-x-auto">

const goToPage = (page) => {

    if (page >= 1 && page <= props.data.last_page) {            <table class="min-w-full divide-y divide-gray-200">                            @input="debouncedSearch"                            @input="debouncedSearch"

        updateUrl({ page })

    }                <thead class="bg-gray-50">

}

                    <tr>                            type="text"                            type="text"

const updateUrl = (params) => {

    const currentParams = new URLSearchParams(window.location.search)                        <th



    Object.entries(params).forEach(([key, value]) => {                            v-for="column in columns"                             :placeholder="searchPlaceholder"                            :placeholder="searchPlaceholder"

        if (value) {

            currentParams.set(key, value)                            :key="column.key"

        } else {

            currentParams.delete(key)                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 text-sm"                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 text-sm"

        }

    })                            @click="sort(column.key)"



    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {                        >                        />                        />

        preserveState: true,

        preserveScroll: true                            <div class="flex items-center">

    })

}                                {{ column.label }}                    </div>                    </div>



const exportPdf = () => {                                <svg v-if="sortKey === column.key" class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

    if (props.exportPdfUrl) {

        window.open(`${props.exportPdfUrl}?search=${searchQuery.value}`, '_blank')                                    <path v-if="sortOrder === 'asc'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>                </div>                </div>

    }

}                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>



const exportExcel = () => {                                </svg>

    if (props.exportExcelUrl) {

        window.open(`${props.exportExcelUrl}?search=${searchQuery.value}`, '_blank')                            </div>

    }

}                        </th>                <!-- Per Page Selector -->                <!-- Per Page Selector -->



watch(() => props.filters, (newFilters) => {                    </tr>

    searchQuery.value = newFilters.search || ''

    perPageSelected.value = newFilters.per_page || 10                </thead>                <div class="flex items-center space-x-2">                <div class="flex items-center space-x-2">

}, { immediate: true })

</script>                <tbody class="bg-white divide-y divide-gray-200">

                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">                    <span class="text-sm text-gray-700">Tampilkan:</span>                    <span class="text-sm text-gray-700">Tampilkan:</span>

                        <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">

                            <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">                    <select                     <select

                                <span :class="column.class || (column.key === 'id' ? 'font-medium text-gray-900' : 'text-gray-500')">

                                    {{ formatValue(getNestedValue(item, column.key), column) }}                        v-model="perPageSelected"                        v-model="perPageSelected"

                                </span>

                            </slot>                        @change="changePerPage"                        @change="changePerPage"

                        </td>

                    </tr>                        class="border border-gray-300 rounded-md text-sm px-2 py-1 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"                        class="border border-gray-300 rounded-md text-sm px-2 py-1 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"

                    <tr v-if="data.data && data.data.length === 0">

                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">                    >                    >

                            <div class="flex flex-col items-center">

                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <option v-for="option in perPageOptions" :key="option" :value="option">                        <option v-for="option in perPageOptions" :key="option" :value="option">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2m16-7l-3.5 3.5M8 13l-3.5 3.5M16 6L8 14"></path>

                                </svg>                            {{ option }}                            {{ option }}

                                {{ emptyMessage }}

                            </div>                        </option>                        </option>

                        </td>

                    </tr>                    </select>                    </select>

                </tbody>

            </table>                    <span class="text-sm text-gray-700">data</span>                    <span class="text-sm text-gray-700">data</span>

        </div>

                </div>                </div>

        <!-- Pagination -->

        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">            </div>            </div>

            <div class="flex items-center justify-between">

                <div class="text-sm text-gray-700">        </div>        </div>

                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data

                </div>

                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">

                    <!-- Previous Page -->        <!-- Success/Error Messages -->        <!-- Success/Error Messages -->

                    <button

                        @click="goToPage(data.current_page - 1)"        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">        <div v-if="$page.props.flash?.success" class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-md">

                        :disabled="!data.prev_page_url"

                        :class="[            <p class="text-green-700">{{ $page.props.flash.success }}</p>            <p class="text-green-700">{{ $page.props.flash.success }}</p>

                            'relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium',

                            data.prev_page_url         </div>        </div>

                                ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'

                                : 'text-gray-300 cursor-not-allowed'        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">        <div v-if="$page.props.flash?.error" class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 rounded-md">

                        ]"

                    >            <p class="text-red-700">{{ $page.props.flash.error }}</p>            <p class="text-red-700">{{ $page.props.flash.error }}</p>

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>        </div>        </div>

                        </svg>

                    </button>



                    <!-- Page Numbers -->        <!-- Loading State -->        <!-- Loading State -->

                    <button

                        v-for="page in paginationPages"        <div v-if="loading" class="px-6 py-8 text-center">        <div v-if="loading" class="px-6 py-8 text-center">

                        :key="page"

                        @click="goToPage(page)"            <div class="inline-flex items-center">            <div class="inline-flex items-center">

                        :class="[

                            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">

                            page === data.current_page

                                ? 'bg-green-50 border-green-500 text-green-600'                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>

                                : 'bg-white text-gray-700 hover:bg-gray-50'

                        ]"                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>

                    >

                        {{ page }}                </svg>                </svg>

                    </button>

                <span class="text-gray-500">Memuat data...</span>                <span class="text-gray-500">Memuat data...</span>

                    <!-- Next Page -->

                    <button            </div>            </div>

                        @click="goToPage(data.current_page + 1)"

                        :disabled="!data.next_page_url"        </div>        </div>

                        :class="[

                            'relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium',

                            data.next_page_url

                                ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'         <!-- Table -->        <!-- Table -->

                                : 'text-gray-300 cursor-not-allowed'

                        ]"        <div v-else class="overflow-x-auto">        <div v-else class="overflow-x-auto">

                    >

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">            <table class="min-w-full divide-y divide-gray-200">            <table class="min-w-full divide-y divide-gray-200">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>

                        </svg>                <thead class="bg-gray-50">                <thead class="bg-gray-50">

                    </button>

                </nav>                    <tr>                    <tr>

            </div>

        </div>                        <th                         <th

    </div>

</template>                            v-for="column in columns"                             v-for="column in columns"



<script setup>                            :key="column.key"                            :key="column.key"

import { ref, computed, watch } from 'vue'

import { router } from '@inertiajs/vue3'                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"



// Native debounce implementation                            @click="sort(column.key)"                            @click="sort(column.key)"

const debounce = (func, wait) => {

    let timeout                        >                        >

    return function executedFunction(...args) {

        const later = () => {                            <div class="flex items-center">                            <div class="flex items-center">

            clearTimeout(timeout)

            func(...args)                                {{ column.label }}                                {{ column.label }}

        }

        clearTimeout(timeout)                                <svg v-if="sortKey === column.key" class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">                                <svg v-if="sortKey === column.key" class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        timeout = setTimeout(later, wait)

    }                                    <path v-if="sortOrder === 'asc'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>                                    <path v-if="sortOrder === 'asc'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>

}

                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>

const props = defineProps({

    title: {                                </svg>                                </svg>

        type: String,

        default: 'Data Table'                            </div>                            </div>

    },

    data: {                        </th>                        </th>

        type: Object,

        required: true                    </tr>                    </tr>

    },

    columns: {                </thead>                </thead>

        type: Array,

        required: true                <tbody class="bg-white divide-y divide-gray-200">                <tbody class="bg-white divide-y divide-gray-200">

    },

    searchPlaceholder: {                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">                    <tr v-for="(item, index) in data.data" :key="item.id || index" class="hover:bg-gray-50">

        type: String,

        default: 'Cari data...'                        <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">                        <td v-for="column in columns" :key="column.key" class="px-6 py-4 whitespace-nowrap text-sm">

    },

    emptyMessage: {                            <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">                            <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">

        type: String,

        default: 'Tidak ada data'                                <span :class="column.class || (column.key === 'id' ? 'font-medium text-gray-900' : 'text-gray-500')">                                <span :class="column.class || (column.key === 'id' ? 'font-medium text-gray-900' : 'text-gray-500')">

    },

    perPageOptions: {                                    {{ formatValue(getNestedValue(item, column.key), column) }}                                    {{ formatValue(getNestedValue(item, column.key), column) }}

        type: Array,

        default: () => [10, 25, 50, 100]                                </span>                                </span>

    },

    exportPdfUrl: {                            </slot>                            </slot>

        type: String,

        default: null                        </td>                        </td>

    },

    exportExcelUrl: {                    </tr>                    </tr>

        type: String,

        default: null                    <tr v-if="data.data && data.data.length === 0">                    <tr v-if="data.data && data.data.length === 0">

    },

    filters: {                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">                        <td :colspan="columns.length" class="px-6 py-8 text-center text-gray-500">

        type: Object,

        default: () => ({})                            <div class="flex flex-col items-center">                            <div class="flex flex-col items-center">

    }

})                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">



// Reactive state                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2m16-7l-3.5 3.5M8 13l-3.5 3.5M16 6L8 14"></path>                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2m16-7l-3.5 3.5M8 13l-3.5 3.5M16 6L8 14"></path>

const loading = ref(false)

const searchQuery = ref(props.filters.search || '')                                </svg>                                </svg>

const perPageSelected = ref(props.filters.per_page || 10)

const sortKey = ref('')                                {{ emptyMessage }}                                {{ emptyMessage }}

const sortOrder = ref('asc')

                            </div>                            </div>

// Computed properties

const paginationPages = computed(() => {                        </td>                        </td>

    const current = props.data.current_page

    const last = props.data.last_page                    </tr>                    </tr>

    const pages = []

                    </tbody>                </tbody>

    if (last <= 7) {

        for (let i = 1; i <= last; i++) {            </table>            </table>

            pages.push(i)

        }        </div>        </div>

    } else {

        if (current <= 4) {

            for (let i = 1; i <= 5; i++) {

                pages.push(i)        <!-- Pagination -->        <!-- Pagination -->

            }

            pages.push('...')        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">        <div v-if="data.last_page > 1" class="px-6 py-4 border-t border-gray-200 bg-gray-50">

            pages.push(last)

        } else if (current >= last - 3) {            <div class="flex items-center justify-between">            <div class="flex items-center justify-between">

            pages.push(1)

            pages.push('...')                <div class="text-sm text-gray-700">                <div class="text-sm text-gray-700">

            for (let i = last - 4; i <= last; i++) {

                pages.push(i)                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data                    Menampilkan {{ data.from || 0 }} sampai {{ data.to || 0 }} dari {{ data.total || 0 }} data

            }

        } else {                </div>                </div>

            pages.push(1)

            pages.push('...')                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">

            for (let i = current - 1; i <= current + 1; i++) {

                pages.push(i)                    <!-- Previous Page -->                    <!-- Previous Page -->

            }

            pages.push('...')                    <button                    <button

            pages.push(last)

        }                        @click="goToPage(data.current_page - 1)"                        @click="goToPage(data.current_page - 1)"

    }

                            :disabled="!data.prev_page_url"                        :disabled="!data.prev_page_url"

    return pages

})                        :class="[                        :class="[



// Methods                            'relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium',                            'relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium',

const getNestedValue = (obj, path) => {

    return path.split('.').reduce((current, key) => {                            data.prev_page_url                             data.prev_page_url

        return current?.[key]

    }, obj)                                ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'                                 ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'

}

                                : 'text-gray-300 cursor-not-allowed'                                : 'text-gray-300 cursor-not-allowed'

const formatValue = (value, column) => {

    if (value === null || value === undefined) {                        ]"                        ]"

        return column.defaultValue || '-'

    }                    >                    >



    if (column.format === 'number') {                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

        return new Intl.NumberFormat('id-ID').format(value)

    }                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>



    if (column.format === 'currency') {                        </svg>                        </svg>

        return new Intl.NumberFormat('id-ID', {

            style: 'currency',                    </button>                    </button>

            currency: 'IDR'

        }).format(value)

    }

                        <!-- Page Numbers -->                    <!-- Page Numbers -->

    if (column.format === 'date') {

        return new Date(value).toLocaleDateString('id-ID')                    <button                    <button

    }

                            v-for="page in paginationPages"                        v-for="page in paginationPages"

    return value

}                        :key="page"                        :key="page"



const debouncedSearch = debounce(() => {                        @click="goToPage(page)"                        @click="goToPage(page)"

    updateUrl({ search: searchQuery.value, page: 1 })

}, 300)                        :class="[                        :class="[



const changePerPage = () => {                            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',                            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium',

    updateUrl({ per_page: perPageSelected.value, page: 1 })

}                            page === data.current_page                            page === data.current_page



const goToPage = (page) => {                                ? 'bg-green-50 border-green-500 text-green-600'                                ? 'bg-green-50 border-green-500 text-green-600'

    if (page !== '...' && page >= 1 && page <= props.data.last_page) {

        updateUrl({ page })                                : 'bg-white text-gray-700 hover:bg-gray-50'                                : 'bg-white text-gray-700 hover:bg-gray-50'

    }

}                        ]"                        ]"



const sort = (key) => {                    >                    >

    if (sortKey.value === key) {

        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'                        {{ page }}                        {{ page }}

    } else {

        sortKey.value = key                    </button>                    </button>

        sortOrder.value = 'asc'

    }



    updateUrl({ sort: key, order: sortOrder.value, page: 1 })                    <!-- Next Page -->                    <!-- Next Page -->

}

                    <button                    <button

const updateUrl = (params) => {

    loading.value = true                        @click="goToPage(data.current_page + 1)"                        @click="goToPage(data.current_page + 1)"



    const currentParams = new URLSearchParams(window.location.search)                        :disabled="!data.next_page_url"                        :disabled="!data.next_page_url"



    Object.entries(params).forEach(([key, value]) => {                        :class="[                        :class="[

        if (value) {

            currentParams.set(key, value)                            'relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium',                            'relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium',

        } else {

            currentParams.delete(key)                            data.next_page_url                             data.next_page_url

        }

    })                                ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'                                 ? 'text-gray-500 hover:bg-gray-50 cursor-pointer'



    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {                                : 'text-gray-300 cursor-not-allowed'                                : 'text-gray-300 cursor-not-allowed'

        preserveState: true,

        preserveScroll: true,                        ]"                        ]"

        onFinish: () => {

            loading.value = false                    >                    >

        }

    })                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

}

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>

const exportPdf = () => {

    if (props.exportPdfUrl) {                        </svg>                        </svg>

        const params = new URLSearchParams({

            search: searchQuery.value,                    </button>                    </button>

            sort: sortKey.value,

            order: sortOrder.value                </nav>                </nav>

        })

                    </div>            </div>

        window.open(`${props.exportPdfUrl}?${params.toString()}`, '_blank')

    }        </div>        </div>

}

    </div>    </div>

const exportExcel = () => {

    if (props.exportExcelUrl) {</template></template>

        const params = new URLSearchParams({

            search: searchQuery.value,

            sort: sortKey.value,

            order: sortOrder.value<script setup><script setup>

        })

        import { ref, computed, watch, onMounted } from 'vue'import { ref, computed, watch, onMounted } from 'vue'

        window.open(`${props.exportExcelUrl}?${params.toString()}`, '_blank')

    }import { router } from '@inertiajs/vue3'import { router } from '@inertiajs/vue3'

}

// Native debounce implementation

// Watchers

watch(() => props.filters, (newFilters) => {// Native debounce implementationconst debounce = (func, wait) => {

    searchQuery.value = newFilters.search || ''

    perPageSelected.value = newFilters.per_page || 10const debounce = (func, wait) => {    let timeout

}, { immediate: true })

</script>    let timeout    return function executedFunction(...args) {

    return function executedFunction(...args) {        const later = () => {

        const later = () => {            clearTimeout(timeout)

            clearTimeout(timeout)            func(...args)

            func(...args)        }

        }        clearTimeout(timeout)

        clearTimeout(timeout)        timeout = setTimeout(later, wait)

        timeout = setTimeout(later, wait)    }

    }}

}

const props = defineProps({

const props = defineProps({    title: {

    title: {        type: String,

        type: String,        default: 'Data Table'

        default: 'Data Table'    },

    },    data: {

    data: {        type: Object,

        type: Object,        required: true

        required: true    },

    },    columns: {

    columns: {        type: Array,

        type: Array,        required: true

        required: true    },

    },    searchPlaceholder: {

    searchPlaceholder: {        type: String,

        type: String,        default: 'Cari data...'

        default: 'Cari data...'    },

    },    emptyMessage: {

    emptyMessage: {        type: String,

        type: String,        default: 'Tidak ada data'

        default: 'Tidak ada data'    },

    },    perPageOptions: {

    perPageOptions: {        type: Array,

        type: Array,        default: () => [10, 25, 50, 100]

        default: () => [10, 25, 50, 100]    },

    },    exportPdfUrl: {

    exportPdfUrl: {        type: String,

        type: String,        default: null

        default: null    },

    },    exportExcelUrl: {

    exportExcelUrl: {        type: String,

        type: String,        default: null

        default: null    },

    },    filters: {

    filters: {        type: Object,

        type: Object,        default: () => ({})

        default: () => ({})    }

    }})

})

// Reactive state

// Reactive stateconst loading = ref(false)

const loading = ref(false)const searchQuery = ref(props.filters.search || '')

const searchQuery = ref(props.filters.search || '')const perPageSelected = ref(props.filters.per_page || 10)

const perPageSelected = ref(props.filters.per_page || 10)const sortKey = ref('')

const sortKey = ref('')const sortOrder = ref('asc')

const sortOrder = ref('asc')

// Computed properties

// Computed propertiesconst paginationPages = computed(() => {

const paginationPages = computed(() => {    const current = props.data.current_page

    const current = props.data.current_page    const last = props.data.last_page

    const last = props.data.last_page    const pages = []

    const pages = []

        if (last <= 7) {

    if (last <= 7) {        for (let i = 1; i <= last; i++) {

        for (let i = 1; i <= last; i++) {            pages.push(i)

            pages.push(i)        }

        }    } else {

    } else {        if (current <= 4) {

        if (current <= 4) {            for (let i = 1; i <= 5; i++) {

            for (let i = 1; i <= 5; i++) {                pages.push(i)

                pages.push(i)            }

            }            pages.push('...')

            pages.push('...')            pages.push(last)

            pages.push(last)        } else if (current >= last - 3) {

        } else if (current >= last - 3) {            pages.push(1)

            pages.push(1)            pages.push('...')

            pages.push('...')            for (let i = last - 4; i <= last; i++) {

            for (let i = last - 4; i <= last; i++) {                pages.push(i)

                pages.push(i)            }

            }        } else {

        } else {            pages.push(1)

            pages.push(1)            pages.push('...')

            pages.push('...')            for (let i = current - 1; i <= current + 1; i++) {

            for (let i = current - 1; i <= current + 1; i++) {                pages.push(i)

                pages.push(i)            }

            }            pages.push('...')

            pages.push('...')            pages.push(last)

            pages.push(last)        }

        }    }

    }

        return pages

    return pages})

})

// Methods

// Methodsconst getNestedValue = (obj, path) => {

const getNestedValue = (obj, path) => {    return path.split('.').reduce((current, key) => {

    return path.split('.').reduce((current, key) => {        return current?.[key]

        return current?.[key]    }, obj)

    }, obj)}

}

const formatValue = (value, column) => {

const formatValue = (value, column) => {    if (value === null || value === undefined) {

    if (value === null || value === undefined) {        return column.defaultValue || '-'

        return column.defaultValue || '-'    }

    }

        if (column.format === 'number') {

    if (column.format === 'number') {        return new Intl.NumberFormat('id-ID').format(value)

        return new Intl.NumberFormat('id-ID').format(value)    }

    }

        if (column.format === 'currency') {

    if (column.format === 'currency') {        return new Intl.NumberFormat('id-ID', {

        return new Intl.NumberFormat('id-ID', {            style: 'currency',

            style: 'currency',            currency: 'IDR'

            currency: 'IDR'        }).format(value)

        }).format(value)    }

    }

        if (column.format === 'date') {

    if (column.format === 'date') {        return new Date(value).toLocaleDateString('id-ID')

        return new Date(value).toLocaleDateString('id-ID')    }

    }

        return value

    return value}

}

const debouncedSearch = debounce(() => {

const debouncedSearch = debounce(() => {    updateUrl({ search: searchQuery.value, page: 1 })

    updateUrl({ search: searchQuery.value, page: 1 })}, 300)

}, 300)

const changePerPage = () => {

const changePerPage = () => {    updateUrl({ per_page: perPageSelected.value, page: 1 })

    updateUrl({ per_page: perPageSelected.value, page: 1 })}

}

const goToPage = (page) => {

const goToPage = (page) => {    if (page !== '...' && page >= 1 && page <= props.data.last_page) {

    if (page !== '...' && page >= 1 && page <= props.data.last_page) {        updateUrl({ page })

        updateUrl({ page })    }

    }}

}

const sort = (key) => {

const sort = (key) => {    if (sortKey.value === key) {

    if (sortKey.value === key) {        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'

        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'    } else {

    } else {        sortKey.value = key

        sortKey.value = key        sortOrder.value = 'asc'

        sortOrder.value = 'asc'    }

    }

        updateUrl({ sort: key, order: sortOrder.value, page: 1 })

    updateUrl({ sort: key, order: sortOrder.value, page: 1 })}

}

const updateUrl = (params) => {

const updateUrl = (params) => {    loading.value = true

    loading.value = true

        const currentParams = new URLSearchParams(window.location.search)

    const currentParams = new URLSearchParams(window.location.search)

        Object.entries(params).forEach(([key, value]) => {

    Object.entries(params).forEach(([key, value]) => {        if (value) {

        if (value) {            currentParams.set(key, value)

            currentParams.set(key, value)        } else {

        } else {            currentParams.delete(key)

            currentParams.delete(key)        }

        }    })

    })

        router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {

    router.get(`${window.location.pathname}?${currentParams.toString()}`, {}, {        preserveState: true,

        preserveState: true,        preserveScroll: true,

        preserveScroll: true,        onFinish: () => {

        onFinish: () => {            loading.value = false

            loading.value = false        }

        }    })

    })}

}

const exportPdf = () => {

const exportPdf = () => {    if (props.exportPdfUrl) {

    if (props.exportPdfUrl) {        const params = new URLSearchParams({

        const params = new URLSearchParams({            search: searchQuery.value,

            search: searchQuery.value,            sort: sortKey.value,

            sort: sortKey.value,            order: sortOrder.value

            order: sortOrder.value        })

        })

                window.open(`${props.exportPdfUrl}?${params.toString()}`, '_blank')

        window.open(`${props.exportPdfUrl}?${params.toString()}`, '_blank')    }

    }}

}

const exportExcel = () => {

const exportExcel = () => {    if (props.exportExcelUrl) {

    if (props.exportExcelUrl) {        const params = new URLSearchParams({

        const params = new URLSearchParams({            search: searchQuery.value,

            search: searchQuery.value,            sort: sortKey.value,

            sort: sortKey.value,            order: sortOrder.value

            order: sortOrder.value        })

        })

                window.open(`${props.exportExcelUrl}?${params.toString()}`, '_blank')

        window.open(`${props.exportExcelUrl}?${params.toString()}`, '_blank')    }

    }}

}

// Watchers

// Watcherswatch(() => props.filters, (newFilters) => {

watch(() => props.filters, (newFilters) => {    searchQuery.value = newFilters.search || ''

    searchQuery.value = newFilters.search || ''    perPageSelected.value = newFilters.per_page || 10

    perPageSelected.value = newFilters.per_page || 10}, { immediate: true })

}, { immediate: true })</script>
</script>