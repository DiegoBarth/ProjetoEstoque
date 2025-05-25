<template>
   <Modal id="modalLogout">
      <h2>Realmente deseja sair?</h2>
      <p style="color: rgb(248, 70, 70)">{{sErro}}</p>
      <div class="flex gap-10 mt-5">
         <Botao @click="logout" sTexto="Sim" sTipo="text" sId="botao_logout_sim" sCor="botaoVerde"    sLargura="w-25"/>
         <Botao @click="fechar" sTexto="Não" sTipo="text" sId="botao_logout_nao" sCor="botaoVermelho" sLargura="w-25"/>
      </div>
   </Modal>
</template>
<script setup>
import { useRouter } from 'vue-router';
import { ref } from 'vue';
import Modal from './UI/Modal.vue';
import api from '../api';
import Botao from './UI/Botao.vue';

const oRouter = useRouter();
const sErro   = ref('');

async function logout() {
   try {
      const response = await api.post('/api/logout');

      if(response.status !== 200) {
         sErro.value = 'Erro ao realizar logout.';
         return;
      }

      oRouter.push({ name: 'Login' });
   }
   catch(error) {
      console.error('Erro no logout:', error);

      sErro.value = 'Não foi possível sair da conta. Tente novamente.';
   }
   finally {
      sErro.value = '';
      fechar();
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