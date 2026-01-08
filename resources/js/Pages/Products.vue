<script setup>
import { ref, computed, watch } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import { Head, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    products: Array,
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const filters = ['All', 'Chairs', 'Tables', 'Sofas', 'Lighting'];
const activeFilter = ref('All');
const isAuthOpen = ref(false);
const toast = ref({ show: false, message: '', type: 'success' });

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

const filteredProducts = computed(() => {
    if (activeFilter.value === 'All') return props.products;
    return props.products.filter(p => p.category === activeFilter.value);
});
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

    <div class="bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-24 text-center" data-aos="fade-up">
                <h1 class="text-5xl font-bold text-zinc-900 tracking-tight">Our Collection</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-zinc-500">
                    Explore our curated selection of fine furniture, designed to bring character and comfort to your home.
                </p>
            </div>

            <div class="flex justify-center flex-wrap gap-2 md:gap-4 mb-16" data-aos="fade-up" data-aos-delay="100">
                <button
                    v-for="filter in filters"
                    :key="filter"
                    @click="activeFilter = filter"
                    :class="[
                        'px-5 py-2 rounded-full text-sm font-semibold transition-all duration-300 ease-in-out',
                        activeFilter === filter
                            ? 'bg-primary-600 text-white shadow-md'
                            : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200'
                    ]"
                >
                    {{ filter }}
                </button>
            </div>

        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-8 my-24">
            <ProductCard
                v-for="(product, index) in filteredProducts"
                :key="product.id"
                :id="product.id"
                :name="product.name"
                :price="product.price"
                :category="product.category"
                :isNew="product.isNew"
                :delay="index * 100"
                @add-to-cart="handleAddToCart"
            />
        </div>
    </div>

    <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
</MainLayout>
</template>
