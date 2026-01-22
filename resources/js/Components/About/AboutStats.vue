<script setup>
import { ref, onMounted } from 'vue';
import { UsersIcon, TrophyIcon, CheckBadgeIcon } from '@heroicons/vue/24/outline';

const statsSection = ref(null);
const customersCount = ref(0);
const designsCount = ref(0);
const ecoCount = ref(0);
const hasAnimated = ref(false);

const animateValue = (refValue, end, duration) => {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        refValue.value = Math.floor(progress * end);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
};

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !hasAnimated.value) {
                hasAnimated.value = true;
                animateValue(customersCount, 10000, 2000);
                animateValue(designsCount, 500, 1500);
                animateValue(ecoCount, 100, 1000);
            }
        });
    }, { threshold: 0.5 });

    if (statsSection.value) {
        observer.observe(statsSection.value);
    }
});
</script>

<template>
    <section ref="statsSection" class="py-24 bg-gradient-to-br from-zinc-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-4">By The Numbers</h2>
                <p class="text-lg text-zinc-600">Our commitment to excellence in every piece</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="0">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl mb-4 transform hover:scale-110 transition-transform duration-300">
                        <UsersIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-blue-600 mb-2">{{ customersCount.toLocaleString() }}+</h2>
                    <p class="text-lg text-zinc-600 font-medium">Happy Customers</p>
                    <p class="text-sm text-zinc-500 mt-2">Nationwide</p>
                </div>
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl mb-4 transform hover:scale-110 transition-transform duration-300">
                        <TrophyIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-emerald-600 mb-2">{{ designsCount }}+</h2>
                    <p class="text-lg text-zinc-600 font-medium">Unique Designs</p>
                    <p class="text-sm text-zinc-500 mt-2">And Growing</p>
                </div>
                <div class="text-center bg-white p-8 rounded-2xl shadow-lg border border-zinc-100 hover:shadow-xl transition-shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl mb-4 transform hover:scale-110 transition-transform duration-300">
                        <CheckBadgeIcon class="w-8 h-8" />
                    </div>
                    <h2 class="text-5xl font-bold text-purple-600 mb-2">{{ ecoCount }}%</h2>
                    <p class="text-lg text-zinc-600 font-medium">Recyclable Packaging</p>
                    <p class="text-sm text-zinc-500 mt-2">Eco-Friendly</p>
                </div>
            </div>
        </div>
    </section>
</template>
