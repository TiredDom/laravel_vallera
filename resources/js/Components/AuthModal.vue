<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close', 'auth-success']);

const isLogin = ref(true);
const isLoading = ref(false);

const loginForm = useForm({
    email: '',
    password: '',
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const close = () => {
    emit('close');
    loginForm.reset();
    registerForm.reset();
    loginForm.clearErrors();
    registerForm.clearErrors();
};

watch(() => props.show, (newVal) => {
    if (!newVal) {
        loginForm.reset();
        registerForm.reset();
        loginForm.clearErrors();
        registerForm.clearErrors();
    }
});

function sanitizeInput(value) {
    if (!value) return '';
    return value.trim().replace(/\s+/g, ' ');
}

function validateName(name) {
    const sanitized = sanitizeInput(name);
    if (sanitized.length < 2) return 'Name must be at least 2 characters';
    if (sanitized.length > 100) return 'Name must be less than 100 characters';
    if (!/^[a-zA-Z\s\-'.]+$/.test(sanitized)) return 'Name contains invalid characters';
    return null;
}

function validateEmail(email) {
    const sanitized = sanitizeInput(email);
    if (!sanitized) return 'Email is required';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(sanitized)) return 'Please enter a valid email address';
    return null;
}

function validatePassword(password) {
    if (!password) return 'Password is required';
    if (password.length < 8) return 'Password must be at least 8 characters';
    return null;
}

function handleLogin() {
    loginForm.email = sanitizeInput(loginForm.email);

    const emailError = validateEmail(loginForm.email);
    const passwordError = validatePassword(loginForm.password);

    if (emailError || passwordError) {
        if (emailError) loginForm.setError('email', emailError);
        if (passwordError) loginForm.setError('password', passwordError);
        return;
    }

    isLoading.value = true;
    loginForm.post('/login', {
        onSuccess: () => {
            emit('auth-success', 'Signed in successfully!');
            close();
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

function handleRegister() {
    registerForm.name = sanitizeInput(registerForm.name);
    registerForm.email = sanitizeInput(registerForm.email);

    const nameError = validateName(registerForm.name);
    const emailError = validateEmail(registerForm.email);
    const passwordError = validatePassword(registerForm.password);
    const confirmError = registerForm.password !== registerForm.password_confirmation ? 'Passwords do not match' : null;

    if (nameError || emailError || passwordError || confirmError) {
        if (nameError) registerForm.setError('name', nameError);
        if (emailError) registerForm.setError('email', emailError);
        if (passwordError) registerForm.setError('password', passwordError);
        if (confirmError) registerForm.setError('password_confirmation', confirmError);
        return;
    }

    isLoading.value = true;
    registerForm.post('/register', {
        onSuccess: () => {
            emit('auth-success', 'Account created successfully!');
            close();
        },
        onFinish: () => {
            isLoading.value = false;
        },
    });
}

function switchMode() {
    isLogin.value = !isLogin.value;
    loginForm.reset();
    registerForm.reset();
    loginForm.clearErrors();
    registerForm.clearErrors();
}
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

                        <form v-if="isLogin" @submit.prevent="handleLogin" class="mt-8 space-y-5">
                            <div>
                                <label for="login-email" class="sr-only">Email</label>
                                <input v-model="loginForm.email" type="email" id="login-email" placeholder="Email Address" autocomplete="email"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', loginForm.errors.email ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="loginForm.errors.email" class="text-red-500 text-sm mt-1">{{ loginForm.errors.email }}</p>
                            </div>
                            <div>
                                <label for="login-password" class="sr-only">Password</label>
                                <input v-model="loginForm.password" type="password" id="login-password" placeholder="Password" autocomplete="current-password"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', loginForm.errors.password ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="loginForm.errors.password" class="text-red-500 text-sm mt-1">{{ loginForm.errors.password }}</p>
                            </div>

                            <button type="submit" :disabled="isLoading"
                                class="w-full bg-primary-600 text-white font-semibold py-3 rounded-lg shadow-sm hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="isLoading">Signing In...</span>
                                <span v-else>Sign In</span>
                            </button>
                        </form>

                        <form v-else @submit.prevent="handleRegister" class="mt-8 space-y-5">
                            <div>
                                <label for="register-name" class="sr-only">Name</label>
                                <input v-model="registerForm.name" type="text" id="register-name" placeholder="Full Name" autocomplete="name"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', registerForm.errors.name ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="registerForm.errors.name" class="text-red-500 text-sm mt-1">{{ registerForm.errors.name }}</p>
                            </div>
                            <div>
                                <label for="register-email" class="sr-only">Email</label>
                                <input v-model="registerForm.email" type="email" id="register-email" placeholder="Email Address" autocomplete="email"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', registerForm.errors.email ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="registerForm.errors.email" class="text-red-500 text-sm mt-1">{{ registerForm.errors.email }}</p>
                            </div>
                            <div>
                                <label for="register-password" class="sr-only">Password</label>
                                <input v-model="registerForm.password" type="password" id="register-password" placeholder="Password" autocomplete="new-password"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', registerForm.errors.password ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="registerForm.errors.password" class="text-red-500 text-sm mt-1">{{ registerForm.errors.password }}</p>
                            </div>
                            <div>
                                <label for="register-password-confirm" class="sr-only">Confirm Password</label>
                                <input v-model="registerForm.password_confirmation" type="password" id="register-password-confirm" placeholder="Confirm Password" autocomplete="new-password"
                                    :class="['w-full bg-zinc-100 border rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-500 outline-none transition', registerForm.errors.password_confirmation ? 'border-red-500' : 'border-zinc-200']">
                                <p v-if="registerForm.errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ registerForm.errors.password_confirmation }}</p>
                            </div>

                            <button type="submit" :disabled="isLoading"
                                class="w-full bg-primary-600 text-white font-semibold py-3 rounded-lg shadow-sm hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span v-if="isLoading">Creating Account...</span>
                                <span v-else>Create Account</span>
                            </button>
                        </form>

                        <div class="mt-6 text-center">
                            <p class="text-sm text-zinc-500">
                                <span v-if="isLogin">Don't have an account? </span>
                                <span v-else>Already have an account? </span>
                                <button @click="switchMode" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                                    {{ isLogin ? 'Sign Up' : 'Sign In' }}
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>
