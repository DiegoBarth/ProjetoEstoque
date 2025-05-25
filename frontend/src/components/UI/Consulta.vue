<template>
    <div>
        <div class="flex items-center justify-between w-full py-10 px-30">
        <h1 class="cabecalho-principal text-3xl text-white font-semibold ">{{ sTitulo }}</h1>
        <button @click="$emit('showModalCadastro')" class="cursor-pointer rounded-sm p-2 bg-[var(--principal)] text-white">Cadastrar {{ sTitulo.toLowerCase() }}</button>
        </div>
        <div class="flex flex-wrap w-full gap-5 px-30">
            <slot name="filtros"></slot>
        </div>
        <slot name="gridConsulta"></slot>
    </div>
</template>
<script setup>
defineProps(['sTitulo'])
defineEmits(['showModalCadastro'])
</script>
<style lang="scss"scoped>

</style>
