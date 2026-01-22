<script setup>
import { Squares2X2Icon, FunnelIcon } from '@heroicons/vue/24/outline';

defineProps({
    stats: Object,
    activeFilter: String,
    categoryDescriptions: Object
});
</script>

<template>
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6">
                    Discover Your Perfect Piece
                </h1>
                <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto mb-8">
                    {{ categoryDescriptions[activeFilter] }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <Squares2X2Icon class="w-5 h-5 text-primary-200" />
                        <span>{{ stats.total }} Products</span>
                    </div>
                    <div class="w-1 h-1 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <FunnelIcon class="w-5 h-5 text-primary-200" />
                        <span>{{ stats.categories }} Categories</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
