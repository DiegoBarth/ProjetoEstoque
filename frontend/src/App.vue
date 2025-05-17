<script setup>
import { RouterLink, RouterView } from 'vue-router'
import Menu from './components/UI/Menu.vue'
import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <div class="principal">
    <Menu v-if="route.name != 'Login'" />
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
