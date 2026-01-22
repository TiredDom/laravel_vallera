<template>
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-4" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-primary-100 px-3 md:px-4 py-1.5 md:py-2 rounded-full mb-3 md:mb-4">
                    <svg class="w-4 md:w-5 h-4 md:h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-primary-700 text-xs md:text-sm font-semibold">Customer Favorites</span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-zinc-900">Our Best Sellers</h2>
                <p class="mt-3 md:mt-4 text-base md:text-lg text-zinc-600 max-w-2xl mx-auto">Discover the pieces our customers can't stop raving about.</p>
            </div>
            <div v-if="featuredProducts && featuredProducts.length > 0" class="mt-12 md:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <ProductCard
                    v-for="(product, index) in featuredProducts"
                    :key="product.id"
                    :id="product.id"
                    :name="product.name"
                    :price="product.price"
                    :stock="product.stock"
                    :category="product.category_name || 'Featured'"
                    :image="product.image_url"
                    :delay="index * 100"
                    @add-to-cart="$emit('addToCart', product)"
                    @click="$emit('productClick', product)"
                />
            </div>
            <div v-else class="mt-16 text-center py-12 bg-zinc-50 rounded-2xl">
                <svg class="mx-auto w-16 h-16 text-zinc-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-zinc-600 font-medium">No featured products yet</p>
                <p class="text-zinc-500 text-sm mt-2">Check back soon for our best sellers!</p>
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <Link href="/products" class="inline-flex items-center gap-2 px-8 py-4 bg-zinc-900 text-white rounded-lg font-semibold hover:bg-zinc-800 transition-all hover:scale-105 shadow-lg">
                    View All Products
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import ProductCard from '@/Components/ProductCard.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    featuredProducts: {
        type: Array,
        default: () => []
    }
});

defineEmits(['addToCart', 'productClick']);
</script>
