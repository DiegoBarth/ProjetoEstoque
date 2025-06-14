<template>
   <GraficoMetaValorQuantidade
      :sTitulo="`${dados.sDescricao} (Valor + Quantidade por usuário) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sUsuario"
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