<template>
  <q-page class="product-detail-page q-pb-xl">
    <div v-if="loading" class="flex flex-center loading-container">
      <q-spinner-orbit color="gold" size="5em" />
    </div>

    <div v-else-if="product" class="container q-pt-xl">
      <div class="row q-col-gutter-xl">
        <!-- Product Image Gallery -->
        <div class="col-12 col-md-6" data-aos="fade-right">
          <q-carousel
            animated
            v-model="slide"
            arrows
            navigation
            infinite
            height="500px"
            class="product-carousel shadow-10"
          >
            <q-carousel-slide
              v-for="(img, index) in images"
              :key="index"
              :name="index"
              :img-src="img"
              class="product-image-slide"
            />
          </q-carousel>
        </div>

        <!-- Product Details -->
        <div class="col-12 col-md-6" data-aos="fade-left">
          <div class="product-info-card q-pa-lg relative-position">
            <!-- Wishlist Button (Start) - Repositioned -->
            <q-btn
              flat
              round
              :color="wishlistStore.isInWishlist(product.id) ? 'pink' : 'gold'"
              :icon="wishlistStore.isInWishlist(product.id) ? 'favorite' : 'favorite_border'"
              size="lg"
              class="absolute-top-right q-mt-md q-mr-md"
              @click="toggleWishlist"
            />
            <!-- Wishlist Button (End) -->

            <q-chip outline color="gold" text-color="gold" label="Exclusive Collection" class="q-mb-md" />
            
            <h1 class="product-title q-mt-none q-mb-sm">{{ product.name }}</h1>
            
            <div class="row items-center q-mb-lg">
              <div class="text-h3 text-gold text-weight-bold">
                LKR {{ priceRange }}
              </div>
              <q-space />
              <q-badge :color="stockColor" class="q-pa-sm text-weight-bold">
                {{ product.stock_status.replace('_', ' ').toUpperCase() }}
              </q-badge>
            </div>

            <p class="product-description text-body1 q-mb-xl">
              {{ product.description }}
            </p>

            <!-- Product Specs -->
            <div class="specs-grid q-mb-xl">
              <div class="spec-item" v-if="product.gold_weight">
                <div class="spec-label">Gold Weight</div>
                <div class="spec-value">{{ product.gold_weight }}</div>
              </div>
              <div class="spec-item" v-if="product.karat">
                <div class="spec-label">Purity</div>
                <div class="spec-value">{{ product.karat }}K Gold</div>
              </div>
              <div class="spec-item">
                <div class="spec-label">SKU</div>
                <div class="spec-value">#{{ product.id.toString().padStart(6, '0') }}</div>
              </div>
            </div>

            <!-- Actions -->
            <div class="row q-gutter-md">
              <q-btn
                unelevated
                rounded
                icon="add_shopping_cart"
                label="Add to Cart"
                size="lg"
                class="add-to-cart-btn col"
                @click="openOptions('cart')"
              />
              <q-btn
                unelevated
                rounded
                icon="flash_on"
                label="Buy Now"
                size="lg"
                class="buy-now-btn col"
                :color="$q.dark.isActive ? 'white' : 'dark'"
                :text-color="$q.dark.isActive ? 'black' : 'white'"
                @click="openOptions('buy')"
              />
            </div>

            <!-- Trust Badges -->
            <div class="trust-indicators row q-gutter-md q-mt-xl">
              <div class="indicator">
                <q-icon name="verified" color="gold" size="sm" />
                <span>100% Authentic</span>
              </div>
              <div class="indicator">
                <q-icon name="local_shipping" color="gold" size="sm" />
                <span>Secure Delivery</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Related Products Placeholder / Section -->
      <div class="q-mt-xl q-pt-xl" v-if="relatedProducts.length > 0">
        <h2 class="section-title text-center">You May Also Like</h2>
        <div class="row q-col-gutter-md">
           <!-- Logic for related products could go here -->
        </div>
      </div>
    </div>
    
    <div v-else class="text-center q-pa-xl empty-state">
      <q-icon name="shopping_bag" size="100px" color="grey-4" />
      <div class="text-h4 text-grey-8 q-mt-md">Product Not Found</div>
      <p class="text-grey-6">We couldn't find the jewelry piece you're looking for.</p>
      <q-btn to="/" unelevated color="primary" label="Back to Collection" class="q-mt-md" />
    </div>

    <!-- Product Options Dialog -->
    <q-dialog v-model="showOptionsDialog" position="bottom">
      <q-card class="options-card">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6 text-gold font-playfair">Select Options</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section class="q-pt-md">
          <div class="row q-col-gutter-md">
            <!-- Product Summary Tiny -->
            <div class="col-12 row items-center q-mb-md">
              <q-img :src="images[0]" style="width: 60px; height: 60px; border-radius: 8px" class="q-mr-md" />
              <div>
                <div class="text-subtitle1 text-weight-bold">{{ product?.name }}</div>
                <div class="text-caption text-gold">LKR {{ formatPrice(currentPrice) }}</div>
              </div>
            </div>

            <!-- Size Selector -->
            <div class="col-12" v-if="sizes.length > 0">
              <div class="text-subtitle2 q-mb-sm text-grey-7">Select Size</div>
              <div class="row q-gutter-sm">
                <q-btn 
                  v-for="s in sizes" 
                  :key="s"
                  :outline="selectedSize !== s"
                  :unelevated="selectedSize === s"
                  :color="selectedSize === s ? 'gold' : ($q.dark.isActive ? 'gold' : 'grey-5')"
                  :text-color="selectedSize === s ? 'black' : ($q.dark.isActive ? 'gold' : 'dark')"
                  :label="s"
                  @click="selectedSize = s"
                  class="size-btn"
                />
              </div>
            </div>

            <!-- Quantity Selector -->
            <div class="col-12 q-mt-md">
              <div class="text-subtitle2 q-mb-sm text-grey-7">Quantity</div>
              <div class="row items-center">
                 <q-btn flat round icon="remove_circle_outline" color="grey-7" @click="quantity > 1 ? quantity-- : null" />
                 <span class="text-h6 q-mx-md">{{ quantity }}</span>
                 <q-btn flat round icon="add_circle_outline" color="grey-7" @click="quantity++" />
              </div>
            </div>
          </div>
        </q-card-section>

        <q-card-actions align="center" class="q-pa-md">
          <q-btn 
            unelevated 
            rounded 
            color="black" 
            text-color="gold"
            size="lg" 
            class="full-width confirm-btn"
            :label="actionType === 'buy' ? 'Proceed to Checkout' : 'Confirm Add to Cart'"
            @click="confirmAction"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </q-page>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useCartStore } from 'stores/cart';
