<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Premium Glassmorphism Header -->
    <q-header elevated class="header-glass">
      <q-toolbar class="container q-py-sm">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
          class="lt-md color-gold"
        />

        <!-- Filter Toggle Button (Desktop/Tablet) -->
        <q-btn 
           flat 
           dense
           round
           icon="tune" 
           @click="filterStore.toggleDrawer"
           class="gt-xs color-gold q-mr-sm"
           v-if="showFilterButton"
        >
          <q-tooltip>Filter & Sort</q-tooltip>
        </q-btn>

        <q-toolbar-title class="brand-logo-wrapper row items-center no-wrap" @click="$router.push('/')">
          <!-- Logo Image -->
          <img src="/logo.png" alt="Nurya Jewellery" class="brand-logo q-mr-sm" />
          
          <!-- Brand Text Restored -->
          <div class="brand-text-container gt-xs">
            <span :class="$q.dark.isActive ? 'text-white' : 'text-dark'">NURYA</span>
            <span class="text-gold q-ml-xs">JEWELLERY</span>
          </div>
        </q-toolbar-title>

        <!-- Desktop Navigation Items -->
        <div class="gt-sm q-gutter-md row items-center no-wrap">
          <q-btn flat no-caps label="Home" to="/" class="nav-link" />
          <q-btn flat no-caps label="Collections" to="/products" class="nav-link" />
          <q-btn flat no-caps label="Vouchers" to="/vouchers" class="nav-link">
            <q-badge color="gold" floating text-color="black">Sale</q-badge>
          </q-btn>
          <q-btn flat no-caps label="Contact Us" to="/contact" class="nav-link" />
        </div>

        <q-space class="gt-sm" />

        <div class="row items-center no-wrap q-gutter-sm">
          <!-- Dark Mode Toggle -->
          <q-btn
            flat
            round
            dense
            :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'"
            @click="toggleDarkMode"
            :color="$q.dark.isActive ? 'amber' : 'white'"
            class="q-mr-xs"
          >
            <q-tooltip>{{ $q.dark.isActive ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}</q-tooltip>
          </q-btn>

          <!-- Wishlist Button -->
          <q-btn flat round dense icon="favorite_border" to="/wishlist" color="white">
            <q-badge color="red" floating v-if="wishlistStore.itemCount > 0" class="text-white text-weight-bold">
              {{ wishlistStore.itemCount }}
            </q-badge>
            <q-tooltip>Wishlist</q-tooltip>
          </q-btn>

          <!-- Cart Button -->
          <q-btn flat round dense icon="shopping_cart" to="/cart" color="white">
            <q-badge color="gold" floating v-if="cartStore.itemCount > 0" class="text-black text-weight-bold">
              {{ cartStore.itemCount }}
            </q-badge>
            <q-tooltip>Shopping Cart</q-tooltip>
          </q-btn>

          <!-- Profile Button -->
          <q-btn flat round dense icon="account_circle" color="white" class="q-ml-sm">
            <q-menu>
              <q-list style="min-width: 150px">
                <q-item clickable v-close-popup>
                  <q-item-section>My Account</q-item-section>
                </q-item>
                <q-item clickable v-close-popup>
                  <q-item-section>Orders</q-item-section>
                </q-item>
                <q-separator />
                <q-item clickable v-close-popup>
                  <q-item-section>Logout</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
            <q-tooltip>My Account</q-tooltip>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <!-- Mobile Drawer -->
    <q-drawer
      v-model="leftDrawerOpen"
      side="left"
      overlay
      bordered
      behavior="mobile"
      :class="$q.dark.isActive ? 'bg-dark' : 'bg-white'"
    >
      <q-list padding class="text-weight-medium">
        <q-item-label header class="text-gold text-weight-bold">MENU</q-item-label>
        
        <q-item clickable v-ripple to="/">
          <q-item-section avatar><q-icon name="home" color="gold" /></q-item-section>
          <q-item-section>Home</q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/products">
          <q-item-section avatar><q-icon name="grid_view" color="gold" /></q-item-section>
          <q-item-section>Collections</q-item-section>
        </q-item>

        <q-item clickable v-ripple>
          <q-item-section avatar><q-icon name="confirmation_number" color="gold" /></q-item-section>
          <q-item-section>Vouchers</q-item-section>
          <q-item-section side><q-badge color="red">Hot</q-badge></q-item-section>
        </q-item>

        <q-separator spaced />

        <q-item clickable v-ripple href="https://wa.me/9471xxxxxxx" target="_blank">
          <q-item-section avatar><q-icon name="chat" color="green" /></q-item-section>
          <q-item-section>WhatsApp Support</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Filter Drawer (Left Side) -->
    <q-drawer
      v-model="filterStore.isDrawerOpen"
      side="left"
      overlay
      behavior="mobile"
      bordered
      :class="$q.dark.isActive ? 'bg-dark' : 'bg-white'"
      :width="320"
      class="filter-drawer column"
    >
      <!-- Drawer Header -->
      <div class="drawer-header q-pa-md row items-center justify-between border-bottom-gold">
        <h3 class="text-h6 q-my-none font-playfair text-weight-bold" :class="$q.dark.isActive ? 'text-gold' : 'text-dark'">
          Filter & Sort
        </h3>
        <q-btn flat round dense icon="close" @click="filterStore.toggleDrawer" :color="$q.dark.isActive ? 'white' : 'grey-8'" />
      </div>

      <!-- Scrollable Content -->
      <q-scroll-area class="col q-px-md q-py-sm">
        
        <!-- Categories Section -->
        <div class="sidebar-section q-mb-lg">
          <h4 class="text-subtitle1 text-weight-bold q-mb-sm text-uppercase" :class="$q.dark.isActive ? 'text-grey-4' : 'text-grey-9'">
            Categories
          </h4>
          <q-list class="category-list">
            <q-item 
              clickable 
              v-ripple 
              :active="filterStore.selectedCategory === 'all'"
              @click="filterStore.selectedCategory = 'all'"
              active-class="active-category"
              class="category-item rounded-borders q-mb-xs transition-any"
              :class="$q.dark.isActive ? 'text-grey-3' : 'text-grey-8'"
            >
              <q-item-section avatar>
                <q-icon name="grid_view" size="xs" />
              </q-item-section>
              <q-item-section class="text-weight-medium">All Jewellery</q-item-section>
            </q-item>

            <q-separator class="q-my-xs opacity-50" />

            <q-item 
              v-for="cat in filterStore.categories" 
              :key="cat.id"
              clickable 
              v-ripple
              :active="filterStore.selectedCategory === cat.id"
              @click="filterStore.selectedCategory = cat.id"
              active-class="active-category"
              class="category-item rounded-borders q-mb-xs transition-any"
              :class="$q.dark.isActive ? 'text-grey-3' : 'text-grey-8'"
            >
              <q-item-section avatar>
                <q-icon name="diamond" size="xs" />
              </q-item-section>
              <q-item-section class="text-weight-medium">{{ cat.name }}</q-item-section>
              <q-item-section side v-if="filterStore.selectedCategory === cat.id">
                <q-icon name="check" color="gold" size="xs" />
              </q-item-section>
            </q-item>
          </q-list>
        </div>

        <q-separator class="q-mb-lg opacity-50" />

        <!-- Price Range Section -->
        <div class="sidebar-section q-mb-lg">
          <h4 class="text-subtitle1 text-weight-bold q-mb-md text-uppercase" :class="$q.dark.isActive ? 'text-grey-4' : 'text-grey-9'">
            Price Range
          </h4>
          
          <div class="price-inputs row q-col-gutter-sm q-mb-md">
            <div class="col-6">
              <q-input 
                outlined 
                dense 
                v-model.number="filterStore.priceRange.min" 
                label="Min" 
                prefix="LKR"
                :color="$q.dark.isActive ? 'gold' : 'primary'"
                class="price-input"
              />
            </div>
            <div class="col-6">
              <q-input 
                outlined 
                dense 
                v-model.number="filterStore.priceRange.max" 
                label="Max" 
                prefix="LKR"
                :color="$q.dark.isActive ? 'gold' : 'primary'"
                class="price-input"
              />
            </div>
          </div>

          <q-range
            v-model="filterStore.priceRange"
            :min="0"
            :max="500000"
            :step="5000"
            label
            color="gold"
            track-color="grey-4"
            thumb-size="18px"
            thumb-color="white"
            class="q-px-sm"
          />
          <div class="row justify-between text-caption text-grey-6 q-mt-sm">
             <span>LKR 0</span>
             <span>LKR 500k+</span>
          </div>
        </div>

        <q-separator class="q-mb-lg opacity-50" />

        <!-- Sort Section -->
        <div class="sidebar-section q-mb-xl">
           <h4 class="text-subtitle1 text-weight-bold q-mb-sm text-uppercase" :class="$q.dark.isActive ? 'text-grey-4' : 'text-grey-9'">
             Sort By
           </h4>
           <q-select 
             outlined 
             dense 
             v-model="filterStore.sortBy" 
             :options="filterStore.sortOptions" 
             emit-value 
             map-options 
             class="sort-select"
             color="gold"
             popup-content-class="text-weight-medium"
           >
             <template v-slot:prepend>
               <q-icon name="sort" color="gold" />
             </template>
           </q-select>
        </div>
      </q-scroll-area>

      <!-- Sticky Footer -->
      <div class="drawer-footer q-pa-md bg-white border-top-gold" :class="$q.dark.isActive ? 'bg-dark-page' : 'bg-white'">
        <div class="row q-gutter-md">
          <q-btn 
            outline 
            :color="$q.dark.isActive ? 'gold' : 'black'"
            label="Reset" 
            class="col-4" 
            @click="filterStore.resetFilters" 
          />
          <q-btn 
            unelevated 
            color="black" 
            label="Show Results" 
            class="col" 
            @click="viewResults"
          />
        </div>
      </div>
    </q-drawer>

    <q-page-container>
      <router-view />
      
      <!-- Premium Footer -->
      <footer class="premium-footer" :class="$q.dark.isActive ? 'bg-dark' : 'bg-grey-2'">
        <div class="container q-py-xl">
          <div class="row q-col-gutter-xl">
            <!-- Brand Column -->
            <div class="col-12 col-md-3">
              <div class="brand-logo-wrapper q-mb-md">
                <img src="/logo.png" alt="Nurya Jewellery" class="brand-logo-footer" />
              </div>
              <p class="text-subtitle1 text-grey-7">
                Crafting timeless elegance and bespoke beauty since 1995.
              </p>
              <div class="row q-gutter-sm q-mt-md">
                <q-btn round flat icon="facebook" color="primary" type="a" href="https://www.facebook.com/share/1Aar2Vdiiv/" target="_blank" />
                <q-btn round flat icon="camera_alt" color="primary" type="a" href="https://www.instagram.com/nuryajewellery" target="_blank">
                  <q-tooltip>Instagram</q-tooltip>
                </q-btn>
                <q-btn round flat icon="music_note" color="primary" type="a" href="https://www.tiktok.com/@nuryajewellery" target="_blank">
                  <q-tooltip>TikTok</q-tooltip>
                </q-btn>
                <q-btn round flat icon="image" color="primary" type="a" href="#" target="_blank">
                  <q-tooltip>Pinterest</q-tooltip>
                </q-btn>
              </div>
            </div>

            <!-- Links Column 1 -->
            <div class="col-6 col-md-2">
              <div class="text-h6 q-mb-lg text-gold">Shop</div>
              <q-list dense flat class="bg-transparent">
                <q-item clickable v-ripple to="/products"><q-item-section>All Collections</q-item-section></q-item>
                <q-item clickable v-ripple to="/products"><q-item-section>Rings</q-item-section></q-item>
                <q-item clickable v-ripple to="/products"><q-item-section>Necklaces</q-item-section></q-item>
                <q-item clickable v-ripple to="/products?sort=newest"><q-item-section>New Arrivals</q-item-section></q-item>
              </q-list>
            </div>

            <!-- Coupons Column -->
            <div class="col-12 col-md-4">
              <div class="text-h6 q-mb-lg text-gold">Exclusive Offers</div>
              
              <div v-if="voucherStore.loading" class="text-grey-7">Loading offers...</div>
              
              <div v-else-if="voucherStore.hasVouchers">
                <q-list dense class="q-gutter-y-sm">
                  <q-card 
                    v-for="voucher in voucherStore.activeVouchers.slice(0, 2)" 
                    :key="voucher.id" 
                    class="bg-dark text-white border-gold-dashed"
                    flat 
                    bordered
                  >
                    <q-card-section class="q-pa-sm row items-center justify-between">
                       <div>
                         <div class="text-bold text-gold">{{ voucher.code }}</div>
                         <div class="text-caption text-grey-4">{{ voucher.title }}</div>
                         <div class="text-caption text-grey-5" v-if="voucher.discount_value">
                            {{ voucher.discount_type === 'percentage' ? voucher.discount_value + '% OFF' : 'LKR ' + voucher.discount_value + ' OFF' }}
                         </div>
                       </div>
                       <q-btn flat round icon="content_copy" size="xs" color="gold" @click="copyCode(voucher.code)" />
                    </q-card-section>
                  </q-card>
                </q-list>
              </div>

              <div v-else class="text-grey-7 q-pa-sm border-gold-dashed rounded-borders text-center">
                 <q-icon name="local_offer" size="sm" class="q-mb-xs" />
                 <div class="text-caption">Stay tuned availability for<br>new exclusive offers!</div>
              </div>
            </div>

            <!-- Contact Column -->
            <div class="col-12 col-md-3">
              <div class="text-h6 q-mb-lg text-gold">Contact Us</div>
              <q-list dense flat class="bg-transparent">
                <q-item>
                  <q-item-section avatar><q-icon name="place" color="primary" /></q-item-section>
                  <q-item-section>No 29, Godakalana, Loluwagoda</q-item-section>
                </q-item>
                <q-item clickable tag="a" href="tel:0776819034">
                  <q-item-section avatar><q-icon name="phone" color="primary" /></q-item-section>
                  <q-item-section>077 681 9034</q-item-section>
                </q-item>
                <q-item clickable tag="a" href="mailto:Nuryajewellery@gmail.com">
                  <q-item-section avatar><q-icon name="email" color="primary" /></q-item-section>
                  <q-item-section>Nuryajewellery@gmail.com</q-item-section>
                </q-item>
                <q-item clickable v-ripple to="/faq">
                  <q-item-section avatar><q-icon name="help" color="primary" /></q-item-section>
                  <q-item-section>Frequently Asked Questions</q-item-section>
                </q-item>
              </q-list>
            </div>
          </div>

          <q-separator class="q-my-xl" />
          
          <div class="text-center text-grey-6">
            © {{ new Date().getFullYear() }} Nurya Jewellery. All rights reserved. Crafted with ❤️ for elegance.
          </div>
        </div>
      </footer>

      <!-- Floating WhatsApp Button with HELP Label -->
      <q-page-sticky position="bottom-right" :offset="[18, 90]">
        <q-btn
          fab-mini
          rounded
          color="green-6"
          text-color="black"
          class="whatsapp-float-btn q-px-md"
          @click="openWhatsAppSupport"
        >
          <!-- Custom WhatsApp SVG Icon -->
          <svg
            class="q-mr-sm"
            style="width: 24px; height: 24px"
            viewBox="0 0 24 24"
            fill="currentColor"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
          </svg>
          <span class="text-weight-bold">Help</span>
        </q-btn>
      </q-page-sticky>

      <!-- Back to top button -->
      <q-page-scroller position="bottom-right" :scroll-offset="150" :offset="[18, 18]">
        <q-btn fab icon="keyboard_arrow_up" color="primary" />
      </q-page-scroller>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useQuasar } from 'quasar';
