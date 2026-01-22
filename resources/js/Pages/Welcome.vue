<script setup>
import { Head } from '@inertiajs/vue3';
import WelcomeHeader from '@/Components/Welcome/WelcomeHeader.vue';
import WelcomeHero from '@/Components/Welcome/WelcomeHero.vue';
import WelcomeLinks from '@/Components/Welcome/WelcomeLinks.vue';
import WelcomeFooter from '@/Components/Welcome/WelcomeFooter.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px]"
            src="https://laravel.com/assets/img/welcome/background.svg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <WelcomeHeader :can-login="canLogin" :can-register="canRegister" />

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <WelcomeHero />
                        <WelcomeLinks />
                    </div>
                </main>

                <WelcomeFooter :laravel-version="laravelVersion" :php-version="phpVersion" />
            </div>
        </div>
    </div>
</template>
