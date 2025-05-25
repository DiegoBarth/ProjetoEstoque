<template>
   <div>
      <div class="flex items-center justify-between w-full py-10 px-30">
         <h1 class="cabecalho-principal text-3xl text-white font-semibold ">{{ sTitulo }}</h1>
         <Botao @click="$emit('showModalCadastro')" :sTexto="`Cadastrar ${sTitulo.toLowerCase()}`" sTipo="text" sId="botao_cadastrar" sLargura="w-fit" />
      </div>
      <div class="flex flex-wrap w-full gap-5 px-30">
         <slot name="filtros"></slot>
      </div>
      <slot name="gridConsulta"></slot>
   </div>
</template>
<script setup>
import Botao from '../UI/Botao.vue';

defineProps(['sTitulo']);
defineEmits(['showModalCadastro']);
</script>
<style lang="scss" scoped></style>