import { useCartStore } from 'stores/cart';
import { useWishlistStore } from 'stores/wishlist';
import { useFilterStore } from 'stores/filter';
import { useVoucherStore } from 'stores/voucher';

const $q = useQuasar();
const cartStore = useCartStore();
const wishlistStore = useWishlistStore();
const filterStore = useFilterStore();
const voucherStore = useVoucherStore();
const leftDrawerOpen = ref(false);
const route = useRoute();
const router = useRouter();

// ...

// Fetch categories and vouchers
onMounted(() => {
  filterStore.fetchCategories();
  voucherStore.fetchVouchers();
});

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}

function toggleDarkMode() {
  $q.dark.toggle();
}


function openWhatsAppSupport() {
  const phone = '94776819034';
  const text = 'Hellow Nurya, I want to help for buy a product';
  const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
  window.open(url, '_blank');
}

const copyCode = (code) => {
  navigator.clipboard.writeText(code);
  $q.notify({
    message: 'Code copied!',
    color: 'gold',
    textColor: 'black',
    icon: 'check',
    position: 'top',
    timeout: 1000
  });
};

const viewResults = () => {
  // Close drawer
  filterStore.toggleDrawer();
  
  // Navigate to products page with current filters as query params
  router.push({
    path: '/products',
    query: {
      category: filterStore.selectedCategory !== 'all' ? filterStore.selectedCategory : undefined,
      min_price: filterStore.priceRange.min > 0 ? filterStore.priceRange.min : undefined,
      max_price: filterStore.priceRange.max < 500000 ? filterStore.priceRange.max : undefined,
      sort: filterStore.sortBy !== 'default' ? filterStore.sortBy : undefined
    }
  });
};

