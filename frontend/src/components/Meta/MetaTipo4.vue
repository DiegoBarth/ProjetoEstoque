<template>
   <GraficoMetaValor
      :sTitulo="`${dados.sDescricao} (Valor total por usuário) - De ${dados.sDataInicial} à ${dados.sDataFinal}`"
      :sSubtitulo="' '"
      :sDescricaoExtra="dados.sUsuario"
      :fValorMeta="dados.fValorMeta"
      :fValorTotal="dados.fValorTotal"
   />
</template>

<script setup>
   import GraficoMetaValor from './GraficoMetaValor.vue';
   
   const props = defineProps({ dados: Object });
</script>