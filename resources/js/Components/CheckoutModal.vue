<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { XMarkIcon, CreditCardIcon, BuildingLibraryIcon, DevicePhoneMobileIcon, TruckIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    total: Number,
    subtotal: { type: Number, default: 0 },
    shipping: { type: Number, default: 500 }
});

const emit = defineEmits(['close', 'success']);

const currentStep = ref(1);
const selectedMethod = ref('');
const isProcessing = ref(false);
const toast = ref({ show: false, message: '', type: 'success' });

const delivery = ref({
    name: '',
    phone: '',
    address: '',
    city: '',
    province: '',
    postalCode: '',
    notes: ''
});

const paymentData = ref({
    gcash: { phone: '' },
    card: { cardNumber: '', cardName: '', expiryDate: '', cvv: '' },
    bank: { bankName: '', accountNumber: '', accountName: '' }
});

const errors = ref({});

const paymentMethods = [
    { id: 'gcash', name: 'GCash', icon: DevicePhoneMobileIcon, description: 'Mobile wallet payment' },
    { id: 'card', name: 'Credit/Debit Card', icon: CreditCardIcon, description: 'Visa, Mastercard, AmEx' },
    { id: 'bank', name: 'Bank Transfer', icon: BuildingLibraryIcon, description: 'Direct bank transfer' }
];

function showToast(message, type = 'success') {
    toast.value = { show: true, message, type };
}

function hideToast() {
    toast.value.show = false;
}

function sanitizeInput(value) {
    if (!value || typeof value !== 'string') return '';
    return value.replace(/<[^>]*>/g, '').trim();
}

function validateDelivery() {
    errors.value = {};
    delivery.value.name = sanitizeInput(delivery.value.name);
    delivery.value.phone = sanitizeInput(delivery.value.phone);
    delivery.value.address = sanitizeInput(delivery.value.address);
    delivery.value.city = sanitizeInput(delivery.value.city);
    delivery.value.province = sanitizeInput(delivery.value.province);
    delivery.value.postalCode = sanitizeInput(delivery.value.postalCode);
    delivery.value.notes = sanitizeInput(delivery.value.notes);

    if (!delivery.value.name || delivery.value.name.length < 2 || !/^[A-Za-z ]+$/.test(delivery.value.name)) {
        errors.value.name = 'Full name is required (letters and spaces only, min 2 characters)';
    }
    if (!delivery.value.phone || !/^(09|\+639)\d{9}$/.test(delivery.value.phone)) {
        errors.value.phone = 'Valid Philippine mobile number required (09XXXXXXXXX)';
    }
    if (!delivery.value.address || delivery.value.address.length < 10) {
        errors.value.address = 'Complete address is required (minimum 10 characters)';
    }
    if (!delivery.value.city || delivery.value.city.length < 2 || !/^[A-Za-z ]+$/.test(delivery.value.city)) {
        errors.value.city = 'City is required (letters and spaces only, min 2 characters)';
    }
    if (!delivery.value.province || delivery.value.province.length < 2 || !/^[A-Za-z ]+$/.test(delivery.value.province)) {
        errors.value.province = 'Province is required (letters and spaces only, min 2 characters)';
    }
    if (!delivery.value.postalCode || !/^\d{4}$/.test(delivery.value.postalCode)) {
        errors.value.postalCode = 'Postal code required (4 digits)';
    }
    return Object.keys(errors.value).length === 0;
}

function proceedToPayment() {
    if (validateDelivery()) {
        currentStep.value = 2;
        errors.value = {};
    }
}

function selectPaymentMethod(method) {
    selectedMethod.value = method;
    errors.value = {};
}

