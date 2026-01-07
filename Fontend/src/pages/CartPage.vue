<template>
  <q-page class="cart-page q-pa-lg">
    <div class="container container-narrow">
      <div class="row items-center q-mb-xl" data-aos="fade-down">
        <h1 class="page-title q-ma-none">Your Shopping Cart</h1>
        <q-space />
        <q-btn flat color="grey-7" icon="arrow_back" label="Continue Shopping" to="/" />
      </div>

      <div v-if="cartStore.items.length === 0" class="empty-cart-section text-center q-pa-xl" data-aos="zoom-in">
        <div class="empty-icon-wrapper q-mb-lg">
          <q-icon name="shopping_bag" size="120px" color="gold-light" />
        </div>
        <h2 class="text-h4 text-weight-light">Your cart is currently empty</h2>
        <p class="text-grey-7 q-mb-xl">Discover our unique collections and start your journey of elegance.</p>
        <q-btn 
          unelevated 
          rounded 
          size="lg" 
          color="gold" 
          label="Explore Collections" 
          to="/" 
          class="explore-btn" 
        />
      </div>

      <div v-else class="row q-col-gutter-xl">
        <!-- Cart Items List -->
        <div class="col-12 col-md-8">
          <div v-for="item in cartStore.items" :key="item.id" class="cart-item-card q-mb-md" data-aos="fade-up">
            <div class="row no-wrap items-center">
              <div class="item-img-container">
                <q-img
                  :src="getImage(item.images_json)"
                  class="cart-item-img"
                  spinner-color="gold"
                />
              </div>
              
              <div class="col q-px-lg">
                <div class="row justify-between items-start">
                  <div>
                    <h3 class="item-name q-mt-none q-mb-xs">{{ item.name }}</h3>
                    <div class="item-price text-gold">LKR {{ formatPrice(item.price) }}</div>
                  </div>
                  <q-btn flat round color="grey-4" icon="close" size="sm" @click="cartStore.removeItem(item.id)">
                     <q-tooltip>Remove Item</q-tooltip>
                  </q-btn>
                </div>

                <div class="row items-center q-mt-md">
                  <div class="quantity-controls row items-center no-wrap">
                    <q-btn flat dense round icon="remove" size="sm" @click="cartStore.updateQuantity(item.id, item.quantity - 1)" />
                    <div class="q-px-md text-weight-bold">{{ item.quantity }}</div>
                    <q-btn flat dense round icon="add" size="sm" @click="cartStore.updateQuantity(item.id, item.quantity + 1)" />
                  </div>
                  <q-space />
                  <div class="item-subtotal text-weight-bold">
                    LKR {{ formatPrice(item.price * item.quantity) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="col-12 col-md-4">
          <q-card class="summary-card" flat bordered>
            <q-card-section>
              <div class="text-h6 text-gold font-playfair q-mb-md">Order Summary</div>
              
              <div class="row justify-between q-mb-sm">
                <span>Subtotal</span>
                <span class="text-weight-bold">LKR {{ formatPrice(cartStore.totalAmount) }}</span>
              </div>
              
              <!-- Coupon Code Section -->
              <div class="row q-my-md">
                 <q-input 
                   outlined 
                   dense 
                   v-model="couponCode" 
                   placeholder="Coupon Code" 
                   class="col"
                   :disable="discount > 0"
                 />
                 <q-btn 
                   unelevated 
                   color="dark" 
                   label="Apply" 
                   class="q-ml-sm" 
                   @click="applyCoupon"
                   :disable="discount > 0"
                 />
              </div>

              <div class="row justify-between q-mb-sm text-green" v-if="discount > 0">
                <span>Discount (10% OFF)</span>
                <span>- LKR {{ formatPrice(discount) }}</span>
              </div>

              <div class="row justify-between q-mb-sm text-grey-7">
                <span>Shipping Fee</span>
                <span>LKR {{ formatPrice(currentShippingFee) }}</span>
              </div>
              
              <q-separator class="q-my-md" />
              
              <div class="row justify-between items-center">
                <span class="text-h6">Total</span>
                <span class="text-h5 text-gold text-weight-bold">LKR {{ formatPrice(cartStore.totalAmount - discount + currentShippingFee) }}</span>
              </div>
            </q-card-section>

            <q-card-actions class="q-pa-md">
              <q-btn
                unelevated
                rounded
                color="green-7"
                class="full-width q-mb-sm q-py-sm text-weight-bold"
                size="lg"
                icon="whatsapp"
                no-caps
                label="Place Order"
                @click="showDetailsDialog = true"
              />
              <q-btn flat color="grey-7" label="Continue Shopping" to="/" class="full-width" />
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </div>

    <!-- Customer Details Dialog -->
    <q-dialog v-model="showDetailsDialog" persistent transition-show="scale" transition-hide="scale">
      <q-card class="order-dialog-card q-pa-lg">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h5 font-playfair text-gold">Checkout Details</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section class="q-pt-md">
          <p class="text-grey-7">Please provide your details to process the order via WhatsApp.</p>
          <div>
            <!-- Full Name -->
            <q-input
              v-model="form.fullName"
              label="Full Name *"
              outlined
              bg-color="white"
              color="gold"
              label-color="grey-9"
              input-class="text-black"
              :rules="[val => !!val || 'Full Name is required']"
              hide-bottom-space
              class="q-mb-md"
            />

            <!-- Mobile Numbers -->
            <div class="row q-col-gutter-md q-mb-md">
              <div class="col-12 col-md-6">
                <q-input
                  v-model="form.primaryPhone"
                  label="Primary Mobile Number *"
                  outlined
                  mask="##########"
                  bg-color="white"
                  color="gold"
                  label-color="grey-9"
                  input-class="text-black"
                  :rules="[val => !!val || 'Primary Mobile is required']"
                  hide-bottom-space
                />
              </div>
              <div class="col-12 col-md-6">
                <q-input
                  v-model="form.secondaryPhone"
                  label="Secondary Mobile (Optional)"
                  outlined
                  mask="##########"
                  bg-color="white"
                  color="gold"
                  label-color="grey-9"
                  input-class="text-black"
                  hide-bottom-space
                />
              </div>
            </div>

            <!-- Address Section -->
            <q-input
              v-model="form.addressLine1"
              label="Address Line 1 *"
              outlined
              bg-color="white"
              color="gold"
              label-color="grey-9"
              input-class="text-black"
              :rules="[val => !!val || 'Address Line 1 is required']"
              hide-bottom-space
              class="q-mb-md"
            />
            
            <q-input
              v-model="form.addressLine2"
              label="Address Line 2 (Optional)"
              outlined
              bg-color="white"
              color="gold"
              label-color="grey-9"
              input-class="text-black"
              hide-bottom-space
              class="q-mb-md"
            />

            <div class="row q-col-gutter-md q-mb-md">
              <div class="col-12 col-md-6">
                 <q-input
                  v-model="form.city"
                  label="City *"
                  outlined
                  bg-color="white"
                  color="gold"
                  label-color="grey-9"
                  input-class="text-black"
                  :rules="[val => !!val || 'City is required']"
                  hide-bottom-space
                />
              </div>
              <div class="col-12 col-md-6">
                 <q-input
                  v-model="form.district"
                  label="District *"
                  outlined
                  bg-color="white"
                  color="gold"
                  label-color="grey-9"
                  input-class="text-black"
                  :rules="[val => !!val || 'District is required']"
                  hide-bottom-space
                />
              </div>
            </div>

            <q-input
              v-model="form.postalCode"
              label="Postal Code *"
              outlined
              bg-color="white"
              color="gold"
              label-color="grey-9"
              input-class="text-black"
              mask="#####"
              :rules="[val => !!val || 'Postal Code is required']"
              hide-bottom-space
              class="q-mb-md"
            />

            <!-- Special Notes -->
            <q-input
              v-model="form.specialNotes"
              label="Special Notes (Optional)"
              outlined
              type="textarea"
              rows="3"
              bg-color="white"
              color="gold"
              label-color="grey-9"
              input-class="text-black"
              hide-bottom-space
            />

            <!-- Payment Method -->
            <div class="q-mt-lg">
              <div class="text-subtitle1 q-mb-sm text-gold font-family-playfair">Payment Method</div>
              <q-list class="q-gutter-y-sm">
                
                <!-- COD Option -->
                <q-item 
                  tag="label" 
                  v-ripple 
                  class="rounded-borders q-pa-md transition-all"
                  :class="form.paymentMethod === 'cod' ? 'bg-amber-1 border-gold' : 'bg-white'"
                >
                  <q-item-section avatar>
                    <q-radio v-model="form.paymentMethod" val="cod" color="gold" size="md" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-weight-bold text-black text-subtitle1">Cash On Delivery (COD)</q-item-label>
                    <q-item-label caption class="text-grey-8">Pay when you receive the package</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-badge color="grey-8" label="LKR 400" />
                  </q-item-section>
                </q-item>

                <!-- Store Pickup Option -->
                <q-item 
                  tag="label" 
                  v-ripple 
                  class="rounded-borders q-pa-md transition-all"
                  :class="form.paymentMethod === 'pickup' ? 'bg-amber-1 border-gold' : 'bg-white'"
                >
                  <q-item-section avatar>
                    <q-radio v-model="form.paymentMethod" val="pickup" color="gold" size="md" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-weight-bold text-black text-subtitle1">Pick up from Store</q-item-label>
                    <q-item-label caption class="text-grey-8">Come to our store and collect</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-badge color="green" label="FREE" />
                  </q-item-section>
                </q-item>

                <!-- Bank Transfer Option -->
                <q-item 
                  tag="label" 
                  v-ripple 
                  class="rounded-borders q-pa-md transition-all"
                  :class="form.paymentMethod === 'bank_transfer' ? 'bg-amber-1 border-gold' : 'bg-white'"
                >
                  <q-item-section avatar>
                    <q-radio v-model="form.paymentMethod" val="bank_transfer" color="gold" size="md" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label class="text-weight-bold text-black text-subtitle1">Bank Transfer</q-item-label>
                    <q-item-label caption class="text-grey-8">Transfer to our bank account</q-item-label>
                  </q-item-section>
                </q-item>

              </q-list>

              <!-- Bank Transfer Note -->
              <transition name="fade">
                  <div v-if="form.paymentMethod === 'bank_transfer'" class="q-mt-sm bg-red-1 q-pa-md rounded-borders text-center text-red-9 border-red">
                    <q-icon name="warning" size="sm" class="q-mr-sm" />
                    <b>Action Required:</b> Please wait for our confirmation on WhatsApp before transferring funds.
                  </div>
              </transition>
            </div>

          </div>
        </q-card-section>

        <q-card-actions align="center" class="q-pb-lg">
          <q-btn 
            unelevated 
            rounded 
            color="green-6" 
            icon="send" 
            label="Send Order to WhatsApp" 
            size="lg"
            class="full-width whatsapp-submit-btn"
            @click="submitOrder"
            :disable="!isValid"
          />
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

const couponCode = ref('');
const discount = ref(0);
// Base shipping fee
const baseShippingFee = 400;

const showDetailsDialog = ref(false);
const form = ref({
  fullName: '',
  primaryPhone: '',
  secondaryPhone: '',
  addressLine1: '',
  addressLine2: '',
  city: '',
  district: '',
  postalCode: '',
  specialNotes: '',
  paymentMethod: 'cod'
});

// Computed Shipping Fee
const currentShippingFee = computed(() => {
  return form.value.paymentMethod === 'pickup' ? 0 : baseShippingFee;
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LK').format(price);
};

const getImage = (images) => {
  if (Array.isArray(images) && images.length > 0) return images[0];
  
  const dummyImages = [
    '/images/products/ring1.png',
    '/images/products/necklace1.png',
    '/images/products/bracelet1.png',
    '/images/products/earrings1.png'
  ];
  return dummyImages[Math.floor(Math.random() * dummyImages.length)];
};

const applyCoupon = () => {
  if (couponCode.value.trim().toUpperCase() === 'NURYA2026') {
    discount.value = cartStore.totalAmount * 0.10;
    $q.notify({
      message: 'Coupon Applied Successfully! 10% Discount.',
      color: 'positive',
      icon: 'check_circle',
      position: 'top'
    });
  } else {
    $q.notify({
      message: 'Invalid Coupon Code',
      color: 'negative',
      icon: 'error',
      position: 'top'
    });
  }
};


const isValid = computed(() => {
  if (!form.value) return false;
  return (form.value.fullName?.length > 2) && 
         (form.value.primaryPhone?.length >= 9) && 
         (form.value.addressLine1?.length > 3) &&
         (form.value.city?.length > 2) &&
         (form.value.district?.length > 2) &&
         (form.value.postalCode?.length > 2);
});

const submitOrder = async () => {
  console.log('submitOrder called');
  
  if (!api) {
    console.error('API client is not defined!');
    $q.notify({ type: 'negative', message: 'System Error: API Client missing' });
    return;
  }

  try {
    $q.loading.show({ message: 'Preparing your order...' });

    const fullAddress = [
      form.value.addressLine1,
      form.value.addressLine2,
      form.value.city,
      form.value.district,
      form.value.postalCode
    ].filter(Boolean).join(', ');

    const payload = {
      customer_name: form.value.fullName,
      phone: form.value.primaryPhone,
      secondary_phone: form.value.secondaryPhone,
      address: fullAddress,
      special_notes: form.value.specialNotes,
      payment_method: form.value.paymentMethod,
      shipping_fee: currentShippingFee.value,
      coupon_code: discount.value > 0 ? couponCode.value : null,
      discount_amount: discount.value,
      items: cartStore.items.map(i => ({
        id: i.id,
        name: i.name,
        price: parseFloat(i.price) || 0,
        quantity: parseInt(i.quantity) || 1,
        size: i.size || ''
      }))
    };
    
    console.log('Order Payload:', payload);

    const res = await api.post('/order/whatsapp', payload);
    console.log('API Response:', res);
    
    if (res.data && res.data.success) {
      $q.notify({
        type: 'positive',
        message: 'Order initiated! Redirecting to WhatsApp...',
        icon: 'check_circle'
      });
      
      cartStore.clearCart();
      showDetailsDialog.value = false;
      
      // Small delay to ensure notify is seen
      setTimeout(() => {
        window.open(res.data.whatsapp_link, '_blank');
      }, 500);
    } else {
      throw new Error('API returned success:false or invalid response');
    }
  } catch (error) {
    console.error('Order Submission Error:', error);
    $q.notify({
      type: 'negative',
      message: 'Failed to process order. Please try again or contact support.',
      icon: 'error'
    });
  } finally {
    $q.loading.hide();
  }
};
</script>

<style lang="scss" scoped>
.cart-page {
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
  min-height: 80vh;
}

body.body--dark .cart-page {
  background: #121212;
}

.container-narrow {
  max-width: 1200px;
  margin: 0 auto;
}

.page-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: #1d1d1d;
}

body.body--dark .page-title {
  color: white;
}

.cart-item-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
  border: 1px solid rgba(0,0,0,0.02);
  transition: transform 0.3s ease;
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  }
}

