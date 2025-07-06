<template>
    <Modal class="p-[35px]">
      <h1 class="mr-auto text-[1.3rem] font-bold">Realizar devolução</h1>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-1/2">
         <Campo class="col-span-2" :disabled="bDevolucaoJaExiste" sTipo="text" maxlength="100" :bObrigatorio="false" sTitulo="Motivo da devolução" v-model="sMotivo"/>
      </div>
      <div class="w-[70vw] my-5 shadow-md h-[calc(100vh-500px)] rounded-xl overflow-hidden">
         <Grid v-if="aProdutos" :aCabecalhos="['Código', 'Produto', 'Quantidade', 'Valor', 'Desconto', 'Valor total', 'Quantidade Devolução']" :bDataTable="false">
            <tr v-for="(oProduto, iIndice) of aProdutos" :key="iIndice">
               <td class="p-2">{{ oProduto.iProduto }}</td>
               <td class="p-2">{{ oProduto.sNome }}</td>
               <td class="p-2">{{ oProduto.iQuantidade }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(oProduto.sValor) }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(parseFloat(oProduto.sDesconto).toFixed(2)) }}</td>
               <td class="p-2">{{ utils.converterParaMoeda(oProduto.sValorTotal) }}</td>
               <td class="p-2"><Campo class="text-center w-1/2" v-model="aDevolucoes[iIndice].iQuantidadeDevolvida" sTipo="number" :max="oProduto.iQuantidade" min="0" :disabled="bDevolucaoJaExiste" :bObrigatorio="false" @change="validarDevolucao(iIndice, oProduto.iQuantidade)"/></td>
            </tr>
         </Grid>         
      </div>
      <div class="my-5 gap-5 flex mr-auto">
         <Botao v-if="!bDevolucaoJaExiste" sCor="botaoVerde" sTexto="Confirmar" sLargura="auto" @click="confirmarDevolucao"   :sStyle="{ padding: '0.5rem 1rem' }"/>
         <Botao sCor="botaoVermelho" :sTexto="bDevolucaoJaExiste ? 'Fechar' : 'Cancelar'" sLargura="auto" @click="$emit('fecharModalDevolucao')" :sStyle="{ padding: '0.5rem 1rem' }"/>
      </div>
   </Modal>
</template>
<script setup>
import Modal from './UI/Modal.vue';
import Grid from './UI/Grid.vue'
import Campo from './UI/Campo.vue'
import * as utils from '../utils/main';
import Botao from './UI/Botao.vue';
import { ref, watch, onMounted } from 'vue';
import { useAtendimentoStore } from '@/stores/atendimentoStore';

const emit        = defineEmits(['fecharModalDevolucao', 'confirmarDevolucao']);
const props       = defineProps(['aProdutos', 'iVendaDevolucao']);
const oVendaStore = useAtendimentoStore();
const aProdutos   = props.aProdutos;
const iVendaDevolucao = props.iVendaDevolucao;
const aDevolucoes = ref([]);
const sMotivo     = ref('');
const bDevolucaoJaExiste = ref(false);

onMounted(async () => {
   if(!iVendaDevolucao) {
      return;
   }

   try {
      const oDados = await oVendaStore.buscarDevolucao(iVendaDevolucao);

      if(oDados.aProdutos) {
         bDevolucaoJaExiste.value = true;
         sMotivo.value            = oDados.sMotivo;

         aDevolucoes.value   = aProdutos.map(produto => {
            const oDevolvido = oDados.aProdutos.find(oProduto => oProduto.iProduto === produto.iProduto);

            return {
               iProduto: produto.iProduto,
               iQuantidadeDevolvida: oDevolvido ? oDevolvido.iQuantidadeDevolvida : 0
            };
         });
      }
   } catch (error) {
      console.error('Erro ao buscar devolução existente:', error);
   }
});

watch(
  () => props.aProdutos,
  (novoValor) => {
    if(novoValor && Array.isArray(novoValor)) {
      aDevolucoes.value = novoValor.map(produto => ({
        iProduto: produto.iProduto,
        iQuantidadeDevolvida: 0
      }));
    }
  },
  { immediate: true }
);

function validarDevolucao(iIndice, iQuantidadeMaxima) {
   const oDevolucao = aDevolucoes.value[iIndice];

   if(!oDevolucao || typeof oDevolucao.iQuantidadeDevolvida !== 'number') {
      return;
   }

   if(oDevolucao.iQuantidadeDevolvida > iQuantidadeMaxima) {
      oDevolucao.iQuantidadeDevolvida = Number(iQuantidadeMaxima);
   }
   else if(oDevolucao.iQuantidadeDevolvida < 0) {
      oDevolucao.iQuantidadeDevolvida = 0;
   }
}

function confirmarDevolucao() {
  const aDevolucoesFiltradas = aDevolucoes.value.filter(item => item.iQuantidadeDevolvida > 0);

   if(!aDevolucoesFiltradas.length) {
      return utils.alerta('Selecione ao menos um produto para realizar a devolução.', 'error');
   }
   
   emit('confirmarDevolucao', aDevolucoesFiltradas, sMotivo.value);
}

</script>