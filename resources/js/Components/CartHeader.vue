<template>
    <div class="relative bg-gradient-to-br from-primary-600 via-emerald-700 to-emerald-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-10"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                    <ShoppingBagIcon class="w-4 h-4" />
                    <span class="text-sm font-medium">{{ itemCount }} {{ itemCount === 1 ? 'Item' : 'Items' }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">
                    Your Shopping Cart
                </h1>
                <p class="text-lg text-emerald-100">
                    Review your items and proceed to secure checkout
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ShoppingBagIcon } from '@heroicons/vue/24/outline';

defineProps({
    itemCount: {
        type: Number,
        default: 0
    }
});
</script>
