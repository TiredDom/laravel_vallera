<template>
    <div class="max-w-2xl mx-auto text-center py-24 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-zinc-100 to-zinc-200 rounded-full flex items-center justify-center mb-6">
            <ShoppingBagIcon class="w-12 h-12 text-zinc-400" />
        </div>
        <h2 class="text-3xl font-bold text-zinc-900 mb-3">Your cart is empty</h2>
        <p class="text-lg text-zinc-600 mb-8">Discover our collection of premium furniture</p>
        <Link href="/products" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold px-8 py-4 rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-lg">
            Continue Shopping
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </Link>
    </div>
</template>

<script setup>
import { ShoppingBagIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
</script>
