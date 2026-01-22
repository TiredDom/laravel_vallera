<template>
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6" data-aos="fade-up" data-aos-delay="50">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row gap-4">
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
                            placeholder="Search products by name or description..."
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
});

defineEmits(['update:searchQuery', 'update:categoryFilter', 'update:sortBy']);
</script>
