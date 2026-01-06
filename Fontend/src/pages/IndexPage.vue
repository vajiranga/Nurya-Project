<template>
  <q-page class="home-page">
    <!-- Hero Section with Gradient Background -->
    <section class="hero-section">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1 class="hero-title" data-aos="fade-up">Nurya Jewellery</h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
          Timeless Elegance, Crafted for You
        </p>
        <p class="hero-description" data-aos="fade-up" data-aos-delay="200">
          Discover the finest collection of handcrafted gold jewellery
        </p>
        <q-btn 
          unelevated
          rounded
          size="lg"
          class="hero-btn"
          color="primary"
          label="Explore Collection"
          @click="scrollToCollections"
          data-aos="fade-up" 
          data-aos-delay="300"
        />
      </div>
    </section>

    <!-- Featured Collections & Products Section -->
    <section class="featured-collections" id="collections">
      <div class="section-header" data-aos="fade-up">
        <h2 class="section-title">Featured Collections</h2>
        <p class="section-subtitle">Premium selection of our finest creations</p>
      </div>

      <!-- Category Filters (Moved logic from Best Products) -->
      <div class="category-filters" data-aos="fade-up">
        <q-chip
          v-for="cat in [{ id: 'all', name: 'All' }, ...categories]"
          :key="cat.id"
          :selected="selectedCategory === cat.id"
          @click="selectCategory(cat.id)"
          clickable
          :color="selectedCategory === cat.id ? 'primary' : 'grey-3'"
          :text-color="selectedCategory === cat.id ? 'white' : 'grey-8'"
          size="md"
          class="filter-chip"
        >
          {{ cat.name }}
        </q-chip>
      </div>

      <!-- Products Grid -->
      <div v-if="!loading" class="products-grid">
        <div 
          v-for="(product, index) in filteredProducts.slice(0, 8)" 
          :key="product.id"
          class="product-card"
          @click="goToProduct(product.id)"
          data-aos="fade-up"
          :data-aos-delay="index * 50"
        >
          <div class="product-image-wrapper">
            <img :src="getImage(product.images_json)" :alt="product.name" class="product-image" />
            <div class="product-overlay">
              <q-btn 
                round 
                color="white"
                text-color="primary"
                icon="visibility" 
                size="md"
                class="view-btn"
              />
            </div>
          </div>
          <div class="product-details">
            <h3 class="product-name">{{ product.name }}</h3>
            <!-- Price Range Display -->
            <p class="product-price">{{ getPriceRange(product) }}</p>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-else class="loading-wrapper">
        <q-spinner-orbit color="gold" size="4em" />
      </div>

      <!-- View All Button -->
      <div class="view-all-wrapper" data-aos="fade-up">
        <q-btn 
          outline
          rounded
          size="lg"
          color="primary"
          label="View All Products"
          @click="viewAllProducts"
          class="view-all-btn"
        />
      </div>
    </section>

    <!-- Craftsmanship Section -->
    <section class="craftsmanship-section" data-aos="fade-up">
      <div class="craftsmanship-content">
        <div class="craftsmanship-text">
          <h2 class="craftsmanship-title">Craftsmanship & Trust</h2>
          <p class="craftsmanship-description">
            Every piece of Nurya jewellery is meticulously crafted by skilled artisans 
            with decades of experience, ensuring unparalleled quality and timeless beauty.
          </p>
        </div>
        <div class="craftsmanship-features">
          <div class="feature-item" v-for="feature in features" :key="feature.icon">
            <q-icon :name="feature.icon" size="3em" color="primary" />
            <h4>{{ feature.title }}</h4>
            <p>{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Exclusive Vouchers Section -->
    <section class="vouchers-section" id="vouchers" data-aos="fade-up">
      <div class="section-header">
        <h2 class="section-title">Exclusive Offers</h2>
        <p class="section-subtitle">Grab these limited-time vouchers for your next purchase</p>
      </div>

      <div class="vouchers-grid">
        <div class="voucher-card" v-for="(voucher, index) in vouchers" :key="index" :data-aos="index % 2 === 0 ? 'fade-right' : 'fade-left'">
          <div class="voucher-content">
            <div class="voucher-left">
              <span class="discount-tag">{{ voucher.discount }} OFF</span>
              <h3 class="voucher-title">{{ voucher.title }}</h3>
              <p class="voucher-code">CODE: <span>{{ voucher.code }}</span></p>
            </div>
            <div class="voucher-right">
              <q-btn unelevated color="white" text-color="primary" label="Copy Code" @click="copyCode(voucher.code)" />
            </div>
          </div>
          <div class="voucher-pattern"></div>
        </div>
      </div>
    </section>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { api } from 'boot/axios';

const router = useRouter();
const categories = ref([]);
const products = ref([]);
const selectedCategory = ref('all');
const loading = ref(true);

const features = [
  {
    icon: 'verified',
    title: 'Certified Quality',
    description: '100% authentic gold with certification'
  },
  {
    icon: 'local_shipping',
    title: 'Secure Delivery',
    description: 'Safe and timely delivery to your doorstep'
  },
  {
    icon: 'workspace_premium',
    title: 'Premium Designs',
    description: 'Exclusive and unique handcrafted designs'
  }
];

const vouchers = [
  {
    discount: '10%',
    title: 'New Year Special',
    code: 'NURYA2026'
  },
  {
    discount: 'LKR 5000',
    title: 'Wedding Collection Offer',
    code: 'WEDDING24'
  }
];

const copyCode = (code) => {
  navigator.clipboard.writeText(code);
  alert('Coupon code copied to clipboard!');
};

onMounted(async () => {
  try {
    const [catRes, prodRes] = await Promise.all([
      api.get('/categories'),
      api.get('/products')
    ]);
    categories.value = catRes.data;
    products.value = prodRes.data;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    loading.value = false;
  }

  // Initialize AOS (Animate On Scroll)
  if (typeof window !== 'undefined' && window.AOS) {
    window.AOS.init({
      duration: 800,
      once: true,
      offset: 100
    });
  }
});

const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') return products.value;
  return products.value.filter((p) => p.category_id === selectedCategory.value);
});

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
    // Check if it's one of our dummy images (starts with /images/products/ and not from upload)
    // Actually, uploaded images also start with /images/products/.
    // We should assume ANY relative path starting with / needs backend URL unless it's in public folder of frontend.
    // The dummy images are in Frontend public folder?
    // Let's check 'public/images/products' in Frontend.
    // Yes, they are.
    // But uploaded images from backend are also path '/images/products/filename'.
    // Conflict!
    // If we use 'http://localhost:8000' for everything start with '/', it will break frontend assets if they are not on backend.
    // But wait, frontend 'public' folder is served at root.
    // So '/images/products/ring1.png' works on frontend.
    // But uploaded image '/images/products/timestamp_name.jpg' is ONLY on backend.
    
    // Solution: We should rely on a pattern or simply try-error? No.
    // Best practice: Store uploaded images with a distinct prefix or rely on full URL from backend.
    // But since we stored as relative path...
    // Let's assume if the filename contains 'timestamp_' (time() . '_') it is backend.
    // Or simpler: If the generic dummy list contains it, use frontend. Else backend.

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

    // It is likely a backend image
    const path = img.startsWith('/') ? img : `/${img}`;
    return `http://localhost:8000${path}`;
  }
  
  // Use random dummy product images as fallback
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


