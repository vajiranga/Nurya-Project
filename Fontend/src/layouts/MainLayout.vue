<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-primary text-secondary">
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title class="text-h5 text-weight-bold" style="font-family: 'Playfair Display'">
          Nurya Jewellery
        </q-toolbar-title>

        <q-btn flat round icon="shopping_cart" to="/cart">
          <q-badge color="red" floating v-if="cartStore.itemCount > 0">{{ cartStore.itemCount }}</q-badge>
        </q-btn>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered class="bg-grey-1">
      <q-list>
        <q-item-label header class="text-primary text-weight-bold">
          Navigation
        </q-item-label>

        <q-item clickable v-ripple to="/">
          <q-item-section avatar>
            <q-icon name="home" color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Home</q-item-label>
          </q-item-section>
        </q-item>

        <q-item clickable v-ripple to="/cart">
          <q-item-section avatar>
            <q-icon name="shopping_cart" color="primary" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Cart</q-item-label>
          </q-item-section>
        </q-item>

        <q-separator spaced />

        <q-item-label header> Contact </q-item-label>
        <q-item clickable v-ripple href="https://wa.me/9471xxxxxxx" target="_blank">
          <q-item-section avatar>
            <q-icon name="wechat" color="green" />
          </q-item-section>
          <q-item-section>
            <q-item-label>WhatsApp Us</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import { useCartStore } from 'stores/cart';

const leftDrawerOpen = ref(false);
const cartStore = useCartStore();

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value;
}
</script>
