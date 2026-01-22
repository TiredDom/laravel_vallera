<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';

const props = defineProps({
    show: Boolean,
    request: Object
});

const emit = defineEmits(['close']);

const formatFieldName = (field) => {
    return field?.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) || '';
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
    <TransitionRoot :show="show" as="template">
        <Dialog @close="$emit('close')" class="relative z-50">
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
                            <div class="p-6 space-y-4" v-if="request">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Request ID</label>
                                        <p class="text-slate-900 font-mono">#{{ request.id }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Status</label>
                                        <p><span :class="getStatusBadgeClass(request.status)">{{ request.status }}</span></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Requested By</label>
                                        <p class="text-slate-900">{{ request.requester?.name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Target User</label>
                                        <p class="text-slate-900">{{ request.target_user?.name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Field to Edit</label>
                                        <p class="text-slate-900">{{ formatFieldName(request.field_name) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Request Date</label>
                                        <p class="text-slate-900">{{ formatDateTime(request.created_at) }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Old Value</label>
                                        <p class="text-slate-900 bg-slate-50 p-3 rounded-lg mt-1 break-all">{{ request.old_value }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">New Value</label>
                                        <p class="text-slate-900 bg-blue-50 p-3 rounded-lg mt-1 font-semibold break-all">{{ request.new_value }}</p>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Reason</label>
                                        <p class="text-slate-900 mt-1">{{ request.reason }}</p>
                                    </div>
                                    <div v-if="request.status !== 'pending'" class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Reviewed By</label>
                                        <p class="text-slate-900">{{ request.reviewer?.name || 'N/A' }}</p>
                                    </div>
                                    <div v-if="request.review_note" class="col-span-2">
                                        <label class="text-sm font-medium text-slate-600">Review Note</label>
                                        <p class="text-slate-900 mt-1">{{ request.review_note }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 rounded-b-xl flex justify-end">
                                <button
                                    @click="$emit('close')"
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
