<template>
  <q-page class="vouchers-page q-pa-md">
    <div class="container">
      <!-- Section Header -->
      <div class="text-center q-mb-xl" data-aos="fade-down">
        <h1 class="text-h3 font-playfair text-gold q-mb-sm">Exclusive Vouchers</h1>
        <p class="text-subtitle1 text-grey-7">Grab these limited-time offers for your next purchase</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="row justify-center q-py-xl">
        <q-spinner-orbit color="gold" size="4em" />
      </div>

      <!-- Vouchers Grid -->
      <div v-else class="row q-col-gutter-lg justify-center">
        <div 
          v-for="(voucher, index) in vouchers" 
          :key="voucher.id" 
          class="col-12 col-md-6 col-lg-4"
          data-aos="fade-up"
          :data-aos-delay="index * 100"
        >
          <q-card class="voucher-card bg-dark text-white">
            <q-card-section class="q-pa-lg relative-position">
              <!-- Decorative Pattern -->
              <div class="voucher-pattern"></div>
              
              <div class="row items-center no-wrap">
                <div class="col">
                  <!-- Discount Badge -->
                  <div class="text-h3 text-gold text-weight-bold font-playfair q-mb-sm">
                    {{ formatDiscount(voucher) }}
                  </div>
                  <div class="text-subtitle2 text-grey-4 text-uppercase tracking-wide q-mb-md">OFF</div>
                  
                  <div class="text-h5 font-playfair q-mb-sm">{{ voucher.title }}</div>
                  <div class="text-caption text-grey-5 q-mb-lg">
                    Valid until {{ formatDate(voucher.expires_at) }}
                  </div>

                  <!-- Code Section -->
                  <div class="code-box row items-center justify-between q-pa-sm bg-white-translucent">
                    <div class="text-weight-bold letter-spacing-1 q-px-sm">{{ voucher.code }}</div>
                    <q-btn 
                      flat 
                      round 
                      color="white" 
                      icon="content_copy" 
                      size="sm"
                      @click="copyCode(voucher.code)"
                    >
                      <q-tooltip>Copy Code</q-tooltip>
                    </q-btn>
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="!loading && vouchers.length === 0" class="text-center q-py-xl text-grey-7">
        <q-icon name="confirmation_number" size="4em" color="grey-4" />
        <p class="text-h6 q-mt-md">No active vouchers at the moment.</p>
        <p>Please check back later!</p>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { api } from 'boot/axios';
import { useQuasar, date } from 'quasar';

const $q = useQuasar();
const vouchers = ref([]);
const loading = ref(true);

onMounted(async () => {
  try {
    const response = await api.get('/vouchers');
    vouchers.value = response.data;
  } catch (error) {
    console.error('Error fetching vouchers:', error);
    $q.notify({
      message: 'Failed to load vouchers',
      color: 'negative',
      icon: 'error'
    });
  } finally {
    loading.value = false;
  }
});

const formatDiscount = (voucher) => {
  if (voucher.discount_type === 'percentage') {
    return `${parseInt(voucher.discount_value)}%`;
  }
  return `LKR ${parseInt(voucher.discount_value)}`;
};

const formatDate = (dateString) => {
  if (!dateString) return 'Forever';
  return date.formatDate(dateString, 'MMM D, YYYY');
};

const copyCode = (code) => {
  navigator.clipboard.writeText(code);
  $q.notify({
    message: 'Coupon code copied to clipboard!',
    color: 'positive',
    icon: 'check_circle',
    position: 'top'
  });
};
</script>

<style scoped>
.vouchers-page {
  min-height: 80vh;
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
}

body.body--dark .vouchers-page {
  background: linear-gradient(135deg, #121212 0%, #000000 100%);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}

.text-gold {
  color: #D4AF37;
}

.voucher-card {
  border-radius: 20px;
  overflow: hidden;
  transition: transform 0.3s ease;
  border: 1px solid rgba(212, 175, 55, 0.3);
  position: relative;
}

.voucher-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 40px rgba(212, 175, 55, 0.2);
}

.voucher-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 150px;
  height: 100%;
  background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
  background-size: 10px 10px;
  opacity: 0.5;
  mask-image: linear-gradient(to left, black, transparent);
}

.bg-white-translucent {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  border: 1px dashed rgba(255, 255, 255, 0.3);
}

.letter-spacing-1 {
  letter-spacing: 1px;
}
</style>
