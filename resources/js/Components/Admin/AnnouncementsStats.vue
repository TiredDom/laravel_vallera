<script setup>
defineProps({
    pendingCount: Number,
    publishedCount: Number,
    approvedCount: Number,
    rejectedCount: Number
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up">
        <div class="bg-white rounded-xl shadow-lg border-2 border-amber-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-amber-600">{{ pendingCount }}</p>
                    <p class="text-sm text-slate-600 mt-1 font-medium">Pending</p>
                </div>
                <div class="p-3 bg-amber-100 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-green-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-green-600">{{ publishedCount }}</p>
                    <p class="text-sm text-slate-600 mt-1 font-medium">Published</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-blue-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-blue-600">{{ approvedCount }}</p>
                    <p class="text-sm text-slate-600 mt-1 font-medium">Approved</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-red-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-3xl font-bold text-red-600">{{ rejectedCount }}</p>
                    <p class="text-sm text-slate-600 mt-1 font-medium">Rejected</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
