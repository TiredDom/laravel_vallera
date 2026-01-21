<script setup>
import { ref } from 'vue';
import { XMarkIcon, CreditCardIcon, BuildingLibraryIcon, DevicePhoneMobileIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    total: Number,
    subtotal: {
        type: Number,
        default: 0
    },
    shipping: {
        type: Number,
        default: 500
    }
});

const emit = defineEmits(['close', 'confirm']);

const step = ref(1);
const selectedMethod = ref('');
const isProcessing = ref(false);
const redirectMessage = ref('');
const paymentData = ref({
    card: {
        cardNumber: '',
        cardName: '',
        expiryDate: '',
        cvv: ''
    },
    bank: {
        bankName: '',
        accountNumber: '',
        accountName: ''
    }
});

const errors = ref({});

const paymentMethods = [
    { id: 'gcash', name: 'GCash', icon: DevicePhoneMobileIcon, description: 'Pay using GCash mobile wallet' },
    { id: 'card', name: 'Credit/Debit Card', icon: CreditCardIcon, description: 'Visa, Mastercard, or American Express' },
    { id: 'bank', name: 'Bank Transfer', icon: BuildingLibraryIcon, description: 'Direct bank transfer' }
];

function selectPaymentMethod(method) {
    selectedMethod.value = method;
    errors.value = {};

    if (method === 'gcash') {
        step.value = 3;
        isProcessing.value = true;
        redirectMessage.value = 'Redirecting to GCash...';

        setTimeout(() => {
            redirectMessage.value = 'Opening GCash App...';
        }, 1000);

        setTimeout(() => {
            redirectMessage.value = 'Processing payment...';

            emit('confirm', {
                method: 'gcash',
                data: {}
            });
        }, 2500);

        setTimeout(() => {
            redirectMessage.value = 'Payment successful!';
            isProcessing.value = false;

            setTimeout(() => {
                closeModal();
            }, 1500);
        }, 4000);
    } else {
        step.value = 2;
    }
}

function validateCard() {
    errors.value = {};

    if (!paymentData.value.card.cardNumber) {
        errors.value.cardNumber = 'Card number is required';
    } else if (!/^\d{16}$/.test(paymentData.value.card.cardNumber.replace(/\s/g, ''))) {
        errors.value.cardNumber = 'Card number must be 16 digits';
    }

    if (!paymentData.value.card.cardName) {
        errors.value.cardName = 'Cardholder name is required';
    }

    if (!paymentData.value.card.expiryDate) {
        errors.value.expiryDate = 'Expiry date is required';
    } else if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(paymentData.value.card.expiryDate)) {
        errors.value.expiryDate = 'Invalid format (MM/YY)';
    }

    if (!paymentData.value.card.cvv) {
        errors.value.cvv = 'CVV is required';
    } else if (!/^\d{3,4}$/.test(paymentData.value.card.cvv)) {
        errors.value.cvv = 'CVV must be 3-4 digits';
    }

    return Object.keys(errors.value).length === 0;
}

function validateBank() {
    errors.value = {};

    if (!paymentData.value.bank.bankName) {
        errors.value.bankName = 'Bank name is required';
    }

    if (!paymentData.value.bank.accountNumber) {
        errors.value.accountNumber = 'Account number is required';
    } else if (!/^\d{10,16}$/.test(paymentData.value.bank.accountNumber)) {
        errors.value.accountNumber = 'Account number must be 10-16 digits';
    }

    if (!paymentData.value.bank.accountName) {
        errors.value.accountName = 'Account name is required';
    }

    return Object.keys(errors.value).length === 0;
}

function formatCardNumber(value) {
    const cleaned = value.replace(/\s/g, '');
    const chunks = cleaned.match(/.{1,4}/g);
    return chunks ? chunks.join(' ') : cleaned;
}

function handleCardNumberInput(event) {
    const value = event.target.value.replace(/[^\d]/g, '');
    if (value.length <= 16) {
        paymentData.value.card.cardNumber = formatCardNumber(value);
    }
}

function handleExpiryInput(event) {
    let value = event.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    paymentData.value.card.expiryDate = value.slice(0, 5);
}

function handleCvvInput(event) {
    const value = event.target.value.replace(/[^\d]/g, '');
    paymentData.value.card.cvv = value.slice(0, 4);
}

function handleAccountNumberInput(event) {
    const value = event.target.value.replace(/[^\d]/g, '');
    paymentData.value.bank.accountNumber = value.slice(0, 16);
}

