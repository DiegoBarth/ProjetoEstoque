<template>
   <button v-if="bVisivel" :type="sTipo" :id="sId" :style="{backgroundColor: `var(--${sCor})`, ...sStyle }" :disabled="bDesabilitado" :class="[
      'botao-card',
      'transition-transform duration-150 ease-in-out font-semibold rounded p-2 text-white',
      'hover:scale-95',
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
      default: 'principal'
   },
   sLargura: {
      type: String,
      default: 'full'
   },
   sStyle: {
      type: Object,
      default: () => ({})
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
   },
   bVisivel: {
      type: Boolean,
      default: true
   }
});

function onClick(event) {
   if (!props.bDesabilitado) {
      emit('click', event);
   }
}
</script>