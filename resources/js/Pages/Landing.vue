<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import { Link, Head } from '@inertiajs/vue3';
import { ShieldCheckIcon, TruckIcon, GlobeAltIcon } from '@heroicons/vue/24/outline';

const bestSellers = [
    { id: 1, name: 'Luxe Comfort Sofa', price: 74999, category: 'Sofas', isNew: true },
    { id: 2, name: 'Minimalist Oak Desk', price: 39999, category: 'Tables', isNew: false },
    { id: 3, name: 'Velvet Dream Armchair', price: 29999, category: 'Chairs', isNew: false },
];
</script>

<template>
<Head title="Welcome to Vallera" />
<MainLayout>
    <section class="min-h-[80vh] bg-zinc-900 text-white flex items-center overflow-hidden">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h1 class="text-5xl md:text-7xl font-bold tracking-tighter">
                        Crafted for Comfort.
                    </h1>
                    <p class="mt-4 text-lg text-zinc-400 max-w-lg">
                        Experience the perfect blend of modern design and timeless craftsmanship, made for the discerning home.
                    </p>
                    <div class="mt-8">
                        <Link href="/products" class="bg-primary-600 text-white font-semibold px-8 py-4 rounded-lg shadow-md hover:bg-primary-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 inline-block">
                            Shop Now
                        </Link>
                    </div>
                </div>
                <div class="h-96 w-full bg-zinc-800 rounded-xl flex items-center justify-center" data-aos="fade-left" data-aos-delay="200">
                    <div class="text-zinc-600 font-medium">Image</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-zinc-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-zinc-900">Why Choose Vallera?</h2>
                <p class="mt-3 text-lg text-zinc-500 max-w-2xl mx-auto">We provide premium quality, exceptional service, and a commitment to sustainability.</p>
            </div>
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm text-center" data-aos="fade-up" data-aos-delay="100">
                     <div class="w-16 h-16 bg-emerald-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                        <ShieldCheckIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-semibold text-xl text-zinc-900 mt-6">5-Year Warranty</h3>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center" data-aos="fade-up" data-aos-delay="200">
                     <div class="w-16 h-16 bg-emerald-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                        <GlobeAltIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-semibold text-xl text-zinc-900 mt-6">Sustainable Materials</h3>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-sm text-center" data-aos="fade-up" data-aos-delay="300">
                     <div class="w-16 h-16 bg-emerald-100 text-primary-600 rounded-full flex items-center justify-center mx-auto">
                        <TruckIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-semibold text-xl text-zinc-900 mt-6">Free Shipping</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-zinc-900">Shop by Category</h2>
                <p class="mt-3 text-lg text-zinc-500">Explore our curated collections.</p>
            </div>
            <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <Link href="/products" class="group block" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative aspect-[4/5] w-full bg-zinc-200 rounded-xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent group-hover:from-black/70 transition-all"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white">Living Room</h3>
                        </div>
                    </div>
                </Link>
                 <Link href="/products" class="group block" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative aspect-[4/5] w-full bg-zinc-200 rounded-xl overflow-hidden">
                         <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent group-hover:from-black/70 transition-all"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white">Dining</h3>
                        </div>
                    </div>
                </Link>
                 <Link href="/products" class="group block" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative aspect-[4/5] w-full bg-zinc-200 rounded-xl overflow-hidden">
                         <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent group-hover:from-black/70 transition-all"></div>
                        <div class="absolute bottom-6 left-6">
                            <h3 class="text-2xl font-bold text-white">Bedroom</h3>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-zinc-900">Our Best Sellers</h2>
                <p class="mt-3 text-lg text-zinc-500">A collection of our most loved pieces.</p>
            </div>
            <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
                <ProductCard
                    v-for="(product, index) in bestSellers"
                    :key="product.id"
                    :name="product.name"
                    :price="product.price"
                    :category="product.category"
                    :isNew="product.isNew"
                    :delay="index * 100"
                />
            </div>
        </div>
    </section>

    <section class="py-24 bg-zinc-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-4xl font-bold">What Our Customers Say</h2>
            </div>
            <div class="mt-16 max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-zinc-800 p-8 rounded-xl" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-zinc-300">"The best chair I've ever owned. The quality is unreal and it's so comfortable. Worth every penny! Highly recommend Vallera to everyone."</p>
                    <div class="flex items-center mt-6">
                        <div class="font-semibold text-white">Alex Johnson</div>
                    </div>
                </div>
                <div class="bg-zinc-800 p-8 rounded-xl" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-zinc-300">"Absolutely transformed my living room. The design is sleek and modern, and the delivery was incredibly fast. 5-star experience."</p>
                     <div class="flex items-center mt-6">
                        <div class="font-semibold text-white">Maria Garcia</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</MainLayout>
</template>
