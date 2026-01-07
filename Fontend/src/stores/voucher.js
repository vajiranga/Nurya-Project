import { defineStore } from 'pinia';
import { api } from 'boot/axios';

export const useVoucherStore = defineStore('voucher', {
    state: () => ({
        vouchers: [],
        loading: false
    }),

    getters: {
        activeVouchers: (state) => state.vouchers,
        hasVouchers: (state) => state.vouchers.length > 0
    },

    actions: {
        async fetchVouchers() {
            this.loading = true;
            try {
                const response = await api.get('/vouchers/public');
                if (response.data && response.data.length > 0) {
                    this.vouchers = response.data;
                } else {
                    this.vouchers = [
                        { id: 991, title: 'New Year Special', code: 'NURYA2026', discount_type: 'percentage', discount_value: 10, is_active: true },
                        { id: 992, title: 'Wedding Collection Offer', code: 'WEDDING24', discount_type: 'fixed', discount_value: 5000, is_active: true }
                    ];
                }
            } catch (error) {
                console.error('Error fetching vouchers:', error);
                this.vouchers = [
                    { id: 991, title: 'New Year Special', code: 'NURYA2026', discount_type: 'percentage', discount_value: 10, is_active: true },
                    { id: 992, title: 'Wedding Collection Offer', code: 'WEDDING24', discount_type: 'fixed', discount_value: 5000, is_active: true }
                ];
            } finally {
                this.loading = false;
            }
        }
    }
});
