<script setup>
import { ref, watch, onMounted } from 'vue';
import { CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon, InformationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    duration: {
        type: Number,
        default: 4000
    },
    show: Boolean
});

const emit = defineEmits(['close']);

const isVisible = ref(false);

watch(() => props.show, (newVal) => {
    if (newVal) {
        isVisible.value = true;
        if (props.duration > 0) {
            setTimeout(() => {
                close();
            }, props.duration);
        }
    }
});

function close() {
    isVisible.value = false;
    setTimeout(() => {
        emit('close');
    }, 300);
}

const typeStyles = {
    success: {
        bg: 'bg-green-50',
        border: 'border-green-200',
        text: 'text-green-800',
        icon: 'text-green-500'
    },
    error: {
        bg: 'bg-red-50',
        border: 'border-red-200',
        text: 'text-red-800',
        icon: 'text-red-500'
    },
    warning: {
        bg: 'bg-yellow-50',
        border: 'border-yellow-200',
        text: 'text-yellow-800',
        icon: 'text-yellow-500'
    },
    info: {
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        text: 'text-blue-800',
        icon: 'text-blue-500'
    }
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="translate-x-full opacity-0"
            enter-to-class="translate-x-0 opacity-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="translate-x-0 opacity-100"
            leave-to-class="translate-x-full opacity-0"
        >
            <div v-if="show && isVisible"
                class="fixed top-24 right-4 z-[100] max-w-sm w-full shadow-lg rounded-lg pointer-events-auto"
                :class="[typeStyles[type].bg, typeStyles[type].border, 'border']">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <CheckCircleIcon v-if="type === 'success'" class="h-6 w-6" :class="typeStyles[type].icon" />
                            <XCircleIcon v-else-if="type === 'error'" class="h-6 w-6" :class="typeStyles[type].icon" />
                            <ExclamationTriangleIcon v-else-if="type === 'warning'" class="h-6 w-6" :class="typeStyles[type].icon" />
                            <InformationCircleIcon v-else class="h-6 w-6" :class="typeStyles[type].icon" />
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium" :class="typeStyles[type].text">
                                {{ message }}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="close" class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2" :class="typeStyles[type].text">
                                <span class="sr-only">Close</span>
                                <XMarkIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

