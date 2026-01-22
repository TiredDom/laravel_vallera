<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="py-24 bg-primary-600 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">
                    Ready to Transform Your Space?
                </h2>
                <p class="text-xl text-primary-100 mb-10 leading-relaxed">
                    Discover furniture that's crafted with care and designed to inspire. Browse our collection or get in touch for a custom design consultation.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <Link href="/products" class="px-8 py-4 bg-white text-primary-600 rounded-lg font-bold hover:bg-primary-50 transition-all hover:scale-105 shadow-xl">
                        Shop Now
                    </Link>
                    <Link href="/contact" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-lg font-bold border-2 border-white/30 hover:bg-white/20 transition-all">
                        Contact Us
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
