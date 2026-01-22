<script setup>
import { SparklesIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                    <SparklesIcon class="w-4 h-4" />
                    <span class="text-sm font-medium">Proudly Filipino Made</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 leading-tight">
                    Design That Inspires<br/>
                    <span class="text-primary-200">Life & Living</span>
                </h1>
                <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                    Founded on the belief that furniture should be more than just functional, Vallera creates beautiful, high-quality pieces cherished for generations.
                </p>
            </div>
        </div>
    </div>
</template>
