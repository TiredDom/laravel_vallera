<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import CollectionHero from '@/Components/Collection/CollectionHero.vue';
import CollectionFilters from '@/Components/Collection/CollectionFilters.vue';
import CollectionGrid from '@/Components/Collection/CollectionGrid.vue';
import CollectionCTA from '@/Components/Collection/CollectionCTA.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { useCart } from '@/Components/useCart.js';

const props = defineProps({
    products: Array,
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const filters = ['All', 'Sofas', 'Chairs', 'Tables', 'Beds', 'Storage', 'Desks'];
const activeFilter = ref('All');
const isAuthOpen = ref(false);
const showDetailModal = ref(false);
const selectedProduct = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });
const sortBy = ref('name');

const { addToCart, isProcessing } = useCart({ showToast, isAuthenticated, isAuthOpen });

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    if (category && filters.includes(category)) {
        activeFilter.value = category;
    }
});

const categoryDescriptions = {
    'All': 'Browse our complete collection of premium furniture',
    'Sofas': 'Comfortable sofas, sectionals, and couches for any space',
    'Chairs': 'Dining chairs, accent chairs, and office seating solutions',
    'Tables': 'Dining tables, coffee tables, and side tables',
    'Beds': 'Quality beds, bed frames, and mattress foundations',
    'Storage': 'Cabinets, shelving units, and organizational furniture',
    'Desks': 'Home office desks, workstations, and study tables',
};

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

function handleAddToCart(product) {
    addToCart(product);
}

function handleProductClick(product) {
    selectedProduct.value = product;
    showDetailModal.value = true;
}

const filteredProducts = computed(() => {
    let filtered = activeFilter.value === 'All'
        ? props.products
        : props.products.filter(p => p.category === activeFilter.value);

    if (sortBy.value === 'price-low') {
        filtered = [...filtered].sort((a, b) => a.price - b.price);
    } else if (sortBy.value === 'price-high') {
        filtered = [...filtered].sort((a, b) => b.price - a.price);
    } else if (sortBy.value === 'name') {
        filtered = [...filtered].sort((a, b) => a.name.localeCompare(b.name));
    }

    return filtered;
});

const stats = computed(() => ({
    total: props.products.length,
    newArrivals: props.products.filter(p => p.isNew).length,
    categories: [...new Set(props.products.map(p => p.category))].length
}));
</script>

<template>
<Head title="Our Collection" />
<MainLayout>
    <ToastNotification
        :show="toast.show"
        :message="toast.message"
        :type="toast.type"
        @close="hideToast"
    />

    <CollectionHero
        :stats="stats"
        :active-filter="activeFilter"
        :category-descriptions="categoryDescriptions"
    />

    <CollectionFilters
        :filters="filters"
        v-model:activeFilter="activeFilter"
        v-model:sortBy="sortBy"
    />

    <CollectionGrid
        :filtered-products="filteredProducts"
        @resetFilter="activeFilter = 'All'"
        @addToCart="handleAddToCart"
        @productClick="handleProductClick"
    />

    <CollectionCTA />

    <ProductDetailModal
        :show="showDetailModal"
        :product="selectedProduct"
        :is-auth-open="isAuthOpen"
        @close="showDetailModal = false"
        @add-to-cart="handleAddToCart"
    />

    <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
</MainLayout>
</template>
