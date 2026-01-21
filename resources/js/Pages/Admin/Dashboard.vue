<script setup>
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import {
    UsersIcon, ShieldCheckIcon, ArrowLeftIcon, ShoppingBagIcon,
    ClockIcon, CheckCircleIcon, BanknotesIcon, ChartBarIcon,
    CalendarIcon, TruckIcon, XCircleIcon, CreditCardIcon
} from '@heroicons/vue/24/outline';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';

const props = defineProps({
    users: Array,
    stats: Object,
    isSuperAdmin: Boolean
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user);
let refreshInterval = null;

const showPromoteModal = ref(false);
const showDemoteModal = ref(false);
const showDeleteModal = ref(false);
const showRequestEditModal = ref(false);
const userToPromote = ref(null);
const userToDemote = ref(null);
const userToDelete = ref(null);
const userToRequestEdit = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });

const requestEditForm = useForm({
    field_name: 'name',
    new_value: '',
    reason: ''
});

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({ only: ['stats'], preserveScroll: true, preserveState: true });
    }, 15000);
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

watch(() => page.props.flash?.success, (message) => {
    if (message) {
        toast.value = { show: true, message, type: 'success' };
    }
});

watch(() => page.props.flash?.error, (message) => {
    if (message) {
        toast.value = { show: true, message, type: 'error' };
    }
});

function hideToast() {
    toast.value.show = false;
}

function promptPromote(user) {
    userToPromote.value = user;
    showPromoteModal.value = true;
}

function confirmPromote() {
    if (userToPromote.value) {
        router.post(`/admin/users/${userToPromote.value.id}/promote`, {}, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToPromote.value = null;
                showPromoteModal.value = false;
            }
        });
    }
}

function promptDemote(user) {
    userToDemote.value = user;
    showDemoteModal.value = true;
}

function confirmDemote() {
    if (userToDemote.value) {
        router.post(`/admin/users/${userToDemote.value.id}/demote`, {}, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToDemote.value = null;
                showDemoteModal.value = false;
            }
        });
    }
}

function promptDelete(user) {
    userToDelete.value = user;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (userToDelete.value) {
        router.delete(`/admin/users/${userToDelete.value.id}`, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToDelete.value = null;
                showDeleteModal.value = false;
            }
        });
    }
}

function promptRequestEdit(user) {
    userToRequestEdit.value = user;
    requestEditForm.field_name = 'name';
    requestEditForm.new_value = '';
    requestEditForm.reason = '';
    showRequestEditModal.value = true;
}

function confirmRequestEdit() {
    if (userToRequestEdit.value) {
        requestEditForm.post(`/admin/users/${userToRequestEdit.value.id}/request-edit`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showRequestEditModal.value = false;
                userToRequestEdit.value = null;
                requestEditForm.reset();
            }
        });
    }
}

function getStatusColor(status) {
    const colors = {
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
        processing: 'bg-blue-50 text-blue-700 border-blue-200',
        completed: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        cancelled: 'bg-red-50 text-red-700 border-red-200'
    };
    return colors[status] || 'bg-zinc-50 text-zinc-700 border-zinc-200';
}

function getStatusIcon(status) {
    const icons = {
        pending: ClockIcon,
        processing: TruckIcon,
        completed: CheckCircleIcon,
        cancelled: XCircleIcon
    };
    return icons[status] || ClockIcon;
}

function getPaymentMethodColor(method) {
    const colors = {
        gcash: 'text-blue-600 bg-blue-50',
        card: 'text-purple-600 bg-purple-50',
        bank: 'text-emerald-600 bg-emerald-50',
    };
    return colors[method] || 'text-zinc-600 bg-zinc-100';
}

const maxRevenue = computed(() => {
    if (!props.stats?.last7DaysRevenue) return 0;
    return Math.max(...props.stats.last7DaysRevenue.map(d => d.revenue), 1);
});

