<template>
    <TransitionRoot :show="show" as="template">
        <Dialog @close="$emit('close')" class="relative z-50">
            <TransitionChild enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
                    <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-2xl bg-white rounded-xl shadow-2xl max-h-[95vh] overflow-y-auto">
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl sticky top-0 z-10">
                                <h3 class="text-base sm:text-lg font-semibold text-white">Add New Product</h3>
                            </div>
                            <form @submit.prevent="submitAddProduct" class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Product Name</label>
                                        <input v-model="form.name" type="text" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Enter product name" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Description</label>
                                        <textarea v-model="form.description" rows="2" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Enter product description"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Price (₱)</label>
                                        <input v-model="form.price" type="number" step="0.01" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="0.00" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Stock</label>
                                        <input v-model="form.stock" type="number" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="0" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Category</label>
                                        <select v-model="form.category" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                            <option value="">Select Category</option>
                                            <option v-for="cat in categoryOptions" :key="cat" :value="cat">{{ cat }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">
                                            Image <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" @change="handleAddImage" accept="image/*" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, or WEBP (max 5MB)</p>
                                    </div>
                                    <div v-if="imagePreview" class="sm:col-span-2">
                                        <img :src="imagePreview" class="w-full h-32 sm:h-48 object-cover rounded-lg border-2 border-slate-200" alt="Preview">
                                    </div>
                                    <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                        <label class="flex items-center gap-2">
                                            <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Featured (Max 3)</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input v-model="form.is_active" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Active</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-200">
                                    <button type="button" @click="$emit('close')" class="w-full sm:w-auto px-4 py-2 text-sm sm:text-base text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">Cancel</button>
                                    <button type="submit" :disabled="form.processing" class="w-full sm:w-auto px-6 py-2 text-sm sm:text-base bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ form.processing ? 'Creating...' : 'Create Product' }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';

const props = defineProps({
    show: Boolean,
    categoryOptions: Array,
});

const emit = defineEmits(['close', 'productAdded', 'toast']);

const form = useForm({
    name: '',
    description: '',
    price: 0,
    stock: 0,
    category: '',
    image: null,
    is_featured: false,
    is_active: true,
});

const imagePreview = ref(null);

function handleAddImage(event) {
    const file = event.target.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            emit('toast', { show: true, message: 'Image must be less than 5MB', type: 'error' });
            event.target.value = '';
            return;
        }
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => imagePreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
}

function submitAddProduct() {
    if (!form.image) {
        emit('toast', { show: true, message: 'Please upload a product image', type: 'error' });
        return;
    }

    form.post('/admin/products', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit('productAdded');
            emit('close');
            form.reset();
            imagePreview.value = null;
            emit('toast', { show: true, message: 'Product created successfully', type: 'success' });
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            emit('toast', { show: true, message: firstError || 'Failed to create product', type: 'error' });
        },
    });
}
</script>
