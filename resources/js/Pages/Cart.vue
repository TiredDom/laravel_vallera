<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import CheckoutModal from '@/Components/CheckoutModal.vue';
import CartHeader from '@/Components/CartHeader.vue';
import SignInPrompt from '@/Components/SignInPrompt.vue';
import EmptyCart from '@/Components/EmptyCart.vue';
import CartItems from '@/Components/CartItems.vue';
import OrderSummary from '@/Components/OrderSummary.vue';
import SuggestedProducts from '@/Components/SuggestedProducts.vue';

import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useCart } from '@/Components/useCart.js';

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
const { addToCart, isProcessing } = useCart({ showToast, isAuthenticated, isAuthOpen });

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
    // Ensure the product object always has an id property
    selectedProduct.value = {
        ...product,
        id: product.id || product.product_id
    };
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
    setTimeout(() => {
        router.visit('/orders', { replace: true });
    }, 1200);
}

function handleAddToCart(product) {
    addToCart(product);
}

function updateQuantityValidated({ productId, quantity, stock }) {
    // Only allow numbers, min 1, max stock
    if (isNaN(quantity) || quantity < 1) {
        showToast('Quantity must be at least 1', 'error');
        return;
    }
    if (quantity > stock) {
        showToast('Not enough stock available', 'error');
        return;
    }
    updateQuantity(productId, quantity);
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

        <CartHeader :item-count="cartItems.length" />

        <div class="bg-zinc-50 min-h-screen">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <SignInPrompt v-if="!isAuthenticated" @open-auth-modal="isAuthOpen = true" />

                <EmptyCart v-else-if="!cartItems.length" />

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-up">
                    <CartItems
                        :cart-items="cartItems"
                        @update-quantity="updateQuantityValidated"
                        @remove-item="removeItem"
                    />
                    <div class="lg:col-span-1">
                        <OrderSummary
                            :subtotal="subtotal"
                            :shipping="shipping"
                            :total="total"
                            @checkout="checkout"
                        />
                    </div>
                </div>
            </div>
        </div>

        <SuggestedProducts
            :suggested-products="suggestedProducts"
            @add-to-cart="handleAddToCart"
            @product-click="handleProductClick"
        />

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
