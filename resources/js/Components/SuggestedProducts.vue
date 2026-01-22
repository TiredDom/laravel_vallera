<template>
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
                    :stock="item.stock"
                    :image="item.image_url"
                    :delay="index * 100"
                    @add-to-cart="$emit('addToCart', item)"
                    @click="$emit('productClick', item)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import ProductCard from '@/Components/ProductCard.vue';

defineProps({
    suggestedProducts: {
        type: Array,
        default: () => []
    }
});

defineEmits(['addToCart', 'productClick']);
</script>
