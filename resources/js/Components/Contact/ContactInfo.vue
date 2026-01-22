<script setup>
import { MapPinIcon, ClockIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl border border-blue-200 hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="0">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <MapPinIcon class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-bold text-zinc-900 mb-2">Visit Us</h3>
            <p class="text-zinc-700 font-medium mb-1">PUP San Pedro Campus</p>
            <p class="text-zinc-600">San Pedro City, Laguna</p>
        </div>
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-8 rounded-2xl border border-emerald-200 hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="100">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <ClockIcon class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-bold text-zinc-900 mb-2">Opening Hours</h3>
            <p class="text-zinc-700 font-medium">Mon - Fri: 9am - 7pm</p>
            <p class="text-zinc-700 font-medium">Sat - Sun: 10am - 5pm</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl border border-purple-200 hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="200">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                <EnvelopeIcon class="w-7 h-7" />
            </div>
            <h3 class="text-xl font-bold text-zinc-900 mb-2">Email Us</h3>
            <p class="text-zinc-700 font-medium">info@vallera.com</p>
            <p class="text-zinc-600">We reply within 24 hours</p>
        </div>
    </div>
</template>
