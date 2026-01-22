<template>
    <div class="max-w-2xl mx-auto text-center py-24 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-primary-100 to-emerald-100 rounded-full flex items-center justify-center mb-6">
            <ShoppingBagIcon class="w-12 h-12 text-primary-600" />
        </div>
        <h2 class="text-3xl font-bold text-zinc-900 mb-3">Sign in to view your cart</h2>
        <p class="text-lg text-zinc-600 mb-8">Please sign in or create an account to start shopping.</p>
        <button @click="$emit('openAuthModal')" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold px-8 py-4 rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-lg">
            <SparklesIcon class="w-5 h-5" />
            Sign In / Sign Up
        </button>
    </div>
</template>

<script setup>
import { ShoppingBagIcon, SparklesIcon } from '@heroicons/vue/24/outline';

defineEmits(['openAuthModal']);
</script>
