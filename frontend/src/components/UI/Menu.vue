<template>
   <aside class="min-w-[160px] w-full sm:w-64 bg-white shadow-lg flex flex-col h-full">
      <RouterLink to="/inicio"
         class="p-4 font-bold border-b border-gray-200 text-center block hover:text-sky-600" style="justify-content: space-around; font-size: 20px;">
         Atendimento
      </RouterLink>
      <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-6 text-sm text-gray-700">
         <div>
            <p class="text-l text-gray-500 uppercase mb-2">Cadastros</p>
            <div class="card-menu flex flex-col">
               <RouterLink v-for="item in itensCadastro" :key="item.sRota"
                  class="flex items-center gap-2 hover:text-teal-600" :to="{ name: item.sRota }">
                  <i :class="item.sIcone || 'fa fa-circle'"></i> {{ item.sTitulo }}
               </RouterLink>
            </div>
         </div>
         <div>
            <p class="text-l text-gray-500 uppercase mb-2">Gestão</p>
            <div class="card-menu flex flex-col">
               <RouterLink v-for="item in itensGestao" :key="item.sRota"
                  class="flex items-center gap-2 hover:text-teal-600" :to="{ name: item.sRota }">
                  <i :class="item.sIcone || 'fa fa-circle'"></i> {{ item.sTitulo }}
               </RouterLink>
            </div>
         </div>
      </nav>
      <div class="perfil border-t border-gray-300  p-4 flex items-center justify-between text-sm min-h-[100px]">
         <div>
            <p class="font-medium">Bem-vindo,</p>
            <p class="truncate max-w-[108px]">{{ sUsuario || 'Usuário' }}</p>
         </div>
         <div class="icone-usuario cursor-pointer flex justify-end items-center gap-2" @click="openModalLogout">
            <i class="fa fa-user-circle text-5xl"></i>
            <i class="fa-solid fa-right-from-bracket icone-logout"></i>
         </div>
      </div>
   </aside>

</template>
<script setup>
import Cookies from 'js-cookie';
import { onMounted, ref, computed } from 'vue';
import router from '@/router';

const sUsuario = ref('');
const iNivel   = ref('');

onMounted(() => {
   let oUsuario = Cookies.get('oUsuario');

   if(oUsuario) {
      oUsuario       = JSON.parse(atob(Cookies.get('oUsuario')));
      sUsuario.value = oUsuario.sNome?.split(" ")[0];
      iNivel.value   = Number(oUsuario.iNivel);
   }
   
});

const aItensMenu = [
   { sTitulo: 'Usuários',     sRota: 'Usuário',    sGrupo: 'cadastro', bControlaVisualizacao: true,  sIcone: 'fa fa-user' },
   { sTitulo: 'Fornecedores', sRota: 'Fornecedor', sGrupo: 'cadastro', bControlaVisualizacao: true,  sIcone: 'fa fa-truck' },
   { sTitulo: 'Clientes',     sRota: 'Cliente',    sGrupo: 'cadastro', bControlaVisualizacao: false, sIcone: 'fa fa-users' },
   { sTitulo: 'Produtos',     sRota: 'Produto',    sGrupo: 'cadastro', bControlaVisualizacao: true,  sIcone: 'fa fa-box' },
   { sTitulo: 'Vendas',       sRota: 'Vendas',     sGrupo: 'gestao',   bControlaVisualizacao: false, sIcone: 'fa fa-shopping-cart' },
   { sTitulo: 'Metas',        sRota: 'Meta',       sGrupo: 'gestao',   bControlaVisualizacao: true,  sIcone: 'fa fa-bullseye' },
   { sTitulo: 'Relatórios',   sRota: 'Relatório',  sGrupo: 'gestao',   bControlaVisualizacao: true,  sIcone: 'fa fa-file-alt' }
];

const itensCadastro = computed(() =>
   aItensMenu.filter(oItem =>
      oItem.sGrupo === 'cadastro' &&
      (!oItem.bControlaVisualizacao || (oItem.bControlaVisualizacao && iNivel.value == 1))
   )
);

const itensGestao = computed(() =>
   aItensMenu.filter(oItem =>
      oItem.sGrupo === 'gestao' &&
      (!oItem.bControlaVisualizacao || (oItem.bControlaVisualizacao && iNivel.value == 1))
   )
);

function openModalLogout() {
   const oModal = document.getElementById('modalLogout');

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

   a {
      display: flex;
      align-items: center;
      border-bottom: 1px solid var(--bordas);
      height: 5%;
      width: 100%;
      text-align: left;
      padding: 15px;
      font-size: 1rem;
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
      box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
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
