<template>
   <button :type="sTipo" :id="sId" :style="sStyle" :disabled="bDesabilitado" :class="[
      'transition-transform duration-150 ease-in-out font-semibold rounded p-2 text-white',
      'hover:scale-95',
      `bg-[var(--textoSecundario)]`,
      'hover:bg-[#4A4A4A]',
      sLargura === 'full' ? 'w-full' : sLargura,
      bDesabilitado ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
      sClasses
   ]" @click="onClick">
      {{ sTexto }}
   </button>
</template>

<script setup>
const emit = defineEmits(['click']);

const props = defineProps({
   sTexto: {
      type: String,
      default: 'Botão'
   },
   sTipo: {
      type: String,
      default: 'button'
   },
   sCor: {
      type: String,
      default: 'textoSecundario'
   },
   sLargura: {
      type: String,
      default: 'full'
   },
   sStyle: {
      type: String,
      default: ''
   },
   sId: {
      type: String,
      default: ''
   },
   bDesabilitado: {
      type: Boolean,
      default: false
   },
   sClasses: {
      type: String,
      default: ''
   }
});

function onClick(event) {
   if (!props.bDesabilitado) {
      emit('click', event);
   }
}
</script>