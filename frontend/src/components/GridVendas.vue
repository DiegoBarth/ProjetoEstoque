<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aVendas" class="mt-10 text-left"
         :aCabecalhos="['Venda', 'Nome do Cliente', 'Vendador', 'Forma de Pagamento', 'Número de parcelas', 'Desconto', 'Valor total', 'Data da venda', 'Situação', 'Ações']">
         <tr v-for="(oVenda, iIndice) of aVendas" :key="iIndice">
            <td class="p-2">{{ oVenda.vecodigo                                     }}</td>
            <td class="p-2">{{ oVenda.cliente.clinome                              }}</td>
            <td class="p-2">{{ oVenda.usuario.usunome                              }}</td>
            <td class="p-2">{{ oVenda.forma_pagamento.fpnome                       }}</td>
            <td class="p-2">{{ oVenda.venumero_parcelas                            }}</td>
            <td class="p-2">{{ converterParaMoeda(oVenda.vedesconto)               }}</td>
            <td class="p-2">{{ converterParaMoeda(oVenda.vevalor_total)            }}</td>
            <td class="p-2">{{ formatarDataHora(oVenda.vedata_hora_venda) }}</td>
            <td class="p-2"><p class="text-center text-white p-1 rounded" :class="{'bg-green-700': oVenda.vesituacao == 2, 'bg-blue-700': oVenda.vesituacao == 1, 'bg-red-500': oVenda.vesituacao == 3}">{{ oVenda.vesituacao_nome }} </p></td>            
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="visualizarVenda(oVenda)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span :class="oVenda.vesituacao == 1 ? 'cursor-pointer' : 'cursor-not-allowed'" @click="$emit('showModal', 1, oVenda.vecodigo)"><i
                     class="fa-solid fa-dollar-sign p-2 px-[0.7rem] bg-green-700 rounded-sm text-white"></i></span>
               <span :class="oVenda.vesituacao == 1 ? 'cursor-pointer' : 'cursor-not-allowed'" @click="$emit('showModal', 2, oVenda.vecodigo)"><i
                     class="fa fa-xmark p-2 px-2.5 bg-red-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalDevolucao', oVenda)"><i
                     class="fa fa-undo p-2 px-2.5 bg-blue-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>   
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';

import { converterParaMoeda, formatarDataHora } from '../utils/main'
import { useAtendimentoStore } from '../stores/atendimentoStore';
import { useRouter } from 'vue-router';

const oAtendimentoStore = useAtendimentoStore();
const router = useRouter();

defineProps(['aVendas']);
defineEmits(['showModal', 'showModalDevolucao'])

async function visualizarVenda(oDados) {   
   const aItensVenda = await oAtendimentoStore.getItensVenda(oDados.vecodigo);
   oDados.aProdutos = aItensVenda;
   oAtendimentoStore.setDadosVenda(oDados);
   router.push({ name: 'Inicio'})
}

</script>