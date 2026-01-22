<template>
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
