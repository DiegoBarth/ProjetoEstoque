<template>
   <GraficoMetaQuantidade
      :sTitulo="`${dados.sDescricao} (Quantidade total) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :iQuantidadeMeta="dados.iQuantidadeMeta"
      :iQuantidadeAtual="dados.iQuantidadeTotal"
   />
</template>

<script setup>
   import GraficoMetaQuantidade from './GraficoMetaQuantidade.vue';
   
   const props = defineProps({ dados: Object });
</script>