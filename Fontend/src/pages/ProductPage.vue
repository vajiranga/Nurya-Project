<template>
  <q-page class="q-pa-md">
    <div v-if="loading" class="flex flex-center" style="height: 80vh">
      <q-spinner-dots color="primary" size="3em" />
    </div>

    <div v-else-if="product" class="row q-col-gutter-lg justify-center">
      <div class="col-12 col-md-6">
        <q-carousel
          animated
          v-model="slide"
          arrows
          navigation
          infinite
          height="400px"
          class="bg-grey-2 shadow-2 rounded-borders"
        >
          <q-carousel-slide
            v-for="(img, index) in images"
            :key="index"
            :name="index"
            :img-src="img"
          />
        </q-carousel>
      </div>

      <div class="col-12 col-md-6">
        <h3 class="text-primary q-mt-none" style="font-family: 'Playfair Display'">{{ product.name }}</h3>
        <div class="text-h4 text-weight-bold q-mb-md">LKR {{ formatPrice(product.price) }}</div>
        
        <p class="text-body1 text-grey-8">{{ product.description }}</p>

        <q-list separator bordered class="rounded-borders q-mb-lg">
          <q-item v-if="product.gold_weight">
            <q-item-section>Gold Weight</q-item-section>
            <q-item-section side>{{ product.gold_weight }}</q-item-section>
          </q-item>
          <q-item v-if="product.karat">
            <q-item-section>Karat</q-item-section>
            <q-item-section side>{{ product.karat }}</q-item-section>
          </q-item>
          <q-item>
            <q-item-section>Stock Status</q-item-section>
            <q-item-section side>
              <q-badge :color="stockColor">{{ product.stock_status.replace('_', ' ').toUpperCase() }}</q-badge>
            </q-item-section>
          </q-item>
        </q-list>

        <div class="row q-gutter-md">
          <q-btn
            color="primary"
            icon="add_shopping_cart"
            label="Add to Cart"
            size="lg"
            class="full-width"
            @click="addToCart"
          />
        </div>
      </div>
    </div>
    
     <div v-else class="text-center q-pa-lg">
      <div class="text-h5">Product not found</div>
      <q-btn to="/" label="Back to Home" flat color="primary" />
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { useQuasar } from 'quasar';
import { api } from 'boot/axios';
import { useCartStore } from 'stores/cart';

const route = useRoute();
const $q = useQuasar();
const cartStore = useCartStore();

const product = ref(null);
const loading = ref(true);
const slide = ref(0);

onMounted(async () => {
  try {
    const res = await api.get(`/products/${route.params.id}`);
    product.value = res.data;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
});

const images = computed(() => {
  if (product.value && Array.isArray(product.value.images_json) && product.value.images_json.length > 0) {
    return product.value.images_json;
  }
  return ['https://via.placeholder.com/600x400?text=Nurya+Gold'];
});

const stockColor = computed(() => {
  return product.value?.stock_status === 'in_stock' ? 'green' : 'red';
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('en-LK').format(price);
};

const addToCart = () => {
  cartStore.addItem(product.value);
  $q.notify({
    message: 'Added to cart!',
    color: 'positive',
    icon: 'check',
    position: 'top',
  });
};
</script>
