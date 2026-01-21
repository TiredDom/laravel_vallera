<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { SparklesIcon, HeartIcon, GlobeAltIcon, UsersIcon, CheckBadgeIcon, TrophyIcon } from '@heroicons/vue/24/outline';
</script>

<template>
<Head title="About Vallera" />
<MainLayout>
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

    <section class="py-24 bg-gradient-to-br from-zinc-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-4">By The Numbers</h2>
                <p class="text-lg text-zinc-600">Our commitment to excellence in every piece</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="0">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl mb-4">
                        <UsersIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-blue-600 mb-2">10,000+</h2>
                    <p class="text-lg text-zinc-600 font-medium">Happy Customers</p>
                    <p class="text-sm text-zinc-500 mt-2">Nationwide</p>
                </div>
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl mb-4">
                        <TrophyIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-emerald-600 mb-2">500+</h2>
                    <p class="text-lg text-zinc-600 font-medium">Unique Designs</p>
                    <p class="text-sm text-zinc-500 mt-2">And Growing</p>
                </div>
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl mb-4">
                        <CheckBadgeIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-purple-600 mb-2">100%</h2>
                    <p class="text-lg text-zinc-600 font-medium">Recyclable Packaging</p>
                    <p class="text-sm text-zinc-500 mt-2">Eco-Friendly</p>
                </div>
            </div>
        </div>
    </section>

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

    <section class="py-24 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-5"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Meet The Team</h2>
                <p class="text-zinc-300 text-lg">The talented developers behind Vallera</p>
            </div>
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-blue-300/30">
                            <img
                                src="/images/developers/Bacalando.jpg"
                                alt="Ginelle Bacalando"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>GB</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-blue-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Ginelle Bacalando</h3>
                    <p class="text-blue-400 font-semibold mb-3">Full Stack Developer</p>
                    <p class="text-zinc-400 text-sm">Crafting seamless user experiences</p>
                </div>
                <div class="group text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-emerald-300/30">
                            <img
                                src="/images/developers/Gonzales.jpg"
                                alt="John Dominic Gonzales"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>JG</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">John Dominic Gonzales</h3>
                    <p class="text-emerald-400 font-semibold mb-3">Lead Developer</p>
                    <p class="text-zinc-400 text-sm">Building robust architectures</p>
                </div>
                <div class="group text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative mb-6 inline-block">
                        <div class="h-48 w-48 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl mx-auto overflow-hidden transform group-hover:scale-105 transition-transform duration-300 shadow-2xl border-4 border-purple-300/30">
                            <img
                                src="/images/developers/Principe.jpg"
                                alt="Franz Jethro Principe"
                                class="w-full h-full object-cover"
                                @error="$event.target.style.display='none'; $event.target.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center text-white font-bold text-4xl\'>FP</div>'"
                            />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-purple-500 rounded-lg opacity-50 blur-xl"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Franz Jethro Principe</h3>
                    <p class="text-purple-400 font-semibold mb-3">Full Stack Developer</p>
                    <p class="text-zinc-400 text-sm">Creating innovative solutions</p>
                </div>
            </div>
        </div>
    </section>

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
</MainLayout>
</template>
