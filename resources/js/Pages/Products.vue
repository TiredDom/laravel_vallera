<script setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    products: Array,
});

const filters = ['All', 'Chairs', 'Tables', 'Sofas', 'Lighting'];
const activeFilter = ref('All');
</script>

<template>
<Head title="Our Collection" />
<MainLayout>
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
                v-for="(product, index) in products"
                :key="product.id"
                :name="product.name"
                :price="product.price"
                :category="product.category"
                :isNew="product.isNew"
                :delay="index * 100"
            />
        </div>
    </div>
</MainLayout>
</template>
