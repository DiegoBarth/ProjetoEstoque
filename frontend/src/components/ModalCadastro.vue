<template>    
    <Modal v-if="bModalAberto" v-bind="$attrs" class="bg-gray-100 p-6 shadow-md w-220" id="modalCadastro">
        <div>
            <div class="flex items-center mb-4">        
                <h2 class="text-xl font-semibold">{{ sTitulo }}</h2>
            </div>
            <slot></slot>
            <div class="flex items-center justify-start mt-5 gap-2">
                <button id="incluir" v-if="iAcao == 1" @click="$emit('incluir')" class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Incluir
                </button>
                <button v-else-if="iAcao == 2" @click="$emit('alterar')" class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Atualizar
                </button>
                <button @click="$emit('fecharModal')" class="cursor-pointer px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">
                    {{ iAcao == 3 ? 'Fechar' : 'Cancelar' }}
                </button>
            </div>
        </div>
    </Modal>    
</template>
<script setup>
import { onUnmounted } from 'vue';
import Modal from '../components/UI/Modal.vue'

defineProps(['iAcao', 'sTitulo', 'bModalAberto']);
defineEmits(['incluir', 'alterar', 'fecharModal']);

</script>
<style lang="scss" scoped>

</style>