<template>
   <aside class="min-w-[160px] w-full sm:w-64 p-4 bg-gray-100 flex flex-col h-full">
      <nav class="flex flex-col gap-2 flex-grow overflow-auto">
         <RouterLink v-for="(oItemMenu, iIndice) of aItensMenu" :key="iIndice" class="hover:text-sky-600" :to="{ name: oItemMenu.sRota }">
            {{ oItemMenu.sTitulo }}
         </RouterLink>
      </nav>
      <div class="perfil w-full border-t border-gray-300 mt-4 pt-4 box-border flex items-center justify-between">
         <h4 class="text-sm max-w-[108px] break-words">
            Bem vindo, <br /> {{ sUsuario || 'Usuário' }}
         </h4>
         <div class="icone-usuario cursor-pointer flex justify-end items-center gap-2" @click="openModalLogout">
            <i class="fa fa-user-circle text-5xl"></i>
            <i class="fa-solid fa-right-from-bracket icone-logout"></i>
         </div>
      </div>
  </aside>
</template>
<script setup>
import Cookies from 'js-cookie';
import { onMounted, ref } from 'vue';

const sUsuario = ref('');

onMounted(() => {
   sUsuario.value = Cookies.get('sUsuario')?.split(" ")[0];
});

const aItensMenu = [
   {
      sTitulo: 'Clientes',
      sRota  : 'Cliente'
   },
   {
      sTitulo: 'Fornecedores',
      sRota  : 'Fornecedor'
   },
   {
      sTitulo: 'Produtos',
      sRota  : 'Produto'
   },
   {
      sTitulo: 'Usuários',
      sRota  : 'Usuário'
   },
   {
      sTitulo: 'Venda',
      sRota  : 'Venda'
   },
   {
      sTitulo: 'Relatórios',
      sRota  : 'Relatório' 
   },
   {
      sTitulo: 'Metas',
      sRota  : 'Meta'  
   },
   {
      sTitulo: 'Usuários',
      sRota  : 'Meta'  
   }
];

function openModalLogout() {
   const oModal = document.getElementById('modalLogout');

   console.log(oModal)

   oModal.style.display = 'flex';
}
</script>
<style lang="scss" scoped>
aside {
   position: relative;
   height: 100%;    
   width: 12%;
   background-color: var(--elementos);
   border-right: 1px solid var(--bordas);
   box-shadow: 4px 0 10px 1px var(--bordas);
   transition: 1s width;
   

   a, span {
      display: flex;
      align-items: center;
      border-bottom: 1px solid var(--bordas);
      height: 5%;
      width: 100%;
      text-align: left;
      padding: 15px;
      font-size: 1rem;
   }   

   span.perfil {        
      border: none;
      border-top: 1px solid var(--bordas);
      position: absolute;
      bottom: 0;
      height: 20%;
      width: 100%;
      justify-content: space-between;      
   }

   .icone-usuario {
      position: relative;
      cursor: pointer;
   }

   .icone-logout {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #fff;
      color: #000;
      padding: 4px;
      border-radius: 50%;
      box-shadow: 0 0 4px rgba(0,0,0,0.2);      
      display: none;            
   }

   .icone-usuario:hover .icone-logout {
      display: block;      
   }
}

.menuFechado {
   width: 100px;
   transition: all 1s;
}
</style>
