import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

export const useCartStore = defineStore('cart', () => {
  const items = ref([]);

  const addItem = (product) => {
    const existing = items.value.find((i) => i.id === product.id);
    if (existing) {
      existing.quantity++;
    } else {
      items.value.push({ ...product, quantity: 1 });
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

  const total = computed(() => {
    return items.value.reduce((acc, item) => acc + item.price * item.quantity, 0);
  });

  const itemCount = computed(() => {
    return items.value.reduce((acc, item) => acc + item.quantity, 0);
  });

  return {
    items,
    addItem,
    removeItem,
    clearCart,
    total,
    itemCount,
  };
});
