<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aProdutos"
         :aCabecalhos="['Produto', 'Nome do Produto', 'Valor de Custo', 'Valor de Venda', 'Quantidade', 'Qtde Mínima Ideal', 'Valor de Desconto', 'Fornecedor', 'Ações']"
         sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr">
         <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
            <td class="p-2">{{ oProduto.iProduto }}</td>
            <td class="p-2">{{ oProduto.sNome }}</td>
            <td class="p-2">{{ oProduto.fValorCompra }}</td>
            <td class="p-2">{{ oProduto.fValorVenda }}</td>
            <td class="p-2">{{ oProduto.iQuantidade }}</td>
            <td class="p-2">{{ oProduto.iEstoqueMinimoIdeal }}</td>
            <td class="p-2">{{ oProduto.fDesconto ?? 'R$ 0,00' }}</td>
            <td class="p-2">{{ oProduto.sFornecedor }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oProduto)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oProduto)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oProduto.iProduto)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aProdutos']);
</script>