<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import CheckoutModal from '@/Components/CheckoutModal.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { ShoppingBagIcon, TrashIcon, PlusIcon, MinusIcon, ShieldCheckIcon, TruckIcon, SparklesIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    suggestedProducts: Array,
});

const page = usePage();
const cartItems = ref(page.props.cartItems || []);
const isAuthenticated = computed(() => !!page.props.auth?.user);
const isAuthOpen = ref(false);
const isCheckoutOpen = ref(false);
const isProductDetailOpen = ref(false);
const selectedProduct = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });

watch(() => page.props.cartItems, (newItems) => {
    cartItems.value = newItems || [];
});

watch(() => page.props.flash?.success, (message) => {
    if (message) {
        showToast(message, 'success');
    }
});

watch(() => page.props.flash?.error, (message) => {
    if (message) {
        showToast(message, 'error');
    }
});

function showToast(message, type = 'success') {
    toast.value = { show: true, message, type };
}

function hideToast() {
    toast.value.show = false;
}

function handleAuthSuccess(message) {
    showToast(message, 'success');
}

function handleProductClick(product) {
    selectedProduct.value = product;
    isProductDetailOpen.value = true;
}

const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0);
});

const shipping = computed(() => cartItems.value.length ? 500 : 0);
const total = computed(() => subtotal.value + shipping.value);

function removeItem(product_id) {
    router.delete(`/cart/${product_id}`, {
        preserveScroll: true,
    });
}

function updateQuantity(product_id, quantity) {
    router.patch(`/cart/${product_id}`, { quantity }, {
        preserveScroll: true,
    });
}

function checkout() {
    if (!isAuthenticated.value) {
        isAuthOpen.value = true;
        return;
    }

    if (cartItems.value.length === 0) {
        showToast('Your cart is empty', 'error');
        return;
    }

    isCheckoutOpen.value = true;
}

function handleCheckoutSuccess() {
    showToast('Order placed successfully! Redirecting to your orders...', 'success');
    isCheckoutOpen.value = false;
}

