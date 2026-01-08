<script setup>
const emit = defineEmits(['add-to-cart']);

const props = defineProps({
    name: String,
    price: [Number, String],
    category: String,
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

function handleAddToCart() {
    emit('add-to-cart', {
        product_id: props.id,
        name: props.name,
        price: props.price,
        quantity: 1,
        category: props.category
    });
}
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col overflow-hidden"
        data-aos="fade-up"
        :data-aos-delay="delay"
    >
        <div class="h-64 w-full bg-zinc-100 flex items-center justify-center relative">
            <div class="text-zinc-400 font-medium">Image</div>
            <div v-if="isNew" class="absolute top-3 left-3 bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full">NEW</div>
        </div>
        <div class="p-5 flex flex-col justify-between flex-grow">
            <div>
                <p class="text-xs text-zinc-400 uppercase tracking-wider">{{ category }}</p>
                <h3 class="font-bold text-lg text-zinc-900 mt-1 truncate">{{ name }}</h3>
                <p class="text-primary-600 font-bold text-xl mt-2">₱{{ price.toLocaleString() }}</p>
            </div>
            <div class="mt-4">
                 <button class="w-full py-3 bg-primary-600 text-white rounded-lg font-semibold transition-all duration-300 opacity-100 md:opacity-0 md:group-hover:opacity-100 md:translate-y-2 md:group-hover:translate-y-0 hover:bg-primary-700" @click="handleAddToCart">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</template>
