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
   <div id="loading_sistema">
      <div class="spinner-loading">
         <div class="anel-loading"></div>
         <span>Carregando</span>
      </div>
   </div>
</template>

<style scoped>

</style>
