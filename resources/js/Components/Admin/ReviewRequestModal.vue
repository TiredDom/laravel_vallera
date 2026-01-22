<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    request: Object,
    action: String
});

const emit = defineEmits(['close', 'success']);

const form = useForm({
    status: props.action,
    review_note: '',
});

watch(() => props.action, (newAction) => {
    form.status = newAction;
});

watch(() => props.show, (newVal) => {
    if (!newVal) {
        form.reset();
    }
});

const formatFieldName = (field) => {
    return field?.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) || '';
};

const submit = () => {
    if (props.request) {
        form.post(`/admin/edit-requests/${props.request.id}/review`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                emit('success');
                emit('close');
            },
        });
    }
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
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div :class="['px-6 py-4 rounded-t-xl', action === 'approved' ? 'bg-gradient-to-r from-green-600 to-emerald-600' : 'bg-gradient-to-r from-red-600 to-rose-600']">
                                <h3 class="text-lg font-semibold text-white">
                                    {{ action === 'approved' ? 'Approve Request' : 'Reject Request' }}
                                </h3>
                            </div>
                            <form @submit.prevent="submit" class="p-6 space-y-4">
                                <div class="bg-slate-50 rounded-lg p-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Field:</span>
                                        <span class="font-semibold text-slate-900">{{ formatFieldName(request?.field_name) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Target User:</span>
                                        <span class="font-semibold text-slate-900">{{ request?.target_user?.name }}</span>
                                    </div>
                                    <div class="text-sm pt-2 border-t border-slate-200">
                                        <p class="text-slate-600 mb-1">Change:</p>
                                        <p class="text-slate-700">
                                            <span class="line-through">{{ request?.old_value }}</span>
                                            <span class="mx-2">→</span>
                                            <span class="font-semibold">{{ request?.new_value }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Review Note (Optional)</label>
                                    <textarea
                                        v-model="form.review_note"
                                        rows="3"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="Add a note about your decision..."
                                    ></textarea>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                    <button
                                        type="button"
                                        @click="$emit('close')"
                                        class="px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        :class="[
                                            'px-4 py-2 text-white rounded-lg font-medium transition-all',
                                            action === 'approved'
                                                ? 'bg-green-600 hover:bg-green-700'
                                                : 'bg-red-600 hover:bg-red-700',
                                            form.processing ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                    >
                                        {{ form.processing ? 'Processing...' : (action === 'approved' ? 'Approve' : 'Reject') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
