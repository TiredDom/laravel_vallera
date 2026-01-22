<template>
    <Head title="Product Management" />

    <ToastNotification :show="toast.show" :message="toast.message" :type="toast.type" @close="toast.show = false" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <Link href="/admin/dashboard" class="p-2 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </Link>
                        <div class="min-w-0">
                            <h1 class="text-xl sm:text-3xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent truncate">Product Management</h1>
                            <p class="mt-1 text-sm sm:text-base text-slate-600">Manage your furniture catalog</p>
                        </div>
                    </div>
                    <button @click="openAddModal" class="px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm sm:text-base whitespace-nowrap">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Add Product</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" data-aos="fade-up">
                <div class="bg-white rounded-xl shadow-lg border-2 border-emerald-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-emerald-600">{{ products.length }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Total Products</p>
                        </div>
                        <div class="p-3 bg-emerald-100 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border-2 border-blue-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-blue-600">{{ featuredCount }}/3</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Featured</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border-2 border-amber-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-amber-600">{{ totalStock }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Total Stock</p>
                        </div>
                        <div class="p-3 bg-amber-100 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border-2 border-purple-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-3xl font-bold text-purple-600">{{ categories.length }}</p>
                            <p class="text-sm text-slate-600 mt-1 font-medium">Categories</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                    <div v-for="product in products" :key="product.id" class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 border-2 border-slate-200 rounded-xl hover:border-emerald-300 hover:shadow-md transition-all">
                        <div class="flex items-start gap-3 sm:gap-4 flex-1">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover">
                                <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate">{{ product.name }}</h3>
                                    <span v-if="product.is_featured" class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-100 text-amber-700 whitespace-nowrap">⭐ Featured</span>
                                    <span v-if="!product.is_active" class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700 whitespace-nowrap">Inactive</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 mb-2 line-clamp-2">{{ product.description || 'No description' }}</p>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm">
                                    <span class="font-semibold text-emerald-600 whitespace-nowrap">₱{{ product.price }}</span>
                                    <span class="text-slate-600 whitespace-nowrap">Stock: {{ product.stock }}</span>
                                    <span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 whitespace-nowrap">{{ product.category }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:flex-col sm:gap-2 justify-end sm:justify-center">
                            <button @click="previewProduct(product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-purple-100 text-purple-700 rounded-lg font-semibold hover:bg-purple-200 transition-colors text-xs sm:text-sm flex items-center justify-center gap-1 whitespace-nowrap">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span class="hidden sm:inline">Preview</span>
                            </button>
                            <button @click="openEditModal(product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors text-xs sm:text-sm whitespace-nowrap">Edit</button>
                            <button @click="confirmDelete(product)" class="flex-1 sm:flex-none sm:w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors text-xs sm:text-sm whitespace-nowrap">Delete</button>
                        </div>
                    </div>

                    <div v-if="products.length === 0" class="text-center py-12">
                        <svg class="mx-auto w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-slate-600 font-medium text-lg">No products yet</p>
                        <p class="text-slate-500 text-sm mt-2">Create your first product to get started</p>
                        <button @click="openAddModal" class="mt-4 px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all">Add Your First Product</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <TransitionRoot :show="showAddModal" as="template">
        <Dialog @close="showAddModal = false" class="relative z-50">
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
                                        <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Enter product name" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Description</label>
                                        <textarea v-model="addForm.description" rows="2" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="Enter product description"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Price (₱)</label>
                                        <input v-model="addForm.price" type="number" step="0.01" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="0.00" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Stock</label>
                                        <input v-model="addForm.stock" type="number" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" placeholder="0" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Category</label>
                                        <select v-model="addForm.category" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
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
                                    <div v-if="addImagePreview" class="sm:col-span-2">
                                        <img :src="addImagePreview" class="w-full h-32 sm:h-48 object-cover rounded-lg border-2 border-slate-200" alt="Preview">
                                    </div>
                                    <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                        <label class="flex items-center gap-2">
                                            <input v-model="addForm.is_featured" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Featured (Max 3)</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input v-model="addForm.is_active" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Active</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-200">
                                    <button type="button" @click="showAddModal = false" class="w-full sm:w-auto px-4 py-2 text-sm sm:text-base text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">Cancel</button>
                                    <button type="submit" :disabled="addForm.processing" class="w-full sm:w-auto px-6 py-2 text-sm sm:text-base bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg font-semibold hover:from-emerald-700 hover:to-teal-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ addForm.processing ? 'Creating...' : 'Create Product' }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <TransitionRoot :show="showEditModal" as="template">
        <Dialog @close="showEditModal = false" class="relative z-50">
            <TransitionChild enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
                    <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-2xl bg-white rounded-xl shadow-2xl max-h-[95vh] overflow-y-auto">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl sticky top-0 z-10">
                                <h3 class="text-base sm:text-lg font-semibold text-white">Edit Product</h3>
                            </div>
                            <form @submit.prevent="submitEditProduct" class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Product Name</label>
                                        <input v-model="editForm.name" type="text" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Description</label>
                                        <textarea v-model="editForm.description" rows="2" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Price (₱)</label>
                                        <input v-model="editForm.price" type="number" step="0.01" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Stock</label>
                                        <input v-model="editForm.stock" type="number" required min="0" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Category</label>
                                        <select v-model="editForm.category" required class="w-full px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                            <option value="">Select Category</option>
                                            <option v-for="cat in categoryOptions" :key="cat" :value="cat">{{ cat }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5 sm:mb-2">Change Image</label>
                                        <input type="file" @change="handleEditImage" accept="image/*" class="w-full px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                        <p class="text-xs text-slate-500 mt-1">JPG, PNG, or WEBP (max 5MB)</p>
                                    </div>
                                    <div v-if="editImagePreview" class="sm:col-span-2">
                                        <img :src="editImagePreview" class="w-full h-32 sm:h-48 object-cover rounded-lg border-2 border-slate-200" alt="Preview">
                                    </div>
                                    <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                        <label class="flex items-center gap-2">
                                            <input v-model="editForm.is_featured" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Featured (Max 3)</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input v-model="editForm.is_active" type="checkbox" class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                                            <span class="text-xs sm:text-sm font-medium text-slate-700">Active</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-200">
                                    <button type="button" @click="showEditModal = false" class="w-full sm:w-auto px-4 py-2 text-sm sm:text-base text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">Cancel</button>
                                    <button type="submit" :disabled="editForm.processing" class="w-full sm:w-auto px-6 py-2 text-sm sm:text-base bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                                    </button>
                                </div>
                            </form>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <TransitionRoot :show="showDeleteModal" as="template">
        <Dialog @close="showDeleteModal = false" class="relative z-50">
            <TransitionChild enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" />
            </TransitionChild>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild enter="ease-out duration-300" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="ease-in duration-200" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-md bg-white rounded-xl shadow-2xl">
                            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4 rounded-t-xl">
                                <h3 class="text-lg font-semibold text-white">Delete Product</h3>
                            </div>
                            <div class="p-6">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-semibold text-slate-900 mb-2">Are you sure?</h4>
                                        <p class="text-slate-600 mb-3">Do you really want to delete "<span class="font-semibold">{{ productToDelete?.name }}</span>"?</p>
                                        <p class="text-sm text-slate-500">This action cannot be undone. The product and its image will be permanently removed.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-slate-50 rounded-b-xl flex items-center justify-end gap-3">
                                <button type="button" @click="showDeleteModal = false" class="px-4 py-2 text-slate-700 bg-white hover:bg-slate-100 rounded-lg font-medium border border-slate-300 transition-colors">Cancel</button>
                                <button type="button" @click="executeDelete" :disabled="deleteProcessing" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors disabled:opacity-50">
                                    {{ deleteProcessing ? 'Deleting...' : 'Delete' }}
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <ProductDetailModal
        :show="showPreviewModal"
        :product="productToPreview"
        :is-admin="true"
        @close="showPreviewModal = false"
    />
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { Dialog, DialogPanel, TransitionRoot, TransitionChild } from '@headlessui/vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import ProductDetailModal from '@/Components/ProductDetailModal.vue';

const props = defineProps({
    products: Array,
    featuredCount: Number,
});

const categoryOptions = [
    'Sofas',
    'Chairs',
    'Tables',
    'Beds',
    'Storage',
    'Desks',
    'Lighting',
];

const page = usePage();
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showPreviewModal = ref(false);
const productToDelete = ref(null);
const productToPreview = ref(null);
const deleteProcessing = ref(false);
const toast = ref({ show: false, message: '', type: 'success' });
const addImagePreview = ref(null);
const editImagePreview = ref(null);

const addForm = useForm({
    name: '',
    description: '',
    price: 0,
    stock: 0,
    category: '',
    image: null,
    is_featured: false,
    is_active: true,
});

const editForm = useForm({
    id: null,
    name: '',
    description: '',
    price: 0,
    stock: 0,
    category: '',
    image: null,
    is_featured: false,
    is_active: true,
});

const totalStock = computed(() => props.products.reduce((sum, p) => sum + p.stock, 0));
const categories = computed(() => [...new Set(props.products.map(p => p.category))]);

watch(() => page.props.flash?.success, (message) => {
    if (message) toast.value = { show: true, message, type: 'success' };
});

watch(() => page.props.flash?.error, (message) => {
    if (message) toast.value = { show: true, message, type: 'error' };
});

function openAddModal() {
    addForm.reset();
    addImagePreview.value = null;
    showAddModal.value = true;
}

function openEditModal(product) {
    editForm.id = product.id;
    editForm.name = product.name;
    editForm.description = product.description;
    editForm.price = product.price;
    editForm.stock = product.stock;
    editForm.category = product.category;
    editForm.is_featured = product.is_featured;
    editForm.is_active = product.is_active;
    editImagePreview.value = product.image_url;
    showEditModal.value = true;
}

function handleAddImage(event) {
    const file = event.target.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            toast.value = { show: true, message: 'Image must be less than 5MB', type: 'error' };
            event.target.value = '';
            return;
        }
        addForm.image = file;
        const reader = new FileReader();
        reader.onload = (e) => addImagePreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
}

function handleEditImage(event) {
    const file = event.target.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            toast.value = { show: true, message: 'Image must be less than 5MB', type: 'error' };
            event.target.value = '';
            return;
        }
        editForm.image = file;
        const reader = new FileReader();
        reader.onload = (e) => editImagePreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
}

