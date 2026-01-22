<script setup>
import { ref } from 'vue';

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
        a: 'You can easily track the status of your order directly from your profile page. Simply log in, go to "My Orders", and you will see real-time updates on your delivery status.',
        open: false
    },
    {
        q: 'Do you offer custom furniture design?',
        a: 'Yes! We offer custom furniture design services tailored to your unique style and space requirements. Contact us to discuss your project and get a personalized quote.',
        open: false
    },
]);

const toggleFaq = index => {
    faqs.value[index].open = !faqs.value[index].open;
};
</script>

<template>
    <div class="py-24 bg-gradient-to-br from-zinc-50 to-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-lg text-zinc-600">Quick answers to common questions about Vallera</p>
            </div>

            <div class="space-y-4" data-aos="fade-up" data-aos-delay="200">
                <div
                    v-for="(faq, index) in faqs"
                    :key="index"
                    class="bg-white rounded-xl shadow-md border border-zinc-200 overflow-hidden hover:shadow-lg transition-shadow"
                >
                    <button
                        @click="toggleFaq(index)"
                        class="w-full flex justify-between items-center text-left p-6 font-semibold text-zinc-900 hover:bg-zinc-50 transition-colors group"
                    >
                        <span class="text-lg">{{ faq.q }}</span>
                        <svg
                            :class="{'rotate-180': faq.open}"
                            class="w-6 h-6 text-primary-600 transition-transform duration-300 flex-shrink-0 ml-4 group-hover:scale-110"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div
                        v-show="faq.open"
                        class="px-6 pb-6 text-zinc-600 leading-relaxed border-t border-zinc-100 pt-4"
                    >
                        <p>{{ faq.a }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
