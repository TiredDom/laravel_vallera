<script setup>
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import { XMarkIcon, ShoppingBagIcon, CheckCircleIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    product: Object,
    isAdmin: {
        type: Boolean,
        default: false
    },
    isAuthOpen: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'add-to-cart']);

function handleAddToCart() {
    emit('add-to-cart', {
        id: props.product.id,
        name: props.product.name,
        price: props.product.price,
        stock: props.product.stock,
        category: props.product.category
    });
}

function getStockStatus(stock) {
    if (stock === 0) return { label: 'Out of Stock', color: 'text-red-600 bg-red-50', icon: ExclamationCircleIcon };
    if (stock < 10) return { label: 'Low Stock', color: 'text-amber-600 bg-amber-50', icon: ExclamationCircleIcon };
    return { label: 'In Stock', color: 'text-emerald-600 bg-emerald-50', icon: CheckCircleIcon };
}
</script>

<template>
    <TransitionRoot :show="show" as="template">
        <Dialog @close="$emit('close')" :class="['relative z-50', isAuthOpen ? 'pointer-events-none' : '']">
            <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
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
                        <DialogPanel :class="[isAuthOpen ? 'pointer-events-none select-none opacity-60' : '']" class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">
                            <div class="relative">
                                <button
                                    @click="$emit('close')"
                                    :class="['absolute top-4 right-4 z-10 p-2 bg-white/90 hover:bg-white rounded-full shadow-lg transition-all hover:scale-110', isAuthOpen ? 'pointer-events-none' : '']"
                                >
                                    <XMarkIcon class="w-6 h-6 text-slate-600" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                                    <div class="relative bg-slate-50 flex items-center justify-center p-8 md:p-12">
                                        <img
                                            v-if="product?.image_url"
                                            :src="product.image_url"
                                            :alt="product.name"
                                            class="w-full h-96 object-cover rounded-xl shadow-lg"
                                        />
                                        <div v-else class="w-full h-96 bg-slate-200 rounded-xl flex items-center justify-center">
                                            <svg class="w-20 h-20 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div v-if="product?.is_featured" class="absolute top-4 left-4 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full text-sm font-bold shadow-lg">
                                            ⭐ Featured
                                        </div>
                                    </div>

                                    <div class="p-8 md:p-12 flex flex-col">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full uppercase tracking-wider">
                                                    {{ product?.category }}
                                                </span>
                                                <component
                                                    :is="getStockStatus(product?.stock).icon"
                                                    class="w-5 h-5"
                                                    :class="getStockStatus(product?.stock).color.split(' ')[0]"
                                                />
                                            </div>

                                            <h2 class="text-3xl font-bold text-slate-900 mb-4">
                                                {{ product?.name }}
                                            </h2>

                                            <div class="flex items-baseline gap-3 mb-6">
                                                <span class="text-4xl font-bold text-primary-600">
                                                    ₱{{ Number(product?.price).toLocaleString() }}
                                                </span>
                                            </div>

                                            <div class="space-y-4 mb-8">
                                                <div class="flex items-center justify-between py-3 border-b border-slate-200">
                                                    <span class="text-sm font-medium text-slate-600">Stock Quantity</span>
                                                    <span class="text-sm font-bold text-slate-900">{{ product?.stock }} units</span>
                                                </div>

                                                <div class="flex items-center justify-between py-3 border-b border-slate-200">
                                                    <span class="text-sm font-medium text-slate-600">Category</span>
                                                    <span class="text-sm font-bold text-slate-900">{{ product?.category }}</span>
                                                </div>

                                                <div v-if="isAdmin" class="flex items-center justify-between py-3 border-b border-slate-200">
                                                    <span class="text-sm font-medium text-slate-600">Status</span>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-bold"
                                                        :class="product?.is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                                                    >
                                                        {{ product?.is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div v-if="product?.description" class="mb-8">
                                                <h3 class="text-sm font-semibold text-slate-900 mb-2 uppercase tracking-wider">Description</h3>
                                                <p class="text-slate-600 leading-relaxed">
                                                    {{ product.description }}
                                                </p>
                                            </div>

                                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 mb-6">
                                                <div class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div class="text-sm text-blue-900">
                                                        <p class="font-semibold mb-1">Premium Quality Guaranteed</p>
                                                        <p class="text-blue-700">Free shipping on orders over ₱5,000 • 30-day return policy</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="!isAdmin" class="space-y-3 pt-4 border-t border-slate-200">
                                            <button
                                                @click="handleAddToCart"
                                                :disabled="product?.stock === 0"
                                                class="w-full py-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 disabled:from-slate-300 disabled:to-slate-400 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all disabled:cursor-not-allowed flex items-center justify-center gap-2 group"
                                            >
                                                <ShoppingBagIcon class="w-6 h-6 group-hover:scale-110 transition-transform" />
                                                {{ product?.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
                                            </button>
                                            <button
                                                @click="$emit('close')"
                                                class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition-colors"
                                            >
                                                Continue Shopping
                                            </button>
                                        </div>

                                        <div v-else class="pt-4 border-t border-slate-200">
                                            <button
                                                @click="$emit('close')"
                                                class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition-colors"
                                            >
                                                Close Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