function submitAddProduct() {
    if (!addForm.image) {
        toast.value = { show: true, message: 'Please upload a product image', type: 'error' };
        return;
    }

    addForm.post('/admin/products', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
            addImagePreview.value = null;
            toast.value = { show: true, message: 'Product created successfully', type: 'success' };
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.value = { show: true, message: firstError || 'Failed to create product', type: 'error' };
        },
    });
}

function submitEditProduct() {
    editForm.post(`/admin/products/${editForm.id}`, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            editImagePreview.value = null;
            toast.value = { show: true, message: 'Product updated successfully', type: 'success' };
        },
        onError: (errors) => {
            const firstError = Object.values(errors)[0];
            toast.value = { show: true, message: firstError || 'Failed to update product', type: 'error' };
        },
    });
}

function previewProduct(product) {
    productToPreview.value = product;
    showPreviewModal.value = true;
}

function confirmDelete(product) {
    productToDelete.value = product;
    showDeleteModal.value = true;
}

function executeDelete() {
    if (productToDelete.value) {
        deleteProcessing.value = true;
        router.delete(`/admin/products/${productToDelete.value.id}`, {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => {
                showDeleteModal.value = false;
                productToDelete.value = null;
                deleteProcessing.value = false;
            }
        });
    }
}
</script>
