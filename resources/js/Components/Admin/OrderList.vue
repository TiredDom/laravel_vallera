<script setup>
import { ShoppingBagIcon, ChevronDownIcon } from '@heroicons/vue/24/outline';

defineProps({
    filteredOrders: Array,
    statusOptions: Array
});

defineEmits(['updateOrderStatus']);
</script>

<template>
    <div class="divide-y divide-zinc-200">
        <div v-if="filteredOrders.length === 0" class="p-12 text-center">
            <ShoppingBagIcon class="w-16 h-16 text-zinc-300 mx-auto mb-4" />
            <p class="text-xl font-semibold text-zinc-400">No orders found</p>
        </div>
        <div v-for="order in filteredOrders" :key="order.id" class="p-4 sm:p-6 mb-4 bg-white rounded-xl shadow border border-zinc-100 flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <div class="font-bold text-lg text-zinc-900">Order #{{ order.id }}</div>
                    <div class="text-xs text-zinc-500">{{ order.user?.name }}</div>
                    <div class="text-xs text-zinc-400">{{ order.created_at }}</div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="relative">
                        <select :value="order.status" @change="$emit('updateOrderStatus', order.id, $event.target.value)"
                            :class="[
                                'appearance-none pr-8 pl-4 py-2 rounded-lg border text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all',
                                order.status === 'pending' ? 'bg-amber-100 text-amber-700 border-amber-300' : '',
                                order.status === 'completed' ? 'bg-emerald-100 text-emerald-700 border-emerald-300' : '',
                                order.status === 'cancelled' ? 'bg-red-100 text-red-700 border-red-300' : '',
                                order.status === 'processing' ? 'bg-blue-100 text-blue-700 border-blue-300' : ''
                            ]">
                            <option v-for="option in statusOptions.filter(s => s.value !== 'all')" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                        <ChevronDownIcon class="w-5 h-5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"
                            :class="[
                                order.status === 'pending' ? 'text-amber-500' : '',
                                order.status === 'completed' ? 'text-emerald-500' : '',
                                order.status === 'cancelled' ? 'text-red-500' : '',
                                order.status === 'processing' ? 'text-blue-500' : ''
                            ]" />
                    </div>
                    <div class="text-base font-bold text-emerald-700">₱{{ order.total }}</div>
                </div>
            </div>
            <div class="bg-zinc-50 rounded-lg p-3 mt-2">
                <div class="font-semibold text-zinc-700 mb-2">Products</div>
                <div v-for="item in order.items" :key="item.id" class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-4 border-b border-zinc-100 last:border-b-0 py-2">
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-zinc-900">{{ item.product_name || item.name || item.title || 'Unnamed Product' }}</div>
                        <div class="text-xs text-zinc-500">x{{ item.quantity }}</div>
                    </div>
                    <div class="text-xs text-zinc-500">₱{{ item.price }}</div>
                </div>
                <div class="flex justify-end mt-2">
                    <div class="font-bold text-zinc-900">Total: <span class="text-emerald-700">₱{{ order.total }}</span></div>
                </div>
            </div>
        </div>
    </div>
</template>
