<template>
    <div class="sticky top-24 space-y-4">
        <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6">
            <h2 class="text-xl font-bold text-zinc-900 mb-6">Order Summary</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between text-zinc-600">
                    <p>Subtotal</p>
                    <p class="font-semibold text-zinc-900">₱{{ Number(subtotal).toLocaleString() }}</p>
                </div>
                <div class="flex items-center justify-between text-zinc-600">
                    <p>Shipping Fee</p>
                    <p class="font-semibold text-emerald-600">₱{{ Number(shipping).toLocaleString() }}</p>
                </div>
                <div class="border-t-2 border-zinc-200 pt-4">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-bold text-zinc-900">Total</p>
                        <p class="text-2xl font-bold text-primary-600">₱{{ Number(total).toLocaleString() }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <button class="w-full bg-gradient-to-r from-primary-600 to-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all" @click="$emit('checkout')">
                    Proceed to Checkout
                </button>
            </div>
            <Link href="/products" class="block text-center mt-4 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                Continue Shopping
            </Link>
        </div>

        <div class="bg-gradient-to-br from-zinc-50 to-white rounded-2xl border border-zinc-200 p-6">
            <h3 class="text-sm font-bold text-zinc-900 mb-3 uppercase tracking-wider">Secure Checkout</h3>
            <div class="space-y-3 text-sm text-zinc-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>SSL Encrypted Payment</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>30-Day Return Policy</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>5-Year Warranty</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    subtotal: {
        type: Number,
        required: true
    },
    shipping: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    }
});

defineEmits(['checkout']);
</script>