const goToProduct = (id) => {
  router.push(`/product/${id}`);
};

const selectCategory = (categoryId) => {
  selectedCategory.value = categoryId;
  document.getElementById('products')?.scrollIntoView({ behavior: 'smooth' });
};

const scrollToCollections = () => {
  document.getElementById('collections')?.scrollIntoView({ behavior: 'smooth' });
};

const viewAllProducts = () => {
  router.push('/products');
};
</script>

<style scoped>
/* Global Page Styles */
.home-page {
  padding: 0 !important;
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
}

body.body--dark .home-page {
  background: linear-gradient(135deg, #121212 0%, #000000 100%);
}

/* Hero Section */
.hero-section {
  position: relative;
  height: 100vh;
  min-height: 600px;
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 50%, #d4af37 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

body.body--dark .hero-section {
  background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: 
    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  animation: shimmer 6s ease-in-out infinite;
}

body.body--dark .hero-overlay {
  background: 
    radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
  animation: shimmer 8s ease-in-out infinite;
}

@keyframes shimmer {
  0%, 100% { opacity: 0.3; }
  50% { opacity: 0.7; }
}

.hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
  color: #000000; /* Light Mode: Black Text */
  max-width: 800px;
  padding: 2rem;
}

body.body--dark .hero-content {
  color: white; /* Dark Mode: White Text */
}

.hero-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(3rem, 8vw, 6rem);
  font-weight: 700;
  margin: 0 0 1rem 0;
  color: #000000;
  letter-spacing: 2px;
}

