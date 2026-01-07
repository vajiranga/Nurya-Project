<template>
  <q-page class="all-products-page">
    <!-- Header -->
    <div class="page-header text-center q-py-xl" data-aos="fade-down">
      <h1 class="text-h2 font-playfair text-gold q-mb-sm">Our Collection</h1>
      <p class="text-grey-7 text-subtitle1">Timeless pieces for every occasion</p>
    </div>

    <div class="container q-px-md q-pb-xl">
      <div class="row q-col-gutter-lg">
        <!-- Sidebar / Filters -->
        <div class="col-12 col-md-3" data-aos="fade-right">
            <q-card flat class="filter-card">
              <q-card-section>
                <div class="text-h6 font-playfair q-mb-md text-gold">Categories</div>
                <q-list separator>
                   <q-item 
                     clickable 
                     v-ripple
                     :active="filterStore.selectedCategory === 'all'"
                     active-class="text-gold text-weight-bold"
                     @click="filterStore.selectedCategory = 'all'"
                     class="category-item"
                   >
                     <q-item-section>All Categories</q-item-section>
                     <q-item-section side v-if="filterStore.selectedCategory === 'all'">
                        <q-icon name="check" size="xs" color="gold" />
                     </q-item-section>
                   </q-item>
                   <q-item 
                     v-for="cat in categories" 
                     :key="cat.id"
                     clickable 
                     v-ripple
                     :active="filterStore.selectedCategory === cat.id"
                     active-class="text-gold text-weight-bold"
                     @click="filterStore.selectedCategory = cat.id"
                     class="category-item"
                   >
                     <q-item-section>{{ cat.name }}</q-item-section>
                     <q-item-section side v-if="filterStore.selectedCategory === cat.id">
                        <q-icon name="check" size="xs" color="gold" />
                     </q-item-section>
                   </q-item>
                </q-list>
              </q-card-section>
           </q-card>
        </div>

        <!-- Product Grid -->
        <div class="col-12 col-md-9">
           <!-- Loading -->
           <div v-if="loading" class="flex flex-center q-pa-xl">
             <q-spinner-orbit color="gold" size="4em" />
           </div>
           
           <!-- Empty State -->
           <div v-else-if="filteredProducts.length === 0" class="flex flex-center column q-pa-xl text-grey-6" data-aos="fade-up">
              <q-icon name="diamond" size="50px" color="grey-4" />
              <div class="q-mt-md text-h6">No products found</div>
              <p>Try selecting a different category.</p>
           </div>

           <!-- Grid -->
           <div v-else class="row q-col-gutter-md">
              <div 
                v-for="(product, index) in filteredProducts" 
                :key="product.id" 
                class="col-12 col-sm-6 col-md-4"
                data-aos="fade-up"
                :data-aos-delay="index * 50"
              >
                 <q-card class="product-card cursor-pointer" @click="goToProduct(product.id)">
                    <div class="product-image-wrapper">
                       <q-img :src="getImage(product.images_json)" :ratio="1" class="product-image" />
                       
                       <!-- Wishlist Button - PREVENT BUBBLING -->
                       <q-btn 
                         round 
                         flat
                         :icon="wishlistStore.isInWishlist(product.id) ? 'favorite' : 'favorite_border'"
                         :color="wishlistStore.isInWishlist(product.id) ? 'pink' : 'grey-8'"
                         class="wishlist-btn absolute-top-right q-ma-sm bg-white"
                         size="sm"
                         @click.stop="toggleWishlist(product)"
                       />

                       <div class="product-overlay flex flex-center">
                          <q-btn 
                            round 
                            color="white" 
                            text-color="primary" 
                            icon="visibility"
                          />
                       </div>
                    </div>
                    
                    <q-card-section class="text-center q-pt-md">
                       <div class="text-h6 font-playfair q-mb-xs ellipsis product-name">{{ product.name }}</div>
                       <!-- Price Range Display -->
                       <div class="text-gold text-weight-bold text-subtitle1">{{ getPriceRange(product) }}</div>
                    </q-card-section>
                 </q-card>
              </div>
           </div>
        </div>
      </div>
    </div>

    <!-- Craftsmanship Section (Best Features) -->
    <section class="craftsmanship-section q-mx-md q-mb-xl" data-aos="fade-up">
      <div class="craftsmanship-content text-center">
        <div class="craftsmanship-text q-mb-xl">
          <h2 class="craftsmanship-title text-h3 font-playfair q-mb-md">Craftsmanship & Trust</h2>
          <p class="craftsmanship-description text-body1">
            Every piece of Nurya jewellery is meticulously crafted by skilled artisans 
            with decades of experience, ensuring unparalleled quality and timeless beauty.
          </p>
        </div>
        <div class="row q-col-gutter-lg justify-center">
          <div class="col-12 col-sm-4" v-for="feature in features" :key="feature.icon">
             <div class="feature-item q-pa-lg">
                <q-icon :name="feature.icon" size="3em" class="text-gold q-mb-md" />
                <h4 class="text-h6 q-mt-none q-mb-sm">{{ feature.title }}</h4>
                <p class="text-grey-7">{{ feature.description }}</p>
             </div>
          </div>
        </div>
      </div>
    </section>

  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { api } from 'boot/axios';
