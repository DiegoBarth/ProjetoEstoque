<script setup>
import TheWelcome from '../components/TheWelcome.vue'
import api from '../api';

async function chamaApi() {
  await api.get('/api');
}

</script>

<template>
  <main>
    <TheWelcome />
    <button @click="chamaApi()">Clica aqui pai</button>
  </main>
</template>