const totalOrdersPercentages = computed(() => {
    const total = props.stats?.ordersByStatus ?
        Object.values(props.stats.ordersByStatus).reduce((a, b) => a + b, 0) : 1;

    return {
        pending: Math.round((props.stats?.ordersByStatus?.pending / total) * 100) || 0,
        processing: Math.round((props.stats?.ordersByStatus?.processing / total) * 100) || 0,
        completed: Math.round((props.stats?.ordersByStatus?.completed / total) * 100) || 0,
        cancelled: Math.round((props.stats?.ordersByStatus?.cancelled / total) * 100) || 0,
    };
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <ToastNotification
        :show="toast.show"
        :message="toast.message"
        :type="toast.type"
        @close="hideToast"
    />

    <div class="min-h-screen bg-gradient-to-br from-zinc-50 via-slate-50 to-zinc-100 overflow-x-hidden">
        <nav class="bg-white shadow-md border-b-2 border-primary-100 sticky top-0 z-40 backdrop-blur-sm bg-white/95">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 md:h-20">
                    <Link :href="route('home')" class="flex items-center gap-2 px-3 py-2 rounded-lg text-zinc-600 hover:text-primary-600 hover:bg-primary-50 transition-all font-medium group">
                        <ArrowLeftIcon class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                        <span class="hidden sm:inline">Back to Site</span>
                    </Link>
                    <div class="flex items-center gap-2 md:gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-primary-600 to-emerald-600 flex items-center justify-center shadow-lg">
                                <span class="text-white font-bold text-lg md:text-xl">V</span>
                            </div>
                            <span class="text-xl md:text-2xl font-bold bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent hidden sm:block">Vallera</span>
                        </div>
                        <span class="px-2 md:px-3 py-1 bg-gradient-to-r from-primary-600 to-emerald-600 text-white text-xs font-bold rounded-full shadow-lg">
                            {{ isSuperAdmin ? 'SUPER ADMIN' : 'ADMIN' }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 md:gap-4">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-bold text-zinc-900">{{ currentUser?.name }}</p>
                            <p class="text-xs text-zinc-500">{{ currentUser?.email }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-primary-200">
                            {{ currentUser?.name?.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 md:py-8 px-4 sm:px-6 lg:px-8">
            <div class="mb-6 md:mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-zinc-900 bg-gradient-to-r from-zinc-900 to-zinc-700 bg-clip-text text-transparent">Dashboard Overview</h1>
                        <p class="text-zinc-600 mt-1 md:mt-2 text-sm md:text-lg">Welcome back! Here's what's happening today.</p>
                    </div>
                    <div class="flex items-center gap-2 px-3 md:px-4 py-2 bg-white rounded-xl shadow-md border border-zinc-200">
                        <CalendarIcon class="w-4 md:w-5 h-4 md:h-5 text-primary-600" />
                        <span class="text-xs md:text-sm font-medium text-zinc-700">{{ new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-emerald-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="0">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 md:p-3 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                            <BanknotesIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                        </div>
                        <div :class="['text-xs font-bold px-2 py-1 rounded-full', stats.revenueChange >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700']">
                            {{ stats.revenueChange >= 0 ? '+' : '' }}{{ stats.revenueChange }}%
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-emerald-600 break-all">₱{{ stats.totalRevenue }}</p>
                    <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium">Total Revenue</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-blue-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="50">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 md:p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                            <ShoppingBagIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                        </div>
                        <div class="text-xs font-bold px-2 py-1 rounded-full bg-blue-100 text-blue-700 whitespace-nowrap">
                            {{ stats.todayOrders }} today
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-blue-600">{{ stats.totalOrders }}</p>
                    <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium">Total Orders</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-purple-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 md:p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                            <UsersIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                        </div>
                        <div :class="['text-xs font-bold px-2 py-1 rounded-full whitespace-nowrap', stats.usersChange >= 0 ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700']">
                            {{ stats.usersChange >= 0 ? '+' : '' }}{{ stats.usersChange }}%
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-purple-600">{{ stats.totalUsers }}</p>
                    <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium">Active Users</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-amber-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="150">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 md:p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                            <ClockIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                        </div>
                        <div v-if="stats.pendingOrders > 10" class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700 animate-pulse whitespace-nowrap">
                            Alert
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-amber-600">{{ stats.pendingOrders }}</p>
                    <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium">Pending Orders</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 border-2 border-sky-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 md:p-3 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl shadow-lg">
                            <ChartBarIcon class="w-5 h-5 md:w-6 md:h-6 text-white" />
                        </div>
                    </div>
                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-sky-600 break-all">₱{{ stats.averageOrderValue }}</p>
                    <p class="text-xs md:text-sm text-zinc-600 mt-1 font-medium">Avg Order Value</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-pink-200 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="250">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-3 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg">
                            <ShieldCheckIcon class="w-6 h-6 text-white" />
                        </div>
                    </div>
                    <p class="text-2xl lg:text-3xl font-bold text-pink-600">{{ stats.newUsersThisWeek }}</p>
                    <p class="text-sm text-zinc-600 mt-1 font-medium">New This Week</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-aos="fade-up">
                <Link href="/admin/orders" class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <ShoppingBagIcon class="w-12 h-12 text-white/80" />
                        <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Order Management</h3>
                    <p class="text-blue-100 text-sm">Track and manage all customer orders with status updates</p>
                </Link>

                <Link href="/admin/products" class="group bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <ChartBarIcon class="w-12 h-12 text-white/80" />
                        <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Product Management</h3>
                    <p class="text-emerald-100 text-sm">Add, edit, and manage your furniture catalog</p>
                </Link>

                <Link v-if="isSuperAdmin" href="/admin/logs" class="group bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white">
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
                <Link href="/admin/edit-requests" class="group bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white relative overflow-hidden">
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

                <Link href="/admin/announcements" class="group bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white relative overflow-hidden">
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
                <div class="group bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all hover:scale-105 text-white relative overflow-hidden">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-zinc-200 p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900">Revenue Trend</h2>
                            <p class="text-sm text-zinc-500 mt-1">Last 7 days performance</p>
                        </div>
                        <BanknotesIcon class="w-8 h-8 text-emerald-500" />
                    </div>
                    <div class="flex items-end justify-between gap-2 h-48">
                        <div v-for="(day, index) in stats.last7DaysRevenue" :key="index" class="flex-1 flex flex-col items-center gap-2">
                            <div class="text-xs font-bold text-emerald-600">
                                ₱{{ day.revenue > 0 ? Math.round(day.revenue).toLocaleString() : '0' }}
                            </div>
                            <div class="w-full bg-gradient-to-t from-emerald-500 to-emerald-400 rounded-t-lg transition-all hover:opacity-80 cursor-pointer shadow-lg"
                                 :style="{ height: `${(day.revenue / maxRevenue) * 100}%` || '2%' }">
                            </div>
                            <div class="text-xs text-zinc-600 font-medium">{{ day.date }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900">Orders Status</h2>
                            <p class="text-sm text-zinc-500 mt-1">Distribution breakdown</p>
                        </div>
                        <ShoppingBagIcon class="w-8 h-8 text-blue-500" />
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                    <span class="text-sm font-medium text-zinc-700">Pending</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900">{{ stats.ordersByStatus?.pending || 0 }}</span>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2 rounded-full transition-all" :style="{ width: `${totalOrdersPercentages.pending}%` }"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                    <span class="text-sm font-medium text-zinc-700">Processing</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900">{{ stats.ordersByStatus?.processing || 0 }}</span>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all" :style="{ width: `${totalOrdersPercentages.processing}%` }"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-medium text-zinc-700">Completed</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900">{{ stats.ordersByStatus?.completed || 0 }}</span>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all" :style="{ width: `${totalOrdersPercentages.completed}%` }"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <span class="text-sm font-medium text-zinc-700">Cancelled</span>
                                </div>
                                <span class="text-sm font-bold text-zinc-900">{{ stats.ordersByStatus?.cancelled || 0 }}</span>
                            </div>
                            <div class="w-full bg-zinc-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full transition-all" :style="{ width: `${totalOrdersPercentages.cancelled}%` }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6" data-aos="fade-up">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900">Recent Orders</h2>
                            <p class="text-sm text-zinc-500 mt-1">Latest order activity</p>
                        </div>
                        <ShoppingBagIcon class="w-8 h-8 text-zinc-400" />
                    </div>
                    <div class="space-y-3">
                        <div v-for="order in stats.recentOrders" :key="order.id" class="p-4 bg-zinc-50 rounded-xl hover:bg-zinc-100 transition-colors border border-zinc-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-md">
                                        #{{ order.id }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-900">{{ order.user.name }}</p>
                                        <p class="text-xs text-zinc-500">{{ order.created_at }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-primary-600">₱{{ order.total }}</p>
                                    <p class="text-xs text-zinc-500">{{ order.items_count }} items</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-3">
                                <div :class="['inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold border', getStatusColor(order.status)]">
                                    <component :is="getStatusIcon(order.status)" class="w-3 h-3" />
                                    {{ order.status }}
                                </div>
                                <div :class="['inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold', getPaymentMethodColor(order.payment_method)]">
                                    <CreditCardIcon class="w-3 h-3" />
                                    {{ order.payment_method }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-zinc-900">Recent Users</h2>
                            <p class="text-sm text-zinc-500 mt-1">New registrations</p>
                        </div>
                        <UsersIcon class="w-8 h-8 text-zinc-400" />
                    </div>
                    <div class="space-y-3">
                        <div v-for="user in stats.recentUsers" :key="user.id" class="p-4 bg-zinc-50 rounded-xl hover:bg-zinc-100 transition-colors border border-zinc-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold shadow-md">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-zinc-900">{{ user.name }}</p>
                                        <p class="text-xs text-zinc-500">{{ user.email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span v-if="user.is_admin" class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                                        Admin
                                    </span>
                                    <p class="text-xs text-zinc-500 mt-1">{{ user.created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                                @click="promptPromote(user)"
                                                class="px-2 md:px-3 py-1 md:py-1.5 bg-emerald-100 text-emerald-700 rounded-lg font-bold hover:bg-emerald-200 transition-all text-xs whitespace-nowrap"
                                            >
                                                Promote
                                            </button>
                                            <button
                                                v-if="user.is_admin"
                                                @click="promptDemote(user)"
                                                class="px-2 md:px-3 py-1 md:py-1.5 bg-amber-100 text-amber-700 rounded-lg font-bold hover:bg-amber-200 transition-all text-xs whitespace-nowrap"
                                            >
                                                Demote
                                            </button>
                                            <button
                                                v-if="!user.is_admin"
                                                @click="promptDelete(user)"
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
                                            @click="promptRequestEdit(user)"
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
        </main>

        <ConfirmModal
            :show="showPromoteModal"
            title="Promote User to Admin"
            message="Are you sure you want to promote this user to admin? They will have access to the admin dashboard and management tools."
            confirm-text="Yes, Promote"
            cancel-text="Cancel"
            type="info"
            @close="showPromoteModal = false"
            @confirm="confirmPromote"
        />

        <ConfirmModal
            :show="showDemoteModal"
            title="Demote Admin to User"
            message="Are you sure you want to demote this admin? They will lose access to all admin features and dashboard."
            confirm-text="Yes, Demote"
            cancel-text="Cancel"
            type="danger"
            @close="showDemoteModal = false"
            @confirm="confirmDemote"
        />

        <ConfirmModal
            :show="showDeleteModal"
            title="Delete User"
            :message="`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone and will remove all their data.`"
            confirm-text="Yes, Delete"
            cancel-text="Cancel"
            type="danger"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />
    </div>

    <TransitionRoot :show="showRequestEditModal" as="template">
        <Dialog @close="showRequestEditModal = false" class="relative z-50">
            <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Request User Edit</h3>
                                <p class="text-sm text-indigo-100 mt-1">Submit request to edit {{ userToRequestEdit?.name }}</p>
                            </div>
                            <form @submit.prevent="confirmRequestEdit" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Field to Edit</label>
                                    <select
                                        v-model="requestEditForm.field_name"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    >
                                        <option value="name">Name</option>
                                        <option value="email">Email</option>
                                        <option value="password">Password</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Value</label>
                                    <input
                                        v-model="requestEditForm.new_value"
                                        :type="requestEditForm.field_name === 'password' ? 'password' : 'text'"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        :placeholder="`Enter new ${requestEditForm.field_name}`"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason for Change</label>
                                    <textarea
                                        v-model="requestEditForm.reason"
                                        rows="3"
                                        required
                                        maxlength="500"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Explain why this change is needed..."
                                    ></textarea>
                                    <p class="text-xs text-slate-500 mt-1">{{ requestEditForm.reason.length }}/500 characters</p>
                                </div>

                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-900">Approval Required</p>
                                            <p class="text-sm text-amber-700 mt-1">Your request will be submitted to the super admin for review and approval.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                    <button
                                        type="button"
                                        @click="showRequestEditModal = false"
                                        class="px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="requestEditForm.processing"
                                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ requestEditForm.processing ? 'Submitting...' : 'Submit Request' }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
