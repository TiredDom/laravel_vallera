<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    isSuperAdmin: Boolean
});

const emit = defineEmits(['close', 'success']);

const createForm = useForm({
    title: '',
    message: '',
    type: 'info',
    target_audience: 'all',
    expires_at: '',
});

watch(() => props.show, (newVal) => {
    if (!newVal) {
        createForm.reset();
    }
});

const createAnnouncement = () => {
    createForm.post('/admin/announcements', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit('success');
            emit('close');
        },
    });
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
                            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Create New Announcement</h3>
                            </div>
                            <form @submit.prevent="createAnnouncement" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                                    <input
                                        v-model="createForm.title"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        placeholder="Enter announcement title"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Message</label>
                                    <textarea
                                        v-model="createForm.message"
                                        rows="4"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        placeholder="Enter announcement message"
                                    ></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                                        <select
                                            v-model="createForm.type"
                                            required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        >
                                            <option value="info">Info</option>
                                            <option value="warning">Warning</option>
                                            <option value="success">Success</option>
                                            <option value="danger">Danger</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Target Audience</label>
                                        <select
                                            v-model="createForm.target_audience"
                                            required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        >
                                            <option value="all">All Users</option>
                                            <option value="admins">Admins Only</option>
                                            <option value="users">Regular Users</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Expiration Date (Optional)</label>
                                    <input
                                        v-model="createForm.expires_at"
                                        type="datetime-local"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                    />
                                </div>

                                <div v-if="!isSuperAdmin" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-900">Approval Required</p>
                                            <p class="text-sm text-amber-700 mt-1">Your announcement will be submitted for super admin approval before being published.</p>
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
                                        :disabled="createForm.processing"
                                        class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ createForm.processing ? 'Creating...' : (isSuperAdmin ? 'Publish' : 'Submit') }}
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
