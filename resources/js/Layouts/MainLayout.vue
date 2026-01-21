<script setup>
import { ref, onMounted, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import AOS from 'aos';
import 'aos/dist/aos.css';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import UserDropdown from '@/Components/UserDropdown.vue';
import { ShoppingCartIcon } from '@heroicons/vue/24/outline';

const page = usePage();
const isMobileMenuOpen = ref(false);
const isAuthOpen = ref(false);

const toast = ref({
    show: false,
    message: '',
    type: 'success'
});

const user = computed(() => page.props.auth?.user);
const isAuthenticated = computed(() => !!user.value);
const cartCount = computed(() => page.props.cartCount || 0);

function showToast(message, type = 'success') {
    toast.value = { show: true, message, type };
}

function hideToast() {
    toast.value.show = false;
}

function handleAuthSuccess(message) {
    showToast(message, 'success');
}

function handleLogoutSuccess() {
    showToast('Signed out successfully!', 'info');
}

function handleMobileLogout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            isMobileMenuOpen.value = false;
            handleLogoutSuccess();
        }
    });
}

onMounted(() => {
    AOS.init({
        duration: 800,
        once: true,
    });
});
</script>

<template>
    <div class="bg-zinc-50 font-sans text-zinc-500 overflow-x-hidden min-w-0">
        <ToastNotification
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            @close="hideToast"
        />

        <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-sm shadow-sm z-50">
            <div class="relative w-full px-3 sm:px-4 md:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 md:h-20 max-w-7xl mx-auto">
                    <Link href="/" class="text-xl md:text-2xl font-bold text-zinc-900 flex-shrink-0">
                        Vallera
                    </Link>

                    <nav class="hidden md:flex items-center space-x-10 text-sm font-medium">
                        <Link :href="route('home')" :class="{'text-primary-600' : $page.url === '/'}">Home</Link>
                        <Link :href="route('products.index')" :class="{'text-primary-600' : $page.url.startsWith('/products')}">Products</Link>
                        <Link :href="route('about')" :class="{'text-primary-600' : $page.url.startsWith('/about')}">About</Link>
                        <Link :href="route('contact')" :class="{'text-primary-600' : $page.url.startsWith('/contact')}">Contact</Link>
                    </nav>

                    <div class="hidden md:flex items-center gap-4">
                        <template v-if="isAuthenticated">
                            <UserDropdown :user="user" @logout-success="handleLogoutSuccess" />
                        </template>
                        <template v-else>
                            <button @click="isAuthOpen = true" class="text-sm font-medium text-zinc-600 hover:text-primary-600 transition-colors">Login</button>
                        </template>
                        <Link :href="route('cart.index')" class="relative text-zinc-600 hover:text-primary-600 transition-colors">
                             <ShoppingCartIcon class="w-6 h-6" />
                             <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-primary-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">
                                 {{ cartCount > 99 ? '99+' : cartCount }}
                             </span>
                        </Link>
                    </div>

                    <div class="md:hidden flex items-center gap-2 flex-shrink-0">
                        <Link :href="route('cart.index')" class="relative text-zinc-600 hover:text-primary-600 transition-colors p-2">
                            <ShoppingCartIcon class="w-6 h-6" />
                            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">
                                {{ cartCount > 9 ? '9+' : cartCount }}
                            </span>
                        </Link>
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-zinc-600 hover:text-primary-600 transition-colors p-2">
                            <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="isMobileMenuOpen" class="absolute left-0 right-0 top-full md:hidden bg-white border-t border-zinc-200 shadow-lg w-full overflow-x-hidden">
                    <nav class="flex flex-col py-4 max-h-[calc(100vh-5rem)] overflow-y-auto overflow-x-hidden">
                        <Link
                            :href="route('home')"
                            @click="isMobileMenuOpen = false"
                            :class="['flex items-center gap-3 px-6 py-3 font-medium transition-colors',
                                    $page.url === '/' ? 'text-primary-600 bg-primary-50' : 'text-zinc-700 hover:bg-zinc-50']"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Home
                        </Link>
                        <Link
                            :href="route('products.index')"
                            @click="isMobileMenuOpen = false"
                            :class="['flex items-center gap-3 px-6 py-3 font-medium transition-colors',
                                    $page.url.startsWith('/products') ? 'text-primary-600 bg-primary-50' : 'text-zinc-700 hover:bg-zinc-50']"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Products
                        </Link>
                        <Link
                            :href="route('about')"
                            @click="isMobileMenuOpen = false"
                            :class="['flex items-center gap-3 px-6 py-3 font-medium transition-colors',
                                    $page.url.startsWith('/about') ? 'text-primary-600 bg-primary-50' : 'text-zinc-700 hover:bg-zinc-50']"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            About
                        </Link>
                        <Link
                            :href="route('contact')"
                            @click="isMobileMenuOpen = false"
                            :class="['flex items-center gap-3 px-6 py-3 font-medium transition-colors',
                                    $page.url.startsWith('/contact') ? 'text-primary-600 bg-primary-50' : 'text-zinc-700 hover:bg-zinc-50']"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact
                        </Link>

                        <div class="h-px bg-zinc-200 my-2 mx-4"></div>

                        <template v-if="isAuthenticated">
                            <div class="px-6 py-3">
                                <div class="flex items-center gap-3 text-sm text-zinc-500">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-600 font-bold text-xs">{{ user.name.charAt(0).toUpperCase() }}</span>
                                    </div>
                                    <span class="font-medium">{{ user.name }}</span>
                                </div>
                            </div>
                            <Link
                                v-if="user.is_admin"
                                :href="route('admin.dashboard')"
                                @click="isMobileMenuOpen = false"
                                class="flex items-center gap-3 px-6 py-3 font-medium text-purple-600 bg-purple-50 hover:bg-purple-100 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Admin Dashboard
                            </Link>
                            <Link
                                :href="route('orders.index')"
                                @click="isMobileMenuOpen = false"
                                class="flex items-center gap-3 px-6 py-3 font-medium text-zinc-700 hover:bg-zinc-50 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                My Orders
                            </Link>
                            <button
                                @click="handleMobileLogout"
                                class="flex items-center gap-3 w-full text-left px-6 py-3 font-medium text-red-600 hover:bg-red-50 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </button>
                        </template>
                        <template v-else>
                            <button
                                @click="isAuthOpen = true; isMobileMenuOpen = false"
                                class="flex items-center gap-3 w-full text-left px-6 py-3 font-medium text-white bg-primary-600 hover:bg-primary-700 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login / Register
                            </button>
                        </template>
                    </nav>
                </div>
            </transition>
        </header>

        <main class="min-h-screen pt-20">
            <slot />
        </main>

        <footer class="bg-zinc-900 text-zinc-300">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="sm:col-span-2 md:col-span-1">
                        <h2 class="text-3xl font-bold text-white">Vallera</h2>
                        <p class="mt-4 text-sm text-zinc-400">Crafting comfort and style for the modern home, designed for life.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white tracking-wider">Quick Links</h3>
                        <ul class="mt-4 space-y-3 text-sm">
                            <li><Link :href="route('products.index')" class="hover:text-primary-500 transition-colors">Shop</Link></li>
                            <li><Link :href="route('about')" class="hover:text-primary-500 transition-colors">About Us</Link></li>
                            <li><Link :href="route('contact')" class="hover:text-primary-500 transition-colors">FAQ</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white tracking-wider">Contact</h3>
                        <ul class="mt-4 space-y-3 text-sm text-zinc-400">
                           <li>hello@vallera.ph</li>
                           <li>Philippines</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-white tracking-wider">Stay Updated</h3>
                        <p class="mt-4 text-sm">Join our newsletter for exclusive deals.</p>
                        <div class="mt-4 flex">
                            <input type="email" placeholder="Your email" class="bg-zinc-800 border-zinc-700 text-white w-full rounded-l-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <button class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 rounded-r-md transition-colors flex-shrink-0">Subscribe</button>
                        </div>
                    </div>
                </div>
                <div class="mt-16 pt-8 border-t border-zinc-800 text-center text-sm text-zinc-500">
                    <p>&copy; 2025 Vallera. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
    </div>
</template>
