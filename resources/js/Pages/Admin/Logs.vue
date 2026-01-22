<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { ArrowLeftIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline';
import LogFilters from '@/Components/Admin/LogFilters.vue';
import LogList from '@/Components/Admin/LogList.vue';
import LogPagination from '@/Components/Admin/LogPagination.vue';

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
                <LogFilters
                    v-model:searchQuery="searchQuery"
                    :filtered-count="filteredLogs.length"
                    :total-count="pagination.total"
                />

                <LogList :filtered-logs="filteredLogs" />

                <LogPagination :pagination="pagination" @goToPage="goToPage" />
            </div>
        </main>
    </div>
</template>