body.body--dark .cart-item-card {
  background: rgba(255,255,255,0.05);
  border-color: rgba(255,255,255,0.1);
}

.item-img-container {
  width: 140px;
  height: 140px;
  background: #f9f9f9;
}

.cart-item-img {
  height: 100%;
  width: 100%;
  object-fit: cover;
}

.item-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem;
  font-weight: 700;
}

.item-price {
  font-size: 1.1rem;
  font-weight: 600;
}

.quantity-controls {
  background: #f0f0f0;
  border-radius: 30px;
  padding: 2px;
}

body.body--dark .quantity-controls {
  background: rgba(255,255,255,0.1);
}

.summary-card {
  background: white;
  border-radius: 24px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
  position: sticky;
  top: 100px;
}

body.body--dark .summary-card {
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
}

.summary-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem;
  border-bottom: 2px solid #D4AF37;
  display: inline-block;
  padding-bottom: 5px;
}

.checkout-btn {
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 100%) !important;
  color: white;
  font-weight: 700;
}

.order-dialog-card {
  border-radius: 24px;
  min-width: 450px;
}

.whatsapp-submit-btn {
  font-weight: 700;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}

.text-gold {
  color: #D4AF37;
}

.empty-icon-wrapper {
  background: rgba(212, 175, 55, 0.05);
  width: 200px;
  height: 200px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

@media (max-width: 600px) {
  .item-img-container {
    width: 100px;
    height: 100px;
  }
  
  .item-name {
    font-size: 1.1rem;
  }

  .order-dialog-card {
    min-width: 90vw;
  }
}

.border-gold {
  border: 1px solid #D4AF37;
}

.border-red {
  border: 1px solid #e57373;
}

.transition-all {
  transition: all 0.3s ease;
}

.font-family-playfair {
  font-family: 'Playfair Display', serif;
}
</style>
