<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import AboutHero from '@/Components/About/AboutHero.vue';
import AboutStory from '@/Components/About/AboutStory.vue';
import AboutStats from '@/Components/About/AboutStats.vue';
import AboutValues from '@/Components/About/AboutValues.vue';
import AboutTeam from '@/Components/About/AboutTeam.vue';
import AboutCTA from '@/Components/About/AboutCTA.vue';
</script>

<template>
<Head title="About Vallera" />
<MainLayout>
    <AboutHero />
    <AboutStory />
    <AboutStats />
    <AboutValues />
    <AboutTeam />
    <AboutCTA />
</MainLayout>
</template>
