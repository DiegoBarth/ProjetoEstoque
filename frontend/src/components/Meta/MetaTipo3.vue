<template>
   <GraficoMetaValorQuantidade
      :sTitulo="`${dados.sDescricao} (Valor + Quantidade) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :iQuantidadeMeta="dados.iQuantidadeMeta"
      :iQuantidadeAtual="dados.iQuantidadeTotal"
      :fValorMeta="dados.fValorMeta"
      :fValorAtual="dados.fValorTotal"
   />
</template>

<script setup>
   import GraficoMetaValorQuantidade from './GraficoMetaValorQuantidade.vue';
   
   const props = defineProps({ dados: Object });
</script>