<script setup>
import { ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <ChatBubbleLeftRightIcon class="w-4 h-4" />
                    <span class="text-sm font-medium">We're Here to Help</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 break-words">
                    Get in Touch
                </h1>
                <p class="text-xl text-primary-100 max-w-2xl mx-auto break-words">
                    Have a question? Want to visit our showroom? We'd love to hear from you.
                </p>
            </div>
        </div>
    </div>
</template>
