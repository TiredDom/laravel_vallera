import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export function useCart({ showToast, isAuthenticated, isAuthOpen }) {
    const isProcessing = ref(false);

    function addToCart(product, onSuccess, onError) {
        if (!isAuthenticated.value) {
            isAuthOpen.value = true;
            return;
        }
        const normalized = {
            id: product.id || product.product_id,
            name: product.name,
            price: product.price,
            stock: product.stock,
            category: product.category
        };
        if (!normalized.id || !normalized.name || !normalized.price || normalized.stock <= 0) {
            showToast('Invalid product or out of stock', 'error');
            return;
        }
        isProcessing.value = true;
        const payload = {
            product_id: normalized.id,
            name: normalized.name,
            price: normalized.price,
            quantity: 1,
            category: normalized.category
        };
        router.post('/cart', payload, {
            preserveScroll: true,
            onSuccess: () => {
                showToast('Product added to cart!', 'success');
                isProcessing.value = false;
                if (onSuccess) onSuccess();
            },
            onError: (errors) => {
                showToast(errors?.error || 'Failed to add to cart', 'error');
                isProcessing.value = false;
                if (onError) onError(errors);
            }
        });
    }

    return {
        addToCart,
        isProcessing
    };
}
