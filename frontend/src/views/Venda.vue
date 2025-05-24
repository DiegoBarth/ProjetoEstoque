<template>
   <div class="grid grid-cols-3 gap-4 bg-neutral-100 min-h-screen box-border w-full items-stretch" style="padding: 30px;">
      <div class="col-span-2 bg-white shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[280px]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-box mr-2"></i> Produtos
         </div>
         <div class="p-4 grid grid-cols-[auto_auto] gap-x-6 gap-y-6 max-w-4xl mx-auto">
            <div class="flex gap-4 items-end">
               <Campo v-model="oProduto.iCodigo" sTipo="text" :bObrigatorio="true" sTitulo="Produto" @change="onChangeCodigoProduto"/>
               <div class="relative">
                  <Campo sTipo="text" :bObrigatorio="false" sTitulo="Produto" :bLabel="false" v-model="oProduto.sNome" sPlaceholder="Digite o nome do produto"  @input="filtrarSugestoes" @focus="() => {bMostrarSugestoes = true, bFocado = true}" @blur="() => {ocultarSugestoes(); bFocado = false;}" class="w-full"/>
                  <ul v-if="bMostrarSugestoes && aSugestoesFiltradas.length" class="absolute bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-y-auto z-50 w-full">
                     <li v-for="(sSugestao, iIndice) in aSugestoesFiltradas" :key="iIndice" @mousedown.prevent="selecionarSugestao(sSugestao)" class="px-4 py-2 hover:bg-blue-100 cursor-pointer truncate">
                        {{ sSugestao }}
                     </li>
                  </ul>
               </div>
            </div>
            <Campo v-model="oProduto.iQuantidade" sTipo="text" :bObrigatorio="true" sTitulo="Quantidade" sStyle="width:40%"/>
            <Campo v-model="oProduto.fValorUnitario" sTipo="text" :bObrigatorio="true" sTitulo="Valor unitário" maxlength="12" sStyle="width:40%"/>
            <Campo v-model="oProduto.fValorDesconto" sTipo="text" :bObrigatorio="false" sTitulo="Valor desconto" maxlength="12" sStyle="width:40%"/>
            <button class="cursor-pointer col-span-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm px-3 py-1.5 w-fit" @click="adicionarProduto">
               Adicionar
            </button>
         </div>
      </div>
      <div class="bg-white shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[280px]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-user mr-2"></i> Cliente
         </div>
         <div class="p-4 w-full">
            <Campo class="w-1/2" sTipo="text" maxlength="14" :bObrigatorio="false" sTitulo="CPF" v-model="sCpf" @change="onChangeCPF" @input="utils.formatarCPF($event.target)"/>
            <div v-if="oCliente.sNome" class="w-full mt-4 space-y-6 text-sm text-gray-700">
               <div class="flex gap-4">
                  <p class="w-1/2"><strong>Nome:</strong> {{ oCliente.sNome }}</p>
                  <p class="w-1/2"><strong>Data de Nascimento:</strong> {{ oCliente.sDataNascimento }}</p>
               </div>
               <p><strong>Endereço:</strong> {{ oCliente.sEndereco }}</p>
               <p><strong>Telefone:</strong> {{ oCliente.sTelefone }}</p>
            </div>
         </div>
      </div>
      <div class="col-span-2 bg-white shadow-lg rounded-xl overflow-hidden min-h-[40vh]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-clipboard-list mr-2"></i> Selecionados
         </div>
         <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm text-left">
               <thead class="bg-blue-800 text-white">
                  <tr>
                     <th class="px-4 py-2">Código</th>
                     <th class="px-4 py-2">Produto</th>
                     <th class="px-4 py-2">Quantidade</th>
                     <th class="px-4 py-2">Valor</th>
                     <th class="px-4 py-2">Desconto</th>
                     <th class="px-4 py-2">Valor total</th>
                     <th class="px-4 py-2">Ações</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
      <div class="bg-white shadow-lg rounded-xl overflow-hidden min-h-[40vh]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-credit-card mr-2"></i> Pagamento
         </div>
         <!-- <div class="p-4 space-y-2">
            <div class="flex justify-between">
               <label>Valor:</label>
               <label>Desconto: <input type="text" class="input w-20" /></label>
            </div>
            <div>Valor total:</div>
            <div>
               <label>Forma de pagamento:</label>
               <select class="input w-full mt-1">
                  <option>Selecione</option>
               </select>
            </div>
            <button class="w-full mt-4 bg-rose-300 hover:bg-rose-400 text-white text-lg py-2 rounded-md">
               Efetuar pagamento
            </button>
         </div> -->
      </div>
   </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useClienteStore } from '../stores/clienteStore';
