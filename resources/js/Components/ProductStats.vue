<template>
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4 mb-6" data-aos="fade-up">
        <div class="bg-white rounded-xl shadow-lg border-2 border-emerald-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-emerald-600">{{ products.length }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Total Products</p>
                </div>
                <div class="p-2 sm:p-3 bg-emerald-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-blue-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-blue-600">{{ featuredCount }}/{{ maxFeatured }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Featured</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-amber-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-amber-600">{{ totalStock }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Total Stock</p>
                </div>
                <div class="p-2 sm:p-3 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-orange-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-orange-600">{{ lowStockCount }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Low Stock</p>
                </div>
                <div class="p-2 sm:p-3 bg-orange-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-red-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-red-600">{{ outOfStockCount }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Sold Out</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-indigo-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base sm:text-xl font-bold text-indigo-600">{{ activeCategories }}</p>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">Active Categories</p>
                </div>
                <div class="p-2 sm:p-3 bg-indigo-100 rounded-lg">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3m-4 4v4a1 1 0 001 1h3m10-5v4a1 1 0 01-1 1h-3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    products: Array,
    featuredCount: Number,
    maxFeatured: {
        type: Number,
        default: 3
    }
});

const totalStock = computed(() => props.products.reduce((sum, p) => sum + p.stock, 0));
const lowStockCount = computed(() => props.products.filter(p => p.stock > 0 && p.stock < 10).length);
const outOfStockCount = computed(() => props.products.filter(p => p.stock === 0).length);
const activeCategories = computed(() => {
    const set = new Set(props.products.map(p => p.category));
    return set.size;
});
</script>