body.body--dark .hero-title {
  color: #D4AF37; /* Dark Mode: Gold Title */
  text-shadow: 0 4px 20px rgba(212, 175, 55, 0.2);
}

.hero-subtitle {
  font-size: clamp(1.5rem, 4vw, 2.5rem);
  font-weight: 300;
  margin: 0 0 1rem 0;
  color: #000000;
}

body.body--dark .hero-subtitle {
  color: #f0f0f0;
}

.hero-description {
  font-size: clamp(1rem, 2vw, 1.25rem);
  margin: 0 0 2rem 0;
  opacity: 0.8;
  color: #ccc;
}

.hero-btn {
  padding: 1rem 3rem;
  font-size: 1.1rem;
  font-weight: 600;
  background: white !important;
  color: #d4af37 !important;
  transition: all 0.3s ease;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.hero-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
}

/* Section Styles */
section {
  padding: 5rem 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.section-header {
  text-align: center;
  margin-bottom: 3rem;
}

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 5vw, 3rem);
  color: #d4af37;
  margin: 0 0 1rem 0;
  font-weight: 700;
}

.section-subtitle {
  font-size: clamp(1rem, 2vw, 1.25rem);
  color: #666;
  margin: 0;
}

body.body--dark .section-subtitle {
  color: #aaa;
}

/* Featured Collections */
.collections-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.collection-card {
  cursor: pointer;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.collection-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(212, 175, 55, 0.3);
}

.collection-image-wrapper {
  position: relative;
  overflow: hidden;
  padding-top: 66.67%; /* 3:2 aspect ratio */
}

.collection-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.collection-card:hover .collection-image {
  transform: scale(1.1);
}

.collection-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  padding: 2rem;
  color: white;
}

.collection-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem;
  margin: 0 0 1rem 0;
  font-weight: 600;
}

.collection-btn {
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.3s ease;
}

.collection-card:hover .collection-btn {
  opacity: 1;
  transform: translateY(0);
}

/* Best Products Section */
.best-products {
  background: white;
  border-radius: 30px;
  padding: 4rem 2rem;
  margin: 3rem auto;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
}

body.body--dark .best-products {
  background: rgba(255, 255, 255, 0.05);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.category-filters {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 0.75rem;
  margin-bottom: 3rem;
}

.filter-chip {
  font-weight: 600;
  transition: all 0.3s ease;
  cursor: pointer;
}

.filter-chip:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 2rem;
}

.product-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
}

body.body--dark .product-card {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(212, 175, 55, 0.25);
}

.product-image-wrapper {
  position: relative;
  overflow: hidden;
  padding-top: 100%; /* 1:1 aspect ratio */
  background: #fafafa;
}

body.body--dark .product-image-wrapper {
  background: #111;
}

.product-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.product-card:hover .product-image {
  transform: scale(1.08);
}

.product-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
  opacity: 1;
}

.view-btn {
  transform: scale(0.8);
  transition: transform 0.3s ease;
}

.product-card:hover .view-btn {
  transform: scale(1);
}

.product-details {
  padding: 1.5rem;
}

.product-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.2rem;
  font-weight: 700;
  color: #333;
  margin: 0 0 0.5rem 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

body.body--dark .product-name {
  color: #eee;
}

.product-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: #d4af37;
  margin: 0;
}

.loading-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 400px;
}

.view-all-wrapper {
  text-align: center;
  margin-top: 3rem;
}

.view-all-btn {
  padding: 0.75rem 2.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.view-all-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(212, 175, 55, 0.3);
}

/* Craftsmanship Section */
.craftsmanship-section {
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 100%);
  color: #000000;
  border-radius: 30px;
  padding: 4rem 2rem;
  margin: 3rem auto;
}

