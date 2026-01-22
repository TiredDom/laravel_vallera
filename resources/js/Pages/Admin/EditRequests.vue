<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import EditRequestsHeader from '@/Components/Admin/EditRequestsHeader.vue';
import EditRequestsStats from '@/Components/Admin/EditRequestsStats.vue';
import EditRequestsTable from '@/Components/Admin/EditRequestsTable.vue';
import ReviewRequestModal from '@/Components/Admin/ReviewRequestModal.vue';
import RequestDetailsModal from '@/Components/Admin/RequestDetailsModal.vue';

const props = defineProps({
    requests: Object,
});

const showReviewModal = ref(false);
const showDetailsModal = ref(false);
const selectedRequest = ref(null);
const reviewAction = ref('approved');
let refreshInterval = null;

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({ only: ['requests'], preserveScroll: true, preserveState: true });
    }, 10000);
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

const manualRefresh = () => {
    router.reload({ only: ['requests'], preserveScroll: true, preserveState: true });
};

const pendingCount = computed(() => props.requests.data?.filter(r => r.status === 'pending').length || 0);
const approvedCount = computed(() => props.requests.data?.filter(r => r.status === 'approved').length || 0);
const rejectedCount = computed(() => props.requests.data?.filter(r => r.status === 'rejected').length || 0);

const openReviewModal = (request, action) => {
    selectedRequest.value = request;
    reviewAction.value = action;
    showReviewModal.value = true;
};

const viewDetails = (request) => {
    selectedRequest.value = request;
    showDetailsModal.value = true;
};

const handleReviewSuccess = () => {
    selectedRequest.value = null;
    // The modal will close itself via the @close event which sets showReviewModal to false
    // But we can also force a reload here if needed, though Inertia usually handles it
};
</script>

<template>
    <Head title="Edit Requests" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <EditRequestsHeader
            :total-requests="requests.total"
            @refresh="manualRefresh"
        />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <EditRequestsStats
                :pending-count="pendingCount"
                :approved-count="approvedCount"
                :rejected-count="rejectedCount"
            />

            <EditRequestsTable
                :requests="requests"
                @openReviewModal="openReviewModal"
                @viewDetails="viewDetails"
            />
        </div>
    </div>

    <ReviewRequestModal
        :show="showReviewModal"
        :request="selectedRequest"
        :action="reviewAction"
        @close="showReviewModal = false"
        @success="handleReviewSuccess"
    />

    <RequestDetailsModal
        :show="showDetailsModal"
        :request="selectedRequest"
        @close="showDetailsModal = false"
    />
</template>
