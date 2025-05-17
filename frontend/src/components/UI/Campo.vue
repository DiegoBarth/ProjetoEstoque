<template>
    <input :type="oCampo.sTipo" :placeholder="oCampo.sPlaceholder" :required="oCampo.bObrigatorio"/>
</template>
<script setup>
const oCampo = defineProps([
    'sTipo',
    'bObrigatorio',
    'sPlaceholder',
    'aEventos'
]);
</script>
<style scoped>

</style>