body.body--dark .craftsmanship-section {
  background: linear-gradient(135deg, #1a1a1a 0%, #111 100%);
  color: white;
  border: 1px solid rgba(212, 175, 55, 0.2);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.craftsmanship-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 5vw, 3rem);
  margin: 0 0 1rem 0;
  font-weight: 700;
  color: #000000;
}

body.body--dark .craftsmanship-title {
  color: #D4AF37;
}

.craftsmanship-description {
  font-size: clamp(1rem, 2vw, 1.25rem);
  line-height: 1.8;
  opacity: 0.95;
  max-width: 800px;
  margin: 0 auto;
}

.craftsmanship-features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-top: 2rem;
}

.feature-item {
  text-align: center;
  padding: 2rem;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 16px;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

.feature-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-5px);
}

.feature-item h4 {
  font-size: 1.3rem;
  margin: 1rem 0 0.5rem 0;
  font-weight: 600;
}

.feature-item p {
  margin: 0;
  opacity: 0.9;
  font-size: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  section {
    padding: 3rem 1rem;
  }

  .best-products {
    padding: 3rem 1rem;
    margin: 2rem 1rem;
  }

  .craftsmanship-section {
    padding: 3rem 1rem;
    margin: 2rem 1rem;
  }

  .hero-section {
    height: auto;
    min-height: 80vh;
  }

  .collections-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
  }

  .craftsmanship-features {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
}

/* Animation keyframes */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

[data-aos] {
  opacity: 0;
  transition-property: opacity, transform;
}

[data-aos].aos-animate {
  opacity: 1;
}

[data-aos="fade-up"] {
  transform: translateY(30px);
}

[data-aos="fade-up"].aos-animate {
  transform: translateY(0);
}

[data-aos="zoom-in"] {
  transform: scale(0.9);
}

[data-aos="zoom-in"].aos-animate {
  transform: scale(1);
}

/* Vouchers Section Styles */
.vouchers-section {
  padding: 5rem 2rem;
  background: transparent;
}

.vouchers-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}

.voucher-card {
  position: relative;
  background: linear-gradient(135deg, #d4af37 0%, #aa8929 100%);
  border-radius: 20px;
  overflow: hidden;
  color: #000000;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s ease;
  
  &:hover {
    transform: scale(1.02);
  }
}

body.body--dark .voucher-card {
  background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
  color: white;
  border: 1px solid rgba(212, 175, 55, 0.3);
}

.voucher-content {
  position: relative;
  z-index: 2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 2.5rem;
  
  /* Ticket notch effect */
  &::before, &::after {
    content: '';
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    background: #fafafa;
    border-radius: 50%;
  }
  
  &::before { left: -15px; }
  &::after { right: -15px; }
}

body.body--dark .voucher-content::before, 
body.body--dark .voucher-content::after {
  background: #121212;
  border: 1px solid rgba(212, 175, 55, 0.3);
}

body.body--dark .voucher-content::before { border-right-color: transparent; }
body.body--dark .voucher-content::after { border-left-color: transparent; }

.discount-tag {
  display: inline-block;
  background: rgba(212, 175, 55, 0.2);
  color: #D4AF37;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  border: 1px solid rgba(212, 175, 55, 0.3);
}

.voucher-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  margin: 0.5rem 0;
  font-weight: 700;
  color: #000000;
}

body.body--dark .voucher-title {
  color: white;
}

.voucher-code {
  margin: 0;
  font-size: 1.1rem;
  opacity: 0.9;
  
  span {
    font-weight: 800;
    letter-spacing: 1px;
    color: #000000;
    border-bottom: 2px dashed rgba(0, 0, 0, 0.5);
  }
}

body.body--dark .voucher-code span {
  color: #D4AF37;
  border-color: rgba(212, 175, 55, 0.5);
}

.voucher-pattern {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0.05;
  background-image: radial-gradient(circle at 2px 2px, #D4AF37 1px, transparent 0);
  background-size: 24px 24px;
}

@media (max-width: 600px) {
  .voucher-content {
    flex-direction: column;
    text-align: center;
    gap: 1.5rem;
    
    &::before, &::after {
      top: auto;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
    }
  }
}
</style>
