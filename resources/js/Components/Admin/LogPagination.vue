<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';

defineProps({
    pagination: Object
});

defineEmits(['goToPage']);
</script>

<template>
    <div v-if="pagination.last_page > 1" class="px-6 py-4 border-t border-zinc-200 bg-zinc-50">
        <div class="flex items-center justify-between">
            <button
                @click="$emit('goToPage', pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="flex items-center gap-2 px-4 py-2 rounded-lg font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed bg-white border-2 border-zinc-200 text-zinc-700 hover:bg-zinc-100"
            >
                <ChevronLeftIcon class="w-4 h-4" />
                Previous
            </button>

            <div class="flex items-center gap-2">
                <template v-for="page in pagination.last_page" :key="page">
                    <button
                        v-if="Math.abs(page - pagination.current_page) < 3 || page === 1 || page === pagination.last_page"
                        @click="$emit('goToPage', page)"
                        :class="[
                            'w-10 h-10 rounded-lg font-bold transition-all',
                            page === pagination.current_page
                                ? 'bg-gradient-to-r from-primary-600 to-emerald-600 text-white shadow-lg'
                                : 'bg-white border-2 border-zinc-200 text-zinc-700 hover:bg-zinc-100'
                        ]"
                    >
                        {{ page }}
                    </button>
                    <span v-else-if="Math.abs(page - pagination.current_page) === 3" class="text-zinc-400">...</span>
                </template>
            </div>

            <button
                @click="$emit('goToPage', pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="flex items-center gap-2 px-4 py-2 rounded-lg font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed bg-white border-2 border-zinc-200 text-zinc-700 hover:bg-zinc-100"
            >
                Next
                <ChevronRightIcon class="w-4 h-4" />
            </button>
        </div>
    </div>
</template>
