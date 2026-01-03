<template>
  <q-page class="q-pa-md bg-grey-1">
    <div class="row justify-center">
      <div class="col-12 col-md-8">
        <h3 class="text-primary q-my-md" style="font-family: 'Playfair Display'">Your Shopping Cart</h3>

        <div v-if="cartStore.items.length === 0" class="text-center q-pa-xl">
          <q-icon name="shopping_cart" size="100px" color="grey-4" />
          <div class="text-h6 text-grey-6 q-mt-md">Your cart is empty</div>
          <q-btn to="/" label="Start Shopping" color="primary" flat class="q-mt-sm" />
        </div>

        <div v-else>
          <q-card class="q-mb-md" v-for="item in cartStore.items" :key="item.id">
            <q-card-section horizontal>
              <q-img
                class="col-3"
                :src="getImage(item.images_json)"
                style="height: 100px; width: 100px"
              />
              <q-card-section class="col">
                <div class="text-h6">{{ item.name }}</div>
                <div class="text-subtitle2 text-grey">Unit: LKR {{ formatPrice(item.price) }}</div>
                <div class="row items-center q-mt-sm">
                   <div class="text-bold q-mr-md">Qty: {{ item.quantity }}</div>
                   <div class="text-bold text-primary">Subtotal: LKR {{ formatPrice(item.price * item.quantity) }}</div>
                </div>
              </q-card-section>

              <q-card-actions vertical class="justify-center">
                <q-btn flat round color="negative" icon="delete" @click="cartStore.removeItem(item.id)" />
              </q-card-actions>
            </q-card-section>
          </q-card>

          <q-card class="q-mt-lg">
            <q-card-section>
              <div class="row justify-between items-center">
                <div class="text-h5">Total Amount</div>
                <div class="text-h4 text-primary text-weight-bold">LKR {{ formatPrice(cartStore.total) }}</div>
              </div>
            </q-card-section>
            <q-card-actions align="right" class="q-pa-md">
              <q-btn flat label="Clear Cart" color="negative" @click="cartStore.clearCart" />
              <q-btn 
                unelevated 
                size="lg" 
                color="green-6" 
                icon="wechat" 
                label="Place Order via WhatsApp" 
                @click="placeOrder" 
              />
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </div>
    
    <!-- Customer Details Dialog -->
    <q-dialog v-model="showDetailsDialog">
      <q-card style="min-width: 350px">
        <q-card-section>
          <div class="text-h6">Enter Your Details</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-input dense v-model="form.name" label="Your Name" autofocus />
          <q-input dense v-model="form.phone" label="Phone Number" mask="##########" />
          <q-input dense v-model="form.address" label="Delivery Address" type="textarea" autogrow />
        </q-card-section>

        <q-card-actions align="right" class="text-primary">
          <q-btn flat label="Cancel" v-close-popup />
          <q-btn flat label="Send to WhatsApp" @click="submitOrder" :disable="!isValid" />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useCartStore } from 'stores/cart';
import { api } from 'boot/axios';
import { useQuasar } from 'quasar';

const cartStore = useCartStore();
const $q = useQuasar();

const showDetailsDialog = ref(false);
const form = ref({
  name: '',
  phone: '',
  address: ''
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LK').format(price);
};

const getImage = (images) => {
  if (Array.isArray(images) && images.length > 0) return images[0];
  return 'https://via.placeholder.com/100x100?text=Nurya';
};

const placeOrder = () => {
    showDetailsDialog.value = true;
};

const isValid = computed(() => {
    return form.value.name && form.value.phone && form.value.address;
})

const submitOrder = async () => {
    try {
        const payload = {
            customer_name: form.value.name,
            phone: form.value.phone,
            address: form.value.address,
            items: cartStore.items.map(i => ({
                name: i.name,
                price: parseFloat(i.price),
                quantity: i.quantity
            }))
        };

        const res = await api.post('/order/whatsapp', payload);
        
        if (res.data.success) {
            cartStore.clearCart();
            showDetailsDialog.value = false;
            
            // Redirect to WhatsApp
             window.open(res.data.whatsapp_link, '_blank');
        }

    } catch (error) {
        console.error(error);
        $q.notify({
            type: 'negative',
            message: 'Failed to process order.'
        });
    }
}
</script>