import { useWishlistStore } from 'stores/wishlist';
import { useQuasar } from 'quasar';
import { useFilterStore } from 'stores/filter';

const router = useRouter();
const route = useRoute();
const $q = useQuasar();
const wishlistStore = useWishlistStore();
const filterStore = useFilterStore();

const categories = ref([]); // We can still fetch valid categories or use store if it fetches
const products = ref([]);
const loading = ref(true);

// ... (features array)

onMounted(async () => {
  try {
    const [catRes, prodRes] = await Promise.all([
      api.get('/categories'),
      api.get('/products')
    ]);
    categories.value = catRes.data;
    products.value = prodRes.data; // Store full list locally to filter against

    // Sync Query Params to Store if present (handling Deep Links / Refreshes)
    if (Object.keys(route.query).length > 0) {
        if (route.query.category) filterStore.selectedCategory = route.query.category.toString(); // Ensure string if ID
        if (route.query.min_price) filterStore.priceRange.min = Number(route.query.min_price);
        if (route.query.max_price) filterStore.priceRange.max = Number(route.query.max_price);
        if (route.query.sort) filterStore.sortBy = route.query.sort;
    }

  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false;
  }
  
  // ... (AOS refresh)
});

const filteredProducts = computed(() => {
  let result = products.value;

  // 1. Filter by Category
  if (filterStore.selectedCategory !== 'all') {
    // Determine type equality. filterStore uses string/number depending on input but typically ids are numbers.
    // Let's use loose equality for safety or convert.
    result = result.filter((p) => p.category_id == filterStore.selectedCategory);
  }

  // 2. Filter by Price
  result = result.filter(p => {
    const price = getProductPrice(p);
    return price >= filterStore.priceRange.min && price <= filterStore.priceRange.max;
  });

  // 3. Sort
  if (filterStore.sortBy === 'price_asc') {
    result.sort((a, b) => getProductPrice(a) - getProductPrice(b));
  } else if (filterStore.sortBy === 'price_desc') {
    result.sort((a, b) => getProductPrice(b) - getProductPrice(a));
  } else if (filterStore.sortBy === 'newest') {
    result.sort((a, b) => b.id - a.id);
  }

  return result;
});

const getProductPrice = (product) => {
  // Helper to get a representational price for sorting/filtering
  if (product.price) return parseFloat(product.price);
  
  // Handle variants
  if (product.sizes) {
    let variants = [];
    if (Array.isArray(product.sizes)) variants = product.sizes;
    else if (typeof product.sizes === 'string') {
      try { variants = JSON.parse(product.sizes); } catch { return 0; }
    }
    
    if (variants.length > 0) {
      const prices = variants.map(v => Number(v.price)).filter(p => !isNaN(p));
      return prices.length > 0 ? Math.min(...prices) : 0;
    }
  }
  return 0;
};

const goToProduct = (id) => {
  router.push(`/product/${id}`);
};

const toggleWishlist = (product) => {
  const added = wishlistStore.toggleItem(product);
  $q.notify({
    message: added ? 'Added to Wishlist' : 'Removed from Wishlist',
    color: added ? 'pink' : 'grey',
    icon: added ? 'favorite' : 'favorite_border',
    position: 'top',
    timeout: 1000
  });
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LK').format(price);
};

