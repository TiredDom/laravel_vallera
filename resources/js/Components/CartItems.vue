<template>
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 overflow-hidden">
            <div class="bg-gradient-to-r from-zinc-50 to-white px-6 py-4 border-b border-zinc-200">
                <h2 class="text-xl font-bold text-zinc-900">Cart Items</h2>
            </div>
            <ul role="list" class="divide-y divide-zinc-200">
                <li v-for="item in cartItems" :key="item.product_id || item.id" class="p-6 hover:bg-zinc-50 transition-colors group">
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 rounded-xl overflow-hidden shadow-sm group-hover:shadow-md transition-shadow">
                            <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="w-full h-full object-cover">
                            <div v-else class="w-full h-full bg-gradient-to-br from-zinc-100 to-zinc-200 flex items-center justify-center">
                                <svg class="w-12 h-12 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-zinc-900 mb-1">{{ item.name }}</h3>
                                        <p class="text-sm text-zinc-500 uppercase tracking-wider">{{ item.category }}</p>
                                    </div>
                                    <p class="text-xl font-bold text-primary-600 ml-4">₱{{ (item.price * item.quantity).toLocaleString() }}</p>
                                </div>
                                <p class="text-sm text-zinc-600 mt-2">₱{{ item.price.toLocaleString() }} each</p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-3 bg-zinc-100 rounded-xl p-1">
                                    <button
                                        @click="$emit('updateQuantity', { productId: item.product_id || item.id, quantity: item.quantity - 1, stock: item.stock })"
                                        :disabled="item.quantity <= 1"
                                        :class="['w-10 h-10 rounded-lg',
                                            item.quantity <= 1 ? 'bg-zinc-200 text-zinc-400 cursor-not-allowed' : 'bg-white hover:bg-primary-600 hover:text-white',
                                            'transition-all flex items-center justify-center shadow-sm']"
                                        aria-label="Decrease quantity"
                                    >
                                        <MinusIcon class="w-4 h-4" />
                                    </button>
                                    <input
                                        type="number"
                                        :value="item.quantity"
                                        min="1"
                                        :max="item.stock"
                                        @input="e => {
                                            let val = e.target.value.replace(/[^0-9]/g, '');
                                            val = val ? Math.max(1, Math.min(Number(val), item.stock)) : 1;
                                            if (val != item.quantity) $emit('updateQuantity', { productId: item.product_id || item.id, quantity: val, stock: item.stock });
                                        }"
                                        @blur="e => {
                                            let val = e.target.value.replace(/[^0-9]/g, '');
                                            val = val ? Math.max(1, Math.min(Number(val), item.stock)) : 1;
                                            if (val != item.quantity) $emit('updateQuantity', { productId: item.product_id || item.id, quantity: val, stock: item.stock });
                                        }"
                                        class="w-16 text-center font-bold text-zinc-900 rounded-lg border border-zinc-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all py-2 px-2 bg-white"
                                        aria-label="Product quantity"
                                    />
                                    <button
                                        @click="$emit('updateQuantity', { productId: item.product_id || item.id, quantity: item.quantity + 1, stock: item.stock })"
                                        :disabled="item.quantity >= item.stock"
                                        :class="['w-10 h-10 rounded-lg',
                                            item.quantity >= item.stock ? 'bg-red-100 text-red-400 cursor-not-allowed' : 'bg-white hover:bg-primary-600 hover:text-white',
                                            'transition-all flex items-center justify-center shadow-sm']"
                                        aria-label="Increase quantity"
                                    >
                                        <PlusIcon class="w-4 h-4" />
                                    </button>
                                </div>
                                <button type="button" class="inline-flex items-center gap-2 font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors" @click="$emit('removeItem', item.product_id || item.id)">
                                    <TrashIcon class="w-4 h-4" />
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6">
            <h3 class="text-lg font-bold text-zinc-900 mb-4">Why Shop With Us?</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <ShieldCheckIcon class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-900 text-sm">Secure Payment</p>
                        <p class="text-xs text-zinc-600">100% Protected</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <TruckIcon class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-900 text-sm">Free Shipping</p>
                        <p class="text-xs text-zinc-600">All Orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { PlusIcon, MinusIcon, TrashIcon, ShieldCheckIcon, TruckIcon } from '@heroicons/vue/24/outline';

defineProps({
    cartItems: {
        type: Array,
        default: () => []
    }
});

defineEmits(['updateQuantity', 'removeItem']);
</script>
