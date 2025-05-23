<template>
    <div>
        <label :class="{'obrigatorio': bObrigatorio}" class="block text-sm font-medium">{{ sTitulo }}</label>
        <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" v-if="sTipo != 'select'" v-bind="$attrs" :type="oCampo.sTipo" class="w-full mt-1 p-2 border rounded disabled:bg-gray-200" :placeholder="oCampo.sPlaceholder" :required="oCampo.bObrigatorio"/>
        <select v-else @change="$emit('update:modelValue', $event.target.value)" v-bind="$attrs" :value="modelValue" class="w-full mt-1 p-2 border rounded disabled:bg-gray-200" :placeholder="oCampo.sPlaceholder" :required="oCampo.bObrigatorio">
            <option value="">Selecione</option>
            <option v-for="(oOpcao, iIndice) of aOpcoes" :key="iIndice" :value="oOpcao.iValor">
                {{ oOpcao.sDescricao }}
            </option>
        </select>
    </div>
</template>
<script setup>
import { onMounted } from "vue";

const oCampo = defineProps([
    'sTipo',
    'bObrigatorio',
    'sPlaceholder',
    'sTitulo',
    'aOpcoes',
    'modelValue'
]);
defineEmits(['update:modelValue'])

</script>
<style scoped>
.obrigatorio {
    color: red;
}
</style>
