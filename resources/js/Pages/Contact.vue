<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';
import ContactHero from '@/Components/Contact/ContactHero.vue';
import ContactInfo from '@/Components/Contact/ContactInfo.vue';
import ContactForm from '@/Components/Contact/ContactForm.vue';
import ContactFAQ from '@/Components/Contact/ContactFAQ.vue';
</script>

<template>
<Head title="Contact Us" />
<MainLayout>
    <ContactHero />

    <div class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            <ContactInfo />
            <ContactForm />
        </div>
    </div>

    <ContactFAQ />
</MainLayout>
</template>
