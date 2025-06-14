<template>
   <GraficoMetaQuantidade
      :sTitulo="`${dados.sDescricao} (Quantidade total por produto) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sProduto"
      :iQuantidadeMeta="dados.iQuantidadeMeta"
      :iQuantidadeAtual="dados.iQuantidadeTotal"
   />
</template>

<script setup>
   import GraficoMetaQuantidade from './GraficoMetaQuantidade.vue';
   
   const props = defineProps({ dados: Object });
</script>