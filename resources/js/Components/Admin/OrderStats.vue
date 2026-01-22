<script setup>
import { ClockIcon, TruckIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline';

defineProps({
    orderStats: Object
});

function getStatusIcon(status) {
    const icons = {
        pending: ClockIcon,
        processing: TruckIcon,
        completed: CheckCircleIcon,
        cancelled: XCircleIcon
    };
    return icons[status] || ClockIcon;
}

function statusCardClass(status) {
    return {
        'border-blue-300': status === 'pending',
        'border-emerald-300': status === 'completed',
        'border-red-300': status === 'cancelled',
        'border-primary-300': status === 'processing',
    };
}

function statusIconColor(status) {
    return {
        'text-blue-500': status === 'pending',
        'text-emerald-500': status === 'completed',
        'text-red-600': status === 'cancelled',
        'text-primary-500': status === 'processing',
    };
}

function statusTextColor(status) {
    return {
        'text-blue-700': status === 'pending',
        'text-emerald-700': status === 'completed',
        'text-red-600': status === 'cancelled',
        'text-primary-700': status === 'processing',
    };
}
</script>

<template>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6" data-aos="fade-up">
        <div v-for="status in ['pending','completed','cancelled','processing']" :key="status" class="bg-white rounded-xl shadow-lg p-6 border-2 flex flex-col items-center justify-center" :class="statusCardClass(status)">
            <component :is="getStatusIcon(status)" class="w-8 h-8 mb-2" :class="statusIconColor(status)" />
            <span class="text-2xl font-extrabold mb-1" :class="statusTextColor(status)">{{ orderStats[status] }}</span>
            <span class="text-sm font-medium text-zinc-600 capitalize">{{ status }}</span>
        </div>
    </div>
</template>
