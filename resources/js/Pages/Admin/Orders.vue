<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import {
    ArrowLeftIcon, ShoppingBagIcon, ClockIcon, CheckCircleIcon,
    XCircleIcon, TruckIcon, CreditCardIcon, UserIcon, ChevronDownIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    orders: Array
});

const selectedStatus = ref('all');
const searchQuery = ref('');
const expandedOrder = ref(null);
const showStatusModal = ref(false);
const statusUpdate = ref({ orderId: null, newStatus: '' });

const statusOptions = [
    { value: 'all', label: 'All Orders', color: 'zinc' },
    { value: 'pending', label: 'Pending', color: 'amber' },
    { value: 'processing', label: 'Processing', color: 'blue' },
    { value: 'completed', label: 'Completed', color: 'emerald' },
    { value: 'cancelled', label: 'Cancelled', color: 'red' }
];

const filteredOrders = computed(() => {
    let filtered = props.orders;

    if (selectedStatus.value !== 'all') {
        filtered = filtered.filter(order => order.status === selectedStatus.value);
    }

    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(order =>
            order.id.toString().includes(query) ||
            order.user.name.toLowerCase().includes(query) ||
            order.user.email.toLowerCase().includes(query)
        );
    }

    return filtered;
});

const orderStats = computed(() => {
    return {
        total: props.orders.length,
        pending: props.orders.filter(o => o.status === 'pending').length,
        processing: props.orders.filter(o => o.status === 'processing').length,
        completed: props.orders.filter(o => o.status === 'completed').length,
        cancelled: props.orders.filter(o => o.status === 'cancelled').length,
    };
});

function updateOrderStatus(orderId, newStatus) {
    statusUpdate.value = { orderId, newStatus };
    showStatusModal.value = true;
}

function confirmStatusUpdate() {
    router.patch(`/admin/orders/${statusUpdate.value.orderId}/status`, { status: statusUpdate.value.newStatus }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            expandedOrder.value = null;
            showStatusModal.value = false;
        }
    });
}

