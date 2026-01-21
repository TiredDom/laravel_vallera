<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { ShoppingBagIcon, ClockIcon, CheckCircleIcon, XCircleIcon, TruckIcon, CreditCardIcon, FunnelIcon, CalendarIcon } from '@heroicons/vue/24/outline';

const page = usePage();
const props = defineProps({
    orders: Array
});

const statusFilter = ref('all');
const toast = ref({ show: false, message: '', type: 'success' });
const showCancelModal = ref(false);
const orderToCancel = ref(null);

watch(() => page.props.flash?.success, (message) => {
    if (message) {
        showToast(message, 'success');
    }
});

watch(() => page.props.flash?.error, (message) => {
    if (message) {
        showToast(message, 'error');
    }
});

function showToast(message, type = 'success') {
    toast.value = { show: true, message, type };
}

function hideToast() {
    toast.value.show = false;
}

const filteredOrders = computed(() => {
    if (statusFilter.value === 'all') return props.orders || [];
    return (props.orders || []).filter(order => order.status === statusFilter.value);
});

const stats = computed(() => {
    const allOrders = props.orders || [];
    return {
        total: allOrders.length,
        pending: allOrders.filter(o => o.status === 'pending').length,
        completed: allOrders.filter(o => o.status === 'completed').length,
        processing: allOrders.filter(o => o.status === 'processing').length,
        cancelled: allOrders.filter(o => o.status === 'cancelled').length,
    };
});

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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

function getStatusColor(status) {
    const colors = {
        pending: 'bg-amber-50 border-amber-200 text-amber-700',
        processing: 'bg-blue-50 border-blue-200 text-blue-700',
        completed: 'bg-emerald-50 border-emerald-200 text-emerald-700',
        cancelled: 'bg-red-50 border-red-200 text-red-700'
    };
    return colors[status] || 'bg-zinc-50 border-zinc-200 text-zinc-700';
}

function getPaymentMethodDisplay(method) {
    const methods = {
        gcash: 'GCash',
        card: 'Credit/Debit Card',
        bank: 'Bank Transfer',
        cash: 'Cash on Delivery'
    };
    return methods[method] || method || 'N/A';
}

function getPaymentMethodColor(method) {
    const colors = {
        gcash: 'text-blue-600 bg-blue-50',
        card: 'text-purple-600 bg-purple-50',
        bank: 'text-emerald-600 bg-emerald-50',
        cash: 'text-zinc-600 bg-zinc-100'
    };
    return colors[method] || 'text-zinc-600 bg-zinc-100';
}

function canCancelOrder(status) {
    return status === 'pending' || status === 'processing';
}

function cancelOrder(orderId) {
    orderToCancel.value = orderId;
    showCancelModal.value = true;
}

function confirmCancel() {
    if (orderToCancel.value) {
        router.post(`/orders/${orderToCancel.value}/cancel`, {}, {
            preserveScroll: true,
            onFinish: () => {
                orderToCancel.value = null;
            }
        });
    }
}
</script>

