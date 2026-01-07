import { defineStore } from 'pinia';
import { ref } from 'vue';
import { api } from 'boot/axios';

export const useFilterStore = defineStore('filter', () => {
    const isDrawerOpen = ref(false);
    const categories = ref([]);
    const selectedCategory = ref('all');
    const priceRange = ref({ min: 0, max: 500000 });
    const sortBy = ref('default');

    const sortOptions = [
        { label: 'Featured', value: 'default' },
        { label: 'Price: Low to High', value: 'price_asc' },
        { label: 'Price: High to Low', value: 'price_desc' },
        { label: 'Newest Arrivals', value: 'newest' }
    ];

    const toggleDrawer = () => {
        isDrawerOpen.value = !isDrawerOpen.value;
    };

    const fetchCategories = async () => {
        // Avoid re-fetching if already loaded? Or maybe refresh? 
        // Let's safe fetch.
        if (categories.value.length > 0) return;

        try {
            const response = await api.get('/categories');
            categories.value = response.data;
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };

    const resetFilters = () => {
        selectedCategory.value = 'all';
        priceRange.value = { min: 0, max: 500000 };
        sortBy.value = 'default';
    };

    return {
        isDrawerOpen,
        categories,
        selectedCategory,
        priceRange,
        sortBy,
        sortOptions,
        toggleDrawer,
        fetchCategories,
        resetFilters
    };
});
