<template>
   <component :is="inputType" v-bind="$attrs"
      :class="['bg-[color:var(--fundoInput)] focus:bg-white border rounded p-2 w-full']"
      autocomplete="off" :style="`outline: none; ${sStyle}`" :type="sTipo !== 'select' ? sTipo : undefined"
      :value="modelValue" :placeholder="sPlaceholder" :required="bObrigatorio" @input="onInput" @change="onChange">
      <template v-if="sTipo === 'select'">
         <option value="">Selecione</option>
         <option v-for="(oOpcao, i) in aOpcoes" :key="i" :value="oOpcao.iValor">
            {{ oOpcao.sDescricao }}
         </option>
      </template>
   </component>
</template>
<script setup>
const emit = defineEmits(["update:modelValue", "change"]);
const props = defineProps({
   modelValue: [String, Number],
   sTipo: {
      type: String,
      default: "text"
   },
   sStyle: String,
   bObrigatorio: Boolean,
   sPlaceholder: String,
   aOpcoes: {
      type: Array,
      default: () => []
   }
});

const inputType = props.sTipo === "select" ? "select" : "input";

function onInput(event) {
   emit("update:modelValue", event.target.value);
}

function onChange(event) {
   emit("change", event.target.value);
}
</script>