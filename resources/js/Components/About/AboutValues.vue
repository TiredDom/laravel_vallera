<script setup>
import { HeartIcon, GlobeAltIcon, SparklesIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <section class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-4">Our Core Values</h2>
                    <p class="text-lg text-zinc-600">What drives us to create exceptional furniture</p>
                </div>
                <div class="space-y-8">
                    <div class="flex gap-6 p-8 bg-zinc-50 rounded-2xl hover:bg-zinc-100 transition-colors" data-aos="fade-up" data-aos-delay="0">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                                <HeartIcon class="w-8 h-8" />
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-zinc-900 mb-3">Quality Craftsmanship</h3>
                            <p class="text-zinc-600 leading-relaxed">Every piece is meticulously crafted by skilled artisans who take pride in their work. We use only the finest materials and time-tested techniques to ensure durability and beauty.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 p-8 bg-zinc-50 rounded-2xl hover:bg-zinc-100 transition-colors" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                                <GlobeAltIcon class="w-8 h-8" />
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-zinc-900 mb-3">Sustainability</h3>
                            <p class="text-zinc-600 leading-relaxed">We're committed to sustainable practices, from ethically sourced materials to eco-friendly packaging. Our goal is to create furniture that's kind to both your home and the environment.</p>
                        </div>
                    </div>
                    <div class="flex gap-6 p-8 bg-zinc-50 rounded-2xl hover:bg-zinc-100 transition-colors" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center shadow-lg">
                                <SparklesIcon class="w-8 h-8" />
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-zinc-900 mb-3">Timeless Design</h3>
                            <p class="text-zinc-600 leading-relaxed">We believe in creating pieces that transcend trends. Our designs focus on clean lines, natural materials, and functional beauty that will look great for years to come.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
