<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <section class="py-20 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Can't Find What You're Looking For?
                </h2>
                <p class="text-zinc-300 text-lg mb-8">
                    We offer custom furniture design services tailored to your unique style and space requirements.
                </p>
                <Link
                    href="/contact"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white text-zinc-900 rounded-lg font-semibold hover:bg-zinc-100 transition-all hover:scale-105 shadow-xl"
                >
                    Get Custom Quote
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>
        </div>
    </section>
</template>
