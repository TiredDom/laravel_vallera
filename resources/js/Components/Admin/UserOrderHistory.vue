<script setup>
defineProps({
    ordersCount: Number
});
</script>

<template>
    <div v-if="ordersCount > 0" class="mt-6 bg-white rounded-xl shadow-lg border border-slate-200 p-6" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Order History</h3>
                <p class="text-sm text-slate-600">This user has {{ ordersCount }} order(s)</p>
            </div>
        </div>
        <div class="bg-slate-50 rounded-lg p-4">
            <p class="text-sm text-slate-600">View detailed order history and management in the Orders section.</p>
        </div>
    </div>
</template>
