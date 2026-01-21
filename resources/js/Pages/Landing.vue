<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import ProductCard from '@/Components/ProductCard.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';
import AuthModal from '@/Components/AuthModal.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import { Link, Head, usePage, router } from '@inertiajs/vue3';
import { ShieldCheckIcon, TruckIcon, GlobeAltIcon } from '@heroicons/vue/24/outline';
import { computed, ref } from 'vue';

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => []
    }
});

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const isAuthOpen = ref(false);
const isProductDetailOpen = ref(false);
const selectedProduct = ref(null);
const toast = ref({ show: false, message: '', type: 'success' });

function showToast(message, type = 'success') {
    toast.value = { show: true, message, type };
}

function hideToast() {
    toast.value.show = false;
}

function handleAuthSuccess(message) {
    showToast(message, 'success');
}

function handleProductClick(product) {
    selectedProduct.value = product;
    isProductDetailOpen.value = true;
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
</script>

<template>
<Head title="Welcome to Vallera" />
<MainLayout>
    <ToastNotification
        :show="toast.show"
        :message="toast.message"
        :type="toast.type"
        @close="hideToast"
    />

    <section class="min-h-[70vh] md:min-h-[80vh] bg-zinc-900 text-white flex items-center overflow-hidden relative py-12 md:py-0">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 to-transparent"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div data-aos="fade-right">
                    <div class="inline-block bg-primary-600/20 backdrop-blur-sm px-3 md:px-4 py-1.5 md:py-2 rounded-full mb-4 md:mb-6">
                        <span class="text-primary-400 text-xs md:text-sm font-semibold">Premium Furniture Collection</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tighter leading-tight">
                        Crafted for<br/>
                        <span class="text-primary-400">Comfort</span>
                    </h1>
                    <p class="mt-4 md:mt-6 text-base md:text-lg text-zinc-300 max-w-lg leading-relaxed">
                        Experience the perfect blend of modern design and timeless craftsmanship, made for the discerning home.
                    </p>
                    <div class="mt-6 md:mt-10 flex flex-col sm:flex-row flex-wrap gap-3 md:gap-4">
                        <Link href="/products" class="bg-primary-600 text-white font-semibold px-6 md:px-8 py-3 md:py-4 rounded-lg shadow-lg hover:bg-primary-700 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2">
                            Shop Now
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                        <Link href="/about" class="bg-white/10 backdrop-blur-sm text-white font-semibold px-6 md:px-8 py-3 md:py-4 rounded-lg border border-white/20 hover:bg-white/20 transition-all duration-300 inline-flex items-center justify-center">
                            Learn More
                        </Link>
                    </div>
                </div>
                <div class="relative h-64 md:h-96 lg:h-[500px] w-full hidden sm:block" data-aos="fade-left" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-primary-800/20 rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <img
                            src="/images/hero-furniture.jpg"
                            alt="Premium Furniture Showcase"
                            class="w-full h-full object-cover"
                            @error="$event.target.style.display='none'"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-primary-600 rounded-2xl opacity-20 blur-3xl"></div>
                    <div class="absolute -top-6 -left-6 w-32 h-32 bg-primary-400 rounded-2xl opacity-20 blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-zinc-900">Why Choose Vallera?</h2>
                <p class="mt-3 md:mt-4 text-base md:text-lg text-zinc-600">We provide premium quality, exceptional service, and a commitment to sustainability.</p>
            </div>
            <div class="mt-12 md:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="group bg-zinc-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-blue-100" data-aos="fade-up" data-aos-delay="100">
                     <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <ShieldCheckIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-bold text-xl text-zinc-900 mt-6 mb-3">5-Year Warranty</h3>
                    <p class="text-zinc-600">Every piece comes with comprehensive warranty coverage for your peace of mind.</p>
                </div>
                <div class="group bg-zinc-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-emerald-100" data-aos="fade-up" data-aos-delay="200">
                     <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <GlobeAltIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-bold text-xl text-zinc-900 mt-6 mb-3">Sustainable Materials</h3>
                    <p class="text-zinc-600">Ethically sourced materials that are kind to the environment and your home.</p>
                </div>
                <div class="group bg-zinc-50 p-8 rounded-2xl hover:bg-white hover:shadow-xl transition-all duration-300 border border-transparent hover:border-purple-100" data-aos="fade-up" data-aos-delay="300">
                     <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <TruckIcon class="w-8 h-8" />
                    </div>
                    <h3 class="font-bold text-xl text-zinc-900 mt-6 mb-3">Free Shipping</h3>
                    <p class="text-zinc-600">Complimentary delivery on all orders with white-glove service available.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-zinc-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-zinc-900">Shop by Category</h2>
                <p class="mt-3 md:mt-4 text-base md:text-lg text-zinc-600">Explore our curated collections designed for every room.</p>
            </div>
            <div class="mt-12 md:mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <Link href="/products?category=Sofas" class="group block relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative aspect-[4/5] w-full bg-gradient-to-br from-zinc-300 to-zinc-400 overflow-hidden">
                        <img
                            src="/images/category-living-room.jpg"
                            alt="Sofas"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            @error="$event.target.style.display='none'"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent group-hover:from-black/90 transition-all duration-500"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8">
                            <h3 class="text-3xl font-bold text-white mb-2 transform group-hover:translate-y-[-8px] transition-transform duration-300">Sofas</h3>
                            <p class="text-white/80 text-sm mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">Sectionals, couches & loveseats</p>
                            <div class="flex items-center text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span>Shop Now</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </Link>
                 <Link href="/products?category=Tables" class="group block relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative aspect-[4/5] w-full bg-gradient-to-br from-amber-300 to-amber-500 overflow-hidden">
                        <img
                            src="/images/category-dining.jpg"
                            alt="Tables"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            @error="$event.target.style.display='none'"
                        />
                         <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent group-hover:from-black/90 transition-all duration-500"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8">
                            <h3 class="text-3xl font-bold text-white mb-2 transform group-hover:translate-y-[-8px] transition-transform duration-300">Tables</h3>
                            <p class="text-white/80 text-sm mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">Dining, coffee & side tables</p>
                            <div class="flex items-center text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span>Shop Now</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </Link>
                 <Link href="/products?category=Beds" class="group block relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative aspect-[4/5] w-full bg-gradient-to-br from-blue-300 to-blue-500 overflow-hidden">
                        <img
                            src="/images/category-bedroom.jpg"
                            alt="Beds"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            @error="$event.target.style.display='none'"
                        />
                         <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent group-hover:from-black/90 transition-all duration-500"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8">
                            <h3 class="text-3xl font-bold text-white mb-2 transform group-hover:translate-y-[-8px] transition-transform duration-300">Beds</h3>
                            <p class="text-white/80 text-sm mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">Bed frames & foundations</p>
                            <div class="flex items-center text-white text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span>Shop Now</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </section>

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
                    :category="'Featured'"
                    :image="product.image_url"
                    :delay="index * 100"
                    @add-to-cart="handleAddToCart"
                    @click="handleProductClick(product)"
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

    <section class="py-24 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-5"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold">What Our Customers Say</h2>
                <p class="mt-4 text-zinc-400 text-lg">Real experiences from real customers</p>
            </div>
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-zinc-200 text-lg leading-relaxed mb-6">"The best chair I've ever owned. The quality is unreal and it's so comfortable. Worth every penny! Highly recommend Vallera to everyone."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-bold">AJ</div>
                        <div>
                            <div class="font-semibold text-white">Alex Johnson</div>
                            <div class="text-sm text-zinc-400">Verified Buyer</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-8 rounded-2xl hover:bg-white/10 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <p class="text-zinc-200 text-lg leading-relaxed mb-6">"Absolutely transformed my living room. The design is sleek and modern, and the delivery was incredibly fast. 5-star experience."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold">MG</div>
                        <div>
                            <div class="font-semibold text-white">Maria Garcia</div>
                            <div class="text-sm text-zinc-400">Verified Buyer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ProductDetailModal
        v-if="selectedProduct"
        :show="isProductDetailOpen"
        :product="selectedProduct"
        @close="isProductDetailOpen = false"
        @add-to-cart="handleAddToCart"
    />

    <AuthModal :show="isAuthOpen" @close="isAuthOpen = false" @auth-success="handleAuthSuccess" />
</MainLayout>
</template>
