<template>
    <Modal>
      <div class="w-[70vw] h-[calc(100vh-300px)] m-[25px] rounded-xl overflow-hidden">
         <Consulta sTitulo='Venda' :bMostraBotao=false>
            <template #gridConsulta>
               <table class="table-auto w-full text-sm text-left">
                     <thead class="text-white" style="background-color: var(--textoPrincipal);">
                     <tr>
                        <th class="px-4 py-2">Código</th>
                        <th class="px-4 py-2">Produto</th>
                        <th class="px-4 py-2">Quantidade</th>
                        <th class="px-4 py-2">Valor</th>
                        <th class="px-4 py-2">Desconto</th>
                        <th class="px-4 py-2">Valor total</th>
                     </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(produto, index) in aProdutos" :key="index">
                           <td class="p-2">{{ produto.iProduto }}</td>
                           <td class="p-2">{{ produto.sNome }}</td>
                           <td class="p-2">{{ produto.iQuantidade }}</td>
                           <td class="p-2">{{ utils.converterParaMoeda(produto.sValor) }}</td>
                           <td class="p-2">{{ utils.converterParaMoeda(produto.sDesconto) }}</td>
                           <td class="p-2">{{ utils.converterParaMoeda(produto.sValorTotal.toFixed(2)) }}</td>
                        </tr>
                     </tbody>
                  </table>
            </template>
         </Consulta>
      </div>
      <div>
         <Botao sCor="botaoVerde"    sTexto="Confirmar" sLargura="auto" @click="$emit('confirmarDevolucao')"   :sStyle="{ padding: '0.5rem 1rem' }"/>
         <Botao sCor="botaoVermelho" sTexto="Cancelar"  sLargura="auto" @click="$emit('fecharModalDevolucao')" :sStyle="{ padding: '0.5rem 1rem' }"/>
      </div>
   </Modal>
</template>
<script setup>
import Modal from './Modal.vue'
import Consulta from './Consulta.vue';
import * as utils from '../utils/main';
import Botao from '../UI/Botao.vue';

defineEmits(['fecharModalDevolucao', 'confirmarDevolucao']);
defineProps(['aProdutos']);

</script>
