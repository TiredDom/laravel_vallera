<script setup>
import { FunnelIcon } from '@heroicons/vue/24/outline';
import ProductCard from '@/Components/ProductCard.vue';

defineProps({
    filteredProducts: Array
});

defineEmits(['resetFilter', 'addToCart', 'productClick']);
</script>

<template>
    <section class="bg-zinc-50 py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div v-if="filteredProducts.length === 0" class="text-center py-24" data-aos="fade-up">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-zinc-200 rounded-full mb-6">
                    <FunnelIcon class="w-10 h-10 text-zinc-400" />
                </div>
                <h3 class="text-2xl font-bold text-zinc-900 mb-2">No products found</h3>
                <p class="text-zinc-600 mb-6">Try adjusting your filters</p>
                <button
                    @click="$emit('resetFilter')"
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors"
                >
                    View All Products
                </button>
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                <ProductCard
                    v-for="(product, index) in filteredProducts"
                    :key="product.id"
                    :id="product.id"
                    :name="product.name"
                    :price="product.price"
                    :stock="product.stock"
                    :category="product.category"
                    :image="product.image_url"
                    :delay="index * 100"
                    @add-to-cart="$emit('addToCart', product)"
                    @click="$emit('productClick', product)"
                />
            </div>
        </div>
    </section>
</template>
