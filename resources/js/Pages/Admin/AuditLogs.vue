<template>
    <Head title="Audit Logs" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <ArrowLeftIcon class="w-6 h-6 text-slate-600" />
                        </Link>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                Audit Logs
                            </h1>
                            <p class="mt-1 text-slate-600">Monitor all system activities and user actions</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-lg border border-slate-200">
                        <ClockIcon class="w-5 h-5 text-slate-400" />
                        <span class="text-sm font-medium text-slate-700">Total Logs: {{ safeLogsData.total || 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 mb-6" data-aos="fade-up">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                        <div class="relative">
                            <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                            <input
                                v-model="filters.search"
                                type="text"
                                placeholder="Search descriptions..."
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                @input="debouncedFilter"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">User</label>
                        <select
                            v-model="filters.user_id"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            @change="applyFilters"
                        >
                            <option value="">All Users</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                        <select
                            v-model="filters.action"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            @change="applyFilters"
                        >
                            <option value="">All Actions</option>
                            <option v-for="action in actionTypes" :key="action" :value="action">
                                {{ action }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Show</label>
                        <select
                            v-model="filters.per_page"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            @change="applyFilters"
                        >
                            <option :value="25">25</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">From Date</label>
                        <input
                            v-model="filters.date_from"
                            type="date"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            @change="applyFilters"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">To Date</label>
                        <input
                            v-model="filters.date_to"
                            type="date"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            @change="applyFilters"
                        />
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        @click="clearFilters"
                        class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
                    >
                        Clear Filters
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Timestamp
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Action
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Description
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    IP Address
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Details
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr
                                v-for="log in safeLogsData.data"
                                :key="log.id"
                                class="hover:bg-slate-50 transition-colors"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ formatDate(log.created_at) }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ formatTime(log.created_at) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-semibold text-sm">
                                            {{ getUserInitials(log.user) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ log.user?.name || 'System' }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ log.user?.email || 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getActionBadgeClass(log.action)">
                                        {{ log.action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 max-w-md truncate">
                                        {{ log.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-600 font-mono">
                                        {{ log.ip_address || 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button
                                        @click="showDetails(log)"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                    >
                                        <EyeIcon class="w-4 h-4 mr-1" />
                                        View
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!safeLogsData.data || safeLogsData.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <DocumentTextIcon class="mx-auto w-12 h-12 text-slate-400 mb-4" />
                                    <p class="text-slate-600 font-medium">No audit logs found</p>
                                    <p class="text-slate-500 text-sm mt-2">Try adjusting your filters</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="safeLogsData.data && safeLogsData.data.length > 0" class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600">
                            Showing {{ safeLogsData.from }} to {{ safeLogsData.to }} of {{ safeLogsData.total }} results
                        </div>
                        <div class="flex gap-2">
                            <template v-for="link in safeLogsData.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    :class="[
                                        'px-4 py-2 text-sm font-medium rounded-lg transition-all',
                                        link.active
                                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/50'
                                            : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-300'
                                    ]"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    :class="'px-4 py-2 text-sm font-medium rounded-lg transition-all bg-slate-100 text-slate-400 cursor-not-allowed'"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <TransitionRoot :show="showDetailsModal" as="template">
        <Dialog @close="showDetailsModal = false" class="relative z-50">
            <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-2xl bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Audit Log Details</h3>
                            </div>
                            <div class="p-6 space-y-4" v-if="selectedLog">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">ID</label>
                                        <p class="text-slate-900 font-mono">{{ selectedLog.id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Action</label>
                                        <p><span :class="getActionBadgeClass(selectedLog.action)">{{ selectedLog.action }}</span></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">User</label>
                                        <p class="text-slate-900">{{ selectedLog.user?.name || 'System' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Email</label>
                                        <p class="text-slate-900">{{ selectedLog.user?.email || 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">IP Address</label>
                                        <p class="text-slate-900 font-mono">{{ selectedLog.ip_address || 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Timestamp</label>
                                        <p class="text-slate-900">{{ formatDateTime(selectedLog.created_at) }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Description</label>
                                        <p class="text-slate-900 mt-1">{{ selectedLog.description }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 rounded-b-xl flex justify-end">
                                <button
                                    @click="showDetailsModal = false"
                                    class="px-4 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-100 rounded-lg border border-slate-300 transition-colors"
                                >
                                    Close
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import {
    ClockIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    DocumentTextIcon,
    ArrowLeftIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    logs: Object,
    users: Array,
    actionTypes: Array,
    filters: Object
});

const safeLogsData = computed(() => {
    if (!props.logs || !props.logs.data) {
        return {
            data: [],
            total: 0,
            from: 0,
            to: 0,
            links: []
        };
    }
    return props.logs;
});

const filters = reactive({
    search: props.filters?.search || '',
    user_id: props.filters?.user_id || '',
    action: props.filters?.action || '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
    per_page: props.filters?.per_page || 50
});

const showDetailsModal = ref(false);
const selectedLog = ref(null);

let debounceTimer = null;
const debouncedFilter = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        applyFilters();
    }, 500);
};

const applyFilters = () => {
    router.get('/admin/logs', filters, {
        preserveState: true,
        preserveScroll: true
    });
};

const clearFilters = () => {
    Object.keys(filters).forEach(key => {
        if (key === 'per_page') {
            filters[key] = 50;
        } else {
            filters[key] = '';
        }
    });
    applyFilters();
};

const showDetails = (log) => {
    selectedLog.value = log;
    showDetailsModal.value = true;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatDateTime = (dateString) => {
    return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getUserInitials = (user) => {
    if (!user?.name) return 'S';
    return user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};

const getActionBadgeClass = (action) => {
    const baseClass = 'inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full';
    const actionLower = action?.toLowerCase() || '';

    if (actionLower.includes('create') || actionLower.includes('add')) {
        return `${baseClass} bg-green-100 text-green-800`;
    }
    if (actionLower.includes('update') || actionLower.includes('edit')) {
        return `${baseClass} bg-blue-100 text-blue-800`;
    }
    if (actionLower.includes('delete') || actionLower.includes('remove')) {
        return `${baseClass} bg-red-100 text-red-800`;
    }
    if (actionLower.includes('login') || actionLower.includes('logout')) {
        return `${baseClass} bg-purple-100 text-purple-800`;
    }
    return `${baseClass} bg-slate-100 text-slate-800`;
};
</script>
