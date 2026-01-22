<template>
    <Head title="Product Management" />
    <ToastNotification :show="toast.show" :message="toast.message" :type="toast.type" @close="toast.show = false" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div class="min-w-0">
                            <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent truncate">Product Management</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Manage your furniture catalog</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2 sm:mt-0">
                        <button
                            @click="refreshProducts"
                            :disabled="isRefreshing"
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-semibold transition-all flex items-center gap-2 text-sm disabled:opacity-50"
                            title="Refresh stock data"
                        >
                            <svg class="w-4 h-4" :class="{ 'animate-spin': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span class="hidden sm:inline">{{ isRefreshing ? 'Refreshing...' : 'Refresh' }}</span>
                        </button>
                        <button @click="openAddModal" class="px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add Product</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <ProductStats
                :products="products"
                :featured-count="featuredCount"
                :max-featured="maxFeatured"
            />

            <ProductFilters
                :search-query="searchQuery"
                :category-filter="categoryFilter"
                :sort-by="sortBy"
                :categories="categoryOptions"
                :sort-options="sortOptions"
                @update:searchQuery="updateSearchQuery"
                @update:categoryFilter="updateCategoryFilter"
                @update:sortBy="updateSortBy"
            />

            <ProductList
                :products="filteredProducts"
                @preview="previewProduct"
                @edit="openEditModal"
                @delete="confirmDelete"
                @add="openAddModal"
            />

            <AddProductModal
                :show="showAddModal"
                :category-options="categoryOptions"
                @close="showAddModal = false"
                @productAdded="handleProductAdded"
                @toast="showToast"
            />

            <EditProductModal
                :show="showEditModal"
                :product="productToEdit"
                :category-options="categoryOptions"
                @close="showEditModal = false"
                @productUpdated="handleProductUpdated"
                @toast="showToast"
            />

            <DeleteProductModal
                :show="showDeleteModal"
                :product="productToDelete"
                :processing="deleteProcessing"
                @close="showDeleteModal = false"
                @deleteConfirmed="executeDelete"
            />

            <ProductDetailModal
                :show="showPreviewModal"
                :product="productToPreview"
                :is-admin="true"
                @close="showPreviewModal = false"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ToastNotification from '@/Components/ToastNotification.vue';
import ProductStats from '@/Components/ProductStats.vue';
import ProductFilters from '@/Components/ProductFilters.vue';
import ProductList from '@/Components/ProductList.vue';
import AddProductModal from '@/Components/AddProductModal.vue';
import EditProductModal from '@/Components/EditProductModal.vue';
import DeleteProductModal from '@/Components/DeleteProductModal.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';

const props = defineProps({
    products: Array,
    featuredCount: Number,
    maxFeatured: {
        type: Number,
        default: 3
    }
});

const categoryOptions = [
    'Sofas',
    'Chairs',
    'Tables',
    'Beds',
    'Storage',
    'Desks',
];

const page = usePage();
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showPreviewModal = ref(false);
const productToDelete = ref(null);
const productToEdit = ref(null);
const isRefreshing = ref(false);
const productToPreview = ref(null);
const deleteProcessing = ref(false);
const toast = ref({ show: false, message: '', type: 'success' });

const categoryFilter = ref('All');
const searchQuery = ref('');
const sortBy = ref('default');

const sortOptions = [
    { value: 'default', label: 'Sort: Default' },
    { value: 'price-asc', label: 'Price: Low to High' },
    { value: 'price-desc', label: 'Price: High to Low' },
    { value: 'stock-asc', label: 'Stock: Low to High' },
    { value: 'stock-desc', label: 'Stock: High to Low' },
];

let debounceTimer = null;
const applyFilters = () => {
    // This function is now implicitly called by the computed property `filteredProducts`
    // when `searchQuery`, `categoryFilter`, or `sortBy` changes.
    // We keep it here for clarity if direct filter application is needed in the future.
};

const debouncedFilter = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        applyFilters();
    }, 300);
};

const filteredProducts = computed(() => {
    let filtered = props.products;
    if (categoryFilter.value !== 'All') {
        filtered = filtered.filter(p => p.category === categoryFilter.value);
    }
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(p =>
            p.name.toLowerCase().includes(query) ||
            (p.description && p.description.toLowerCase().includes(query))
        );
    }
    if (sortBy.value === 'price-asc') {
        filtered = [...filtered].sort((a, b) => Number(a.price) - Number(b.price));
    } else if (sortBy.value === 'price-desc') {
        filtered = [...filtered].sort((a, b) => Number(b.price) - Number(a.price));
    } else if (sortBy.value === 'stock-asc') {
        filtered = [...filtered].sort((a, b) => a.stock - b.stock);
    } else if (sortBy.value === 'stock-desc') {
        filtered = [...filtered].sort((a, b) => b.stock - a.stock);
    }
    return filtered;
});

watch(() => page.props.flash?.success, (message) => {
    if (message) showToast({ message, type: 'success' });
});

watch(() => page.props.flash?.error, (message) => {
    if (message) showToast({ message, type: 'error' });
});

function showToast({ message, type }) {
    toast.value = { show: true, message, type };
}

function updateSearchQuery(query) {
    searchQuery.value = query;
    debouncedFilter();
}

function updateCategoryFilter(category) {
    categoryFilter.value = category;
    applyFilters();
}

function updateSortBy(sortOption) {
    sortBy.value = sortOption;
    applyFilters();
}

function refreshProducts() {
    isRefreshing.value = true;
    router.reload({
        only: ['products', 'featuredCount'],
        onFinish: () => {
            isRefreshing.value = false;
            showToast({ message: 'Stock data refreshed', type: 'success' });
        },
    });
}

function openAddModal() {
    showAddModal.value = true;
}

function handleProductAdded() {
    showAddModal.value = false;
    router.reload({ only: ['products', 'featuredCount'] });
}

function openEditModal(product) {
    productToEdit.value = product;
    showEditModal.value = true;
}

function handleProductUpdated() {
    showEditModal.value = false;
    router.reload({ only: ['products', 'featuredCount'] });
}

function previewProduct(product) {
    productToPreview.value = product;
    showPreviewModal.value = true;
}

function confirmDelete(product) {
    productToDelete.value = product;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (productToDelete.value) {
        deleteProcessing.value = true;
        router.delete(`/admin/products/${productToDelete.value.id}`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                showToast({ message: 'Product deleted successfully', type: 'success' });
            },
            onError: (errors) => {
                const firstError = Object.values(errors)[0];
                showToast({ message: firstError || 'Failed to delete product', type: 'error' });
            },
            onFinish: () => {
                showDeleteModal.value = false;
                productToDelete.value = null;
                deleteProcessing.value = false;
                router.reload({ only: ['products', 'featuredCount'] });
            }
        });
    }
}
</script>
