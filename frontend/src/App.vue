<script setup>
import Menu from './components/UI/Menu.vue'
import ModalLogout from './components/ModalLogout.vue'
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <div class="principal">
    <Menu v-if="route.name != 'Login'" />
    <ModalLogout />
    <RouterView />
  </div>
</template>

<style scoped>
.principal {
  display: flex;
  width: 100%;
  height: 100%;
}
</style>
