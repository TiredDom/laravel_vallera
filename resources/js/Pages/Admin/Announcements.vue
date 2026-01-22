<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AnnouncementsHeader from '@/Components/Admin/AnnouncementsHeader.vue';
import AnnouncementsStats from '@/Components/Admin/AnnouncementsStats.vue';
import AnnouncementsList from '@/Components/Admin/AnnouncementsList.vue';
import CreateAnnouncementModal from '@/Components/Admin/CreateAnnouncementModal.vue';
import DeleteAnnouncementModal from '@/Components/Admin/DeleteAnnouncementModal.vue';

const props = defineProps({
    announcements: Object,
    isSuperAdmin: Boolean,
});

const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const announcementToDelete = ref(null);
let refreshInterval = null;

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({ only: ['announcements'], preserveScroll: true, preserveState: true });
    }, 10000);
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

const manualRefresh = () => {
    router.reload({ only: ['announcements'], preserveScroll: true, preserveState: true });
};

const safeAnnouncements = computed(() => {
    if (!props.announcements || !props.announcements.data) {
        return {
            data: [],
            links: [],
            total: 0
        };
    }
    return props.announcements;
});

const pendingCount = computed(() => safeAnnouncements.value.data?.filter(a => a.status === 'pending').length || 0);
const publishedCount = computed(() => safeAnnouncements.value.data?.filter(a => a.status === 'published').length || 0);
const approvedCount = computed(() => safeAnnouncements.value.data?.filter(a => a.status === 'approved').length || 0);
const rejectedCount = computed(() => safeAnnouncements.value.data?.filter(a => a.status === 'rejected').length || 0);

const reviewAnnouncement = (announcement, status) => {
    router.post(`/admin/announcements/${announcement.id}/review`, {
        status: status,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const confirmDelete = (announcement) => {
    announcementToDelete.value = announcement;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (announcementToDelete.value) {
        router.delete(`/admin/announcements/${announcementToDelete.value.id}`, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                showDeleteModal.value = false;
                announcementToDelete.value = null;
            }
        });
    }
};

const handleCreateSuccess = () => {
    showCreateModal.value = false;
};
</script>

<template>
    <Head title="Announcements" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-blue-50">
        <AnnouncementsHeader
            @refresh="manualRefresh"
            @create="showCreateModal = true"
        />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div v-if="isSuperAdmin">
                <AnnouncementsStats
                    :pending-count="pendingCount"
                    :published-count="publishedCount"
                    :approved-count="approvedCount"
                    :rejected-count="rejectedCount"
                />
            </div>

            <AnnouncementsList
                :announcements="safeAnnouncements"
                :is-super-admin="isSuperAdmin"
                :current-user-id="announcements.currentUserId"
                @review="reviewAnnouncement"
                @delete="confirmDelete"
                @create="showCreateModal = true"
            />
        </div>
    </div>

    <CreateAnnouncementModal
        :show="showCreateModal"
        :is-super-admin="isSuperAdmin"
        @close="showCreateModal = false"
        @success="handleCreateSuccess"
    />

    <DeleteAnnouncementModal
        :show="showDeleteModal"
        :announcement="announcementToDelete"
        @close="showDeleteModal = false"
        @confirm="executeDelete"
    />
</template>
