<script setup>
import { ShoppingBagIcon, UsersIcon, ClockIcon, TruckIcon, CheckCircleIcon, XCircleIcon, CreditCardIcon, BanknotesIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    stats: Object
});

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
</template>
