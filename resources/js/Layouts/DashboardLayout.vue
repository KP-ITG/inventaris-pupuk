<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});

const sidebarOpen = ref(false);

const navigation = computed(() => {
    const nav = [
        {
            name: 'Dashboard',
            href: '/dashboard',
            icon: 'M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z',
            current: page.url === '/dashboard'
        },
    ];

    // Admin navigation
    if (user.value.role === 'admin') {
        nav.push(
            {
                name: 'Manajemen User',
                href: '/admin/users',
                icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z',
                current: page.url.startsWith('/admin/users')
            },
            {
                name: 'Validasi User',
                href: '/admin/users/validations',
                icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                current: page.url === '/admin/users/validations'
            },
            {
                name: 'Manajemen Pupuk',
                href: '/admin/pupuk',
                icon: 'M20.24 12.24a6 6 0 00-8.49-8.49L5 10.5V19h8.5z M16 8L2 22 M17.5 15H9',
                current: page.url.startsWith('/admin/pupuk')
            },
            {
                name: 'Manajemen Stok',
                href: '/admin/stok',
                icon: 'M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z',
                current: page.url.startsWith('/admin/stok')
            }
        );
    } else {
        nav.push(
            {
                name: 'Stok Saya',
                href: '/distributor/stok',
                icon: 'M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z',
                current: page.url.startsWith('/distributor/stok')
            }
        );
    }

    return nav;
});
</script>

<template>
    <div class="h-screen flex overflow-hidden bg-gray-100">
        <!-- Sidebar for mobile -->
        <div class="lg:hidden">
            <div v-show="sidebarOpen" class="fixed inset-0 flex z-40">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <img class="h-8 w-auto" src="/assets/images/logo_dinas_pertanian.png" alt="Logo Dinas Pertanian" />
                            <span class="ml-2 text-lg font-semibold text-gray-900">Inventaris Pupuk</span>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">
                                <svg :class="[item.current ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-4 h-6 w-6']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                                </svg>
                                {{ item.name }}
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div class="flex flex-col h-0 flex-1 bg-white border-r border-gray-200">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <img class="h-8 w-auto" src="/assets/images/logo_dinas_pertanian.png" alt="Logo Dinas Pertanian" />
                            <span class="ml-2 text-lg font-semibold text-gray-900">Inventaris Pupuk</span>
                        </div>
                        <nav class="mt-5 flex-1 px-2 bg-white space-y-1">
                            <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'bg-green-100 text-green-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                <svg :class="[item.current ? 'text-green-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 h-6 w-6']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                                </svg>
                                {{ item.name }}
                            </Link>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                        <div class="flex-shrink-0 w-full group block">
                            <div class="flex items-center">
                                <div class="w-9 h-9 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-green-600">
                                        {{ user.nama ? user.nama.charAt(0).toUpperCase() : 'U' }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ user.nama || 'User' }}</p>
                                    <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700 capitalize">{{ user.role || 'guest' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button @click="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Profile dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-green-600">
                                            {{ user.nama ? user.nama.charAt(0).toUpperCase() : 'U' }}
                                        </span>
                                    </div>
                                </button>
                            </template>
                            
                            <template #content>
                                <DropdownLink href="/profile">Profile</DropdownLink>
                                <DropdownLink href="/logout" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
