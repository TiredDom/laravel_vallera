<script setup>
import { ref } from 'vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';

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
    form.reset();
    errors.value = {};
};

const faqs = ref([
    {
        q: 'What is your return policy?',
        a: 'We offer a 30-day return policy on all items. If you are not satisfied with your purchase, you can return it for a full refund or exchange, provided it is in its original condition and packaging.',
        open: true
    },
    {
        q: 'Do you ship internationally?',
        a: 'Currently, we only ship within the Philippines. We are working on expanding our shipping options to more countries in the near future. Please subscribe to our newsletter for updates.',
        open: false
    },
    {
        q: 'How can I track my order?',
        a: 'Once your order has shipped, you will receive an email with a tracking number and a link to the carrier\'s website. You can use this information to track the status of your delivery.',
        open: false
    },
]);

const toggleFaq = index => {
    faqs.value[index].open = !faqs.value[index].open;
};
</script>

<template>
<Head title="Contact Us" />
<MainLayout>
    <div class="py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-16 gap-y-12">
                <div data-aos="fade-right">
                    <h2 class="text-base font-semibold leading-7 text-primary-600">Contact Us</h2>
                    <h1 class="mt-2 text-4xl sm:text-5xl font-bold tracking-tight text-zinc-900">Visit Our Showroom</h1>
                    <p class="mt-6 text-lg text-zinc-600">Experience the quality and comfort of Vallera furniture in person. Our team is ready to assist you in finding the perfect pieces for your home.</p>

                    <div class="mt-10 space-y-8">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-zinc-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-zinc-900">Address</h3>
                                <p class="text-zinc-600">Langgam, San Pedro City, Laguna, Philippines</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                             <div class="flex-shrink-0 w-12 h-12 bg-zinc-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-zinc-900">Opening Hours</h3>
                                <p class="text-zinc-600">Monday - Friday: 9am - 7pm</p>
                                <p class="text-zinc-600">Saturday - Sunday: 10am - 5pm</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 h-96 w-full bg-zinc-200 rounded-xl flex items-center justify-center" data-aos="zoom-in" data-aos-delay="300">
                        <div class="text-zinc-400 font-medium">Map</div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8" data-aos="fade-left" data-aos-delay="200">
                     <h2 class="text-2xl font-bold text-zinc-900 mb-6">Send a Message</h2>
                     <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                         {{ successMessage }}
                     </div>
                     <form @submit.prevent="submit" class="space-y-5">
                         <div>
                            <label for="name" class="font-medium text-zinc-700">Name</label>
                            <input v-model="form.name" type="text" id="name" :class="['mt-1 block w-full bg-zinc-50 border rounded-lg shadow-sm py-3 px-4 focus:ring-primary-500 focus:border-primary-500', errors.name ? 'border-red-500' : 'border-zinc-300']">
                            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
                         </div>
                         <div>
                            <label for="email" class="font-medium text-zinc-700">Email</label>
                            <input v-model="form.email" type="email" id="email" :class="['mt-1 block w-full bg-zinc-50 border rounded-lg shadow-sm py-3 px-4 focus:ring-primary-500 focus:border-primary-500', errors.email ? 'border-red-500' : 'border-zinc-300']">
                            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                         </div>
                         <div>
                            <label for="subject" class="font-medium text-zinc-700">Subject</label>
                            <input v-model="form.subject" type="text" id="subject" :class="['mt-1 block w-full bg-zinc-50 border rounded-lg shadow-sm py-3 px-4 focus:ring-primary-500 focus:border-primary-500', errors.subject ? 'border-red-500' : 'border-zinc-300']">
                            <p v-if="errors.subject" class="text-red-500 text-sm mt-1">{{ errors.subject }}</p>
                         </div>
                         <div>
                            <label for="message" class="font-medium text-zinc-700">Message</label>
                            <textarea v-model="form.message" id="message" rows="4" :class="['mt-1 block w-full bg-zinc-50 border rounded-lg shadow-sm py-3 px-4 focus:ring-primary-500 focus:border-primary-500', errors.message ? 'border-red-500' : 'border-zinc-300']"></textarea>
                            <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
                         </div>
                         <button type="submit" class="w-full bg-primary-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-primary-700 transition-all">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-24 bg-zinc-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
             <h2 class="text-center text-4xl font-bold text-zinc-900" data-aos="fade-up">Frequently Asked Questions</h2>
             <div class="mt-12 space-y-4" data-aos="fade-up" data-aos-delay="200">
                <div v-for="(faq, index) in faqs" :key="index" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <button @click="toggleFaq(index)" class="w-full flex justify-between items-center text-left p-5 font-semibold text-zinc-800">
                        <span>{{ faq.q }}</span>
                        <svg :class="{'rotate-180': faq.open}" class="w-5 h-5 text-zinc-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div v-show="faq.open" class="px-5 pb-5 text-zinc-600">
                        <p>{{ faq.a }}</p>
                    </div>
                </div>
             </div>
        </div>
    </div>
</MainLayout>
</template>
