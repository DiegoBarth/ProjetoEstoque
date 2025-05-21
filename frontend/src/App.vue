<script setup>
import Menu from './components/UI/Menu.vue'
import ModalLogout from './components/ModalLogout.vue'
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <div class="flex w-full h-full">
    <Menu v-if="route.name != 'Login'" />
    <ModalLogout />
    <RouterView />
  </div>
  <div class='alerta-container'></div>
</template>

<style scoped>

</style>
