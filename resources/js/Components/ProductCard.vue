<template>
    <div
        @click="handleViewDetails"
        class="bg-white rounded-xl shadow-sm hover:shadow-2xl transition-all duration-300 group flex flex-col overflow-hidden cursor-pointer border border-transparent hover:border-primary-200"
        :class="{ 'opacity-75': stock === 0 }"
        data-aos="fade-up"
        :data-aos-delay="delay"
        @mouseleave="handleMouseLeave"
    >
        <div class="relative h-64 w-full bg-gradient-to-br from-slate-50 to-slate-100 overflow-hidden">
            <img
                v-if="image"
                :src="image"
                :alt="name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                :class="{ 'grayscale': stock === 0 }"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div v-if="stock === 0" class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center">
                <div class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white text-lg font-bold rounded-full shadow-2xl">
                    SOLD OUT
                </div>
            </div>
            <div v-else-if="isNew" class="absolute top-3 left-3 px-3 py-1.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-xs font-bold rounded-full shadow-lg backdrop-blur-sm">
                NEW
            </div>
            <div v-if="stock > 0" class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <button
                v-if="showAddToCart && stock > 0"
                @click.stop="handleAddToCart"
                class="absolute bottom-4 right-4 p-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-full shadow-lg hover:from-emerald-700 hover:to-teal-700 transition-all opacity-0 group-hover:opacity-100 focus:opacity-100"
                aria-label="Add to Cart"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
        <div class="flex-1 flex flex-col p-6">
            <h3 class="font-bold text-base text-slate-900 line-clamp-2 mb-2 group-hover:text-primary-600 transition-colors">
                {{ name }}
            </h3>
            <div class="mt-auto flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-slate-900">₱{{ Number(price).toLocaleString() }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['add-to-cart', 'view-details']);

const props = defineProps({
    name: String,
    price: [Number, String],
    category: String,
    stock: {
        type: Number,
        default: 0
    },
    image: {
        type: String,
        default: null
    },
    delay: {
        type: [Number, String],
        default: 0
    },
    isNew: {
        type: Boolean,
        default: false
    },
    id: {
        type: [Number, String],
        required: true
    }
});

const showAddToCart = ref(true);

function handleAddToCart(event) {
    event.stopPropagation();
    if (props.stock <= 0) return;

    emit('add-to-cart', {
        id: props.id,
        name: props.name,
        price: props.price,
        stock: props.stock,
        category: props.category
    });
}

function handleViewDetails() {
    emit('view-details');
}

function handleMouseLeave() {
    showAddToCart.value = true;
}
</script>
