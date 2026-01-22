<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';

defineProps({
    show: Boolean,
    filters: Array,
    activeFilter: String,
    sortBy: String
});

defineEmits(['close', 'update:activeFilter', 'update:sortBy']);
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
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-medium leading-6 text-zinc-900">Filters & Sort</h3>
                                <button
                                    type="button"
                                    class="rounded-full p-1 hover:bg-zinc-100 transition-colors"
                                    @click="$emit('close')"
                                >
                                    <XMarkIcon class="h-6 w-6 text-zinc-500" />
                                </button>
                            </div>

                            <div class="space-y-8 max-h-[60vh] overflow-y-auto pr-2">
                                <!-- Sort Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-zinc-900 mb-3 uppercase tracking-wider">Sort By</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div v-for="option in [
                                            { value: 'name', label: 'Name (A-Z)' },
                                            { value: 'name-desc', label: 'Name (Z-A)' },
                                            { value: 'price-low', label: 'Price (Low-High)' },
                                            { value: 'price-high', label: 'Price (High-Low)' }
                                        ]" :key="option.value">
                                            <label
                                                :class="[
                                                    'flex items-center justify-between w-full px-4 py-3 rounded-xl border cursor-pointer transition-all',
                                                    sortBy === option.value
                                                        ? 'border-primary-500 bg-primary-50 text-primary-700 shadow-sm'
                                                        : 'border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700'
                                                ]"
                                            >
                                                <input
                                                    type="radio"
                                                    name="sort"
                                                    :value="option.value"
                                                    :checked="sortBy === option.value"
                                                    @change="$emit('update:sortBy', option.value)"
                                                    class="sr-only"
                                                />
                                                <span class="font-medium text-sm">{{ option.label }}</span>
                                                <CheckIcon v-if="sortBy === option.value" class="w-5 h-5 text-primary-600" />
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-zinc-100" />

                                <!-- Category Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-zinc-900 mb-3 uppercase tracking-wider">Category</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        <div v-for="filter in filters" :key="filter">
                                            <label
                                                :class="[
                                                    'flex items-center justify-center w-full px-4 py-3 rounded-xl border cursor-pointer transition-all text-center h-full',
                                                    activeFilter === filter
                                                        ? 'border-primary-500 bg-primary-50 text-primary-700 shadow-sm font-bold'
                                                        : 'border-zinc-200 hover:border-zinc-300 hover:bg-zinc-50 text-zinc-700 font-medium'
                                                ]"
                                            >
                                                <input
                                                    type="radio"
                                                    name="category"
                                                    :value="filter"
                                                    :checked="activeFilter === filter"
                                                    @change="$emit('update:activeFilter', filter)"
                                                    class="sr-only"
                                                />
                                                <span class="text-sm">{{ filter }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-zinc-100">
                                <button
                                    type="button"
                                    class="w-full inline-flex justify-center rounded-xl border border-transparent bg-primary-600 px-4 py-3.5 text-base font-bold text-white hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 transition-all shadow-lg hover:shadow-xl hover:scale-[1.02]"
                                    @click="$emit('close')"
                                >
                                    Show Results
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
