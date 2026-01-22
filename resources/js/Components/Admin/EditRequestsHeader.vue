<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    totalRequests: Number
});

defineEmits(['refresh']);
</script>

<template>
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3 sm:gap-4">
                    <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </Link>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                            Edit Requests
                        </h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600">Review admin requests</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        @click="$emit('refresh')"
                        class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0"
                        title="Refresh requests"
                    >
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                    <div class="flex items-center gap-2 px-3 py-2 sm:px-4 sm:py-2 bg-amber-50 rounded-lg border border-amber-200 flex-1 sm:flex-none">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs sm:text-sm font-medium text-amber-900 truncate">{{ totalRequests || 0 }} Total</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
