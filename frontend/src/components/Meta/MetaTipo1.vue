<template>
   <GraficoMetaValor
      :sTitulo="`${dados.sDescricao} (Valor total) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :fValorMeta="dados.fValorMeta"
      :fValorTotal="dados.fValorTotal"
   />
</template>

<script setup>
   import GraficoMetaValor from './GraficoMetaValor.vue';
   
   const props = defineProps({ dados: Object });
</script>