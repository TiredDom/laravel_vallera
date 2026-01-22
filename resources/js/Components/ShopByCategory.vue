<template>
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
