<script setup>
import { Link } from '@inertiajs/vue3';

defineEmits(['refresh', 'create']);
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
                        <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
                            Announcements
                        </h1>
                        <p class="mt-1 text-sm sm:text-base text-slate-600">Create and manage announcements</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button
                        @click="$emit('refresh')"
                        class="flex-1 sm:flex-none px-3 py-2 sm:px-4 sm:py-2 bg-white text-slate-700 border border-slate-300 rounded-lg font-semibold hover:bg-slate-50 transition-all shadow hover:shadow-md flex items-center justify-center gap-2 text-sm sm:text-base"
                        title="Refresh announcements"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                    <button
                        @click="$emit('create')"
                        class="flex-1 sm:flex-none px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
