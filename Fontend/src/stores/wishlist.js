import { defineStore } from 'pinia';
import { computed, ref, watch } from 'vue';

export const useWishlistStore = defineStore('wishlist', () => {
    // Load from localStorage if available
    const savedItems = localStorage.getItem('nurya_wishlist');
    const items = ref(savedItems ? JSON.parse(savedItems) : []);

    // Persist to localStorage whenever items change
    watch(items, (newItems) => {
        localStorage.setItem('nurya_wishlist', JSON.stringify(newItems));
    }, { deep: true });

    const addItem = (product) => {
        const existing = items.value.find((i) => i.id === product.id);
        if (!existing) {
            items.value.push(product);
        }
    };

    const removeItem = (productId) => {
        const index = items.value.findIndex((i) => i.id === productId);
        if (index > -1) {
            items.value.splice(index, 1);
        }
    };

    const isInWishlist = (productId) => {
        return items.value.some((i) => i.id === productId);
    };

    const toggleItem = (product) => {
        if (isInWishlist(product.id)) {
            removeItem(product.id);
            return false; // Removed
        } else {
            addItem(product);
            return true; // Added
        }
    };

    const clearWishlist = () => {
        items.value = [];
    };

    const itemCount = computed(() => items.value.length);

    return {
        items,
        addItem,
        removeItem,
        toggleItem,
        isInWishlist,
        clearWishlist,
        itemCount,
    };
});
