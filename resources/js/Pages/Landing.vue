<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import HeroSection from '@/Components/HeroSection.vue';
import WhyChooseUs from '@/Components/WhyChooseUs.vue';
import ShopByCategory from '@/Components/ShopByCategory.vue';
import BestSellers from '@/Components/BestSellers.vue';
import Testimonials from '@/Components/Testimonials.vue';

import { Link, Head, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => []
    }
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const isAuthOpen = ref(false);
const isProductDetailOpen = ref(false);
const selectedProduct = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });

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

function handleAddToCart(product) {
    if (!isAuthenticated.value) {
        isProductDetailOpen.value = false;
        isAuthOpen.value = true;
        return;
    }

    const cartItem = {
        product_id: product.id,
        name: product.name,
        price: product.price,
        quantity: 1,
        category: product.category
    };

    router.post('/cart', cartItem, {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Product added to cart!', 'success');
            // Do not close modal for signed in user
        },
        onError: (errors) => {
            showToast(errors?.error || 'Failed to add to cart', 'error');
        }
    });
}
</script>

<template>
<Head title="Welcome to Vallera" />
<MainLayout>
    <ToastNotification
        :show="toast.show"
        :message="toast.message"
        :type="toast.type"
        @close="hideToast"
    />

    <HeroSection />
    <WhyChooseUs />
    <ShopByCategory />
    <BestSellers
        :featured-products="featuredProducts"
        @add-to-cart="handleAddToCart"
        @product-click="handleProductClick"
    />
    <Testimonials />

    <ProductDetailModal
        v-if="selectedProduct"
        :show="isProductDetailOpen"
        :product="selectedProduct"
        @close="isProductDetailOpen = false"
        @add-to-cart="handleAddToCart"
    />

    <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
</MainLayout>
</template>
