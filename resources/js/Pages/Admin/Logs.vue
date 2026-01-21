<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    ArrowLeftIcon, ClockIcon, ShieldCheckIcon,
    ChevronLeftIcon, ChevronRightIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    logs: Array,
    pagination: Object
});

const searchQuery = ref('');

const filteredLogs = computed(() => {
    if (!searchQuery.value) return props.logs;

    const query = searchQuery.value.toLowerCase();
    return props.logs.filter(log =>
        log.user.name.toLowerCase().includes(query) ||
        log.user.email.toLowerCase().includes(query) ||
        log.action.toLowerCase().includes(query) ||
        log.description.toLowerCase().includes(query)
    );
});

function goToPage(page) {
    router.get(`/admin/logs?page=${page}`, {}, {
        preserveScroll: true,
        preserveState: true
    });
}

function getActionColor(action) {
    const colors = {
        promote_user: 'bg-emerald-100 text-emerald-700',
        demote_user: 'bg-red-100 text-red-700',
        update_order_status: 'bg-blue-100 text-blue-700',
        create_order: 'bg-purple-100 text-purple-700',
        cancel_order: 'bg-amber-100 text-amber-700',
    };
    return colors[action] || 'bg-zinc-100 text-zinc-700';
}
</script>

<template>
    <Head title="Audit Logs" />

    <div class="min-h-screen bg-gradient-to-br from-zinc-50 to-zinc-100">
        <nav class="bg-white shadow-lg border-b border-zinc-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <Link :href="route('admin.dashboard')" class="flex items-center text-zinc-600 hover:text-primary-600 transition-colors font-medium">
                        <ArrowLeftIcon class="w-5 h-5 mr-2" />
                        Back to Dashboard
                    </Link>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-emerald-600 bg-clip-text text-transparent">Audit Logs</span>
                        <span class="px-3 py-1 bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-bold rounded-full shadow-lg">SUPER ADMIN</span>
                    </div>
                    <div class="w-32"></div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="mb-8" data-aos="fade-up">
                <div class="flex items-center gap-3 mb-4">
                    <ShieldCheckIcon class="w-10 h-10 text-primary-600" />
                    <div>
                        <h1 class="text-4xl font-bold text-zinc-900">Audit Logs</h1>
                        <p class="text-zinc-600 text-lg">System activity tracking and monitoring</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 mb-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-6 border-b border-zinc-200">
                    <div class="flex items-center justify-between mb-4">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search logs by user, action, or description..."
                            class="flex-1 px-4 py-3 rounded-xl border-2 border-zinc-200 focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all"
                        />
                    </div>
                    <div class="flex items-center gap-2 text-sm text-zinc-600">
                        <ClockIcon class="w-4 h-4" />
                        <span>Showing {{ filteredLogs.length }} of {{ pagination.total }} total logs</span>
                    </div>
                </div>

                <div class="divide-y divide-zinc-200">
                    <div v-if="filteredLogs.length === 0" class="p-12 text-center">
                        <ClockIcon class="w-16 h-16 text-zinc-300 mx-auto mb-4" />
                        <p class="text-xl font-semibold text-zinc-400">No logs found</p>
                    </div>

                    <div v-for="log in filteredLogs" :key="log.id" class="p-6 hover:bg-zinc-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold shadow-lg">
                                {{ log.user.name.charAt(0).toUpperCase() }}
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-zinc-900">{{ log.user.name }}</span>
                                            <span :class="['px-2 py-0.5 rounded-full text-xs font-bold', getActionColor(log.action)]">
                                                {{ log.action.replace(/_/g, ' ').toUpperCase() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-zinc-500">{{ log.user.email }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-1.5 text-sm text-zinc-500">
                                            <ClockIcon class="w-4 h-4" />
                                            <span>{{ log.created_at }}</span>
                                        </div>
                                        <div v-if="log.ip_address" class="text-xs text-zinc-400 mt-1">
                                            IP: {{ log.ip_address }}
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-zinc-50 rounded-lg p-4 border border-zinc-200">
                                    <p class="text-sm text-zinc-700 font-medium">{{ log.description }}</p>
                                    <div v-if="log.model_type" class="flex items-center gap-2 mt-2 text-xs text-zinc-500">
                                        <span class="px-2 py-1 bg-white rounded border border-zinc-200">
                                            {{ log.model_type }} #{{ log.model_id }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="pagination.last_page > 1" class="px-6 py-4 border-t border-zinc-200 bg-zinc-50">
                    <div class="flex items-center justify-between">
                        <button
                            @click="goToPage(pagination.current_page - 1)"
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
                                    @click="goToPage(page)"
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
                            @click="goToPage(pagination.current_page + 1)"
                            :disabled="pagination.current_page === pagination.last_page"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed bg-white border-2 border-zinc-200 text-zinc-700 hover:bg-zinc-100"
                        >
                            Next
                            <ChevronRightIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
