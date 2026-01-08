<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { UsersIcon, ShieldCheckIcon, UserGroupIcon, BuildingOfficeIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    users: Array,
    stats: Object,
    isSuperAdmin: Boolean
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user);

const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.errors?.error);

function promoteUser(userId) {
    if (confirm('Are you sure you want to promote this user to admin?')) {
        router.post(`/admin/users/${userId}/promote`);
    }
}

function demoteUser(userId) {
    if (confirm('Are you sure you want to demote this user?')) {
        router.post(`/admin/users/${userId}/demote`);
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>

<template>
    <Head title="Admin Dashboard" />
    <div class="min-h-screen bg-zinc-100">
        <nav class="bg-white shadow-sm border-b border-zinc-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link :href="route('home')" class="flex items-center text-zinc-600 hover:text-zinc-900 transition-colors">
                            <ArrowLeftIcon class="w-5 h-5 mr-2" />
                            Back to Site
                        </Link>
                    </div>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-zinc-900">Vallera</span>
                        <span class="ml-2 px-2 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded">ADMIN</span>
                    </div>
                    <div class="flex items-center">
                        <div class="text-right">
                            <p class="text-sm font-medium text-zinc-900">{{ currentUser?.name }}</p>
                            <p class="text-xs text-zinc-500">{{ currentUser?.email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div v-if="flashSuccess" class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ flashSuccess }}
            </div>
            <div v-if="flashError" class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ flashError }}
            </div>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-zinc-900">Dashboard</h1>
                <p class="text-zinc-600 mt-1">Welcome to the Vallera admin panel. Manage users and view statistics.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-zinc-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <UsersIcon class="w-6 h-6 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-zinc-500">Total Users</p>
                            <p class="text-2xl font-bold text-zinc-900">{{ stats.totalUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-zinc-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <ShieldCheckIcon class="w-6 h-6 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-zinc-500">Admin Users</p>
                            <p class="text-2xl font-bold text-zinc-900">{{ stats.adminUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-zinc-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <UserGroupIcon class="w-6 h-6 text-purple-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-zinc-500">Regular Users</p>
                            <p class="text-2xl font-bold text-zinc-900">{{ stats.regularUsers }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-zinc-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-amber-100 rounded-lg">
                            <BuildingOfficeIcon class="w-6 h-6 text-amber-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-zinc-500">Vallera Staff</p>
                            <p class="text-2xl font-bold text-zinc-900">{{ stats.valleraUsers }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-zinc-200">
                <div class="px-6 py-4 border-b border-zinc-200">
                    <h2 class="text-lg font-semibold text-zinc-900">User Management</h2>
                    <p class="text-sm text-zinc-500">View all users and manage admin privileges</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Registered</th>
                                <th v-if="isSuperAdmin" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-zinc-200">
                            <tr v-for="user in users" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-zinc-200 rounded-full flex items-center justify-center">
                                            <span class="text-zinc-600 font-medium">{{ user.name.charAt(0).toUpperCase() }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-zinc-900">{{ user.name }}</div>
                                            <div class="text-sm text-zinc-500">{{ user.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="user.email === 'superadmin@vallera.com'" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Super Admin
                                    </span>
                                    <span v-else-if="user.is_admin" class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Admin
                                    </span>
                                    <span v-else class="px-2 py-1 text-xs font-semibold rounded-full bg-zinc-100 text-zinc-800">
                                        User
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">
                                    {{ formatDate(user.created_at) }}
                                </td>
                                <td v-if="isSuperAdmin" class="px-6 py-4 whitespace-nowrap text-sm">
                                    <template v-if="user.email !== 'superadmin@vallera.com'">
                                        <button
                                            v-if="!user.is_admin && user.email.endsWith('@vallera.com')"
                                            @click="promoteUser(user.id)"
                                            class="text-green-600 hover:text-green-800 font-medium mr-3"
                                        >
                                            Promote
                                        </button>
                                        <button
                                            v-if="user.is_admin"
                                            @click="demoteUser(user.id)"
                                            class="text-red-600 hover:text-red-800 font-medium"
                                        >
                                            Demote
                                        </button>
                                        <span v-if="!user.is_admin && !user.email.endsWith('@vallera.com')" class="text-zinc-400">
                                            Not eligible
                                        </span>
                                    </template>
                                    <span v-else class="text-zinc-400">Protected</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-4">Session Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-zinc-50 rounded-lg p-4">
                        <p class="text-zinc-500">Current Session User</p>
                        <p class="font-medium text-zinc-900">{{ currentUser?.name }} ({{ currentUser?.email }})</p>
                    </div>
                    <div class="bg-zinc-50 rounded-lg p-4">
                        <p class="text-zinc-500">Admin Status</p>
                        <p class="font-medium text-zinc-900">{{ isSuperAdmin ? 'Super Administrator' : 'Administrator' }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

