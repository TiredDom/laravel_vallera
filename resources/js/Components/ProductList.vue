<template>
    <div class="bg-white rounded-xl shadow-lg border border-slate-200" data-aos="fade-up" data-aos-delay="100">
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            <div v-for="product in products" :key="product.id" class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-emerald-300 hover:shadow-md transition-all">
                <div class="flex items-start gap-3 sm:gap-4 flex-1">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover">
                        <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate">{{ product.name }}</h3>
                            <span v-if="product.is_featured" class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-100 text-amber-700 whitespace-nowrap">⭐ Featured</span>
                            <span v-if="!product.is_active" class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700 whitespace-nowrap">Inactive</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 mb-2 line-clamp-2">{{ product.description || 'No description' }}</p>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm">
                            <span class="font-semibold text-emerald-600 whitespace-nowrap">₱{{ Number(product.price).toLocaleString() }}</span>
                            <div class="flex items-center gap-1.5 whitespace-nowrap">
                                <span class="text-slate-600 font-medium">Stock:</span>
                                <span
                                    class="px-2 py-0.5 rounded-md font-bold"
                                    :class="{
                                        'bg-red-100 text-red-700': product.stock === 0,
                                        'bg-amber-100 text-amber-700': product.stock > 0 && product.stock < 10,
                                        'bg-emerald-100 text-emerald-700': product.stock >= 10
                                    }"
                                >
                                    {{ product.stock }}
                                </span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 whitespace-nowrap">{{ product.category_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:flex-col sm:gap-2 justify-end sm:justify-center">
                    <button @click="$emit('preview', product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-purple-100 text-purple-700 rounded-lg font-semibold hover:bg-purple-200 transition-colors text-xs sm:text-sm flex items-center justify-center gap-1 whitespace-nowrap">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="hidden sm:inline">Preview</span>
                    </button>
                    <button @click="$emit('edit', product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors text-xs sm:text-sm whitespace-nowrap">Edit</button>
                    <button @click="$emit('delete', product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors text-xs sm:text-sm whitespace-nowrap">Delete</button>
                </div>
            </div>

            <div v-if="products.length === 0" class="text-center py-12">
                <svg class="mx-auto w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-slate-600 font-medium text-lg">No products yet</p>
                <p class="text-slate-500 text-sm mt-2">Create your first product to get started</p>
                <button @click="$emit('add')" class="mt-4 px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all">Add Your First Product</button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    products: Array,
});

defineEmits(['preview', 'edit', 'delete', 'add']);
</script>
