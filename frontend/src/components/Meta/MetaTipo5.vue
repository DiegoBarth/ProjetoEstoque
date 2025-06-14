<template>
   <GraficoMetaQuantidade
      :sTitulo="`${dados.sDescricao} (Quantidade total por usuário) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sUsuario"
      :iQuantidadeMeta="dados.iQuantidadeMeta"
      :iQuantidadeAtual="dados.iQuantidadeTotal"
   />
</template>

<script setup>
   import GraficoMetaQuantidade from './GraficoMetaQuantidade.vue';
   
   const props = defineProps({ dados: Object });
</script>