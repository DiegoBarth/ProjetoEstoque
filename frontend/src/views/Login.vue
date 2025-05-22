<template>
   <div class="flex w-full h-full">
      <div class="cardLogin z-1 card bg-white w-2/3 h-full items-center justify-center flex">
         <div class="bg-white shadow-xl h-auto max-h-screen w-[90%] max-w-[320px] sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto flex flex-col items-center justify-center rounded-2xl p-4 sm:p-6" style="box-shadow: 0px 0px 50px lightgray">
            <div class="w-full">
               <div class="flex flex-col sm:flex-row items-center justify-center gap-5 lg:gap-10 py-7 w-full px-4">
                  <img src="../assets/img/logo1.png" class="h-20 sm:h-24 md:h-28 w-auto max-w-[80%] sm:max-w-[40%]" alt="" />
                  <img src="../assets/img/logo2.png" class="h-20 sm:h-24 md:h-28 w-auto max-w-[80%] sm:max-w-[40%]" alt="" />
               </div>
               <form @submit.prevent="login" class="flex flex-col items-center w-full px-4 sm:px-6">
                  <div class="flex flex-col gap-3 w-2/3 max-w-xs sm:max-w-sm md:max-w-md mx-auto">
                     <input class="border rounded p-2 w-full max-w-[280px]" style="border-color: #E0E0E0; outline: none;" type="text" v-model="sUsuario" name="usuario" placeholder="Usuário"/>
                     <div class="relative w-full max-w-[280px]">
                        <input class="border rounded p-2 w-full" id="senha" style="border-color: #E0E0E0; outline: none;" type="password" v-model="sSsenha" name="senha" placeholder="Senha"/>
                        <i class="fa fa-eye absolute top-3.5 right-3 cursor-pointer" @click="alterarCampoSenha"></i>
                     </div>
                     <p style="color: rgb(248, 70, 70); text-align: center;">{{ sErro }}</p>
                     <button type="submit" style="background-color: #7ac1e3; font-weight: 600" class="cursor-pointer bg-sky-400 text-white font-semibold rounded p-2 w-full sm:w-30 mt-2">
                        Entrar
                     </button>
                  </div>
               </form>
            </div>
         </div>
      </div>        
      <img src="../assets/img/fundoLogin.png" class="w-1/3 z-0">
   </div>
</template>
<script setup>
   import { useRouter } from 'vue-router';
   import { ref } from 'vue';
   import api from '../api';
   import Cookies from 'js-cookie';

   const sUsuario = ref('');
   const sSsenha  = ref('');
   const sErro    = ref('');
   const oRouter  = useRouter();

   function alterarCampoSenha() {
      if($('#senha').prop('type') == 'text') {
         return $('#senha').prop('type', 'password');
      }
      
      $('#senha').prop('type', 'text');
   }

   async function login() {
      sErro.value = '';

      if(!sUsuario.value || !sSsenha.value) {
         sErro.value = 'Usuário e senha são obrigatórios.';
         return;
      }

      try {
         await api.get('/sanctum/csrf-cookie')

         const {data, status} = await api.post('/api/login', {
            usunome_usuario: sUsuario.value,
            password       : sSsenha.value
         });         

         if(status != 200) {
            return sErro.value = data.sMensagem;
         }

         setDadosCookies(data.oUsuario);
         
         oRouter.push({ name: 'Inicio' });
      }
      catch(error) {
         console.error('Erro no login', error);

         sErro.value = 'Não foi possível realizar o login. Tente novamente.';
      }
   }

   function setDadosCookies(oUsuario) {
      Cookies.set('iUsuario', oUsuario.usucodigo);
      Cookies.set('sUsuario', oUsuario.usunome);
   }
</script>
<style scoped>
.cardLogin {
   box-shadow: 10px 0px 100px rgb(19, 19, 19);
}
</style>
