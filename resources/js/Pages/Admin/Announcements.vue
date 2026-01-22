<template>
    <Head title="Announcements" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-cyan-50 to-blue-50">
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent">
                                Announcements
                            </h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Create and manage announcements</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button
                            @click="manualRefresh"
                            class="flex-1 sm:flex-none px-3 py-2 sm:px-4 sm:py-2 bg-white text-slate-700 border border-slate-300 rounded-lg font-semibold hover:bg-slate-50 transition-all shadow hover:shadow-md flex items-center justify-center gap-2 text-sm sm:text-base"
                            title="Refresh announcements"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="hidden sm:inline">Refresh</span>
                        </button>
                        <button
                            @click="showCreateModal = true"
                            class="flex-1 sm:flex-none px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>New</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div v-if="isSuperAdmin" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" data-aos="fade-up">
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

            <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
                <div
                    v-for="announcement in safeAnnouncements.data"
                    :key="announcement.id"
                    class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-xl transition-shadow"
                >
                    <div :class="['px-6 py-4 border-l-4', getTypeColorClass(announcement.type)]">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl font-bold text-slate-900">{{ announcement.title }}</h3>
                                    <span :class="getStatusBadgeClass(announcement.status)">
                                        {{ announcement.status }}
                                    </span>
                                    <span :class="getTypeBadgeClass(announcement.type)">
                                        {{ announcement.type }}
                                    </span>
                                </div>
                                <p class="text-slate-700 mb-3">{{ announcement.message }}</p>
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ announcement.creator?.name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>{{ formatAudience(announcement.target_audience) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ formatDate(announcement.created_at) }}</span>
                                    </div>
                                    <div v-if="announcement.expires_at" class="flex items-center gap-1 text-amber-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Expires {{ formatDate(announcement.expires_at) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <button
                                    v-if="isSuperAdmin && announcement.status === 'pending'"
                                    @click="reviewAnnouncement(announcement, 'approved')"
                                    class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-semibold hover:bg-green-200 transition-colors"
                                >
                                    Approve
                                </button>
                                <button
                                    v-if="isSuperAdmin && announcement.status === 'pending'"
                                    @click="reviewAnnouncement(announcement, 'rejected')"
                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors"
                                >
                                    Reject
                                </button>
                                <button
                                    v-if="canDelete(announcement)"
                                    @click="confirmDelete(announcement)"
                                    class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="!safeAnnouncements.data || safeAnnouncements.data.length === 0" class="bg-white rounded-xl shadow-lg border border-slate-200 p-12 text-center">
                    <svg class="mx-auto w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    <p class="text-slate-600 font-medium text-lg">No announcements yet</p>
                    <p class="text-slate-500 text-sm mt-2">Create your first announcement to notify users</p>
                    <button
                        @click="showCreateModal = true"
                        class="mt-4 px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all"
                    >
                        Create Announcement
                    </button>
                </div>
            </div>

            <div v-if="safeAnnouncements.data && safeAnnouncements.data.length > 0" class="mt-6 flex justify-center">
                <div class="flex gap-2">
                    <template v-for="link in safeAnnouncements.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-4 py-2 text-sm font-medium rounded-lg transition-all',
                                link.active
                                    ? 'bg-cyan-600 text-white shadow-lg'
                                    : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-300'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-400 cursor-not-allowed"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>

    <TransitionRoot :show="showCreateModal" as="template">
        <Dialog @close="showCreateModal = false" class="relative z-50">
            <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-2xl bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-cyan-600 to-blue-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Create New Announcement</h3>
                            </div>
                            <form @submit.prevent="createAnnouncement" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Title</label>
                                    <input
                                        v-model="createForm.title"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        placeholder="Enter announcement title"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Message</label>
                                    <textarea
                                        v-model="createForm.message"
                                        rows="4"
                                        required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        placeholder="Enter announcement message"
                                    ></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Type</label>
                                        <select
                                            v-model="createForm.type"
                                            required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        >
                                            <option value="info">Info</option>
                                            <option value="warning">Warning</option>
                                            <option value="success">Success</option>
                                            <option value="danger">Danger</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Target Audience</label>
                                        <select
                                            v-model="createForm.target_audience"
                                            required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                        >
                                            <option value="all">All Users</option>
                                            <option value="admins">Admins Only</option>
                                            <option value="users">Regular Users</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Expiration Date (Optional)</label>
                                    <input
                                        v-model="createForm.expires_at"
                                        type="datetime-local"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                    />
                                </div>

                                <div v-if="!isSuperAdmin" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-900">Approval Required</p>
                                            <p class="text-sm text-amber-700 mt-1">Your announcement will be submitted for super admin approval before being published.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                    <button
                                        type="button"
                                        @click="showCreateModal = false"
                                        class="px-4 py-2 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="createForm.processing"
                                        class="px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ createForm.processing ? 'Creating...' : (isSuperAdmin ? 'Publish' : 'Submit') }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <TransitionRoot :show="showDeleteModal" as="template">
        <Dialog @close="showDeleteModal = false" class="relative z-50">
            <TransitionChild
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        enter="ease-out duration-300"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Delete Announcement</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-slate-900 mb-2">Are you sure?</h4>
                                        <p class="text-slate-600 mb-3">
                                            Do you really want to delete "<span class="font-semibold">{{ announcementToDelete?.title }}</span>"?
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            This action cannot be undone. The announcement will be permanently removed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 rounded-b-xl flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    @click="showDeleteModal = false"
                                    class="px-4 py-2 text-slate-700 bg-white hover:bg-slate-100 rounded-lg font-medium border border-slate-300 transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    @click="executeDelete"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors"
                                >
                                    Delete
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';

const props = defineProps({
    announcements: Object,
    isSuperAdmin: Boolean,
});

const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const announcementToDelete = ref(null);
let refreshInterval = null;

const createForm = useForm({
    title: '',
    message: '',
    type: 'info',
    target_audience: 'all',
    expires_at: '',
});

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

const createAnnouncement = () => {
    createForm.post('/admin/announcements', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

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

const canDelete = (announcement) => {
    if (props.isSuperAdmin) return true;
    return announcement.created_by === props.announcements.currentUserId;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatAudience = (audience) => {
    const map = {
        all: 'All Users',
        admins: 'Admins Only',
        users: 'Regular Users'
    };
    return map[audience] || audience;
};

const getStatusBadgeClass = (status) => {
    const baseClass = 'inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-full';
    const classes = {
        pending: `${baseClass} bg-amber-100 text-amber-800`,
        published: `${baseClass} bg-green-100 text-green-800`,
        approved: `${baseClass} bg-blue-100 text-blue-800`,
        rejected: `${baseClass} bg-red-100 text-red-800`,
    };
    return classes[status] || baseClass;
};

const getTypeBadgeClass = (type) => {
    const baseClass = 'inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full';
    const classes = {
        info: `${baseClass} bg-blue-50 text-blue-700`,
        warning: `${baseClass} bg-amber-50 text-amber-700`,
        success: `${baseClass} bg-green-50 text-green-700`,
        danger: `${baseClass} bg-red-50 text-red-700`,
    };
    return classes[type] || baseClass;
};

const getTypeColorClass = (type) => {
    const classes = {
        info: 'border-blue-500 bg-blue-50',
        warning: 'border-amber-500 bg-amber-50',
        success: 'border-green-500 bg-green-50',
        danger: 'border-red-500 bg-red-50',
    };
    return classes[type] || 'border-slate-500 bg-slate-50';
};
</script>
