<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    user: Object
});

const emit = defineEmits(['close', 'success']);

const form = useForm({
    field_name: 'name',
    new_value: '',
    reason: ''
});

watch(() => props.show, (newVal) => {
    if (!newVal) {
        form.reset();
    }
});

function submit() {
    if (props.user) {
        form.post(`/admin/users/${props.user.id}/request-edit`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                emit('success');
                emit('close');
            }
        });
    }
}
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
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Request User Edit</h3>
                                <p class="text-sm text-indigo-100 mt-1">Submit request to edit {{ user?.name }}</p>
                            </div>
                            <form @submit.prevent="submit" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Field to Edit</label>
                                    <select
                                        v-model="form.field_name"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                    >
                                        <option value="name">Name</option>
                                        <option value="email">Email</option>
                                        <option value="password">Password</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Value</label>
                                    <input
                                        v-model="form.new_value"
                                        :type="form.field_name === 'password' ? 'password' : 'text'"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        :placeholder="`Enter new ${form.field_name}`"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason for Change</label>
                                    <textarea
                                        v-model="form.reason"
                                        rows="3"
                                        required
                                        maxlength="500"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                        placeholder="Explain why this change is needed..."
                                    ></textarea>
                                    <p class="text-xs text-slate-500 mt-1">{{ form.reason.length }}/500 characters</p>
                                </div>

                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-900">Approval Required</p>
                                            <p class="text-sm text-amber-700 mt-1">Your request will be submitted to the super admin for review and approval.</p>
                                        </div>
                                    </div>
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
                                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ form.processing ? 'Submitting...' : 'Submit Request' }}
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
