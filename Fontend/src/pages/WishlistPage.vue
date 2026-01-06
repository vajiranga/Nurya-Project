<template>
  <q-page class="wishlist-page q-pa-lg">
    <div class="container container-narrow">
      <div class="row items-center q-mb-xl" data-aos="fade-down">
        <h1 class="page-title q-ma-none">My Wishlist</h1>
        <q-space />
        <q-btn flat color="grey-7" icon="arrow_back" label="Continue Shopping" to="/" />
      </div>

      <div v-if="wishlistStore.items.length === 0" class="empty-wishlist-section text-center q-pa-xl" data-aos="zoom-in">
        <div class="empty-icon-wrapper q-mb-lg">
          <q-icon name="favorite_border" size="100px" color="grey-4" />
        </div>
        <h2 class="text-h4 text-weight-light">Your wishlist is empty</h2>
        <p class="text-grey-7 q-mb-xl">Save your favorite jewelry pieces here to buy later.</p>
        <q-btn 
          unelevated 
          rounded 
          size="lg" 
          color="gold" 
          label="Browse Collections" 
          to="/" 
          class="explore-btn" 
        />
      </div>

      <!-- Wishlist Grid -->
      <div v-else class="row q-col-gutter-lg">
        <div 
          v-for="item in wishlistStore.items" 
          :key="item.id" 
          class="col-12 col-md-6 col-lg-4"
          data-aos="fade-up"
        >
          <div class="wishlist-card">
            <div class="wishlist-img-wrapper">
              <img :src="getImage(item.images_json)" class="wishlist-img" />
              <q-btn 
                round 
                color="white" 
                text-color="red" 
                icon="delete" 
                class="remove-btn shadow-3"
                @click="wishlistStore.removeItem(item.id)"
              >
                <q-tooltip>Remove</q-tooltip>
              </q-btn>
            </div>
            
            <div class="wishlist-details q-pa-md">
              <h3 class="item-name">{{ item.name }}</h3>
              <div class="item-price text-gold q-mb-md">LKR {{ formatPrice(item.price) }}</div>
              
              <q-btn 
                unelevated 
                rounded 
                color="black" 
                icon="add_shopping_cart" 
                label="Add to Cart" 
                class="full-width"
                @click="moveToCart(item)"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { useWishlistStore } from 'stores/wishlist';
import { useCartStore } from 'stores/cart';
import { useQuasar } from 'quasar';

const wishlistStore = useWishlistStore();
const cartStore = useCartStore();
const $q = useQuasar();

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

const moveToCart = (item) => {
  cartStore.addItem(item);
  wishlistStore.removeItem(item.id);
  $q.notify({
    message: 'Moved to Cart',
    color: 'positive',
    icon: 'check',
    position: 'top'
  });
};
</script>

<style lang="scss" scoped>
.wishlist-page {
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
  min-height: 80vh;
}

body.body--dark .wishlist-page {
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

.wishlist-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
  
  &:hover {
    transform: translateY(-5px);
  }
}

body.body--dark .wishlist-card {
  background: rgba(255, 255, 255, 0.05);
}

.wishlist-img-wrapper {
  position: relative;
  height: 250px;
  overflow: hidden;
}

.wishlist-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.remove-btn {
  position: absolute;
  top: 10px;
  right: 10px;
}

.item-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.2rem;
  margin: 0 0 0.5rem 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

body.body--dark .item-name {
  color: white;
}

.item-price {
  font-weight: 700;
  font-size: 1.1rem;
}
</style>