function handleAccountNameInput(event) {
    const value = event.target.value.replace(/[^a-zA-Z\s\-.']/g, '');
    paymentData.value.bank.accountName = value.slice(0, 100);
}

function handleCardNameInput(event) {
    const value = event.target.value.replace(/[^a-zA-Z\s\-.']/g, '').toUpperCase();
    paymentData.value.card.cardName = value.slice(0, 100);
}

function handleGcashNumberInput(event) {
    const value = event.target.value.replace(/[^\d]/g, '');
    paymentData.value.gcash.mobileNumber = value.slice(0, 11);
}

function handleReferenceInput(event) {
    const value = event.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    paymentData.value.gcash.referenceNumber = value.slice(0, 13);
}

function processPayment() {
    let isValid = false;

    if (selectedMethod.value === 'card') {
        isValid = validateCard();
    } else if (selectedMethod.value === 'bank') {
        isValid = validateBank();
    }

    if (isValid) {
        isProcessing.value = true;

        setTimeout(() => {
            step.value = 3;
            isProcessing.value = false;

            setTimeout(() => {
                emit('confirm', {
                    method: selectedMethod.value,
                    data: paymentData.value[selectedMethod.value]
                });
                closeModal();
            }, 2000);
        }, 1500);
    }
}

function closeModal() {
    step.value = 1;
    selectedMethod.value = '';
    redirectMessage.value = '';
    paymentData.value = {
        card: { cardNumber: '', cardName: '', expiryDate: '', cvv: '' },
        bank: { bankName: '', accountNumber: '', accountName: '' }
    };
    errors.value = {};
    isProcessing.value = false;
    emit('close');
}

function goBack() {
    if (step.value === 2) {
        step.value = 1;
        selectedMethod.value = '';
        errors.value = {};
    }
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
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="closeModal"></div>

                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-4 scale-95"
                >
                    <div v-if="show" class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">
                                {{ step === 1 ? 'Select Payment Method' : step === 2 ? 'Enter Payment Details' : 'Payment Successful' }}
                            </h3>
                            <button @click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                                <XMarkIcon class="w-6 h-6" />
                            </button>
                        </div>

                        <div class="p-6">
                            <div v-if="step === 1" class="space-y-4">
                                <div class="bg-gradient-to-br from-zinc-50 to-zinc-100 rounded-xl p-6 mb-6 border border-zinc-200">
                                    <h4 class="text-sm font-semibold text-zinc-700 uppercase tracking-wider mb-4">Payment Summary</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between text-zinc-700">
                                            <span class="text-sm">Subtotal</span>
                                            <span class="font-medium">₱{{ subtotal.toLocaleString() }}</span>
                                        </div>
                                        <div class="flex items-center justify-between text-zinc-700">
                                            <span class="text-sm">Shipping Fee</span>
                                            <span class="font-medium">₱{{ shipping.toLocaleString() }}</span>
                                        </div>
                                        <div class="border-t border-zinc-300 pt-3 flex items-center justify-between">
                                            <span class="text-base font-bold text-zinc-900">Total Amount</span>
                                            <span class="text-2xl font-bold text-primary-600">₱{{ total.toLocaleString() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <button
                                        v-for="method in paymentMethods"
                                        :key="method.id"
                                        @click="selectPaymentMethod(method.id)"
                                        class="w-full flex items-center gap-4 p-4 border-2 border-zinc-200 rounded-xl hover:border-primary-600 hover:bg-primary-50 transition-all group"
                                    >
                                        <div class="p-3 bg-primary-100 rounded-lg group-hover:bg-primary-200 transition-colors">
                                            <component :is="method.icon" class="w-6 h-6 text-primary-600" />
                                        </div>
                                        <div class="flex-1 text-left">
                                            <h4 class="font-semibold text-zinc-900">{{ method.name }}</h4>
                                            <p class="text-sm text-zinc-500">{{ method.description }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-zinc-400 group-hover:text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>



                            <div v-if="step === 2 && selectedMethod === 'card'" class="space-y-4">
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center gap-3">
                                        <CreditCardIcon class="w-6 h-6 text-purple-600" />
                                        <div>
                                            <p class="font-semibold text-purple-900">Card Payment</p>
                                            <p class="text-sm text-purple-700">Secure payment via Visa, Mastercard, or Amex</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Card Number</label>
                                    <input
                                        :value="paymentData.card.cardNumber"
                                        @input="handleCardNumberInput"
                                        type="text"
                                        placeholder="1234 5678 9012 3456"
                                        maxlength="19"
                                        class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono"
                                        :class="{ 'border-red-500': errors.cardNumber }"
                                    />
                                    <p v-if="errors.cardNumber" class="mt-1 text-sm text-red-600">{{ errors.cardNumber }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Cardholder Name</label>
                                    <input
                                        :value="paymentData.card.cardName"
                                        @input="handleCardNameInput"
                                        type="text"
                                        placeholder="JUAN DELA CRUZ"
                                        maxlength="100"
                                        class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 uppercase"
                                        :class="{ 'border-red-500': errors.cardName }"
                                    />
                                    <p v-if="errors.cardName" class="mt-1 text-sm text-red-600">{{ errors.cardName }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 mb-2">Expiry Date</label>
                                        <input
                                            :value="paymentData.card.expiryDate"
                                            @input="handleExpiryInput"
                                            type="text"
                                            placeholder="MM/YY"
                                            maxlength="5"
                                            class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono"
                                            :class="{ 'border-red-500': errors.expiryDate }"
                                        />
                                        <p v-if="errors.expiryDate" class="mt-1 text-sm text-red-600">{{ errors.expiryDate }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 mb-2">CVV</label>
                                        <input
                                            :value="paymentData.card.cvv"
                                            @input="handleCvvInput"
                                            type="password"
                                            placeholder="***"
                                            maxlength="4"
                                            class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono"
                                            :class="{ 'border-red-500': errors.cvv }"
                                        />
                                        <p v-if="errors.cvv" class="mt-1 text-sm text-red-600">{{ errors.cvv }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button @click="goBack" class="flex-1 px-6 py-3 border border-zinc-300 text-zinc-700 rounded-lg hover:bg-zinc-50 font-medium transition-colors">
                                        Back
                                    </button>
                                    <button
                                        @click="processPayment"
                                        :disabled="isProcessing"
                                        class="flex-1 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ isProcessing ? 'Processing...' : 'Pay ₱' + total.toLocaleString() }}
                                    </button>
                                </div>
                            </div>

                            <div v-if="step === 2 && selectedMethod === 'bank'" class="space-y-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center gap-3">
                                        <BuildingLibraryIcon class="w-6 h-6 text-green-600" />
                                        <div>
                                            <p class="font-semibold text-green-900">Bank Transfer</p>
                                            <p class="text-sm text-green-700">Direct transfer from your bank account</p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Bank Name</label>
                                    <select
                                        v-model="paymentData.bank.bankName"
                                        class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        :class="{ 'border-red-500': errors.bankName }"
                                    >
                                        <option value="">Select Bank</option>
                                        <option value="BDO">BDO Unibank</option>
                                        <option value="BPI">Bank of the Philippine Islands</option>
                                        <option value="Metrobank">Metrobank</option>
                                        <option value="Unionbank">Unionbank</option>
                                        <option value="Landbank">Landbank</option>
                                        <option value="PNB">Philippine National Bank</option>
                                        <option value="Security Bank">Security Bank</option>
                                    </select>
                                    <p v-if="errors.bankName" class="mt-1 text-sm text-red-600">{{ errors.bankName }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Account Number</label>
                                    <input
                                        :value="paymentData.bank.accountNumber"
                                        @input="handleAccountNumberInput"
                                        type="text"
                                        placeholder="1234567890123"
                                        maxlength="16"
                                        class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono"
                                        :class="{ 'border-red-500': errors.accountNumber }"
                                    />
                                    <p v-if="errors.accountNumber" class="mt-1 text-sm text-red-600">{{ errors.accountNumber }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 mb-2">Account Name</label>
                                    <input
                                        :value="paymentData.bank.accountName"
                                        @input="handleAccountNameInput"
                                        type="text"
                                        placeholder="Juan Dela Cruz"
                                        maxlength="100"
                                        class="w-full px-4 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                        :class="{ 'border-red-500': errors.accountName }"
                                    />
                                    <p v-if="errors.accountName" class="mt-1 text-sm text-red-600">{{ errors.accountName }}</p>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button @click="goBack" class="flex-1 px-6 py-3 border border-zinc-300 text-zinc-700 rounded-lg hover:bg-zinc-50 font-medium transition-colors">
                                        Back
                                    </button>
                                    <button
                                        @click="processPayment"
                                        :disabled="isProcessing"
                                        class="flex-1 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{ isProcessing ? 'Processing...' : 'Pay ₱' + total.toLocaleString() }}
                                    </button>
                                </div>
                            </div>

                            <div v-if="step === 3" class="text-center py-8">
                                <div v-if="isProcessing" class="space-y-6">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full">
                                        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-zinc-900 mb-2">{{ redirectMessage }}</h3>
                                        <p class="text-zinc-600">Please wait...</p>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                                        <CheckCircleIcon class="w-12 h-12 text-green-600" />
                                    </div>
                                    <h3 class="text-2xl font-bold text-zinc-900 mb-2">Payment Successful!</h3>
                                    <p class="text-zinc-600 mb-4">Your order has been placed successfully.</p>
                                    <p class="text-sm text-zinc-500">Redirecting...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </Transition>
</template>
