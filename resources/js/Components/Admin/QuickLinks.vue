<script setup>
import { Link } from '@inertiajs/vue3';
import { ShoppingBagIcon, ChartBarIcon } from '@heroicons/vue/24/outline';

defineProps({
    isSuperAdmin: Boolean,
    stats: Object
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 overflow-hidden" data-aos="fade-up">
        <Link href="/admin/orders" class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all text-white">
            <div class="flex items-center justify-between mb-4">
                <ShoppingBagIcon class="w-12 h-12 text-white/80" />
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Order Management</h3>
            <p class="text-blue-100 text-sm">Track and manage all customer orders with status updates</p>
        </Link>

        <Link href="/admin/products" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all text-white">
            <div class="flex items-center justify-between mb-4">
                <ChartBarIcon class="w-12 h-12 text-white/80" />
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Product Management</h3>
            <p class="text-emerald-100 text-sm">Add, edit, and manage your furniture catalog</p>
        </Link>

        <Link v-if="isSuperAdmin" href="/admin/logs" class="group bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all text-white">
            <div class="flex items-center justify-between mb-4">
                <svg class="w-12 h-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Audit Logs</h3>
            <p class="text-purple-100 text-sm">View system activity and track changes</p>
        </Link>

        <div v-else class="bg-gradient-to-br from-zinc-200 to-zinc-300 rounded-2xl shadow-lg p-6 opacity-60 cursor-not-allowed">
            <div class="flex items-center justify-between mb-4">
                <svg class="w-12 h-12 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2 text-zinc-600">Audit Logs</h3>
            <p class="text-zinc-500 text-sm">Super Admin Only</p>
        </div>
    </div>

    <div v-if="isSuperAdmin" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8" data-aos="fade-up">
        <Link href="/admin/edit-requests" class="group bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all  text-white relative overflow-hidden">
            <div class="absolute top-2 right-2" v-if="stats.pendingEditRequests > 0">
                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold leading-none text-amber-700 bg-white rounded-full shadow-lg animate-pulse">
                    {{ stats.pendingEditRequests }}
                </span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <svg class="w-12 h-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Edit Requests</h3>
            <p class="text-amber-100 text-sm">Review admin requests to edit user accounts</p>
        </Link>

        <Link href="/admin/announcements" class="group bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all  text-white relative overflow-hidden">
            <div class="absolute top-2 right-2" v-if="stats.pendingAnnouncements > 0">
                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold leading-none text-cyan-700 bg-white rounded-full shadow-lg animate-pulse">
                    {{ stats.pendingAnnouncements }}
                </span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <svg class="w-12 h-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Announcements</h3>
            <p class="text-cyan-100 text-sm">Review and publish announcements</p>
        </Link>
    </div>

    <Link v-else href="/admin/announcements" class="block mb-8" data-aos="fade-up">
        <div class="group bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all  text-white relative overflow-hidden">
            <div class="absolute top-2 right-2" v-if="stats.newAnnouncementsCount > 0">
                <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold leading-none text-cyan-700 bg-white rounded-full shadow-lg animate-pulse">
                    {{ stats.newAnnouncementsCount }}
                </span>
            </div>
            <div class="flex items-center justify-between mb-4">
                <svg class="w-12 h-12 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-2">Announcements</h3>
            <p class="text-cyan-100 text-sm">Create and manage announcements</p>
        </div>
    </Link>
</template>
