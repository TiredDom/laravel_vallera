<script setup>
import { XMarkIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    title: {
        type: String,
        default: 'Confirm Action'
    },
    message: {
        type: String,
        default: 'Are you sure you want to proceed?'
    },
    confirmText: {
        type: String,
        default: 'Confirm'
    },
    cancelText: {
        type: String,
        default: 'Cancel'
    },
    type: {
        type: String,
        default: 'danger'
    }
});

const emit = defineEmits(['close', 'confirm']);

function handleConfirm() {
    emit('confirm');
    emit('close');
}

function handleCancel() {
    emit('close');
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
        <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity" @click="handleCancel"></div>

                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-4 scale-95"
                >
                    <div v-if="show" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                        <div class="absolute top-4 right-4">
                            <button @click="handleCancel" class="text-zinc-400 hover:text-zinc-600 transition-colors">
                                <XMarkIcon class="w-6 h-6" />
                            </button>
                        </div>

                        <div class="p-8">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-6 rounded-full" :class="type === 'danger' ? 'bg-red-100' : 'bg-blue-100'">
                                <ExclamationTriangleIcon class="w-8 h-8" :class="type === 'danger' ? 'text-red-600' : 'text-blue-600'" />
                            </div>

                            <h3 class="text-2xl font-bold text-zinc-900 text-center mb-3">
                                {{ title }}
                            </h3>

                            <p class="text-zinc-600 text-center leading-relaxed mb-8">
                                {{ message }}
                            </p>

                            <div class="flex gap-3">
                                <button
                                    @click="handleCancel"
                                    class="flex-1 px-6 py-3 bg-zinc-100 text-zinc-700 rounded-xl font-bold hover:bg-zinc-200 transition-all"
                                >
                                    {{ cancelText }}
                                </button>
                                <button
                                    @click="handleConfirm"
                                    class="flex-1 px-6 py-3 rounded-xl font-bold text-white transition-all hover:scale-105 shadow-lg"
                                    :class="type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                                >
                                    {{ confirmText }}
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </Transition>
</template>
