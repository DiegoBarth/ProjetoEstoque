<template>
    <div>
        <div class="flex items-center justify-between w-full py-10 px-30">
        <h1 class="text-3xl">{{ sTitulo }}</h1>
        <button @click="$emit('showModalCadastro')" class="cursor-pointer bg-green-400 rounded-sm p-2">Cadastrar {{ sTitulo.toLowerCase() }}</button>
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
