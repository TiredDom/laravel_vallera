<script setup>
import { FunnelIcon } from '@heroicons/vue/24/outline';

defineProps({
    filters: Array,
    activeFilter: String,
    sortBy: String
});

defineEmits(['update:activeFilter', 'update:sortBy', 'openDrawer']);
</script>

<template>
    <!-- Desktop Filter Bar -->
    <div class="hidden md:block bg-white border-b border-zinc-200 sticky top-[80px] z-40 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 md:py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 md:gap-6">
                    <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0" data-aos="fade-right">
                        <div class="flex gap-2 min-w-max md:min-w-0 md:flex-wrap">
                            <button
                                v-for="filter in filters"
                                :key="filter"
                                @click="$emit('update:activeFilter', filter)"
                                :class="[
                                    'px-4 md:px-5 py-2 md:py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 whitespace-nowrap',
                                    activeFilter === filter
                                        ? 'bg-primary-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                {{ filter }}
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0" data-aos="fade-left">
                        <label class="text-sm font-medium text-zinc-700 hidden sm:block">Sort:</label>
                        <select
                            :value="sortBy"
                            @change="$emit('update:sortBy', $event.target.value)"
                            class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2.5 bg-zinc-100 border-0 rounded-lg text-sm font-medium text-zinc-700 focus:ring-2 focus:ring-primary-500 cursor-pointer"
                        >
                            <option value="name">Name (A-Z)</option>
                            <option value="name-desc">Name (Z-A)</option>
                            <option value="price-low">Price (Low-High)</option>
                            <option value="price-high">Price (High-Low)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Button (Sticky Bottom) -->
    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40">
        <button
            @click="$emit('openDrawer')"
            class="flex items-center gap-2 bg-zinc-900 text-white px-6 py-3 rounded-full shadow-xl hover:scale-105 transition-transform font-medium text-sm"
        >
            <FunnelIcon class="w-5 h-5" />
            Filter & Sort
        </button>
    </div>
</template>
