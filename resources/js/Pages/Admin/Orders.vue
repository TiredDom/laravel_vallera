<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import OrderStats from '@/Components/Admin/OrderStats.vue';
import OrderFilters from '@/Components/Admin/OrderFilters.vue';
import OrderList from '@/Components/Admin/OrderList.vue';

const props = defineProps({
    orders: Array
});

const selectedStatus = ref('all');
const searchQuery = ref('');
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
        revenue: props.orders.reduce((sum, o) => sum + (o.total || 0), 0)
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
            showStatusModal.value = false;
        }
    });
}
</script>

<template>
    <Head title="Order Management" />
    <div class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100">
        <div class="bg-white shadow-lg border-b border-zinc-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex items-center gap-3 sm:gap-4">
                    <Link :href="route('admin.dashboard')" class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                        <ArrowLeftIcon class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" />
                    </Link>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent">Order Management</h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600">Manage and track customer orders</p>
                    </div>
                </div>
            </div>
        </div>
        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <OrderStats :order-stats="orderStats" />

            <OrderFilters v-model:searchQuery="searchQuery" />

            <OrderList
                :filtered-orders="filteredOrders"
                :status-options="statusOptions"
                @updateOrderStatus="updateOrderStatus"
            />
        </main>

        <ConfirmModal
            :show="showStatusModal"
            title="Update Order Status"
            :message="`Are you sure you want to change the status of Order #${statusUpdate.orderId} to ${statusUpdate.newStatus}?`"
            confirm-text="Yes, Update"
            cancel-text="Cancel"
            type="info"
            @close="showStatusModal = false"
            @confirm="confirmStatusUpdate"
        />
    </div>
</template>
