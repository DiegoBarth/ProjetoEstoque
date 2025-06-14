<template>
   <GraficoMetaValorQuantidade
      :sTitulo="`${dados.sDescricao} (Valor + Quantidade por produto) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sProduto"
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