<template>
   <aside>
      <span v-for="(sItemMenu, iIndice) of aItensMenu" :key="iIndice">{{ sItemMenu }}</span>
      <span class="perfil">
         <h4>Bem vindo, <br> Fulano dos Santos.</h4>      
         <i class="fa fa-user-circle block text-5xl relative"></i>
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
         border: 1px solid var(--bordas);
      }
   }
}

.menuFechado {
   width: 100px;
   transition: all 1s;
}
</style>
