<script setup>
defineProps({
    searchQuery: String
});

defineEmits(['update:searchQuery']);
</script>

<template>
    <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 mb-6" data-aos="fade-up" data-aos-delay="200">
        <div class="p-6 border-b border-zinc-200">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input
                        :value="searchQuery"
                        @input="$emit('update:searchQuery', $event.target.value)"
                        type="text"
                        placeholder="Search by order ID, customer name, or email..."
                        class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 focus:border-primary-500 focus:ring focus:ring-primary-200 transition-all"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
