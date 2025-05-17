<template>
    <table>
        <thead>
            <th v-for="(sCabecalho, iIndice) of aCabecalhos" :style="{'grid-template-columns': sLayout}" :key="iIndice">{{ sCabecalho }}</th>
        </thead>
    </table>
</template>
<script setup>
const oPropriedades = defineProps(['aCabecalhos', 'sLayout']);
</script>
<style lang="scss" scoped>
table {
    display: grid;
}
</style>