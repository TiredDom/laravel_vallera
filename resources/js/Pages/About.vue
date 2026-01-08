<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
<Head title="About Vallera" />
<MainLayout>
    <div class="overflow-x-hidden">
        <section class="py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12 items-center">
                    <div data-aos="fade-right">
                        <h2 class="text-base font-semibold leading-7 text-primary-600">About Vallera</h2>
                        <h1 class="mt-2 text-4xl sm:text-5xl font-bold tracking-tight text-zinc-900">
                            Design That Inspires Life.
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-zinc-600">
                            Proudly Filipino made furniture. Founded on the belief that furniture should be more than just functional, Vallera was born from a passion for design, craftsmanship, and the art of living well. Our journey began with a simple idea: to create beautiful, high-quality pieces that would be cherished for generations.
                        </p>
                    </div>
                    <div class="h-96 lg:h-[32rem] w-full bg-zinc-200 rounded-xl flex items-center justify-center" data-aos="fade-left" data-aos-delay="200">
                        <div class="text-zinc-400 font-medium">Image</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-zinc-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div data-aos="fade-up" data-aos-delay="0">
                        <h2 class="text-5xl font-bold text-primary-600">10k+</h2>
                        <p class="mt-2 text-lg text-zinc-600">Happy Customers</p>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="100">
                        <h2 class="text-5xl font-bold text-primary-600">500+</h2>
                        <p class="mt-2 text-lg text-zinc-600">Unique Designs</p>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="200">
                        <h2 class="text-5xl font-bold text-primary-600">100%</h2>
                        <p class="mt-2 text-lg text-zinc-600">Recyclable Packaging</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                    <h2 class="text-base font-semibold leading-7 text-primary-600">Our Philosophy</h2>
                    <h1 class="mt-2 text-4xl font-bold tracking-tight text-zinc-900">
                        Timeless Design, Modern Craft
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-zinc-600">
                        We believe that good design is about more than just aesthetics; it's about creating pieces that are a joy to use and live with every day. Our philosophy is rooted in the principles of minimalism, functionality, and durability. We focus on clean lines, natural materials, and a neutral color palette to create furniture that feels both modern and timeless.
                    </p>
                </div>
            </div>
        </section>

        <section class="py-24 bg-zinc-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="text-4xl font-bold text-zinc-900">Meet The Team</h2>
                    <p class="mt-3 text-lg text-zinc-500 max-w-2xl mx-auto">The developers behind Vallera.</p>
                </div>
                <div class="mt-16 flex justify-center gap-16 flex-wrap">
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="h-48 w-48 bg-zinc-200 rounded-full mx-auto flex items-center justify-center">
                            <div class="text-zinc-400 font-medium">Image</div>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-zinc-900">Ginelle Bacalando</h3>
                        <p class="text-primary-600">Full Stack Developer</p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="h-48 w-48 bg-zinc-200 rounded-full mx-auto flex items-center justify-center">
                            <div class="text-zinc-400 font-medium">Image</div>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-zinc-900">John Dominic Gonzales</h3>
                        <p class="text-primary-600">Lead Developer</p>
                    </div>
                    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="h-48 w-48 bg-zinc-200 rounded-full mx-auto flex items-center justify-center">
                            <div class="text-zinc-400 font-medium">Image</div>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-zinc-900">Franz Jethro Principe</h3>
                        <p class="text-primary-600">Full Stack Developer</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</MainLayout>
</template>
