<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    requests: Object
});

defineEmits(['openReviewModal', 'viewDetails']);

const getInitials = (name) => {
    if (!name) return 'U';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};

const formatFieldName = (field) => {
    return field?.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) || '';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const truncate = (str, length) => {
    if (!str) return 'N/A';
    return str.length > length ? str.substring(0, length) + '...' : str;
};

const getStatusBadgeClass = (status) => {
    const baseClass = 'inline-flex items-center px-3 py-1 text-xs font-bold rounded-full';
    const classes = {
        pending: `${baseClass} bg-amber-100 text-amber-800`,
        approved: `${baseClass} bg-green-100 text-green-800`,
        rejected: `${baseClass} bg-red-100 text-red-800`,
    };
    return classes[status] || baseClass;
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Request Info</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Target User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Changes</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr v-for="request in requests.data" :key="request.id" class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ getInitials(request.requester?.name) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ request.requester?.name }}</p>
                                    <p class="text-xs text-slate-500">{{ formatDate(request.created_at) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ request.target_user?.name }}</p>
                                <p class="text-xs text-slate-500">{{ request.target_user?.email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-100 text-blue-800 text-xs font-semibold mb-1">
                                    {{ formatFieldName(request.field_name) }}
                                </span>
                                <p class="text-xs text-slate-500 mt-1 break-words">
                                    <span class="line-through">{{ truncate(request.old_value, 20) }}</span>
                                    <span class="mx-1">→</span>
                                    <span class="font-semibold text-slate-700">{{ truncate(request.new_value, 20) }}</span>
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700 max-w-xs break-words">{{ truncate(request.reason, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="getStatusBadgeClass(request.status)">
                                {{ request.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button
                                    v-if="request.status === 'pending'"
                                    @click="$emit('openReviewModal', request, 'approved')"
                                    class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition-colors"
                                >
                                    Approve
                                </button>
                                <button
                                    v-if="request.status === 'pending'"
                                    @click="$emit('openReviewModal', request, 'rejected')"
                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors"
                                >
                                    Reject
                                </button>
                                <button
                                    @click="$emit('viewDetails', request)"
                                    class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-colors"
                                >
                                    Details
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!requests.data || requests.data.length === 0">
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto w-12 h-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-slate-600 font-medium">No edit requests found</p>
                            <p class="text-slate-500 text-sm mt-2">When admins submit edit requests, they will appear here</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="requests.data && requests.data.length > 0" class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    Showing {{ requests.from }} to {{ requests.to }} of {{ requests.total }} results
                </div>
                <div class="flex gap-2">
                    <template v-for="link in requests.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-4 py-2 text-sm font-medium rounded-lg transition-all',
                                link.active
                                    ? 'bg-amber-600 text-white shadow-lg'
                                    : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-300'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
