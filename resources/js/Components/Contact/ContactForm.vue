<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { PhoneIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const errors = ref({});
const successMessage = ref('');

function sanitizeInput(value) {
    if (!value) return '';
    return value.trim().replace(/\s+/g, ' ');
}

function validateForm() {
    errors.value = {};

    const name = sanitizeInput(form.name);
    const email = sanitizeInput(form.email);
    const subject = sanitizeInput(form.subject);
    const message = sanitizeInput(form.message);

    if (!name || name.length < 2) {
        errors.value.name = 'Name must be at least 2 characters';
    } else if (name.length > 100) {
        errors.value.name = 'Name must be less than 100 characters';
    } else if (!/^[a-zA-Z\s\-'\.]+$/.test(name)) {
        errors.value.name = 'Name contains invalid characters';
    }

    if (!email) {
        errors.value.email = 'Email is required';
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errors.value.email = 'Please enter a valid email address';
    }

    if (!subject || subject.length < 3) {
        errors.value.subject = 'Subject must be at least 3 characters';
    } else if (subject.length > 200) {
        errors.value.subject = 'Subject must be less than 200 characters';
    }

    if (!message || message.length < 10) {
        errors.value.message = 'Message must be at least 10 characters';
    } else if (message.length > 2000) {
        errors.value.message = 'Message must be less than 2000 characters';
    }

    return Object.keys(errors.value).length === 0;
}

const submit = () => {
    form.name = sanitizeInput(form.name);
    form.email = sanitizeInput(form.email);
    form.subject = sanitizeInput(form.subject);
    form.message = sanitizeInput(form.message);

    if (!validateForm()) {
        return;
    }

    successMessage.value = 'Thank you for your message! We will get back to you soon.';

    setTimeout(() => {
        form.reset();
        successMessage.value = '';
    }, 5000);

    errors.value = {};
};
</script>

<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <div data-aos="fade-right">
            <div class="sticky top-24">
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 mb-4">Visit Our Showroom</h2>
                <p class="text-lg text-zinc-600 mb-8">
                    Experience the quality and comfort of Vallera furniture in person. Our team is ready to assist you in finding the perfect pieces for your home.
                </p>

                <div class="aspect-video w-full rounded-2xl overflow-hidden shadow-xl border-2 border-zinc-200" data-aos="zoom-in" data-aos-delay="300">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2298.5050931365195!2d121.02918239022121!3d14.334107561258623!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d6e09878739f%3A0x4e7bba7291cae75d!2sPolytechnic%20University%20of%20the%20Philippines%20%E2%80%93%20San%20Pedro%20Campus!5e0!3m2!1sen!2sph!4v1768968988777!5m2!1sen!2sph"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                         referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full"
                    ></iframe>
                </div>

                <div class="mt-8 p-6 bg-zinc-50 rounded-xl border border-zinc-200">
                    <h3 class="font-bold text-zinc-900 mb-3 flex items-center gap-2">
                        <PhoneIcon class="w-5 h-5 text-primary-600" />
                        Call Us
                    </h3>
                    <p class="text-2xl font-bold text-primary-600">+63 912 345 6789</p>
                    <p class="text-sm text-zinc-600 mt-1">Available during business hours</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl p-8 lg:p-10 border border-zinc-100" data-aos="fade-left" data-aos-delay="200">
            <h2 class="text-3xl font-bold text-zinc-900 mb-2">Send Us a Message</h2>
            <p class="text-zinc-600 mb-8">Fill out the form below and we'll get back to you as soon as possible.</p>

            <div v-if="successMessage" class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-semibold">Message Sent!</p>
                    <p class="text-sm">{{ successMessage }}</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-zinc-700 mb-2">Full Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        id="name"
                        placeholder="Juan Dela Cruz"
                        :class="['block w-full bg-zinc-50 border rounded-xl shadow-sm py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all', errors.name ? 'border-red-500 bg-red-50' : 'border-zinc-300']"
                    >
                    <p v-if="errors.name" class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ errors.name }}
                    </p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-zinc-700 mb-2">Email Address</label>
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        placeholder="juan@example.com"
                        :class="['block w-full bg-zinc-50 border rounded-xl shadow-sm py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all', errors.email ? 'border-red-500 bg-red-50' : 'border-zinc-300']"
                    >
                    <p v-if="errors.email" class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ errors.email }}
                    </p>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-semibold text-zinc-700 mb-2">Subject</label>
                    <input
                        v-model="form.subject"
                        type="text"
                        id="subject"
                        placeholder="How can we help you?"
                        :class="['block w-full bg-zinc-50 border rounded-xl shadow-sm py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all', errors.subject ? 'border-red-500 bg-red-50' : 'border-zinc-300']"
                    >
                    <p v-if="errors.subject" class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ errors.subject }}
                    </p>
                </div>

                <div>
                    <label for="message" class="block text-sm font-semibold text-zinc-700 mb-2">Message</label>
                    <textarea
                        v-model="form.message"
                        id="message"
                        rows="5"
                        placeholder="Tell us more about your inquiry..."
                        :class="['block w-full bg-zinc-50 border rounded-xl shadow-sm py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none', errors.message ? 'border-red-500 bg-red-50' : 'border-zinc-300']"
                    ></textarea>
                    <p v-if="errors.message" class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ errors.message }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-primary-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-primary-700 hover:shadow-xl transition-all hover:scale-[1.02] flex items-center justify-center gap-2"
                >
                    <EnvelopeIcon class="w-5 h-5" />
                    Send Message
                </button>
            </form>
        </div>
    </div>
</template>
