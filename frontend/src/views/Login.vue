<template>
   <div class="flex w-full h-full">
      <div class="cardLogin z-1 card bg-white w-2/3 h-full items-center justify-center flex">
         <div class="card-principal-login shadow-xl min-h-[70vh] w-[90%] max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg mx-auto flex flex-col items-center justify-center rounded-2xl p-4 sm:p-6">
            <div class="w-full">
               <div class="flex flex-col sm:flex-row items-center justify-center gap-5 lg:gap-10 py-7 w-full px-4">
                  <a href="https://www.instagram.com/ifix_foryou" target="_blank" rel="noopener noreferrer">
                     <img src="../assets/img/logo1.png" alt="" class="h-20 sm:h-24 md:h-28  cursor-pointer transition-transform duration-300 ease-in-out hover:scale-95"/>
                  </a>
                  <a href="https://www.instagram.com/foryou__tech" target="_blank" rel="noopener noreferrer">
                     <img src="../assets/img/logo2.png" alt="" class="h-20 sm:h-24 md:h-28  cursor-pointer transition-transform duration-300 ease-in-out hover:scale-95"/>
                  </a>
               </div>
               <form @submit.prevent="login" class="flex flex-col items-center w-full px-4 sm:px-6">
                  <div class="texto relative w-full max-w-[280px]" style="text-align: center; height:70px; font-size:30px; font-weight: 500;">Acesso ao Sistema</div>
                  <div class="flex flex-col gap-3 w-2/3 max-w-xs sm:max-w-sm md:max-w-md mx-auto">
                     <Input sTipo="text" v-model="sUsuario" name="usuario" sPlaceholder="Usuário"/>
                     <div class="relative w-full max-w-[280px]">
                        <Input sTipo="password" v-model="sSenha" id="senha" name="senha" sPlaceholder="Senha"/>
                        <i class="fa fa-eye absolute top-3.5 right-3 cursor-pointer" style="color: #4D4D4D;" @click="alterarCampoSenha"></i>
                     </div>
                     <p style="color: rgb(248, 70, 70); text-align: center;">{{ sErro }}</p>
                     <Botao :sStyle="{fontWeight: 600,alignSelf: 'center'}" sCor="textoSecundario" sTexto="Entrar" sTipo="submit" sId="button_login" sLargura="w-full sm:w-40"/>
                  </div>
               </form>
            </div>
         </div>
      </div>        
      <img src="../assets/img/fundoLogin.png" class="w-1/3 z-0">
   </div>
</template>
<script setup>
   import api from '../api';
   import { ref } from 'vue';
   import { useRouter } from 'vue-router';
   import { useUsuarioStore } from '../stores/usuarioStore';
   import Cookies from 'js-cookie';
   import Input from '../components/UI/Input.vue';
   import Botao from '../components/UI/Botao.vue';

   const sUsuario      = ref('');
   const sSenha        = ref('');
   const sErro         = ref('');
   const oRouter       = useRouter();
   const oUsuarioStore = useUsuarioStore();

   async function login() {
      sErro.value = '';

      if(!sUsuario.value || !sSenha.value) {
         sErro.value = 'Usuário e senha são obrigatórios.';
         return;
      }

      try {
         await oUsuarioStore.getToken();

         const {data, status} = await oUsuarioStore.login(sUsuario.value, sSenha.value);

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

   function alterarCampoSenha() {
      if($('#senha').prop('type') == 'text') {
         return $('#senha').prop('type', 'password');
      }
      
      $('#senha').prop('type', 'text');
   }

   function setDadosCookies(oUsuario) {
      let sUsuarioBase64 = btoa(JSON.stringify(oUsuario));
      
      Cookies.set('oUsuario', sUsuarioBase64);
   }

</script>
<style scoped>
.cardLogin {
   box-shadow: 10px 0px 100px rgb(19, 19, 19);
   background-color: #E9EFF2;
}
</style>