function toggleOrder(orderId) {
    expandedOrder.value = expandedOrder.value === orderId ? null : orderId;
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

function getPaymentColor(method) {
    const colors = {
        gcash: 'text-blue-600 bg-blue-50',
        card: 'text-purple-600 bg-purple-50',
        bank: 'text-emerald-600 bg-emerald-50',
    };
    return colors[method] || 'text-zinc-600 bg-zinc-100';
}
</script>

<template>
    <Head title="Order Management" />

    <div class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100">
        <nav class="bg-white shadow-lg border-b border-zinc-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 sm:h-16 gap-3 sm:gap-0">
                    <Link :href="route('admin.dashboard')" class="flex items-center text-zinc-600 hover:text-primary-600 transition-colors font-medium text-sm sm:text-base">
                        <ArrowLeftIcon class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" />
                        <span class="whitespace-nowrap">Back to Dashboard</span>
                    </Link>
                    <div class="flex items-center justify-center">
                        <h1 class="text-lg sm:text-2xl font-bold bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent text-center">Order Management</h1>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="mb-8" data-aos="fade-up">
                <h1 class="text-4xl font-bold text-zinc-900 mb-2">Orders</h1>
                <p class="text-zinc-600 text-lg">Manage and track all customer orders</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-zinc-200">
                    <div class="flex items-center justify-between mb-2">
                        <ShoppingBagIcon class="w-8 h-8 text-zinc-500" />
                        <span class="text-2xl font-bold text-zinc-900">{{ orderStats.total }}</span>
                    </div>
                    <p class="text-sm font-medium text-zinc-600">Total Orders</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-amber-200">
                    <div class="flex items-center justify-between mb-2">
                        <ClockIcon class="w-8 h-8 text-amber-600" />
                        <span class="text-2xl font-bold text-amber-600">{{ orderStats.pending }}</span>
                    </div>
                    <p class="text-sm font-medium text-zinc-600">Pending</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <TruckIcon class="w-8 h-8 text-blue-600" />
                        <span class="text-2xl font-bold text-blue-600">{{ orderStats.processing }}</span>
                    </div>
                    <p class="text-sm font-medium text-zinc-600">Processing</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <CheckCircleIcon class="w-8 h-8 text-emerald-600" />
                        <span class="text-2xl font-bold text-emerald-600">{{ orderStats.completed }}</span>
                    </div>
                    <p class="text-sm font-medium text-zinc-600">Completed</p>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-red-200">
                    <div class="flex items-center justify-between mb-2">
                        <XCircleIcon class="w-8 h-8 text-red-600" />
                        <span class="text-2xl font-bold text-red-600">{{ orderStats.cancelled }}</span>
                    </div>
                    <p class="text-sm font-medium text-zinc-600">Cancelled</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 mb-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-6 border-b border-zinc-200">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search by order ID, customer name, or email..."
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all"
                            />
                        </div>
                        <div class="md:w-64">
                            <select
                                v-model="selectedStatus"
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all font-medium"
                            >
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-zinc-200">
                    <div v-if="filteredOrders.length === 0" class="p-12 text-center">
                        <ShoppingBagIcon class="w-16 h-16 text-zinc-300 mx-auto mb-4" />
                        <p class="text-xl font-semibold text-zinc-400">No orders found</p>
                    </div>

                    <div v-for="order in filteredOrders" :key="order.id" class="p-6 hover:bg-zinc-50 transition-colors">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    #{{ order.id }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <UserIcon class="w-4 h-4 text-zinc-400" />
                                        <span class="font-bold text-zinc-900">{{ order.user.name }}</span>
                                    </div>
                                    <p class="text-sm text-zinc-500">{{ order.user.email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-primary-600">₱{{ order.total }}</p>
                                    <p class="text-sm text-zinc-500">{{ order.items_count }} items</p>
                                </div>
                                <button
                                    @click="toggleOrder(order.id)"
                                    class="p-2 hover:bg-zinc-200 rounded-lg transition-colors"
                                >
                                    <ChevronDownIcon
                                        class="w-5 h-5 text-zinc-600 transition-transform"
                                        :class="{ 'rotate-180': expandedOrder === order.id }"
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <div :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold border', getStatusColor(order.status)]">
                                <component :is="getStatusIcon(order.status)" class="w-4 h-4" />
                                {{ order.status }}
                            </div>
                            <div :class="['inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold', getPaymentColor(order.payment_method)]">
                                <CreditCardIcon class="w-4 h-4" />
                                {{ order.payment_method }}
                            </div>
                            <span class="text-sm text-zinc-500">{{ order.created_at }}</span>
                        </div>

                        <div v-if="expandedOrder === order.id" class="mt-4 pt-4 border-t border-zinc-200 space-y-4">
                            <div>
                                <h4 class="font-bold text-zinc-900 mb-3">Order Items</h4>
                                <div class="space-y-2">
                                    <div v-for="(item, index) in order.items" :key="index" class="flex items-center justify-between p-3 bg-zinc-50 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-zinc-900">{{ item.product_name }}</p>
                                            <p class="text-sm text-zinc-500">Quantity: {{ item.quantity }}</p>
                                        </div>
                                        <p class="font-bold text-zinc-900">₱{{ item.price }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-zinc-900 mb-3">Update Status</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="status in ['pending', 'processing', 'completed', 'cancelled']"
                                        :key="status"
                                        @click="updateOrderStatus(order.id, status)"
                                        :disabled="order.status === status"
                                        :class="[
                                            'px-4 py-2 rounded-lg font-bold transition-all',
                                            order.status === status
                                                ? 'bg-zinc-200 text-zinc-400 cursor-not-allowed'
                                                : 'bg-primary-600 text-white hover:bg-primary-700 hover:scale-105'
                                        ]"
                                    >
                                        {{ status.charAt(0).toUpperCase() + status.slice(1) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <ConfirmModal
            :show="showStatusModal"
            title="Update Order Status"
            :message="`Change order status to ${statusUpdate.newStatus}?`"
            confirm-text="Yes, Update"
            cancel-text="Cancel"
            type="primary"
            @close="showStatusModal = false"
            @confirm="confirmStatusUpdate"
        />
    </div>
</template>
