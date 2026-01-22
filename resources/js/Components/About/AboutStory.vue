<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <div class="inline-block bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        Our Story
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-6 leading-tight">
                        Crafting Excellence Since Day One
                    </h2>
                    <p class="text-lg text-zinc-600 leading-relaxed mb-6">
                        Our journey began with a simple idea: to create beautiful, high-quality furniture that would be cherished for generations. We started in a small workshop with a passion for design, craftsmanship, and the art of living well.
                    </p>
                    <p class="text-lg text-zinc-600 leading-relaxed mb-8">
                        Today, Vallera stands as a testament to Filipino craftsmanship, combining traditional techniques with modern design principles to create pieces that transform houses into homes.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <Link href="/products" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all hover:scale-105">
                            Explore Collection
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                        <Link href="/contact" class="px-6 py-3 border-2 border-zinc-900 text-zinc-900 rounded-lg font-semibold hover:bg-zinc-900 hover:text-white transition-all">
                            Get In Touch
                        </Link>
                    </div>
                </div>
                <div class="relative" data-aos="fade-left" data-aos-delay="200">
                    <div class="aspect-[4/5] w-full bg-gradient-to-br from-zinc-200 to-zinc-300 rounded-2xl overflow-hidden shadow-2xl">
                        <img
                            src="/images/about-workshop.jpg"
                            alt="Vallera Furniture Workshop"
                            class="w-full h-full object-cover"
                            @error="$event.target.style.display='none'; $event.target.parentElement.classList.add('flex', 'items-center', 'justify-center')"
                        />
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-64 h-64 bg-primary-600/20 rounded-2xl blur-3xl -z-10"></div>
                    <div class="absolute -top-6 -left-6 w-64 h-64 bg-primary-400/20 rounded-2xl blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>
</template>
