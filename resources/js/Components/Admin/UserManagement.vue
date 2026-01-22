<script setup>
import { Link } from '@inertiajs/vue3';
import { UsersIcon, ShoppingBagIcon } from '@heroicons/vue/24/outline';

defineProps({
    users: Array,
    isSuperAdmin: Boolean
});

defineEmits(['promote', 'demote', 'delete', 'requestEdit']);
</script>

<template>
    <div class="bg-white rounded-2xl shadow-lg border-2 border-zinc-100" data-aos="fade-up">
        <div class="px-4 md:px-6 py-4 md:py-5 border-b-2 border-zinc-100 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
                            <UsersIcon class="w-5 h-5 text-white" />
                        </div>
                        <h2 class="text-lg md:text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">User Management</h2>
                    </div>
                    <p class="text-xs md:text-sm text-zinc-600 ml-11">View all users and manage admin privileges</p>
                </div>
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg border border-zinc-200 shadow-sm">
                    <span class="text-2xl font-bold text-zinc-900">{{ users.length }}</span>
                    <span class="text-xs text-zinc-500 font-medium">Total</span>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-50">
                    <tr>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-bold text-zinc-700 uppercase tracking-wider">User</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-bold text-zinc-700 uppercase tracking-wider">Role</th>
                        <th class="hidden sm:table-cell px-3 md:px-6 py-3 text-left text-xs font-bold text-zinc-700 uppercase tracking-wider">Orders</th>
                        <th class="hidden lg:table-cell px-3 md:px-6 py-3 text-left text-xs font-bold text-zinc-700 uppercase tracking-wider">Registered</th>
                        <th class="px-3 md:px-6 py-3 text-left text-xs font-bold text-zinc-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200">
                    <tr v-for="user in users" :key="user.id" class="hover:bg-zinc-50 transition-colors">
                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="relative flex-shrink-0 h-8 w-8 md:h-10 md:w-10 bg-gradient-to-br from-zinc-200 to-zinc-300 rounded-full flex items-center justify-center font-bold text-zinc-700 shadow-sm text-sm md:text-base">
                                    {{ user.name.charAt(0).toUpperCase() }}
                                    <span v-if="user.orders_count > 0" class="absolute -top-1 -right-1 h-4 w-4 md:h-5 md:w-5 bg-primary-600 text-white text-xs font-bold rounded-full flex items-center justify-center shadow-lg">
                                        {{ user.orders_count }}
                                    </span>
                                </div>
                                <div class="ml-2 md:ml-4 min-w-0">
                                    <div class="text-xs md:text-sm font-bold text-zinc-900 truncate">{{ user.name }}</div>
                                    <div class="text-xs text-zinc-500 truncate hidden sm:block">{{ user.email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            <span v-if="user.email === 'superadmin@vallera.com'" class="px-2 md:px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                Super Admin
                            </span>
                            <span v-else-if="user.is_admin" class="px-2 md:px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Admin
                            </span>
                            <span v-else class="px-2 md:px-3 py-1 text-xs font-bold rounded-full bg-zinc-100 text-zinc-700 border border-zinc-200">
                                User
                            </span>
                        </td>
                        <td class="hidden sm:table-cell px-3 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm font-medium text-zinc-900">
                                <ShoppingBagIcon class="w-4 h-4 mr-1.5 text-zinc-400" />
                                {{ user.orders_count || 0 }}
                            </div>
                        </td>
                        <td class="hidden lg:table-cell px-3 md:px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                            {{ new Date(user.created_at).toLocaleDateString() }}
                        </td>
                        <td v-if="isSuperAdmin" class="px-3 md:px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-1 md:gap-2 flex-wrap">
                                <template v-if="user.email !== 'superadmin@vallera.com'">
                                    <Link
                                        v-if="!user.is_admin && !user.isSuperAdmin"
                                        :href="`/admin/users/${user.id}/edit`"
                                        class="px-2 md:px-3 py-1 md:py-1.5 bg-blue-100 text-blue-700 rounded-lg font-bold hover:bg-blue-200 transition-all text-xs"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        v-if="!user.is_admin && user.email.endsWith('@vallera.com')"
                                        @click="$emit('promote', user)"
                                        class="px-2 md:px-3 py-1 md:py-1.5 bg-emerald-100 text-emerald-700 rounded-lg font-bold hover:bg-emerald-200 transition-all text-xs whitespace-nowrap"
                                    >
                                        Promote
                                    </button>
                                    <button
                                        v-if="user.is_admin"
                                        @click="$emit('demote', user)"
                                        class="px-2 md:px-3 py-1 md:py-1.5 bg-amber-100 text-amber-700 rounded-lg font-bold hover:bg-amber-200 transition-all text-xs whitespace-nowrap"
                                    >
                                        Demote
                                    </button>
                                    <button
                                        v-if="!user.is_admin"
                                        @click="$emit('delete', user)"
                                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-bold hover:bg-red-200 transition-all text-xs"
                                    >
                                        Delete
                                    </button>
                                    <span v-if="!user.is_admin && !user.email.endsWith('@vallera.com') && user.orders_count === 0" class="text-zinc-400 font-medium text-xs">
                                        Not eligible for promotion
                                    </span>
                                </template>
                                <span v-else class="text-zinc-400 font-medium text-xs">Protected</span>
                            </div>
                        </td>
                        <td v-else-if="!isSuperAdmin" class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <button
                                    v-if="!user.is_admin && !user.isSuperAdmin"
                                    @click="$emit('requestEdit', user)"
                                    class="px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg font-bold hover:bg-indigo-200 transition-all text-xs"
                                >
                                    Request Edit
                                </button>
                                <span v-else class="text-zinc-400 font-medium text-xs">Cannot request edit</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
