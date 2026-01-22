<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    announcements: Object,
    isSuperAdmin: Boolean,
    currentUserId: Number
});

const emit = defineEmits(['review', 'delete', 'create']);

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

const canDelete = (announcement) => {
    if (props.isSuperAdmin) return true;
    return announcement.created_by === props.currentUserId;
};
</script>

<template>
    <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
        <div
            v-for="announcement in announcements.data"
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
                            @click="$emit('review', announcement, 'approved')"
                            class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-semibold hover:bg-green-200 transition-colors"
                        >
                            Approve
                        </button>
                        <button
                            v-if="isSuperAdmin && announcement.status === 'pending'"
                            @click="$emit('review', announcement, 'rejected')"
                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors"
                        >
                            Reject
                        </button>
                        <button
                            v-if="canDelete(announcement)"
                            @click="$emit('delete', announcement)"
                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="!announcements.data || announcements.data.length === 0" class="bg-white rounded-xl shadow-lg border border-slate-200 p-12 text-center">
            <svg class="mx-auto w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            <p class="text-slate-600 font-medium text-lg">No announcements yet</p>
            <p class="text-slate-500 text-sm mt-2">Create your first announcement to notify users</p>
            <button
                @click="$emit('create')"
                class="mt-4 px-6 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg font-semibold hover:from-cyan-700 hover:to-blue-700 transition-all"
            >
                Create Announcement
            </button>
        </div>
    </div>

    <div v-if="announcements.data && announcements.data.length > 0" class="mt-6 flex justify-center">
        <div class="flex gap-2">
            <template v-for="link in announcements.links" :key="link.label">
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
</template>