<template>
    <Head title="My Orders" />
    <MainLayout>
        <ToastNotification
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            @close="hideToast"
        />

        <div class="relative bg-gradient-to-br from-primary-600 via-emerald-700 to-emerald-800 text-white overflow-hidden">
            <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-10"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 relative">
                <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                        <ShoppingBagIcon class="w-4 h-4" />
                        <span class="text-sm font-medium">{{ stats.total }} {{ stats.total === 1 ? 'Order' : 'Orders' }}</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-4">
                        My Orders
                    </h1>
                    <p class="text-xl text-emerald-100 max-w-2xl mx-auto">
                        Track and manage all your furniture orders
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-zinc-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div v-if="orders && orders.length > 0" class="space-y-8">
                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6" data-aos="fade-up">
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="p-3 bg-blue-100 rounded-xl">
                                    <ShoppingBagIcon class="w-6 h-6 text-blue-600" />
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-blue-600">{{ stats.total }}</p>
                            <p class="text-sm text-zinc-600 mt-1">Total Orders</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-amber-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="p-3 bg-amber-100 rounded-xl">
                                    <ClockIcon class="w-6 h-6 text-amber-600" />
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-amber-600">{{ stats.pending }}</p>
                            <p class="text-sm text-zinc-600 mt-1">Pending</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-sky-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="p-3 bg-sky-100 rounded-xl">
                                    <TruckIcon class="w-6 h-6 text-sky-600" />
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-sky-600">{{ stats.processing }}</p>
                            <p class="text-sm text-zinc-600 mt-1">Processing</p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-lg p-6 border-2 border-emerald-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="p-3 bg-emerald-100 rounded-xl">
                                    <CheckCircleIcon class="w-6 h-6 text-emerald-600" />
                                </div>
                            </div>
                            <p class="text-3xl font-bold text-emerald-600">{{ stats.completed }}</p>
                            <p class="text-sm text-zinc-600 mt-1">Completed</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center gap-3 mb-6">
                            <FunnelIcon class="w-5 h-5 text-zinc-600" />
                            <h2 class="text-lg font-bold text-zinc-900">Filter Orders</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button
                                @click="statusFilter = 'all'"
                                :class="[
                                    'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all',
                                    statusFilter === 'all'
                                        ? 'bg-primary-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                All Orders
                            </button>
                            <button
                                @click="statusFilter = 'pending'"
                                :class="[
                                    'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all',
                                    statusFilter === 'pending'
                                        ? 'bg-amber-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                Pending
                            </button>
                            <button
                                @click="statusFilter = 'processing'"
                                :class="[
                                    'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all',
                                    statusFilter === 'processing'
                                        ? 'bg-blue-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                Processing
                            </button>
                            <button
                                @click="statusFilter = 'completed'"
                                :class="[
                                    'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all',
                                    statusFilter === 'completed'
                                        ? 'bg-emerald-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                Completed
                            </button>
                            <button
                                @click="statusFilter = 'cancelled'"
                                :class="[
                                    'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all',
                                    statusFilter === 'cancelled'
                                        ? 'bg-red-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                Cancelled
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div v-for="order in filteredOrders" :key="order.id" class="bg-white rounded-2xl shadow-lg border border-zinc-200 overflow-hidden hover:shadow-xl transition-all" data-aos="fade-up">
                            <div class="p-6 bg-gradient-to-r from-zinc-50 to-white border-b border-zinc-200">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center shadow-lg">
                                            <ShoppingBagIcon class="w-7 h-7 text-white" />
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-zinc-900">Order #{{ order.id }}</h3>
                                            <div class="flex items-center gap-2 mt-1 text-sm text-zinc-500">
                                                <CalendarIcon class="w-4 h-4" />
                                                <span>{{ formatDate(order.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4">
                                        <div :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-sm', getPaymentMethodColor(order.payment_method)]">
                                            <CreditCardIcon class="w-4 h-4" />
                                            {{ getPaymentMethodDisplay(order.payment_method) }}
                                        </div>
                                        <div :class="['inline-flex items-center gap-2 px-4 py-2 rounded-xl border-2 font-bold text-sm', getStatusColor(order.status)]">
                                            <component :is="getStatusIcon(order.status)" class="w-5 h-5" />
                                            <span class="capitalize">{{ order.status }}</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-zinc-500 font-medium">Total</p>
                                            <p class="text-2xl font-bold text-primary-600">₱{{ order.total.toLocaleString() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="canCancelOrder(order.status)" class="mt-4 flex justify-end">
                                    <button
                                        @click="cancelOrder(order.id)"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all hover:scale-105 shadow-lg"
                                    >
                                        <XCircleIcon class="w-5 h-5" />
                                        Cancel Order
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <h4 class="text-sm font-bold text-zinc-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Order Items
                                </h4>
                                <div class="space-y-3">
                                    <div v-for="(item, index) in order.items" :key="index" class="flex items-center gap-4 p-5 bg-zinc-50 rounded-xl hover:bg-zinc-100 transition-colors border border-zinc-200">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-zinc-200 to-zinc-300 rounded-lg flex items-center justify-center">
                                            <span class="text-zinc-400 text-xs font-medium">IMG</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-bold text-zinc-900">{{ item.name }}</p>
                                            <p class="text-sm text-zinc-500 uppercase tracking-wider">{{ item.category }}</p>
                                        </div>
                                        <div class="flex items-center gap-8">
                                            <div class="text-center">
                                                <p class="text-xs text-zinc-500 font-medium uppercase">Qty</p>
                                                <p class="text-lg font-bold text-zinc-900">{{ item.quantity }}</p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-zinc-500 font-medium uppercase">Price</p>
                                                <p class="text-lg font-bold text-zinc-900">₱{{ item.price.toLocaleString() }}</p>
                                            </div>
                                            <div class="text-right min-w-[120px]">
                                                <p class="text-xs text-zinc-500 font-medium uppercase">Subtotal</p>
                                                <p class="text-xl font-bold text-primary-600">₱{{ (item.price * item.quantity).toLocaleString() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="filteredOrders.length === 0" class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-12 text-center" data-aos="fade-up">
                        <FunnelIcon class="w-16 h-16 text-zinc-300 mx-auto mb-4" />
                        <h3 class="text-2xl font-bold text-zinc-900 mb-2">No {{ statusFilter }} orders found</h3>
                        <p class="text-zinc-500 mb-6">Try adjusting your filter</p>
                        <button @click="statusFilter = 'all'" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all hover:scale-105">
                            View All Orders
                        </button>
                    </div>
                </div>

                <div v-else class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-16 text-center" data-aos="fade-up">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-zinc-100 to-zinc-200 rounded-full mb-6">
                        <ShoppingBagIcon class="w-12 h-12 text-zinc-400" />
                    </div>
                    <h3 class="text-3xl font-bold text-zinc-900 mb-3">No Orders Yet</h3>
                    <p class="text-lg text-zinc-600 mb-8 max-w-md mx-auto">Start shopping to see your order history here. Discover our premium furniture collection.</p>
                    <Link :href="route('products.index')" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-primary-600 to-emerald-600 text-white font-bold rounded-xl hover:shadow-xl transition-all hover:scale-105">
                        Browse Products
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>

                <div class="mt-12 text-center">
                    <Link :href="route('home')" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-bold text-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Home
                    </Link>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="showCancelModal"
            title="Cancel Order"
            message="Are you sure you want to cancel this order? This action cannot be undone and your order will be permanently cancelled."
            confirm-text="Yes, Cancel Order"
            cancel-text="Keep Order"
            type="danger"
            @close="showCancelModal = false"
            @confirm="confirmCancel"
        />
    </MainLayout>
</template>
