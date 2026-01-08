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
    <div class="bg-zinc-50 font-sans text-zinc-500">
        <ToastNotification
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            @close="hideToast"
        />

        <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-sm shadow-sm z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <Link href="/" class="text-2xl font-bold text-zinc-900">
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

                    <div class="md:hidden flex items-center">
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-zinc-600">
                             <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>
             <div v-if="isMobileMenuOpen" class="md:hidden bg-white border-t border-zinc-100">
                <nav class="flex flex-col space-y-1 p-4">
                    <Link :href="route('home')" class="px-4 py-2 rounded-md font-medium">Home</Link>
                    <Link :href="route('products.index')" class="px-4 py-2 rounded-md font-medium">Products</Link>
                    <Link :href="route('about')" class="px-4 py-2 rounded-md font-medium">About</Link>
                    <Link :href="route('contact')" class="px-4 py-2 rounded-md font-medium">Contact</Link>
                    <template v-if="isAuthenticated">
                        <div class="px-4 py-2 text-sm text-zinc-500">Signed in as {{ user.name }}</div>
                        <Link v-if="user.is_admin" :href="route('admin.dashboard')" class="w-full text-left px-4 py-3 mt-1 rounded-md font-medium bg-zinc-100 hover:bg-zinc-200">Admin Dashboard</Link>
                        <Link :href="route('profile.edit')" class="w-full text-left px-4 py-3 mt-1 rounded-md font-medium bg-zinc-100 hover:bg-zinc-200">Settings</Link>
                        <button @click="handleMobileLogout" class="w-full text-left px-4 py-3 mt-1 rounded-md font-medium bg-red-50 text-red-600 hover:bg-red-100">Sign Out</button>
                    </template>
                    <template v-else>
                        <button @click="isAuthOpen = true" class="w-full text-left px-4 py-3 mt-2 rounded-md font-medium bg-zinc-100 hover:bg-zinc-200">Login</button>
                    </template>
                    <Link :href="route('cart.index')" class="w-full text-left px-4 py-3 mt-1 rounded-md font-medium bg-zinc-100 hover:bg-zinc-200">My Cart</Link>
                </nav>
            </div>
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
