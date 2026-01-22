<template>
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
</template>

<script setup>
import { ShieldCheckIcon, TruckIcon, GlobeAltIcon } from '@heroicons/vue/24/outline';
</script>