// Check if we are on a page that supports filtering
const showFilterButton = computed(() => {
  return ['/', '/products'].includes(route.path);
});


// Fetch categories for the drawer
onMounted(() => {
  filterStore.fetchCategories();
});
</script>

<style lang="scss">
.header-glass {
  background: rgba(18, 18, 18, 0.7) !important;
  backdrop-filter: blur(15px);
  border-bottom: 1px solid rgba(212, 175, 55, 0.3);
  transition: all 0.3s ease;
}

body.body--light .header-glass {
  background: rgba(255, 255, 255, 0.8) !important;
  .text-white { color: #1d1d1d !important; }
  .nav-link { color: #333 !important; }
}

.brand-logo-wrapper {
  cursor: pointer;
  display: flex;
  align-items: center;
}

.brand-text-container {
  font-family: 'Playfair Display', serif;
  font-weight: 800;
  letter-spacing: 2px;
  font-size: 1.25rem;
  line-height: 1;
}

.brand-logo {
  height: 40px; /* Adjust height as needed */
  width: auto;
  object-fit: contain;
}

.brand-logo-footer {
  height: 60px; /* Slightly larger for footer */
  width: auto;
  object-fit: contain;
}

.text-gold {
  color: #D4AF37;
}

.color-gold {
  color: #D4AF37;
}

.bg-gold {
  background: #D4AF37 !important;
}

.nav-link {
  font-weight: 600;
  font-size: 0.95rem;
  transition: color 0.3s ease;
  
  &:hover {
    color: #D4AF37 !important;
  }
}

.premium-footer {
  border-top: 1px solid rgba(212, 175, 55, 0.2);
  
  .q-item {
    padding-left: 0;
    transition: color 0.3s ease;
    &:hover {
      color: #D4AF37;
    }
  }
}

.container {
  max-width: 1400px;
  margin: 0 auto;
}

.whatsapp-float-btn {
  z-index: 2000;
  box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
  animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
  }
  70% {
    transform: scale(1.05);
    box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
  }
}

.border-gold-dashed {
  border: 1px dashed rgba(212, 175, 55, 0.5) !important;
}
</style>

