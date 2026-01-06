<template>
  <router-view />
</template>

<script setup>
import { useQuasar } from 'quasar';

const $q = useQuasar();
$q.dark.set(true); // Set Dark Mode by default
</script>
