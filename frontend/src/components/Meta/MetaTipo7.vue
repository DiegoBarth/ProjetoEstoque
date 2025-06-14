<template>
   <GraficoMetaValor
      :sTitulo="`${dados.sDescricao} (Valor total por produto) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sProduto"
      :fValorMeta="dados.fValorMeta"
      :fValorTotal="dados.fValorTotal"
   />
</template>

<script setup>
   import GraficoMetaValor from './GraficoMetaValor.vue';

   const props = defineProps({ dados: Object });
</script>