import { useWishlistStore } from 'stores/wishlist';

const route = useRoute();
const router = useRouter();
const $q = useQuasar();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();

const product = ref(null);
const loading = ref(true);
const slide = ref(0);
const relatedProducts = ref([]);

// Options State
const showOptionsDialog = ref(false);
const actionType = ref('cart'); // 'cart' or 'buy'
const selectedSize = ref('');

const quantity = ref(1);

onMounted(async () => {
  try {
    const res = await api.get(`/products/${route.params.id}`);
    product.value = res.data;
    
    // Smooth transition for AOS if present
    if (window.AOS) window.AOS.refresh();
  } catch (error) {
    console.error('Error loading product:', error);
  } finally {
    loading.value = false;
  }
});

const images = computed(() => {
  if (product.value && product.value.images_json) {
    let imgArray = product.value.images_json;
    if (typeof imgArray === 'string') {
      try { imgArray = JSON.parse(imgArray); } catch (e) { console.error(e); imgArray = []; }
    }
    
    if (Array.isArray(imgArray) && imgArray.length > 0) {
      return imgArray.map(img => {
        if (img.startsWith('http')) return img;
        const dum = [
          '/images/products/ring1.png',
          '/images/products/necklace1.png',
          '/images/products/bracelet1.png',
          '/images/products/earrings1.png',
          '/images/products/ring2.png',
          '/images/products/necklace2.png',
          '/images/products/bracelet2.png',
          '/images/products/earrings2.png',
        ];
        if (dum.includes(img)) return img;
        const path = img.startsWith('/') ? img : `/${img}`;
        return `http://localhost:8000${path}`;
      });
    }
  }
  // Fallback to local dummy images
  return [
    '/images/products/ring1.png',
    '/images/products/necklace1.png',
    '/images/products/bracelet1.png',
    '/images/products/earrings1.png'
  ];
});

const stockColor = computed(() => {
  return product.value?.stock_status === 'in_stock' ? 'positive' : 'negative';
});

// Dynamic Variants Handling
const dynamicVariants = computed(() => {
  if (!product.value?.sizes) return [];
  if (Array.isArray(product.value.sizes)) return product.value.sizes;
  try {
    return JSON.parse(product.value.sizes);
  } catch (e) {
    console.error(e);
    return [];
  }
});

const availableSizes = computed(() => {
  const dSpecs = dynamicVariants.value;
  if (dSpecs && dSpecs.length > 0) {
    // Return all distinct sizes present
    const sizes = [...new Set(dSpecs.map(s => s.size).filter(s => !!s))];
    if (sizes.length > 0) return sizes;
  }
  return [];
});

const sizes = availableSizes;
watch(sizes, (newSizes) => {
  if (newSizes.length > 0 && !selectedSize.value) {
    selectedSize.value = newSizes[0];
  }
}, { immediate: true });

const calculatePriceByVariant = (basePrice, size) => {
  if (!basePrice) return 0;
  
  const dSpecs = dynamicVariants.value;
  if (dSpecs && dSpecs.length > 0) {
    // Find precise match
    let matched = dSpecs.find(s => s.size === size);
    
    return matched ? Number(matched.price) : Number(basePrice);
  }

  // Fallback Logic (Old Size logic)
  const baseSize = 10;
  const sizeInt = parseInt(size);
  if (isNaN(sizeInt)) return Number(basePrice);
  
  const steps = (sizeInt - baseSize) / 2; 
  return Number(basePrice) + (steps * 5000); 
};

