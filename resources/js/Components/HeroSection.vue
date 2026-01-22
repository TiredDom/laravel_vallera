<template>
    <section class="min-h-[70vh] md:min-h-[80vh] bg-zinc-900 text-white flex items-center overflow-hidden relative py-12 md:py-0">
        <!-- Mobile Background Image (Cross-fade) -->
        <div class="absolute inset-0 z-0 md:hidden">
            <transition-group name="fade">
                <img
                    v-for="(img, index) in images"
                    :key="img"
                    v-show="currentImageIndex === index"
                    :src="img"
                    alt="Background"
                    class="absolute inset-0 w-full h-full object-cover opacity-40"
                />
            </transition-group>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 via-zinc-900/80 to-zinc-900/40"></div>
        </div>

        <!-- Animated Background Blobs (Desktop Only) -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none hidden md:block">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-600/20 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>
            <div class="absolute top-[20%] right-[20%] w-[30%] h-[30%] bg-purple-600/20 rounded-full blur-[100px] animate-blob animation-delay-4000"></div>
        </div>

        <div class="absolute inset-0 bg-[url('/grid.svg')] opacity-20 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 to-transparent z-0 hidden md:block"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div data-aos="fade-right" class="text-center md:text-left">
                    <div class="inline-block bg-primary-600/20 backdrop-blur-sm px-3 md:px-4 py-1.5 md:py-2 rounded-full mb-4 md:mb-6 border border-primary-500/30">
                        <span class="text-primary-400 text-xs md:text-sm font-semibold tracking-wide uppercase">Premium Furniture Collection</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tighter leading-tight">
                        Crafted for<br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-emerald-400">Comfort</span>
                    </h1>
                    <p class="mt-4 md:mt-6 text-base md:text-lg text-zinc-300 max-w-lg leading-relaxed mx-auto md:mx-0">
                        Experience the perfect blend of modern design and timeless craftsmanship, made for the discerning home.
                    </p>
                    <div class="mt-6 md:mt-10 flex flex-col sm:flex-row flex-wrap gap-3 md:gap-4 justify-center md:justify-start">
                        <Link href="/products" class="bg-primary-600 text-white font-semibold px-6 md:px-8 py-3 md:py-4 rounded-lg shadow-lg hover:bg-primary-700 hover:shadow-primary-600/50 hover:-translate-y-1 transition-all duration-300 inline-flex items-center justify-center gap-2 group">
                            Shop Now
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </Link>
                        <Link href="/about" class="bg-white/10 backdrop-blur-sm text-white font-semibold px-6 md:px-8 py-3 md:py-4 rounded-lg border border-white/20 hover:bg-white/20 transition-all duration-300 inline-flex items-center justify-center">
                            Learn More
                        </Link>
                    </div>
                </div>
                <div class="relative h-64 md:h-96 lg:h-[500px] w-full hidden md:block group" data-aos="fade-left" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-600/20 to-primary-800/20 rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                        <transition-group name="fade">
                            <img
                                v-for="(img, index) in images"
                                :key="img"
                                v-show="currentImageIndex === index"
                                :src="img"
                                alt="Premium Furniture Showcase"
                                class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                                @error="$event.target.style.display='none'"
                            />
                        </transition-group>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-primary-600 rounded-2xl opacity-20 blur-3xl animate-pulse-slow"></div>
                    <div class="absolute -top-6 -left-6 w-32 h-32 bg-primary-400 rounded-2xl opacity-20 blur-3xl animate-pulse-slow" style="animation-delay: 1s;"></div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const images = [
    '/images/hero-furniture.jpg',
    '/images/hero-furniture-1.jpg'
];

const currentImageIndex = ref(0);
let intervalId = null;

onMounted(() => {
    intervalId = setInterval(() => {
        currentImageIndex.value = (currentImageIndex.value + 1) % images.length;
    }, 5000); // Switch every 5 seconds
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
});
</script>

<style scoped>
/* Cross-fade Transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 1.5s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Existing Animations */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
@keyframes ken-burns {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}
@keyframes pulse-slow {
    0%, 100% { opacity: 0.2; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.animate-ken-burns {
    animation: ken-burns 20s ease-out infinite alternate;
}
.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}
</style>
