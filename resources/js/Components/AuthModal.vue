<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const isLogin = ref(true);

const close = () => {
    emit('close');
};
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" @click.self="close" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div v-if="show" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                     <button @click="close" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 transition-colors z-10">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <div class="p-8 sm:p-12">
                        <h2 class="text-3xl font-bold text-center text-zinc-900">{{ isLogin ? 'Welcome Back' : 'Join Vallera' }}</h2>
                        <p class="text-center text-zinc-500 mt-2">{{ isLogin ? 'Sign in to continue' : 'Create an account to get started' }}</p>

                        <form class="mt-8 space-y-5">
                            <div v-if="!isLogin">
                                <label for="name" class="sr-only">Name</label>
                                <input type="text" name="name" id="name" placeholder="Full Name"
                                    class="w-full bg-zinc-100 border-zinc-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition">
                            </div>
                            <div>
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email Address"
                                    class="w-full bg-zinc-100 border-zinc-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition">
                            </div>
                            <div>
                                <label for="password" class="sr-only">Password</label>
                                <input type="password" name="password" id="password" placeholder="Password"
                                    class="w-full bg-zinc-100 border-zinc-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition">
                            </div>

                            <button type="submit"
                                class="w-full bg-primary-600 text-white font-semibold py-3 rounded-lg shadow-sm hover:bg-primary-700 transition-colors">
                                {{ isLogin ? 'Sign In' : 'Create Account' }}
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <button @click="isLogin = !isLogin" class="text-sm text-zinc-500 hover:text-primary-600 transition-colors">
                                {{ isLogin ? "Don't have an account? Sign Up" : "Already have an account? Sign In" }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