function validatePayment() {
    errors.value = {};
    if (selectedMethod.value === 'gcash') {
        paymentData.value.gcash.phone = sanitizeInput(paymentData.value.gcash.phone);
        if (!paymentData.value.gcash.phone || !/^(09|\+639)\d{9}$/.test(paymentData.value.gcash.phone)) {
            errors.value.gcashPhone = 'Valid GCash number required (09XXXXXXXXX)';
        }
    } else if (selectedMethod.value === 'card') {
        paymentData.value.card.cardNumber = sanitizeInput(paymentData.value.card.cardNumber);
        paymentData.value.card.cardName = sanitizeInput(paymentData.value.card.cardName);
        paymentData.value.card.expiryDate = sanitizeInput(paymentData.value.card.expiryDate);
        paymentData.value.card.cvv = sanitizeInput(paymentData.value.card.cvv);
        if (!paymentData.value.card.cardNumber || !/^\d{16}$/.test(paymentData.value.card.cardNumber.replace(/\s/g, ''))) {
            errors.value.cardNumber = 'Valid 16-digit card number required';
        }
        if (!paymentData.value.card.cardName || paymentData.value.card.cardName.length < 3 || !/^[A-Za-z ]+$/.test(paymentData.value.card.cardName)) {
            errors.value.cardName = 'Cardholder name required (letters and spaces only, min 3 characters)';
        }
        if (!paymentData.value.card.expiryDate || !/^\d{2}\/\d{2}$/.test(paymentData.value.card.expiryDate)) {
            errors.value.expiryDate = 'Valid expiry date required (MM/YY)';
        }
        if (!paymentData.value.card.cvv || !/^\d{3,4}$/.test(paymentData.value.card.cvv)) {
            errors.value.cvv = 'Valid CVV required (3 or 4 digits)';
        }
    } else if (selectedMethod.value === 'bank') {
        paymentData.value.bank.bankName = sanitizeInput(paymentData.value.bank.bankName);
        paymentData.value.bank.accountNumber = sanitizeInput(paymentData.value.bank.accountNumber);
        paymentData.value.bank.accountName = sanitizeInput(paymentData.value.bank.accountName);
        if (!paymentData.value.bank.bankName || paymentData.value.bank.bankName.length < 3) {
            errors.value.bankName = 'Bank name required';
        }
        if (!paymentData.value.bank.accountNumber || !/^\d{10,20}$/.test(paymentData.value.bank.accountNumber)) {
            errors.value.accountNumber = 'Valid account number required (10-20 digits)';
        }
        if (!paymentData.value.bank.accountName || paymentData.value.bank.accountName.length < 3 || !/^[A-Za-z ]+$/.test(paymentData.value.bank.accountName)) {
            errors.value.accountName = 'Account holder name required (letters and spaces only, min 3 characters)';
        }
    }
    return Object.keys(errors.value).length === 0;
}

function processCheckout() {
    if (!validatePayment()) return;
    isProcessing.value = true;
    let paymentPayload;
    if (selectedMethod.value === 'card') {
        paymentPayload = {
            holder: paymentData.value.card.cardName,
            number: paymentData.value.card.cardNumber.replace(/\s/g, ''),
            expiry: paymentData.value.card.expiryDate,
            cvc: paymentData.value.card.cvv
        };
    } else if (selectedMethod.value === 'bank') {
        paymentPayload = {
            account_name: paymentData.value.bank.accountName,
            account_number: paymentData.value.bank.accountNumber,
            reference: paymentData.value.bank.reference || 'BANKREF' + Date.now()
        };
    } else {
        paymentPayload = JSON.parse(JSON.stringify(paymentData.value[selectedMethod.value]));
    }
    router.post('/cart/checkout', {
        payment_method: selectedMethod.value,
        payment_data: paymentPayload,
        delivery: JSON.parse(JSON.stringify(delivery.value))
    }, {
        onSuccess: () => {
            emit('success');
            emit('close');
            resetForm();
        },
        onError: (errs) => {
            isProcessing.value = false;
            if (errs) {
                Object.keys(errs).forEach(key => {
                    errors.value[key] = errs[key];
                });
            }
        },
        onFinish: () => {
            isProcessing.value = false;
        }
    });
}

