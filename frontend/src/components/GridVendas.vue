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
            <td class="p-2">{{ format(parseISO(oVenda.vedata_venda), 'dd/MM/yyyy') }}</td>
            <td class="p-2"><p class="text-center text-white p-1 rounded" :class="{'bg-green-700': oVenda.vesituacao == 1, 'bg-blue-700': oVenda.vesituacao == 2, 'bg-red-500': oVenda.vesituacao == 3}">{{ oVenda.vesituacao_nome }} </p></td>            
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oVenda)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span :class="oVenda.vesituacao == 1 ? 'cursor-pointer' : 'cursor-not-allowed'" @click="$emit('showModalCadastro', 3, oVenda)"><i
                     class="fa fa-receipt p-2 px-[1.1vh] bg-green-700 rounded-sm text-white"></i></span>
               <span :class="oVenda.vesituacao == 1 ? 'cursor-pointer' : 'cursor-not-allowed'" @click="$emit('showModalExclusao', oVenda.clicodigo)"><i
                     class="fa fa-xmark p-2 px-2.5 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';

import { parseISO, format } from 'date-fns'
import { converterParaMoeda } from '../utils/main'

defineProps(['aVendas']);

</script>