function handleAddToCart(product) {
    if (!isAuthenticated.value) {
        isAuthOpen.value = true;
        return;
    }

    router.post('/cart', product, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Your Cart" />
    <MainLayout>
        <ToastNotification
            :show="toast.show"
            :message="toast.message"
            :type="toast.type"
            @close="hideToast"
        />

        <div class="relative bg-gradient-to-br from-primary-600 via-emerald-700 to-emerald-800 text-white overflow-hidden">
            <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-10"></div>
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">
                <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                        <ShoppingBagIcon class="w-4 h-4" />
                        <span class="text-sm font-medium">{{ cartItems.length }} {{ cartItems.length === 1 ? 'Item' : 'Items' }}</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">
                        Your Shopping Cart
                    </h1>
                    <p class="text-lg text-emerald-100">
                        Review your items and proceed to secure checkout
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-zinc-50 min-h-screen">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div v-if="!isAuthenticated" class="max-w-2xl mx-auto text-center py-24 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-primary-100 to-emerald-100 rounded-full flex items-center justify-center mb-6">
                        <ShoppingBagIcon class="w-12 h-12 text-primary-600" />
                    </div>
                    <h2 class="text-3xl font-bold text-zinc-900 mb-3">Sign in to view your cart</h2>
                    <p class="text-lg text-zinc-600 mb-8">Please sign in or create an account to start shopping.</p>
                    <button @click="isAuthOpen = true" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold px-8 py-4 rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-lg">
                        <SparklesIcon class="w-5 h-5" />
                        Sign In / Sign Up
                    </button>
                </div>

                <div v-else-if="!cartItems.length" class="max-w-2xl mx-auto text-center py-24 bg-white rounded-2xl shadow-lg" data-aos="fade-up">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-br from-zinc-100 to-zinc-200 rounded-full flex items-center justify-center mb-6">
                        <ShoppingBagIcon class="w-12 h-12 text-zinc-400" />
                    </div>
                    <h2 class="text-3xl font-bold text-zinc-900 mb-3">Your cart is empty</h2>
                    <p class="text-lg text-zinc-600 mb-8">Discover our collection of premium furniture</p>
                    <Link href="/products" class="inline-flex items-center gap-2 bg-primary-600 text-white font-bold px-8 py-4 rounded-xl hover:bg-primary-700 transition-all hover:scale-105 shadow-lg">
                        Continue Shopping
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-up">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-zinc-50 to-white px-6 py-4 border-b border-zinc-200">
                                <h2 class="text-xl font-bold text-zinc-900">Cart Items</h2>
                            </div>
                            <ul role="list" class="divide-y divide-zinc-200">
                                <li v-for="item in cartItems" :key="item.product_id || item.id" class="p-6 hover:bg-zinc-50 transition-colors group">
                                    <div class="flex gap-6">
                                        <div class="flex-shrink-0 w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-zinc-100 to-zinc-200 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                            <span class="text-zinc-400 text-sm font-medium">Image</span>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between">
                                                    <div>
                                                        <h3 class="text-lg font-bold text-zinc-900 mb-1">{{ item.name }}</h3>
                                                        <p class="text-sm text-zinc-500 uppercase tracking-wider">{{ item.category }}</p>
                                                    </div>
                                                    <p class="text-xl font-bold text-primary-600 ml-4">₱{{ (item.price * item.quantity).toLocaleString() }}</p>
                                                </div>
                                                <p class="text-sm text-zinc-600 mt-2">₱{{ item.price.toLocaleString() }} each</p>
                                            </div>
                                            <div class="flex items-center justify-between mt-4">
                                                <div class="flex items-center gap-3 bg-zinc-100 rounded-xl p-1">
                                                    <button @click="updateQuantity(item.product_id || item.id, item.quantity - 1)" :disabled="item.quantity <= 1" class="w-10 h-10 rounded-lg bg-white hover:bg-primary-600 hover:text-white disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-zinc-900 transition-all flex items-center justify-center shadow-sm">
                                                        <MinusIcon class="w-4 h-4" />
                                                    </button>
                                                    <span class="min-w-[60px] text-center font-bold text-zinc-900">{{ item.quantity }}</span>
                                                    <button @click="updateQuantity(item.product_id || item.id, item.quantity + 1)" class="w-10 h-10 rounded-lg bg-white hover:bg-primary-600 hover:text-white transition-all flex items-center justify-center shadow-sm">
                                                        <PlusIcon class="w-4 h-4" />
                                                    </button>
                                                </div>
                                                <button type="button" class="inline-flex items-center gap-2 font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors" @click="removeItem(item.product_id || item.id)">
                                                    <TrashIcon class="w-4 h-4" />
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6">
                            <h3 class="text-lg font-bold text-zinc-900 mb-4">Why Shop With Us?</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <ShieldCheckIcon class="w-6 h-6 text-white" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-zinc-900 text-sm">Secure Payment</p>
                                        <p class="text-xs text-zinc-600">100% Protected</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                                        <TruckIcon class="w-6 h-6 text-white" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-zinc-900 text-sm">Free Shipping</p>
                                        <p class="text-xs text-zinc-600">All Orders</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-4">
                            <div class="bg-white rounded-2xl shadow-lg border border-zinc-200 p-6">
                                <h2 class="text-xl font-bold text-zinc-900 mb-6">Order Summary</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between text-zinc-600">
                                        <p>Subtotal</p>
                                        <p class="font-semibold text-zinc-900">₱{{ subtotal.toLocaleString() }}</p>
                                    </div>
                                    <div class="flex items-center justify-between text-zinc-600">
                                        <p>Shipping Fee</p>
                                        <p class="font-semibold text-emerald-600">₱{{ shipping.toLocaleString() }}</p>
                                    </div>
                                    <div class="border-t-2 border-zinc-200 pt-4">
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-bold text-zinc-900">Total</p>
                                            <p class="text-2xl font-bold text-primary-600">₱{{ total.toLocaleString() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button class="w-full bg-gradient-to-r from-primary-600 to-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all" @click="checkout">
                                        Proceed to Checkout
                                    </button>
                                </div>
                                <Link href="/products" class="block text-center mt-4 text-primary-600 hover:text-primary-700 font-semibold text-sm">
                                    Continue Shopping
                                </Link>
                            </div>

                            <div class="bg-gradient-to-br from-zinc-50 to-white rounded-2xl border border-zinc-200 p-6">
                                <h3 class="text-sm font-bold text-zinc-900 mb-3 uppercase tracking-wider">Secure Checkout</h3>
                                <div class="space-y-3 text-sm text-zinc-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>SSL Encrypted Payment</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>30-Day Return Policy</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>5-Year Warranty</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="suggestedProducts && suggestedProducts.length > 0" class="bg-white py-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up">
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 mb-3">You Might Also Like</h2>
                    <p class="text-lg text-zinc-600">Complete your home with these handpicked items</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="(item, index) in suggestedProducts"
                        :key="item.id"
                        :id="item.id"
                        :name="item.name"
                        :price="item.price"
                        :category="item.category"
                        :image="item.image_url"
                        :delay="index * 100"
                        @add-to-cart="handleAddToCart"
                        @click="handleProductClick(item)"
                    />
                </div>
            </div>
        </div>

        <ProductDetailModal
            v-if="selectedProduct"
            :show="isProductDetailOpen"
            :product="selectedProduct"
            @close="isProductDetailOpen = false"
            @add-to-cart="handleAddToCart"
        />

        <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
        <CheckoutModal
            :show="isCheckoutOpen"
            :total="total"
            :subtotal="subtotal"
            :shipping="shipping"
            @close="isCheckoutOpen = false"
            @success="handleCheckoutSuccess"
        />
    </MainLayout>
</template>
