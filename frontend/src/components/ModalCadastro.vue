<template>    
    <Modal v-bind="$attrs" class="bg-gray-100 p-6 shadow-md w-220" id="modalCadastro">
        <div>
            <div class="flex items-center mb-4">        
                <h2 class="text-xl font-semibold">{{ sTitulo }}</h2>
            </div>
            <slot></slot>
            <div class="flex items-center justify-start mt-5 gap-2">
                <button v-if="!bAlterar" @click="$emit('adicionarProduto')" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Incluir
                </button>
                <button v-else @click="$emit('atualizarProduto', iCodigo)" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Atualizar
                </button>
                <button @click="fecharModal" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">
                    Cancelar
                </button>
            </div>
        </div>
    </Modal>    
</template>
<script setup>
import Modal from '../components/UI/Modal.vue'

defineProps(['bAlterar', 'iCodigo', 'sTitulo']);
defineEmits(['adicionarProduto', 'atualizarProduto']);

function fecharModal() {
    $('#modalCadastro').hide();
}

</script>
<style lang="scss" scoped>
#modalCadastro {
    display: none;
}
</style>