const currentPrice = computed(() => {
  return calculatePriceByVariant(product.value?.price, selectedSize.value);
});

const priceRange = computed(() => {
  if (!product.value?.price) return '0.00';
  
  const dSpecs = dynamicVariants.value;
  if (dSpecs && dSpecs.length > 0) {
    const prices = dSpecs.map(s => Number(s.price));
    const min = Math.min(...prices);
    const max = Math.max(...prices);
    if (min === max) return formatPrice(min);
    return `${formatPrice(min)} - ${formatPrice(max)}`;
  }

  const min = calculatePriceByVariant(product.value.price, '10');
  const max = calculatePriceByVariant(product.value.price, '20');
  return `${formatPrice(min)} - ${formatPrice(max)}`;
});

const formatPrice = (price) => {
  if (!price) return '0.00';
  return new Intl.NumberFormat('en-LK').format(price);
};

const openOptions = (type) => {
  actionType.value = type;
  showOptionsDialog.value = true;
};

const confirmAction = () => {
  // Validate selection if needed (currently defaults are set)
  
  const options = {
    size: selectedSize.value,
    quantity: quantity.value,
    price: currentPrice.value // Pass calculated price
  };

  cartStore.addItem(product.value, options);
  showOptionsDialog.value = false;

  if (actionType.value === 'buy') {
    router.push('/cart');
  } else {
    $q.notify({
      message: 'Item added to cart successfully',
      color: 'gold',
      textColor: 'black',
      icon: 'check_circle',
      position: 'top'
    });
  }
};

const toggleWishlist = () => {
  const added = wishlistStore.toggleItem(product.value);
  $q.notify({
    message: added ? 'Added to Wishlist' : 'Removed from Wishlist',
    color: added ? 'pink' : 'grey',
    icon: added ? 'favorite' : 'favorite_border',
    position: 'top',
    timeout: 1000
  });
};

</script>

<style lang="scss" scoped>
.product-detail-page {
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
  min-height: 90vh;
}

body.body--dark .product-detail-page {
  background: #121212;
}

.loading-container {
  height: 80vh;
}

.product-carousel {
  border-radius: 24px;
  background: white;
}

.product-image-slide {
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}

.product-info-card {
  background: transparent;
}

.product-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 5vw, 3.5rem);
  font-weight: 700;
  color: #1d1d1d;
  line-height: 1.1;
}

body.body--dark .product-title {
  color: white;
}

.text-gold {
  color: #D4AF37;
}

.product-description {
  color: #555;
  line-height: 1.8;
  font-size: 1.1rem;
}

body.body--dark .product-description {
  color: #bbb;
}

.specs-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  border-top: 1px solid rgba(0,0,0,0.05);
  border-bottom: 1px solid rgba(0,0,0,0.05);
  padding: 1.5rem 0;
}

body.body--dark .specs-grid {
  border-color: rgba(255,255,255,0.1);
}

.spec-item {
  text-align: center;
}

.spec-label {
  font-size: 0.8rem;
  text-transform: uppercase;
  color: #999;
  letter-spacing: 1px;
  margin-bottom: 0.25rem;
}

.spec-value {
  font-weight: 700;
  font-size: 1.1rem;
  color: #d4af37;
}

.add-to-cart-btn {
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 100%) !important;
  color: white;
  font-weight: 700;
  padding: 0.75rem 2rem;
  box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
  }
}

.trust-indicators {
  .indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #888;
  }
}

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  color: #D4AF37;
  margin-bottom: 2rem;
}

  @media (max-width: 600px) {
    .product-carousel {
      height: 350px !important;
    }
  
    .specs-grid {
      grid-template-columns: 1fr 1fr;
    }
  }

.options-card {
  width: 100%;
  max-width: 500px;
  border-radius: 20px 20px 0 0;
  
  @media (min-width: 600px) {
    border-radius: 20px;
  }
}

body.body--dark .options-card {
  background: #1e1e1e;
  border: 1px solid rgba(212, 175, 55, 0.2);
  
  .text-h6, .text-subtitle1, .text-subtitle2, .q-btn__content {
    color: white !important;
  }
  
  .text-grey-7 {
    color: #ccc !important;
  }
}

.size-btn {
  min-width: 40px;
  font-weight: 700;
}

.color-option {
  transition: transform 0.2s;
  
  &:hover {
    transform: scale(1.1);
  }
}

.color-circle {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.confirm-btn {
  background: linear-gradient(135deg, #1d1d1d 0%, #000000 100%) !important;
  font-weight: 700;
}

body.body--dark .confirm-btn {
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 100%) !important;
  color: white !important;
}
</style>
