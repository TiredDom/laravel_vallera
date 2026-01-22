<script setup>
import { BanknotesIcon, ShoppingBagIcon, UsersIcon, ClockIcon, ChartBarIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline';

defineProps({
    stats: Object
});
</script>

<template>
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-emerald-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="0">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 md:p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                    <BanknotesIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
                <div :class="['text-xs font-bold px-2 py-1 rounded-full', stats.revenueChange >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700']">
                    {{ stats.revenueChange >= 0 ? '+' : '' }}{{ stats.revenueChange }}%
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-base md:text-xl lg:text-2xl font-bold text-emerald-600 break-all">₱{{ stats.totalRevenue }}</p>
            </div>
            <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium text-center">Total Revenue</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-blue-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="50">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 md:p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <ShoppingBagIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
                <div class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">
                    {{ stats.todayOrders }} today
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-blue-700">{{ stats.totalOrders }}</p>
            </div>
            <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium text-center">Total Orders</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-purple-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 md:p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                    <UsersIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
                <div :class="['text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap', stats.usersChange >= 0 ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700']">
                    {{ stats.usersChange >= 0 ? '+' : '' }}{{ stats.usersChange }}%
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-purple-700">{{ stats.totalUsers }}</p>
            </div>
            <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium text-center">Active Users</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-amber-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 md:p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                    <ClockIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
                <div v-if="stats.pendingOrders > 10" class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 animate-pulse whitespace-nowrap">
                    Alert
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-amber-700">{{ stats.pendingOrders }}</p>
            </div>
            <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium text-center">Pending Orders</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-sky-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 md:p-3 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl shadow-lg">
                    <ChartBarIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-sky-700 break-all">₱{{ stats.averageOrderValue }}</p>
            </div>
            <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium text-center">Avg Order Value</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-pink-200 hover:shadow-xl transition-shadow flex flex-col justify-between" data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between mb-3">
                <div class="p-3 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg">
                    <ShieldCheckIcon class="w-6 h-6 text-white" />
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center">
                <p class="text-4xl lg:text-5xl font-extrabold text-pink-700">{{ stats.newUsersThisWeek }}</p>
            </div>
            <p class="text-sm text-zinc-600 mt-1 font-medium text-center">New This Week</p>
        </div>
    </div>
</template>
