<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    editingUser: Object,
    form: Object
});

const showPasswordFields = ref(false);

const getUserInitials = (name) => {
    if (!name) return 'U';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};
</script>

<template>
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden" data-aos="fade-up">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                    {{ getUserInitials(editingUser.name) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ editingUser.name }}</h2>
                    <p class="text-sm text-slate-600">{{ editingUser.email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span v-if="editingUser.is_super_admin" class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                            Super Admin
                        </span>
                        <span v-else-if="editingUser.is_admin" class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800">
                            Admin
                        </span>
                        <span v-else class="px-2 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700">
                            User
                        </span>
                        <span class="text-xs text-slate-500">
                            Joined {{ editingUser.created_at }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <form @submit.prevent="$emit('submit')" class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    placeholder="Enter full name"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                <input
                    v-model="form.email"
                    type="email"
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                    placeholder="Enter email address"
                />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <div class="border-t border-slate-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-sm font-semibold text-slate-700">Change Password</label>
                    <button
                        type="button"
                        @click="showPasswordFields = !showPasswordFields"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                    >
                        {{ showPasswordFields ? 'Cancel' : 'Change Password' }}
                    </button>
                </div>

                <div v-if="showPasswordFields" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Enter new password (min 8 characters)"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">{{ form.errors.password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm Password</label>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Confirm new password"
                        />
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Important Notes</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Changes will be applied immediately</li>
                            <li>• This action will be logged in the audit system</li>
                            <li>• User will be notified of any email changes</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                <Link
                    href="/admin/dashboard"
                    class="px-6 py-3 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold transition-colors"
                >
                    Cancel
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="form.processing">Saving...</span>
                    <span v-else>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</template>