import { useProdutoStore } from '../stores/produtoStore';
import api from '../api';
import Campo from '../components/UI/Campo.vue';
import * as utils from "../utils/main.js";

const oProdutoStore       = useProdutoStore();
const oClienteStore       = useClienteStore();
const bMostrarSugestoes   = ref(false)
const aSugestoesFiltradas = ref([])
const bFocado             = ref(false);
let debounceTimeout       = null
let oProdutoSelecionado   = null;
let aProdutosFiltrados    = [];

const sCpf     = ref('');
const oCliente = ref({
   sNome: '',
   sEndereco: '',
   sTelefone: '',
   sDataNascimento: ''
});
const oProduto = ref({
   iCodigo: '',
   sNome: '',
   iQuantidade: '',
   fValorUnitario: '',
   fValorDesconto: ''
});

function onChangeCodigoProduto() {
   if(!oProduto.value.iCodigo) {
      resetarProduto();
   }
}

async function onChangeCPF() {
   let sValorCpf = sCpf.value.replace(/\D/g, '');

   if(sValorCpf.length >= 11) {
      try {
         const oDados = await oClienteStore.getClienteByCPF(sValorCpf);

         oCliente.value = {
            sNome          : oDados.sNome,
            sEndereco      : oDados.sEndereco,
            sTelefone      : oDados.sTelefone,
            sDataNascimento: oDados.sDataNascimento
         };
      }
      catch(e) {
         console.error('Erro ao buscar cliente', e);
         resetarCliente();
      }
   }

   if(!sValorCpf) {
      resetarCliente();
   }
}

function filtrarSugestoes() {
   if(debounceTimeout) {
      clearTimeout(debounceTimeout);
   }

   debounceTimeout = setTimeout(async () => {
      if(!bFocado.value) {
         return;
      }

      const search = oProduto.value.sNome.trim();

      if(!search) {
         aSugestoesFiltradas.value = [];

         return;
      }

      try {
         const aProdutos = await oProdutoStore.getProdutoByNome(search);

         aSugestoesFiltradas.value = aProdutos.map(oProduto => {
            return oProduto.sNome;
         });

         aProdutosFiltrados = aProdutos;
      }
      catch (error) {
         console.error('Erro ao buscar sugestões:', error);
         aSugestoesFiltradas.value = [];
      }
   }, 1500);
}

function selecionarSugestao(sSugestao) {
   oProduto.value.sNome      = sSugestao;
   bMostrarSugestoes.value   = false;
   aSugestoesFiltradas.value = [];

   const aResultados = aProdutosFiltrados.filter(oProduto =>
      oProduto.sNome.toLowerCase() === sSugestao.toLowerCase()
   );

   if(aResultados.length > 0) {
      const oResultadoProduto = aResultados[0];
      oProdutoSelecionado     = oResultadoProduto;

      oProduto.value = {
         iCodigo       : oResultadoProduto.iProduto,
         sNome         : oResultadoProduto.sNome,
         iQuantidade   : oResultadoProduto.iQuantidade,
         fValorUnitario: oResultadoProduto.fValorVenda,
         fValorDesconto: oResultadoProduto.fDesconto
      };
   }
   else {
      resetarProduto();
   }
};

function ocultarSugestoes() {
   if(!oProduto.value.sNome && oProduto.value.iCodigo) {
      oProduto.value.sNome  = oProdutoSelecionado.sNome;
   }

   setTimeout(() => {
      bMostrarSugestoes.value = false;
   }, 100)
}

function adicionarProduto() {
   utils.validarCamposObrigatorios();
}

function resetarCliente() {
   oCliente.value = {
      sNome: '',
      sEndereco: '',
      sTelefone: '',
      sDataNascimento: ''
   };
}

function resetarProduto() {
   oProduto.value = {
      iCodigo:        '',
      sNome:          '',
      iQuantidade:    '',
      fValorUnitario: '',
      fValorDesconto: ''
   };
}
</script>