<template>
   <Modal id="modalLogout">
      <h2>Realmente deseja sair?</h2>
      <p style="color: rgb(248, 70, 70)">{{sErro}}</p>
      <div class="flex gap-10 mt-5">
         <button class="cursor-pointer w-25 p-2 bg-green-600" @click="logout">Sim</button>
         <button class="cursor-pointer w-25 p-2 bg-red-600" @click="fechar">Não</button>
      </div>
   </Modal>
</template>
<script setup>
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import Modal from './UI/Modal.vue';
import api from '../api';

const oRouter = useRouter();
const sErro   = ref('');

async function logout() {
   utils.adicionarLoading();
   try {
      const response = await api.post('/api/logout');

      if(response.status !== 200) {
         sErro.value = 'Erro ao realizar logout.';
         return;
      }

      fechar();
      oRouter.push({ name: 'Login' });
   }
   catch(error) {
      console.error('Erro no logout:', error);

      sErro.value = 'Não foi possível sair da conta. Tente novamente.';
   }
   finally {
      utils.removerLoading();
   }
}

function fechar() {
   const oModal = document.getElementById('modalLogout');

   oModal.style.display = 'none';
}
</script>
<style lang="scss" scoped>
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