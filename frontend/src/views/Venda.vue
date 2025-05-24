<template>
   <div class="grid grid-cols-3 gap-4 bg-neutral-100 min-h-screen box-border w-full items-stretch" style="padding: 30px;">
      <div class="col-span-2 bg-white shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[280px]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-box mr-2"></i> Produtos
         </div>
         <div class="p-4 grid grid-cols-[auto_auto] gap-x-6 gap-y-6 max-w-4xl mx-auto">
            <div class="flex gap-4 items-end">
               <Campo v-model="oProduto.iCodigo" sTipo="text" :bObrigatorio="true" sTitulo="Produto" />
               <div class="relative">
                  <Campo sTipo="text" :bObrigatorio="false" sTitulo="Produto" :bLabel="false" v-model="sConsultaProduto" sPlaceholder="Digite o nome do produto"  @input="filtrarSugestoes" @focus="bMostrarSugestoes = true" @blur="ocultarSugestoes" class="w-full"/>
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
            <button class="cursor-pointer col-span-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm px-3 py-1.5 w-fit">
               Adicionar
            </button>
         </div>
      </div>
      <div class="bg-white shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[280px]">
         <div class="bg-neutral-800 text-white font-semibold px-4 py-2 flex items-center">
            <i class="fas fa-user mr-2"></i> Cliente
         </div>
         <div class="p-4 w-full">
            <Campo class="w-1/2" sTipo="text" :bObrigatorio="false" sTitulo="CPF" v-model="sCpf" @input="utils.formatarCPF($event.target)"/>
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
import Campo from '../components/UI/Campo.vue';
import api from '../api';
import * as utils from "../utils/main.js";

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

const sConsultaProduto    = ref('')
const bMostrarSugestoes   = ref(false)
const aSugestoesFiltradas = ref([])
let aProdutosFiltrados    = [];
let debounceTimeout = null

watch(sCpf, async (novoValor) => {
   let sValorCpf = novoValor.replace(/\D/g, '');

   if(sValorCpf.length >= 11) {
      try {
         const { data } = await api.get(`/api/cliente/cpf/${sValorCpf}`);
         const oDados = data.oCliente;

         oCliente.value = {
         sNome: oDados.clinome || '',
         sEndereco: oDados.cliendereco || '',
         sTelefone: utils.formatarTelefone(oDados.clitelefone) || '',
         sDataNascimento: oDados.clidata_nascimento || ''
         };
      }
      catch (e) {
         console.error('Erro ao buscar cliente', e);
         oCliente.value = {
         sNome: '',
         sEndereco: '',
         sTelefone: '',
         sDataNascimento: ''
         };
      }
   }

   if(!sValorCpf) {
      oCliente.value = {
         sNome: '',
         sEndereco: '',
         sTelefone: '',
         sDataNascimento: ''
      };
   }
});

function filtrarSugestoes() {
   if(debounceTimeout) {
      clearTimeout(debounceTimeout);
   }

   debounceTimeout = setTimeout(async () => {
      const search = sConsultaProduto.value.trim();

      if(!search) {
         aSugestoesFiltradas.value = [];

         return;
      }

      try {
         const response = await api.get(`/api/produto?search=${encodeURIComponent(search)}`);
         const aDados = response.data.aProdutos;

         aSugestoesFiltradas.value = aDados.map(oDados => {
            return oDados.pronome;
         });

         aProdutosFiltrados = aDados;

      }
      catch (error) {
         console.error('Erro ao buscar sugestões:', error);
         aSugestoesFiltradas.value = [];
      }
   }, 1500)
}

function selecionarSugestao(sSugestao) {
   sConsultaProduto.value    = sSugestao;
   bMostrarSugestoes.value   = false;
   aSugestoesFiltradas.value = [];

   const resultados = aProdutosFiltrados.filter(item =>
      item.pronome.toLowerCase() === sSugestao.toLowerCase()
   );

   if(resultados.length > 0) {
      const produto = resultados[0];

      oProduto.value = {
         iCodigo:        produto.procodigo,
         sNome:          produto.pronome,
         iQuantidade:    produto.proestoque,
         fValorUnitario: produto.provalor,
         fValorDesconto: produto.provalor_desconto
      };
   }
   else {
      oProduto.value = null;
   }
};

function ocultarSugestoes() {
   setTimeout(() => {
      bMostrarSugestoes.value = false;
   }, 100)
}
</script>
