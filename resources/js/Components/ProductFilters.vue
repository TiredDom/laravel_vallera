<template>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-2 w-full">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    <input
                        :value="searchQuery"
                        @input="$emit('update:searchQuery', $event.target.value)"
                        type="text"
                        placeholder="Search products..."
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    />
                </div>
            </div>
            <div class="sm:w-64">
                <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                <select
                    :value="categoryFilter"
                    @change="$emit('update:categoryFilter', $event.target.value)"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium"
                >
                    <option value="All">All Categories</option>
                    <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                </select>
            </div>
            <div class="sm:w-56">
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort By</label>
                <select
                    :value="sortBy"
                    @change="$emit('update:sortBy', $event.target.value)"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium"
                >
                    <option v-for="option in sortOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <button
                @click="$emit('refresh')"
                :disabled="isRefreshing"
                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition-all flex items-center gap-2 text-sm disabled:opacity-50"
                title="Refresh stock data"
            >
                <svg class="w-4 h-4" :class="{ 'animate-spin': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span class="hidden sm:inline">{{ isRefreshing ? 'Refreshing...' : 'Refresh' }}</span>
            </button>
            <button @click="$emit('add')" class="px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Product</span>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    searchQuery: String,
    categoryFilter: String,
    sortBy: String,
    categories: Array,
    sortOptions: Array,
    isRefreshing: Boolean,
});

defineEmits(['update:searchQuery', 'update:categoryFilter', 'update:sortBy', 'refresh', 'add']);
</script>
