<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { FunnelIcon, Squares2X2Icon, SparklesIcon } from '@heroicons/vue/24/outline';

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
    if (!isAuthenticated.value) {
        isAuthOpen.value = true;
        return;
    }

    router.post('/cart', product, {
        preserveScroll: true,
    });
}

function viewProductDetail(product) {
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

    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <SparklesIcon class="w-4 h-4" />
                    <span class="text-sm font-medium">{{ stats.newArrivals }} New Arrivals</span>
                </div>
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6">
                    Discover Your Perfect Piece
                </h1>
                <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto mb-8">
                    {{ categoryDescriptions[activeFilter] }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <Squares2X2Icon class="w-5 h-5 text-primary-200" />
                        <span>{{ stats.total }} Products</span>
                    </div>
                    <div class="w-1 h-1 bg-white/30 rounded-full"></div>
                    <div class="flex items-center gap-2">
                        <FunnelIcon class="w-5 h-5 text-primary-200" />
                        <span>{{ stats.categories }} Categories</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-zinc-200 lg:sticky lg:top-20 z-30 shadow-sm">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 md:py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 md:gap-6">
                    <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0" data-aos="fade-right">
                        <div class="flex gap-2 min-w-max md:min-w-0 md:flex-wrap">
                            <button
                                v-for="filter in filters"
                                :key="filter"
                                @click="activeFilter = filter"
                                :class="[
                                    'px-4 md:px-5 py-2 md:py-2.5 rounded-lg text-sm font-semibold transition-all duration-300 whitespace-nowrap',
                                    activeFilter === filter
                                        ? 'bg-primary-600 text-white shadow-md'
                                        : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'
                                ]"
                            >
                                {{ filter }}
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 flex-shrink-0" data-aos="fade-left">
                        <label class="text-sm font-medium text-zinc-700 hidden sm:block">Sort:</label>
                        <select
                            v-model="sortBy"
                            class="w-full sm:w-auto px-3 md:px-4 py-2 md:py-2.5 bg-zinc-100 border-0 rounded-lg text-sm font-medium text-zinc-700 focus:ring-2 focus:ring-primary-500 cursor-pointer"
                        >
                            <option value="name">Name (A-Z)</option>
                            <option value="price-low">Price (Low-High)</option>
                            <option value="price-high">Price (High-Low)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="bg-zinc-50 py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div v-if="filteredProducts.length === 0" class="text-center py-24" data-aos="fade-up">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-zinc-200 rounded-full mb-6">
                    <FunnelIcon class="w-10 h-10 text-zinc-400" />
                </div>
                <h3 class="text-2xl font-bold text-zinc-900 mb-2">No products found</h3>
                <p class="text-zinc-600 mb-6">Try adjusting your filters</p>
                <button
                    @click="activeFilter = 'All'"
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors"
                >
                    View All Products
                </button>
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <ProductCard
                    v-for="(product, index) in filteredProducts"
                    :key="product.id"
                    :id="product.id"
                    :name="product.name"
                    :price="product.price"
                    :category="product.category"
                    :image="product.image_url"
                    :delay="index * 50"
                    @add-to-cart="handleAddToCart"
                    @view-details="viewProductDetail(product)"
                />
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Can't Find What You're Looking For?
                </h2>
                <p class="text-zinc-300 text-lg mb-8">
                    We offer custom furniture design services tailored to your unique style and space requirements.
                </p>
                <Link
                    href="/contact"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white text-zinc-900 rounded-lg font-semibold hover:bg-zinc-100 transition-all hover:scale-105 shadow-xl"
                >
                    Get Custom Quote
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </section>

    <ProductDetailModal
        :show="showDetailModal"
        :product="selectedProduct"
        @close="showDetailModal = false"
        @add-to-cart="handleAddToCart"
    />


    <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
</MainLayout>
</template>
