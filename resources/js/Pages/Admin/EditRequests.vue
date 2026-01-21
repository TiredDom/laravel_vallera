<template>
    <Head title="Edit Requests" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                                Edit Requests
                            </h1>
                            <p class="mt-1 text-slate-600">Review and approve admin requests to edit user accounts</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            @click="manualRefresh"
                            class="p-2 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Refresh requests"
                        >
                            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 rounded-lg border border-amber-200">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-medium text-amber-900">{{ requests.total || 0 }} Total Requests</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" data-aos="fade-up">
                <div class="bg-white rounded-xl shadow-lg border-2 border-amber-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-amber-600">{{ pendingCount }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Pending Review</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border-2 border-green-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-green-600">{{ approvedCount }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Approved</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border-2 border-red-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-red-600">{{ rejectedCount }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Rejected</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

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
                                            @click="openReviewModal(request, 'approved')"
                                            class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition-colors"
                                        >
                                            Approve
                                        </button>
                                        <button
                                            v-if="request.status === 'pending'"
                                            @click="openReviewModal(request, 'rejected')"
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-semibold hover:bg-red-200 transition-colors"
                                        >
                                            Reject
                                        </button>
                                        <button
                                            @click="viewDetails(request)"
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
        </div>
    </div>

    <TransitionRoot :show="showReviewModal" as="template">
        <Dialog @close="showReviewModal = false" class="relative z-50">
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
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div :class="['px-6 py-4 rounded-t-xl', reviewAction === 'approved' ? 'bg-gradient-to-r from-green-600 to-emerald-600' : 'bg-gradient-to-r from-red-600 to-rose-600']">
                                <h3 class="text-lg font-semibold text-white">
                                    {{ reviewAction === 'approved' ? 'Approve Request' : 'Reject Request' }}
                                </h3>
                            </div>
                            <form @submit.prevent="submitReview" class="p-6 space-y-4">
                                <div class="bg-slate-50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Field:</span>
                                        <span class="font-semibold text-slate-900">{{ formatFieldName(selectedRequest?.field_name) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Target User:</span>
                                        <span class="font-semibold text-slate-900">{{ selectedRequest?.target_user?.name }}</span>
                                    </div>
                                    <div class="text-sm pt-2 border-t border-slate-200">
                                        <p class="text-slate-600 mb-1">Change:</p>
                                        <p class="text-slate-700">
                                            <span class="line-through">{{ selectedRequest?.old_value }}</span>
                                            <span class="mx-2">→</span>
                                            <span class="font-semibold">{{ selectedRequest?.new_value }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Review Note (Optional)</label>
                                    <textarea
                                        v-model="reviewForm.review_note"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Add a note about your decision..."
                                    ></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                    <button
                                        type="button"
                                        @click="showReviewModal = false"
                                        class="px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="reviewForm.processing"
                                        :class="[
                                            'px-4 py-2 text-white rounded-lg font-medium transition-all',
                                            reviewAction === 'approved'
                                                ? 'bg-green-600 hover:bg-green-700'
                                                : 'bg-red-600 hover:bg-red-700',
                                            reviewForm.processing ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                    >
                                        {{ reviewForm.processing ? 'Processing...' : (reviewAction === 'approved' ? 'Approve' : 'Reject') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

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
                                <h3 class="text-lg font-semibold text-white">Request Details</h3>
                            </div>
                            <div class="p-6 space-y-4" v-if="selectedRequest">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Request ID</label>
                                        <p class="text-slate-900 font-mono">#{{ selectedRequest.id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Status</label>
                                        <p><span :class="getStatusBadgeClass(selectedRequest.status)">{{ selectedRequest.status }}</span></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Requested By</label>
                                        <p class="text-slate-900">{{ selectedRequest.requester?.name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Target User</label>
                                        <p class="text-slate-900">{{ selectedRequest.target_user?.name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Field to Edit</label>
                                        <p class="text-slate-900">{{ formatFieldName(selectedRequest.field_name) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Request Date</label>
                                        <p class="text-slate-900">{{ formatDateTime(selectedRequest.created_at) }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Old Value</label>
                                        <p class="text-slate-900 bg-slate-50 p-3 rounded-lg mt-1 break-all">{{ selectedRequest.old_value }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">New Value</label>
                                        <p class="text-slate-900 bg-blue-50 p-3 rounded-lg mt-1 font-semibold break-all">{{ selectedRequest.new_value }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Reason</label>
                                        <p class="text-slate-900 mt-1">{{ selectedRequest.reason }}</p>
                                    </div>
                                    <div v-if="selectedRequest.status !== 'pending'" class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Reviewed By</label>
                                        <p class="text-slate-900">{{ selectedRequest.reviewer?.name || 'N/A' }}</p>
                                    </div>
                                    <div v-if="selectedRequest.review_note" class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Review Note</label>
                                        <p class="text-slate-900 mt-1">{{ selectedRequest.review_note }}</p>
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
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';

const props = defineProps({
    requests: Object,
});

const showReviewModal = ref(false);
const showDetailsModal = ref(false);
const selectedRequest = ref(null);
const reviewAction = ref('approved');
let refreshInterval = null;

const reviewForm = useForm({
    status: 'approved',
    review_note: '',
});

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({ only: ['requests'], preserveScroll: true, preserveState: true });
    }, 10000);
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

const manualRefresh = () => {
    router.reload({ only: ['requests'], preserveScroll: true, preserveState: true });
};

const pendingCount = computed(() => props.requests.data?.filter(r => r.status === 'pending').length || 0);
const approvedCount = computed(() => props.requests.data?.filter(r => r.status === 'approved').length || 0);
const rejectedCount = computed(() => props.requests.data?.filter(r => r.status === 'rejected').length || 0);

const openReviewModal = (request, action) => {
    selectedRequest.value = request;
    reviewAction.value = action;
    reviewForm.status = action;
    reviewForm.review_note = '';
    showReviewModal.value = true;
};

const submitReview = () => {
    reviewForm.post(`/admin/edit-requests/${selectedRequest.value.id}/review`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showReviewModal.value = false;
            selectedRequest.value = null;
            reviewForm.reset();
        },
    });
};

const viewDetails = (request) => {
    selectedRequest.value = request;
    showDetailsModal.value = true;
};

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

const formatDateTime = (dateString) => {
    return new Date(dateString).toLocaleString('en-US', {
        month: 'long',
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