function resetForm() {
    currentStep.value = 1;
    selectedMethod.value = '';
    delivery.value = { name: '', phone: '', address: '', city: '', province: '', postalCode: '', notes: '' };
    paymentData.value = {
        gcash: { phone: '' },
        card: { cardNumber: '', cardName: '', expiryDate: '', cvv: '' },
        bank: { bankName: '', accountNumber: '', accountName: '' }
    };
    errors.value = {};
}

function closeModal() {
    emit('close');
    setTimeout(resetForm, 300);
}

function formatCardNumber(event) {
    let value = event.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    paymentData.value.card.cardNumber = formattedValue;
}

function formatExpiryDate(event) {
    let value = event.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2, 4);
    }
    paymentData.value.card.expiryDate = value;
}
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal"></div>

                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden">
                        <button @click="closeModal" class="absolute top-4 right-4 z-10 p-2 hover:bg-slate-100 rounded-full transition-colors">
                            <XMarkIcon class="w-6 h-6 text-slate-600" />
                        </button>

                        <div class="p-8">
                            <div class="flex items-center justify-center mb-8">
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold', currentStep >= 1 ? 'bg-primary-600 text-white' : 'bg-slate-200 text-slate-500']">
                                            1
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-slate-700">Delivery</span>
                                    </div>
                                    <div class="w-24 h-1 mx-4" :class="currentStep >= 2 ? 'bg-primary-600' : 'bg-slate-200'"></div>
                                    <div class="flex items-center">
                                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold', currentStep >= 2 ? 'bg-primary-600 text-white' : 'bg-slate-200 text-slate-500']">
                                            2
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-slate-700">Payment</span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 1" class="space-y-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <TruckIcon class="w-8 h-8 text-primary-600" />
                                    <h2 class="text-2xl font-bold text-slate-900">Delivery Information</h2>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Full Name *</label>
                                        <input v-model="delivery.name" type="text" placeholder="Juan Dela Cruz" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Mobile Number *</label>
                                        <input v-model="delivery.phone" type="tel" placeholder="09123456789" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Complete Address *</label>
                                    <textarea v-model="delivery.address" rows="3" placeholder="House No., Street, Barangay" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                    <p v-if="errors.address" class="text-red-600 text-sm mt-1">{{ errors.address }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">City *</label>
                                        <input v-model="delivery.city" type="text" placeholder="Manila" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.city" class="text-red-600 text-sm mt-1">{{ errors.city }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Province *</label>
                                        <input v-model="delivery.province" type="text" placeholder="Metro Manila" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.province" class="text-red-600 text-sm mt-1">{{ errors.province }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Postal Code *</label>
                                        <input v-model="delivery.postalCode" type="text" maxlength="4" placeholder="1000" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.postalCode" class="text-red-600 text-sm mt-1">{{ errors.postalCode }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Delivery Notes (Optional)</label>
                                    <textarea v-model="delivery.notes" rows="2" placeholder="Special instructions, landmarks, etc." class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-slate-600">Subtotal:</span>
                                        <span class="font-semibold">₱{{ Number(subtotal).toLocaleString() }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-3">
                                        <span class="text-slate-600">Shipping:</span>
                                        <span class="font-semibold">₱{{ Number(shipping).toLocaleString() }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t border-slate-200 pt-3">
                                        <span>Total:</span>
                                        <span class="text-primary-600">₱{{ Number(total).toLocaleString() }}</span>
                                    </div>
                                </div>

                                <button @click="proceedToPayment" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg transition-colors">
                                    Continue to Payment
                                </button>
                            </div>

                            <div v-if="currentStep === 2" class="space-y-6">
                                <button @click="currentStep = 1" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 mb-4">
                                    <ArrowLeftIcon class="w-5 h-5" />
                                    <span class="text-sm font-medium">Back to Delivery</span>
                                </button>

                                <h2 class="text-2xl font-bold text-slate-900 mb-6">Select Payment Method</h2>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <button v-for="method in paymentMethods" :key="method.id" @click="selectPaymentMethod(method.id)" :class="['p-6 border-2 rounded-xl transition-all hover:border-primary-300 hover:shadow-md', selectedMethod === method.id ? 'border-primary-600 bg-primary-50' : 'border-slate-200']">
                                        <component :is="method.icon" class="w-12 h-12 mx-auto mb-3" :class="selectedMethod === method.id ? 'text-primary-600' : 'text-slate-400'" />
                                        <h3 class="font-bold text-slate-900 mb-1">{{ method.name }}</h3>
                                        <p class="text-xs text-slate-600">{{ method.description }}</p>
                                    </button>
                                </div>

                                <div v-if="selectedMethod === 'gcash'" class="space-y-4 bg-slate-50 p-6 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">GCash Number *</label>
                                        <input v-model="paymentData.gcash.phone" type="tel" placeholder="09123456789" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.gcashPhone" class="text-red-600 text-sm mt-1">{{ errors.gcashPhone }}</p>
                                    </div>
                                </div>

                                <div v-if="selectedMethod === 'card'" class="space-y-4 bg-slate-50 p-6 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Card Number *</label>
                                        <input :value="paymentData.card.cardNumber" @input="formatCardNumber" type="text" maxlength="19" placeholder="1234 5678 9012 3456" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.cardNumber" class="text-red-600 text-sm mt-1">{{ errors.cardNumber }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Cardholder Name *</label>
                                        <input v-model="paymentData.card.cardName" type="text" placeholder="JUAN DELA CRUZ" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.cardName" class="text-red-600 text-sm mt-1">{{ errors.cardName }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-2">Expiry Date *</label>
                                            <input :value="paymentData.card.expiryDate" @input="formatExpiryDate" type="text" maxlength="5" placeholder="MM/YY" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <p v-if="errors.expiryDate" class="text-red-600 text-sm mt-1">{{ errors.expiryDate }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-2">CVV *</label>
                                            <input v-model="paymentData.card.cvv" type="password" maxlength="4" placeholder="•••" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <p v-if="errors.cvv" class="text-red-600 text-sm mt-1">{{ errors.cvv }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="selectedMethod === 'bank'" class="space-y-4 bg-slate-50 p-6 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Bank Name *</label>
                                        <select v-model="paymentData.bank.bankName" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            <option value="">Select Bank</option>
                                            <option value="BDO">BDO</option>
                                            <option value="BPI">BPI</option>
                                            <option value="Metrobank">Metrobank</option>
                                            <option value="Unionbank">Unionbank</option>
                                            <option value="Landbank">Landbank</option>
                                        </select>
                                        <p v-if="errors.bankName" class="text-red-600 text-sm mt-1">{{ errors.bankName }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Account Number *</label>
                                        <input v-model="paymentData.bank.accountNumber" type="text" placeholder="1234567890" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.accountNumber" class="text-red-600 text-sm mt-1">{{ errors.accountNumber }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Account Holder Name *</label>
                                        <input v-model="paymentData.bank.accountName" type="text" placeholder="Juan Dela Cruz" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <p v-if="errors.accountName" class="text-red-600 text-sm mt-1">{{ errors.accountName }}</p>
                                    </div>
                                </div>

                                <div class="bg-slate-50 p-4 rounded-lg">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total to Pay:</span>
                                        <span class="text-primary-600">₱{{ Number(total).toLocaleString() }}</span>
                                    </div>
                                </div>

                                <button @click="processCheckout" :disabled="isProcessing || !selectedMethod" class="w-full py-4 bg-primary-600 hover:bg-primary-700 disabled:bg-slate-300 text-white font-bold rounded-lg transition-colors disabled:cursor-not-allowed">
                                    {{ isProcessing ? 'Processing...' : 'Complete Order' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>

    <div v-if="toast.show" class="fixed top-24 right-4 z-[100] max-w-sm w-full">
        <div class="p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg shadow-lg flex items-center gap-3">
            <span>{{ toast.message }}</span>
            <button @click="hideToast" class="ml-auto text-emerald-700 hover:text-emerald-900">
                <XMarkIcon class="w-5 h-5" />
            </button>
        </div>
    </div>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
