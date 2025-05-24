<template>
   <div>
      <label v-if="bLabel" :class="{'obrigatorio': bObrigatorio}" class="block text-sm font-medium">
         {{ sTitulo }}
      </label>
      <input :style="sStyle || null" :value="modelValue" @input="onInput" @change="onChange" v-if="sTipo != 'select'" v-bind="$attrs" :type="oCampo.sTipo" class="w-full mt-1 p-2 border rounded disabled:bg-gray-200" :placeholder="oCampo.sPlaceholder" :required="oCampo.bObrigatorio"/>
      <select v-else @change="$emit('update:modelValue', $event.target.value)" v-bind="$attrs" :value="modelValue" class="w-full mt-1 p-2 border rounded disabled:bg-gray-200" :placeholder="oCampo.sPlaceholder" :required="oCampo.bObrigatorio">
         <option value="">Selecione</option>
         <option v-for="(oOpcao, iIndice) of aOpcoes" :key="iIndice" :value="oOpcao.iValor">
            {{ oOpcao.sDescricao }}
         </option>
      </select>
   </div>
</template>
<script setup>
import { ref, onMounted, watch } from "vue";

const oCampo = defineProps({
   'sTipo' : String,
   'sStyle': String,
   'bObrigatorio' : Boolean,
   'sPlaceholder' : String,
   'sTitulo' : String,
   'bLabel': {
      type: Boolean,
      default: true
   },
   'aOpcoes' : String,
   'modelValue': [String, Number]
});

const emit        = defineEmits(['update:modelValue', 'change']);
const oLocalValue = ref(oCampo.modelValue);

watch(() => oCampo.modelValue, (sValue) => {
  oLocalValue.value = sValue;
});

function onInput(oEvento) {
  oLocalValue.value = oEvento.target.value;
  emit('update:modelValue', oEvento.target.value);
}

function onChange(oEvento) {
  emit('change', oEvento.target.value);
}

</script>
<style scoped>
   .obrigatorio {
      color: red;
   }
</style>