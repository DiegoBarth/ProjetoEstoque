<template>
   <div class="flex w-full h-full">
      <div class="cardLogin z-1 card bg-white w-2/3 h-full items-center justify-center flex">
         <div class="card bg-white shadow-xl h-1/2 w-2/4 items-center justify-center flex flex-col rounded-2xl" style="box-shadow: 0px 0px 50px lightgray">
               <div class="flex items-center gap-5 py-7">
                  <img src="../assets/img/logo1.png" class="h-29" alt="">
                  <i class="fa fa-plus text-4xl"></i>
                  <img src="../assets/img/logo2.png" class="h-29" alt="">
               </div>                
               <div class="flex flex-col gap-2">
                  <input class="border-1 rounded-sm w-xs p-2" style="border-color: #E0E0E0; outline: none;" type="text" v-model="sUsuario" name="usuario" placeholder="Usuário"/>
                  <input class="border-1 rounded-sm w-xs p-2" style="border-color: #E0E0E0; outline: none;" type="text" v-model="sSsenha" name="senha" placeholder="Senha"/>
                  <p style="color: rgb(248, 70, 70)">{{sErro}}</p>
                  <button @click="handleLogin" type="submit" style="background-color: #7ac1e3; font-weight: 600" class="cursor-pointer w-1/3 rounded p-1 text-white">Entrar</button>
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

   const sUsuario = ref('');
   const sSsenha  = ref('');
   const sErro    = ref('');
   const oRouter  = useRouter();

   const handleLogin = async () => {
      sErro.value = '';
      try {
         await api.get('/sanctum/csrf-cookie')

         const response = await api.post('/api/login', {
            usunome_usuario: sUsuario.value,
            password: sSsenha.value
         });         

         if(response.status != 200) {
            return sErro.value = response.data.sMensagem;
         }
         
         oRouter.push({ name: 'Inicio' });
      }
      catch (e) {
         console.error(e);
      }
   }
</script>
<style scoped>
.cardLogin {
   box-shadow: 10px 0px 100px rgb(19, 19, 19);
}
</style>
