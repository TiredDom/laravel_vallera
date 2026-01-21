<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { UserCircleIcon, ShoppingBagIcon, ArrowRightOnRectangleIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object
});

const page = usePage();
const ordersCount = computed(() => page.props.auth?.user?.orders_count || 0);

const emit = defineEmits(['logout-success']);

const isOpen = ref(false);
const dropdown = ref(null);

function toggle() {
    isOpen.value = !isOpen.value;
}

function close() {
    isOpen.value = false;
}

function handleLogout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            close();
            emit('logout-success');
        }
    });
}

function handleClickOutside(event) {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        close();
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative" ref="dropdown">
        <button @click="toggle" class="flex items-center gap-2 text-sm font-medium text-zinc-600 hover:text-primary-600 transition-colors focus:outline-none">
            <div class="relative">
                <UserCircleIcon class="w-6 h-6" />
                <span v-if="ordersCount > 0" class="absolute -top-1 -right-1 h-4 w-4 bg-primary-600 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ ordersCount > 9 ? '9+' : ordersCount }}
                </span>
            </div>
            <span class="hidden sm:inline max-w-[120px] truncate">{{ user.name }}</span>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': isOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-if="isOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-zinc-100 py-2 z-50">
                <div class="px-4 py-3 border-b border-zinc-100">
                    <p class="text-sm font-semibold text-zinc-900 truncate">{{ user.name }}</p>
                    <p class="text-xs text-zinc-500 truncate">{{ user.email }}</p>
                </div>

                <div class="py-1">
                    <Link v-if="user.is_admin" :href="route('admin.dashboard')" @click="close" class="flex items-center gap-3 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                        <ShieldCheckIcon class="w-5 h-5 text-zinc-400" />
                        Admin Dashboard
                    </Link>
                    <Link :href="route('orders.index')" @click="close" class="flex items-center justify-between px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <ShoppingBagIcon class="w-5 h-5 text-zinc-400" />
                            My Orders
                        </div>
                        <span v-if="ordersCount > 0" class="ml-2 px-2 py-0.5 bg-primary-600 text-white text-xs font-bold rounded-full">
                            {{ ordersCount }}
                        </span>
                    </Link>
                </div>

                <div class="border-t border-zinc-100 py-1">
                    <button @click="handleLogout" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <ArrowRightOnRectangleIcon class="w-5 h-5" />
                        Sign Out
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

