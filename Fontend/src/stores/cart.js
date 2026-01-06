import { defineStore } from 'pinia';
import { computed, ref, watch } from 'vue';

export const useCartStore = defineStore('cart', () => {
  // Load from localStorage if available
  const savedItems = localStorage.getItem('nurya_cart');
  const items = ref(savedItems ? JSON.parse(savedItems) : []);

  // Persist to localStorage whenever items change
  watch(items, (newItems) => {
    localStorage.setItem('nurya_cart', JSON.stringify(newItems));
  }, { deep: true });

  const addItem = (product, options = {}) => {
    // Create a unique key for the cart item based on product ID + options
    // For simplicity in this version, we will just match by ID, but you can extend this
    const existing = items.value.find((i) => i.id === product.id);

    // Default quantity to 1 if not provided in options
    const quantityToAdd = options.quantity || 1;

    // Determine price: Use price from options if provided (dynamic), else base price
    const itemPrice = options.price || product.price;

    if (existing) {
      existing.quantity += quantityToAdd;
      // Update options if provided (last win logic for simple ID matching)
      if (options.size) existing.size = options.size;
      if (options.color) existing.color = options.color;
      // Update price if it changed (though usually price is tied to size, so this acts as update)
      existing.price = itemPrice;
    } else {
      items.value.push({
        ...product,
        price: itemPrice, // Store the specific price for this item configuration
        quantity: quantityToAdd,
        size: options.size || null,
        color: options.color || null
      });
    }
  };

  const updateQuantity = (productId, quantity) => {
    const item = items.value.find((i) => i.id === productId);
    if (item) {
      item.quantity = Math.max(0, quantity);
      if (item.quantity === 0) {
        removeItem(productId);
      }
    }
  };

  const removeItem = (productId) => {
    const index = items.value.findIndex((i) => i.id === productId);
    if (index > -1) {
      items.value.splice(index, 1);
    }
  };

  const clearCart = () => {
    items.value = [];
  };

  const totalAmount = computed(() => {
    return items.value.reduce((acc, item) => {
      const price = parseFloat(item.price) || 0;
      const qty = parseInt(item.quantity) || 0;
      return acc + (price * qty);
    }, 0);
  });

  const itemCount = computed(() => {
    return items.value.reduce((acc, item) => acc + (parseInt(item.quantity) || 0), 0);
  });

  return {
    items,
    addItem,
    updateQuantity,
    removeItem,
    clearCart,
    totalAmount,
    itemCount,
  };
});
