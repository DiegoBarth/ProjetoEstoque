<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aProdutos" class="mt-10 text-left"
         :aCabecalhos="['Produto', 'Nome do Produto', 'Valor de Custo', 'Valor de Venda', 'Quantidade', 'Valor de Desconto', 'Fornecedor', 'Ações']"
         sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr">
         <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
            <td class="p-2">{{ oProduto.procodigo }}</td>
            <td class="p-2">{{ oProduto.pronome }}</td>
            <td class="p-2">R$ {{ oProduto.procusto.replace('.', ',') }}</td>
            <td class="p-2">R$ {{ oProduto.provalor.replace('.', ',') }}</td>
            <td class="p-2">{{ oProduto.proestoque }}</td>
            <td class="p-2">R$ {{ oProduto.provalor_desconto.replace('.', ',') }}</td>
            <td class="p-2">{{ oProduto.forrazao_social }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oProduto)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oProduto)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oProduto.procodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import { format, parseISO } from 'date-fns';
import Grid from './UI/Grid.vue';

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aProdutos']);
</script>