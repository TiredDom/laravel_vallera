<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';

defineProps({
    currentUser: Object,
    isSuperAdmin: Boolean
});
</script>

<template>
    <nav class="bg-white shadow-md border-b-2 border-primary-100 sticky top-0 z-40 backdrop-blur-sm bg-white/95 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="flex justify-between items-center h-16 md:h-20">
                <Link :href="route('home')" class="flex items-center gap-2 px-3 py-2 rounded-lg text-zinc-600 hover:text-primary-600 hover:bg-primary-50 transition-all font-medium group">
                    <ArrowLeftIcon class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    <span class="hidden sm:inline">Back to Site</span>
                </Link>
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-primary-600 to-emerald-600 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg md:text-xl">V</span>
                        </div>
                        <span class="text-xl md:text-2xl font-bold bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent hidden sm:block">Vallera</span>
                    </div>
                    <span class="px-2 md:px-3 py-1 bg-gradient-to-r from-primary-600 to-emerald-600 text-white text-xs font-bold rounded-full shadow-lg">
                        {{ isSuperAdmin ? 'SUPER ADMIN' : 'ADMIN' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-bold text-zinc-900">{{ currentUser?.name }}</p>
                        <p class="text-xs text-zinc-500">{{ currentUser?.email }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-primary-200">
                        {{ currentUser?.name?.charAt(0).toUpperCase() }}
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
