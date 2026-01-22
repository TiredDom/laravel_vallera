<script setup>
import { ClockIcon } from '@heroicons/vue/24/outline';

defineProps({
    searchQuery: String,
    filteredCount: Number,
    totalCount: Number
});

defineEmits(['update:searchQuery']);
</script>

<template>
    <div class="p-6 border-b border-zinc-200">
        <div class="flex items-center justify-between mb-4">
            <input
                :value="searchQuery"
                @input="$emit('update:searchQuery', $event.target.value)"
                type="text"
                placeholder="Search logs by user, action, or description..."
                class="flex-1 px-4 py-3 rounded-xl border-2 border-zinc-200 focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all"
            />
        </div>
        <div class="flex items-center gap-2 text-sm text-zinc-600">
            <ClockIcon class="w-4 h-4" />
            <span>Showing {{ filteredCount }} of {{ totalCount }} total logs</span>
        </div>
    </div>
</template>
