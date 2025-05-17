<template>
   <aside>
      <span class="controleMenu"><i @click="controlaMenu()" class="fa-solid fa-xmark"></i></span>
      <span v-for="(sItemMenu, iIndice) of aItensMenu" :key="iIndice">{{ sItemMenu }}</span>
      <span class="perfil">
         <!-- <h4>Bem vindo, <br> Fulano dos Santos.</h4> -->
         <div class="perfilImg rounded-full w-20 h-20">
               
         </div>
      </span>
   </aside>
</template>
<script setup>
const aItensMenu = [
   'Cadastros',
   'Venda',
   'Relatórios',
   'Metas'
]

function controlaMenu() {
   const oMenu = document.querySelector('aside');
   const oIcon = document.querySelector('aside span i');

   if(oMenu.classList.contains('menuFechado')) {
      oMenu.classList.remove('menuFechado');
      oIcon.classList.remove('fa-bars');
      oIcon.classList.add('fa-xmark');
      return
   }

   oIcon.classList.remove('fa-xmark');
   oMenu.classList.add('menuFechado');
   oIcon.classList.add('fa-bars');
}

</script>
<style lang="scss" scoped>
aside {
   position: relative;
   height: 100%;    
   width: 18%;
   background-color: var(--elementos);
   transition: 1s width;
   

   span {
      display: flex;
      align-items: center;
      border-bottom: 1px solid var(--bordas);
      height: 8%;
      width: 100%;
      text-align: left;
      padding: 15px;
      font-size: 1.4rem;
   }

   span.controleMenu {
      justify-content: center;

      i {
         font-size: 2rem;
      }
   }    

   span.perfil {        
      border: none;
      border-top: 1px solid var(--bordas);
      position: absolute;
      bottom: 0;
      height: 20%;
      width: 100%;
      justify-content: space-between;

      .perfilImg {                              
         border: 1px solid white;
      }
   }
}

.menuFechado {
   width: 100px;
   transition: all 1s;
}
</style>
