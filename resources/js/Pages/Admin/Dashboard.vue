<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { CalendarIcon } from '@heroicons/vue/24/outline';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import AdminNavbar from '@/Components/Admin/AdminNavbar.vue';
import DashboardStats from '@/Components/Admin/DashboardStats.vue';
import QuickLinks from '@/Components/Admin/QuickLinks.vue';
import RecentActivity from '@/Components/Admin/RecentActivity.vue';
import UserManagement from '@/Components/Admin/UserManagement.vue';
import RequestEditModal from '@/Components/Admin/RequestEditModal.vue';

const props = defineProps({
    users: Array,
    stats: Object,
    isSuperAdmin: Boolean
});

const page = usePage();
const currentUser = computed(() => page.props.auth?.user);
let refreshInterval = null;

const showPromoteModal = ref(false);
const showDemoteModal = ref(false);
const showDeleteModal = ref(false);
const showRequestEditModal = ref(false);
const userToPromote = ref(null);
const userToDemote = ref(null);
const userToDelete = ref(null);
const userToRequestEdit = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({ only: ['stats'], preserveScroll: true, preserveState: true });
    }, 15000);
});

onUnmounted(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});

watch(() => page.props.flash?.success, (message) => {
    if (message) {
        toast.value = { show: true, message, type: 'success' };
    }
});

watch(() => page.props.flash?.error, (message) => {
    if (message) {
        toast.value = { show: true, message, type: 'error' };
    }
});

function hideToast() {
    toast.value.show = false;
}

function promptPromote(user) {
    userToPromote.value = user;
    showPromoteModal.value = true;
}

function confirmPromote() {
    if (userToPromote.value) {
        router.post(`/admin/users/${userToPromote.value.id}/promote`, {}, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToPromote.value = null;
                showPromoteModal.value = false;
            }
        });
    }
}

function promptDemote(user) {
    userToDemote.value = user;
    showDemoteModal.value = true;
}

function confirmDemote() {
    if (userToDemote.value) {
        router.post(`/admin/users/${userToDemote.value.id}/demote`, {}, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToDemote.value = null;
                showDemoteModal.value = false;
            }
        });
    }
}

function promptDelete(user) {
    userToDelete.value = user;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (userToDelete.value) {
        router.delete(`/admin/users/${userToDelete.value.id}`, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                userToDelete.value = null;
                showDeleteModal.value = false;
            }
        });
    }
}

function promptRequestEdit(user) {
    userToRequestEdit.value = user;
    showRequestEditModal.value = true;
}

function handleRequestEditSuccess() {
    showRequestEditModal.value = false;
    userToRequestEdit.value = null;
}
</script>

<template>
    <Head title="Admin Dashboard" />

    <ToastNotification
        :show="toast.show"
        :message="toast.message"
        :type="toast.type"
        @close="hideToast"
    />

    <div class="min-h-screen bg-gradient-to-br from-zinc-50 via-slate-50 to-zinc-100 w-full max-w-full overflow-hidden">
        <AdminNavbar :current-user="currentUser" :is-super-admin="isSuperAdmin" />

        <main class="max-w-7xl mx-auto py-6 md:py-8 px-4 sm:px-6 lg:px-8 w-full">
            <div class="mb-6 md:mb-8" data-aos="fade-up">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 md:gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-zinc-900 bg-gradient-to-r from-zinc-900 to-zinc-700 bg-clip-text text-transparent">Dashboard Overview</h1>
                        <p class="text-zinc-600 mt-1 md:mt-2 text-sm md:text-lg">Welcome back! Here's what's happening today.</p>
                    </div>
                    <div class="flex items-center gap-2 px-3 md:px-4 py-2 bg-white rounded-xl shadow-md border border-zinc-200">
                        <CalendarIcon class="w-4 md:w-5 h-4 md:h-5 text-primary-600" />
                        <span class="text-xs md:text-sm font-medium text-zinc-700">{{ new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) }}</span>
                    </div>
                </div>
            </div>

            <DashboardStats :stats="stats" />

            <QuickLinks :is-super-admin="isSuperAdmin" :stats="stats" />

            <RecentActivity :stats="stats" />

            <UserManagement
                :users="users"
                :is-super-admin="isSuperAdmin"
                @promote="promptPromote"
                @demote="promptDemote"
                @delete="promptDelete"
                @request-edit="promptRequestEdit"
            />
        </main>

        <ConfirmModal
            :show="showPromoteModal"
            title="Promote User to Admin"
            message="Are you sure you want to promote this user to admin? They will have access to the admin dashboard and management tools."
            confirm-text="Yes, Promote"
            cancel-text="Cancel"
            type="info"
            @close="showPromoteModal = false"
            @confirm="confirmPromote"
        />

        <ConfirmModal
            :show="showDemoteModal"
            title="Demote Admin to User"
            message="Are you sure you want to demote this admin? They will lose access to all admin features and dashboard."
            confirm-text="Yes, Demote"
            cancel-text="Cancel"
            type="danger"
            @close="showDemoteModal = false"
            @confirm="confirmDemote"
        />

        <ConfirmModal
            :show="showDeleteModal"
            title="Delete User"
            :message="`Are you sure you want to delete ${userToDelete?.name}? This action cannot be undone and will remove all their data.`"
            confirm-text="Yes, Delete"
            cancel-text="Cancel"
            type="danger"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />
    </div>

    <RequestEditModal
        :show="showRequestEditModal"
        :user="userToRequestEdit"
        @close="showRequestEditModal = false"
        @success="handleRequestEditSuccess"
    />
</template>
