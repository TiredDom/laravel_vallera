<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const cartItems = [
    { id: 1, name: 'Luxe Comfort Sofa', price: 74999, quantity: 1, category: 'Sofas' },
    { id: 2, name: 'Minimalist Oak Desk', price: 39999, quantity: 1, category: 'Tables' },
    { id: 3, name: 'Velvet Dream Armchair', price: 29999, quantity: 2, category: 'Chairs' },
];

const suggestedItems = [
    { id: 5, name: 'Aether Minimalist Lamp', price: 7999, category: 'Lighting', isNew: true },
    { id: 6, name: 'Granite-Top Coffee Table', price: 34999, category: 'Tables', isNew: false },
    { id: 7, name: 'Emerald Velvet Accent Chair', price: 27999, category: 'Chairs', isNew: false },
    { id: 4, name: 'Industrial Metal Bookshelf', price: 44999, category: 'Storage', isNew: true },
];

const subtotal = computed(() => {
    return cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
});

const shipping = computed(() => 500);

const total = computed(() => subtotal.value + shipping.value);

</script>

<template>
    <Head title="Your Cart" />
    <MainLayout>
        <div class="bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 tracking-tight text-center mb-12">Your Shopping Cart</h1>

                <div v-if="!cartItems.length" class="text-center py-24">
                    <p class="text-lg text-zinc-600">Your cart is empty.</p>
                    <Link href="/products" class="mt-4 inline-block text-primary-600 hover:text-primary-800 font-semibold">
                        Continue Shopping
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8 xl:gap-12 items-start">
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-zinc-200">
                        <ul role="list" class="divide-y divide-zinc-200">
                            <li v-for="item in cartItems" :key="item.id" class="flex py-6 px-4 sm:px-6">
                                <div class="flex-shrink-0 w-24 h-24 bg-zinc-100 rounded-md"></div>
                                <div class="ml-4 flex-1 flex flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-zinc-900">
                                            <h3>{{ item.name }}</h3>
                                            <p class="ml-4">₱{{ (item.price * item.quantity).toLocaleString() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-end justify-between text-sm">
                                        <p class="text-zinc-500">Qty {{ item.quantity }}</p>
                                        <div class="flex">
                                            <button type="button" class="font-medium text-primary-600 hover:text-primary-500">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-6">
                            <h2 class="text-lg font-medium text-zinc-900">Order summary</h2>
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center justify-between"><p class="text-sm text-zinc-600">Subtotal</p><p class="text-sm font-medium text-zinc-900">₱{{ subtotal.toLocaleString() }}</p></div>
                                <div class="flex items-center justify-between"><p class="text-sm text-zinc-600">Shipping</p><p class="text-sm font-medium text-zinc-900">₱{{ shipping.toLocaleString() }}</p></div>
                                <div class="border-t border-zinc-200 pt-4 flex items-center justify-between"><p class="text-base font-medium text-zinc-900">Order total</p><p class="text-base font-medium text-zinc-900">₱{{ total.toLocaleString() }}</p></div>
                            </div>
                            <div class="mt-6">
                                <button class="w-full bg-primary-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-primary-700">Checkout</button>
                            </div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 text-sm text-zinc-500">
                            <div class="flex items-center gap-3"><svg class="w-5 h-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Zm0 13.036h.008v.008h-.008v-.008Z" /></svg><span>Secure Checkout</span></div>
                            <div class="flex items-center gap-3"><svg class="w-5 h-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5h10.5m-10.5-4.5v-3.375c0-.621.504-1.125 1.125-1.125h14.25c.621 0 1.125.504 1.125 1.125v3.375m-17.25-4.5c0-1.657 1.343-3 3-3h11.25c1.657 0 3 1.343 3 3v1.5M3.75 9h16.5" /></svg><span>Fast Shipping</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <h2 class="text-2xl font-bold text-zinc-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-8">
                <ProductCard
                    v-for="item in suggestedItems"
                    :key="item.id"
                    :name="item.name"
                    :price="item.price"
                    :category="item.category"
                    :isNew="item.isNew"
                />
            </div>
        </div>
    </MainLayout>
</template>