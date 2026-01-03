<template>
  <q-page class="q-pa-md bg-grey-1">
    <!-- Hero Section -->
    <div class="row justify-center q-mb-lg">
      <div class="col-12 col-md-8 text-center">
        <h2 class="text-primary text-weight-bold q-my-md" style="font-family: 'Playfair Display'">
          Nurya Jewellery
        </h2>
        <p class="text-h6 text-grey-8">Timeless Elegance, Crafted for You.</p>
      </div>
    </div>

    <!-- Categories Filters -->
    <div class="row justify-center q-mb-lg">
      <q-tabs
        v-model="selectedCategory"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
      >
        <q-tab name="all" label="All" />
        <q-tab v-for="cat in categories" :key="cat.id" :name="cat.id" :label="cat.name" />
      </q-tabs>
    </div>

    <!-- Product Grid -->
    <div class="row q-col-gutter-lg">
      <div
        class="col-12 col-sm-6 col-md-4 col-lg-3"
        v-for="product in filteredProducts"
        :key="product.id"
      >
        <q-card class="my-card cursor-pointer shadow-3 hover-lift" @click="goToProduct(product.id)">
          <q-img :src="getImage(product.images_json)" :ratio="1" />
          <q-card-section>
            <div class="text-h6 ellipsis">{{ product.name }}</div>
            <div class="text-primary text-weight-bold">LKR {{ formatPrice(product.price) }}</div>
          </q-card-section>
        </q-card>
      </div>
    </div>
    
    <div v-if="loading" class="row justify-center q-my-lg">
        <q-spinner-dots color="primary" size="3em" />
    </div>
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
});

const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') return products.value;
  return products.value.filter((p) => p.category_id === selectedCategory.value);
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LK').format(price);
};

const getImage = (images) => {
  if (Array.isArray(images) && images.length > 0) return images[0];
  // Placeholder if no image (using a dummy image online or local placeholder)
  return 'https://via.placeholder.com/300x300?text=Nurya+Gold';
};

const goToProduct = (id) => {
  router.push(`/product/${id}`);
};
</script>

<style scoped>
.my-card {
  transition: transform 0.3s ease;
}
.hover-lift:hover {
  transform: translateY(-5px);
}
</style>
