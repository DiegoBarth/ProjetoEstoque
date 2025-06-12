<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo="Relatórios" :bMostraBotao="false">
         <template #gridConsulta>
            <div class="px-[calc(10vw-70px)]">
               <Campo v-model="iTipo" sTitulo="Tipo" bLabel sTipo="select" class="w-50 mb-5" :aOpcoes="aOpcoesRelatorio" />
               
               <h1 v-if="iTipo   " class="text-2xl mb-5">Filtros</h1>

               <div v-if="iTipo == 1">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Data Final" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Situação" bLabel sTipo="select" :aOpcoes="[{iValor: 1, sDescricao: 'Ativo'}, {iValor:2, sDescricao: 'Inativo'}]" />
                  </div>
                  <Grid sTitulo="Fornecedores" :aCabecalhos="['', 'Fornecedor', 'Nome Fantasia']" :bDataTable="false" />               
               </div>

               <div v-if="iTipo == 2">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Data Final" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Situação" bLabel sTipo="select" :aOpcoes="[{iValor: 1, sDescricao: 'Aberta'}, {iValor:2, sDescricao: 'Finalizada'}, {iValor: 3, sDescricao: 'Cancelada'}]" />
                  </div>
                  <div class="flex gap-10">
                     <Grid class="w-1/2" sTitulo="Clientes"  :aCabecalhos="['', 'Cliente', 'Nome Cliente']" :bDataTable="false" />
                     <Grid class="w-1/2" sTitulo="Usuário (Vendedor)"  :aCabecalhos="['', 'Usuário', 'Nome Usuário']" :bDataTable="false" />
                  </div>
               </div>               

               <div v-if="iTipo >= 3 && iTipo < 6">
                  <div class="flex gap-10">
                     <Campo class="mb-3 w-50" sTitulo="Data Inicial (Venda)" bLabel sTipo="date" />
                     <Campo class="mb-3 w-50" sTitulo="Data Final (Venda)" bLabel sTipo="date" />                                          
                     <Campo class="mb-3 w-50" sTitulo="Situação (Venda)" bLabel sTipo="select" :aOpcoes="[{iValor: 1, sDescricao: 'Aberta'}, {iValor:2, sDescricao: 'Finalizada'}, {iValor: 3, sDescricao: 'Cancelada'}]" />
                  </div>                  
                  <Grid v-if="iTipo == 3" sTitulo="Produtos" :aCabecalhos="['', 'Produto', 'Nome do Produto']" :bDataTable="false" />
                  <Grid v-if="iTipo == 4" sTitulo="Usuário (Vendedor)" :aCabecalhos="['', 'Usuário', 'Nome de Usuário']" :bDataTable="false" />
                  <Grid v-if="iTipo == 5" sTitulo="Clientes" :aCabecalhos="['', 'Cliente', 'Nome do Cliente']" :bDataTable="false" />
               </div>    

               <div v-if="iTipo == 6">
                  <div class="flex gap-10">
                     <Grid class="w-1/2" sTitulo="Produtos" :aCabecalhos="['', 'Produto', 'Nome do Produto']" :bDataTable="false" />
                     <Grid class="w-1/2" sTitulo="Fornecedores" :aCabecalhos="['', 'Fornecedor', 'Nome do Fornecedor']" :bDataTable="false" />
                  </div>
               </div>           
               
            </div>
         </template>
      </Consulta>
   </div>
</template>
<script setup>
//#region Componentes
import Consulta from '../components/UI/Consulta.vue'
import Campo from '../components/UI/Campo.vue'
import Grid from '../components/UI/Grid.vue'
//#endregion

import { ref } from 'vue'

const iTipo = ref('');   
const aOpcoesRelatorio = ref([
   {
      iValor: 1,
      sDescricao: 'Produtos'   
   },   
   {
      iValor: 2,
      sDescricao: 'Vendas'   
   },   
   {
      iValor: 3,
      sDescricao: 'Vendas X Produto'
   },
   {
      iValor: 4,
      sDescricao: 'Vendas X Vendedor'
   },
   {
      iValor: 5,
      sDescricao: 'Vendas X Cliente'
   },
   {
      iValor: 6,
      sDescricao: 'Produto X Fornecedor'
   }   
])
</script>