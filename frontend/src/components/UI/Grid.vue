<template>
    <table class="shadow-md mx-auto rounded-sm" style="width: 86%;">
        <thead class="grid bg-blue-800 rounded-t-sm" :style="{'grid-template-columns': sLayout}">
            <th v-for="(sCabecalho, iIndice) of aCabecalhos" class="p-2 text-left text-white" :style="{'grid-template-columns': sLayout}" :key="iIndice">{{ sCabecalho }}</th>
        </thead>
        <tbody>
            <slot></slot>
        </tbody>
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