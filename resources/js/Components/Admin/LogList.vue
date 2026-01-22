<script setup>
import { ClockIcon } from '@heroicons/vue/24/outline';

defineProps({
    filteredLogs: Array
});

function getActionColor(action) {
    const colors = {
        promote_user: 'bg-emerald-100 text-emerald-700',
        demote_user: 'bg-red-100 text-red-700',
        update_order_status: 'bg-blue-100 text-blue-700',
        create_order: 'bg-purple-100 text-purple-700',
        cancel_order: 'bg-amber-100 text-amber-700',
    };
    return colors[action] || 'bg-zinc-100 text-zinc-700';
}
</script>

<template>
    <div class="divide-y divide-zinc-200">
        <div v-if="filteredLogs.length === 0" class="p-12 text-center">
            <ClockIcon class="w-16 h-16 text-zinc-300 mx-auto mb-4" />
            <p class="text-xl font-semibold text-zinc-400">No logs found</p>
        </div>

        <div v-for="log in filteredLogs" :key="log.id" class="p-6 hover:bg-zinc-50 transition-colors">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ log.user.name.charAt(0).toUpperCase() }}
                </div>

                <div class="flex-1">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-zinc-900">{{ log.user.name }}</span>
                                <span :class="['px-2 py-0.5 rounded-full text-xs font-bold', getActionColor(log.action)]">
                                    {{ log.action.replace(/_/g, ' ').toUpperCase() }}
                                </span>
                            </div>
                            <p class="text-sm text-zinc-500">{{ log.user.email }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1.5 text-sm text-zinc-500">
                                <ClockIcon class="w-4 h-4" />
                                <span>{{ log.created_at }}</span>
                            </div>
                            <div v-if="log.ip_address" class="text-xs text-zinc-400 mt-1">
                                IP: {{ log.ip_address }}
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-50 rounded-lg p-4 border border-zinc-200">
                        <p class="text-sm text-zinc-700 font-medium">{{ log.description }}</p>
                        <div v-if="log.model_type" class="flex items-center gap-2 mt-2 text-xs text-zinc-500">
                            <span class="px-2 py-1 bg-white rounded border border-zinc-200">
                                {{ log.model_type }} #{{ log.model_id }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
