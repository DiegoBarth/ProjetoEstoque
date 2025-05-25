<template>
    <Modal>
        <h1 class="text-xl mb-4">Confirma a exclusão do registro?</h1>
        <div class="flex items-center gap-4 w-2/5">
            <button @click="$emit('excluirRegistro')" class="text-white cursor-pointer p-2 bg-green-500 rounded-sm w-1/2">Sim</button>
            <button @click="$emit('fecharModal')" class="text-white cursor-pointer p-2 bg-red-500 rounded-sm w-1/2">Não</button>
        </div>
    </Modal>
</template>
<script setup>
import Modal from './Modal.vue'

defineEmits(['excluirRegistro', 'fecharModal'])
</script>
