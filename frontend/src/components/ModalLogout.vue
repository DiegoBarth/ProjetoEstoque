<template>
    <Modal id="modalLogout">         
        <h2>Realmente deseja sair?</h2>
        <div class="flex gap-10 mt-5">
            <button class="cursor-pointer w-25 p-2 bg-green-600">Sim</button>
            <button class="cursor-pointer w-25 p-2 bg-red-600" @click="fechar">Não</button>
        </div>           
    </Modal>
</template>
<script setup>
import Modal from './UI/Modal.vue'

function fechar() {
    const oModal = document.getElementById('modalLogout');

    oModal.style.display = 'none';
}
</script>
<style lang="scss"scoped>
#modalLogout {
    display: none;

    h2 {
        display: block;
        font-size: 1.5rem;        
    }

    button {
        border-radius: 5px;
        color: white;
    }

}
</style>