const getPriceRange = (product) => {
  // Check for dynamic variants
  if (product.sizes) {
    let variants = [];
    if (Array.isArray(product.sizes)) {
      variants = product.sizes;
    } else if (typeof product.sizes === 'string') {
      try {
        variants = JSON.parse(product.sizes);
      } catch {
        variants = [];
      }
    }

    if (variants.length > 0) {
      const prices = variants.map(v => Number(v.price)).filter(p => !isNaN(p));
      if (prices.length > 0) {
        const min = Math.min(...prices);
        const max = Math.max(...prices);
        
        if (min === max) return `LKR ${formatPrice(min)}`;
        return `LKR ${formatPrice(min)} - ${formatPrice(max)}`;
      }
    }
  }

  // Fallback to single base price
  if (!product.price) return 'LKR 0.00';
  return `LKR ${formatPrice(product.price)}`;
};

const getImage = (images) => {
  let imgArray = images;
  
  if (typeof images === 'string') {
    try {
      imgArray = JSON.parse(images);
    } catch (e) {
      console.error(e);
      imgArray = [];
    }
  }

  if (Array.isArray(imgArray) && imgArray.length > 0) {
    const img = imgArray[0];
    if (!img) return '/images/logo.png';
    if (img.startsWith('http')) return img;
    
    // Check local dummy images
    const dummyImagesCommon = [
      '/images/products/ring1.png',
      '/images/products/necklace1.png',
      '/images/products/bracelet1.png',
      '/images/products/earrings1.png',
      '/images/products/ring2.png',
      '/images/products/necklace2.png',
      '/images/products/bracelet2.png',
      '/images/products/earrings2.png',
    ];

    if (dummyImagesCommon.includes(img)) {
       return img;
    }

    const path = img.startsWith('/') ? img : `/${img}`;
    return `http://localhost:8000${path}`;
  }
  
  const dummyImages = [
    '/images/products/ring1.png',
    '/images/products/necklace1.png',
    '/images/products/bracelet1.png',
    '/images/products/earrings1.png',
    '/images/products/ring2.png',
    '/images/products/necklace2.png',
    '/images/products/bracelet2.png',
    '/images/products/earrings2.png',
  ];
  
  return dummyImages[Math.floor(Math.random() * dummyImages.length)];
};
</script>

<style scoped>
.all-products-page {
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
  min-height: 100vh;
}

body.body--dark .all-products-page {
  background: #121212;
}

.text-gold {
  color: #D4AF37;
}

.font-playfair {
  font-family: 'Playfair Display', serif;
}

.filter-card {
  border-radius: 16px;
  background: white;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

body.body--dark .filter-card {
  background: #1e1e1e;
}

.category-item {
  border-radius: 8px;
  margin-bottom: 4px;
  transition: all 0.3s ease;
}

.category-item:hover {
  background: rgba(212, 175, 55, 0.1);
}

.product-card {
  border-radius: 16px;
  background: white;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

body.body--dark .product-card {
  background: #1e1e1e;
  border: 1px solid rgba(255,255,255,0.1);
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 30px rgba(212, 175, 55, 0.2);
}

.product-image-wrapper {
  position: relative;
  overflow: hidden;
}

.product-image {
  transition: transform 0.6s ease;
}

.product-card:hover .product-image {
  transform: scale(1.1);
}

.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.3);
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

.wishlist-btn {
  z-index: 10;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
  pointer-events: auto; /* Ensure clickable */
}

.wishlist-btn:hover {
  transform: scale(1.1);
}

.product-name {
  color: #333;
}

body.body--dark .product-name {
  color: #f0f0f0;
}

/* Craftsmanship Styles */
.craftsmanship-section {
  background: white;
  border-radius: 24px;
  padding: 3rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

body.body--dark .craftsmanship-section {
  background: #1e1e1e;
  border: 1px solid rgba(212, 175, 55, 0.2);
}

.feature-item {
  background: rgba(0,0,0,0.02);
  border-radius: 16px;
  height: 100%;
  transition: transform 0.3s ease;
}

body.body--dark .feature-item {
  background: rgba(255,255,255,0.05);
}

.feature-item:hover {
  transform: translateY(-5px);
}
</style>
