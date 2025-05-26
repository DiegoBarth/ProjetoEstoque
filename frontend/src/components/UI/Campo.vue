<template>
   <div>
      <label v-if="bLabel" :class="{ 'obrigatorio': bObrigatorio }" class="block text-sm font-medium">
         {{ sTitulo }}
      </label>
      <Input v-bind="$attrs" :modelValue="modelValue" @update:modelValue="$emit('update:modelValue', $event)"
         @change="$emit('change', $event)" :sTipo="sTipo" :sStyle="sStyle" :sPlaceholder="sPlaceholder"
         :bObrigatorio="bObrigatorio" :bDesabilitado="bDesabilitado" :aOpcoes="aOpcoes" />
   </div>
</template>

<script setup>
import Input from './Input.vue'

defineProps({
   sTitulo: String,
   bObrigatorio: Boolean,
   bDesabilitado: Boolean,
   bLabel: {
      type: Boolean,
      default: true
   },
   sStyle: String,
   sTipo: {
      type: String,
      default: "text"
   },
   sPlaceholder: String,
   aOpcoes: {
      type: Array,
      default: () => []
   },
   modelValue: [String, Number]
});

defineEmits(['update:modelValue', 'change']);
</script>