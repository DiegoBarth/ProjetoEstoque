<template>    
    <Modal v-if="bModalAberto" v-bind="$attrs" class="bg-gray-100 p-6 shadow-md w-220" id="modalCadastro">
        <div>
            <div class="flex items-center mb-4">        
                <h2 class="text-xl font-semibold">{{ sTitulo }}</h2>
            </div>
            <slot></slot>
            <div class="flex items-center justify-start mt-5 gap-2">
               <Botao v-if="iAcao === 1" sId="incluir" sCor="botaoVerde" sTexto="Incluir" sLargura="auto" @click="$emit('incluir')" :sStyle="{ padding: '0.5rem 1rem' }"/>
               <Botao v-else-if="iAcao === 2" sCor="botaoAzul" sTexto="Atualizar" sLargura="auto" @click="$emit('alterar')" :sStyle="{ padding: '0.5rem 1rem' }"/>
               <Botao sCor="botaoVermelho" :sTexto="iAcao === 3 ? 'Fechar' : 'Cancelar'" sLargura="auto" @click="$emit('fecharModal')" :sStyle="{ padding: '0.5rem 1rem' }"/>
            </div>
        </div>
    </Modal>    
</template>
<script setup>
import { onUnmounted } from 'vue';
import Modal from './Modal.vue'
import Botao from '../UI/Botao.vue';

defineProps(['iAcao', 'sTitulo', 'bModalAberto']);
defineEmits(['incluir', 'alterar', 'fecharModal']);

</script>
<style lang="scss" scoped>

</style>