<template>
    <TransitionRoot :show="show" as="template">
        <Dialog @close="$emit('close')" class="relative z-50">
            <TransitionChild enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Delete Product</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-slate-900 mb-2">Are you sure?</h4>
                                        <p class="text-slate-600 mb-3">Do you really want to delete "<span class="font-semibold">{{ product?.name }}</span>"?</p>
                                        <p class="text-sm text-slate-500">This action cannot be undone. The product and its image will be permanently removed.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 rounded-b-xl flex items-center justify-end gap-3">
                                <button type="button" @click="$emit('close')" class="px-4 py-2 text-slate-700 bg-white hover:bg-slate-100 rounded-lg font-medium border border-slate-300 transition-colors">Cancel</button>
                                <button type="button" @click="$emit('deleteConfirmed')" :disabled="processing" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors disabled:opacity-50">
                                    {{ processing ? 'Deleting...' : 'Delete' }}
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
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';

defineProps({
    show: Boolean,
    product: Object,
    processing: Boolean,
});

defineEmits(['close', 'deleteConfirmed']);
</script>
