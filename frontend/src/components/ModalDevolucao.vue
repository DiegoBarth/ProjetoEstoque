<template>
    <Modal class="p-[35px]">
      <h1 class="mr-auto text-[1.3rem] font-bold">Realizar devolução</h1>
      <div class="w-[70vw] my-5 shadow-md h-[calc(100vh-500px)] rounded-xl overflow-hidden">
         <Grid v-if="aProdutos" :aCabecalhos="['Código', 'Produto', 'Quantidade', 'Valor', 'Desconto', 'Valor total', 'Quantidade Devolução']" :bDataTable="false">
            <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
               <td class="p-2">{{ oProduto.iProduto }}</td>
               <td class="p-2">{{ oProduto.sNome }}</td>
               <td class="p-2">{{ oProduto.iQuantidade }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(oProduto.sValor) }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(parseFloat(oProduto.sDesconto).toFixed(2)) }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(oProduto.sValorTotal) }}</td>
               <td class="p-2"><Campo class="text-right" v-model="aDevolucoes[iIndice]" sTipo="number" :max="oProduto.iQuantidade" min="0" :bObrigatorio="false" /></td>
            </tr>
         </Grid>         
      </div>
      <div class="my-5 gap-5 flex mr-auto">
         <Botao sCor="botaoVerde"    sTexto="Confirmar" sLargura="auto" @click="$emit('confirmarDevolucao')"   :sStyle="{ padding: '0.5rem 1rem' }"/>
         <Botao sCor="botaoVermelho" sTexto="Cancelar"  sLargura="auto" @click="$emit('fecharModalDevolucao')" :sStyle="{ padding: '0.5rem 1rem' }"/>
      </div>
   </Modal>
</template>
<script setup>
import Modal from './UI/Modal.vue';
import Grid from './UI/Grid.vue'
import Campo from './UI/Campo.vue'
import * as utils from '../utils/main';
import Botao from './UI/Botao.vue';
import { ref } from 'vue';

defineEmits(['fecharModalDevolucao', 'confirmarDevolucao']);
defineProps(['aProdutos']);

const aDevolucoes = ref([]);

</script>
