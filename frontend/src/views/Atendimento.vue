<template>
   <div class="fundo-principal grid grid-cols-3 gap-4 min-h-screen box-border w-full items-stretch" style="padding: 30px; height: 100%; overflow:auto; grid-template-rows: minmax(290px, 35%) 1fr;">
      <div class="card-principal col-span-2 shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[290px]">
         <div class="cabecalho-principal text-white font-semibold px-4 py-2 flex items-center">
            <i class="icone-cabecalho fas fa-box mr-2"></i> Produtos
         </div>
         <div class="p-4 grid grid-cols-[auto_auto] gap-x-6 gap-y-6 max-w-4xl mx-auto">
            <div class="flex gap-4 items-end">
               <Campo v-model="oProduto.iProduto" sTipo="text" :bObrigatorio="true" sTitulo="Produto" @change="onChangeCodigoProduto"/>
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
            <Campo v-model="oProduto.fValorVenda" sTipo="text" :bObrigatorio="true" sTitulo="Valor unitário" maxlength="12" sStyle="width:40%"/>
            <Campo v-model="oProduto.fDesconto" sTipo="text" :bObrigatorio="false" sTitulo="Valor desconto" maxlength="12" sStyle="width:40%"/>
            <Botao sTexto="Adicionar" sTipo="text" sId="botao_adicionar_produto" sLargura="w-fit" @click="adicionarProduto"/>
         </div>
      </div>
      <div class="card-principal shadow-lg rounded-xl overflow-hidden max-h-[35vh] min-h-[290px]">
         <div class="cabecalho-principal text-white font-semibold px-4 py-2 flex items-center">
            <i class="icone-cabecalho fas fa-user mr-2"></i> Cliente
         </div>
         <div class="p-4 w-full" style="overflow: auto; height: calc(100% - 47px);">
            <Campo class="w-1/2" sTipo="text" maxlength="14" :bObrigatorio="false" sTitulo="CPF" v-model="sCpf" @change="onChangeCPF" @input="utils.formatarCPF($event.target)"/>
            <div v-if="oCliente.sNome" class="w-full mt-4 space-y-6 text-sm text-gray-700">
               <div class="flex gap-4">
                  <p class="w-1/2"><strong style="font-weight: bold;">Nome:</strong> {{ oCliente.sNome }}</p>
                  <p class="w-1/2"><strong style="font-weight: bold;">Data de Nascimento:</strong> {{ oCliente.sDataNascimento }}</p>
               </div>
               <p><strong style="font-weight: bold;">Endereço:</strong> {{ oCliente.sEndereco }}</p>
               <p><strong style="font-weight: bold;">Telefone:</strong> {{ oCliente.sTelefone }}</p>
            </div>
         </div>
      </div>
      <div class="card-principal col-span-2 shadow-lg rounded-xl overflow-hidden min-h-[290px]">
         <div class="cabecalho-principal text-white font-semibold px-4 py-2 flex items-center">
            <i class="icone-cabecalho fas fa-clipboard-list mr-2"></i> Selecionados
         </div>
         <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm text-left">
               <thead class="text-white" style="background-color: var(--textoPrincipal);">
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
      <div class="card-principal shadow-lg rounded-xl overflow-hidden min-h-[290px]">
         <div class="cabecalho-principal text-white font-semibold px-4 py-2 flex items-center">
            <i class="icone-cabecalho fas fa-credit-card mr-2"></i> Pagamento
         </div>
         <div class="p-4 w-full flex flex-col" style="overflow: auto; height: calc(100% - 47px);">
            <div class="mb-4">
               <Campo v-model="oVenda.iFormaPagamento" sTipo="select" :bObrigatorio="true" sTitulo="Forma de Pagamento" :aOpcoes="aFormasPagamento"/>
            </div>
            <div class="mb-4">
               <Campo v-model="oVenda.fDesconto" sTipo="text" :bObrigatorio="false" sTitulo="Desconto"/>
            </div>
            <div class="mb-4">
               <Campo v-model="oVenda.fValorTotal" sTipo="text" :bObrigatorio="false" :bDesabilitado="true" sTitulo="Valor total"/>
            </div>
            <Botao @click="finalizarVenda" sTexto="Finalizar Venda" sTipo="text" sId="botao_finalizar_venda" sLargura="w-full" sClasses="py-2 px-4 rounded mt-auto"/>
         </div>
      </div>
   </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useClienteStore } from '../stores/clienteStore';
import { useProdutoStore } from '../stores/produtoStore';
import { useAtendimentoStore } from '../stores/atendimentoStore';
import Botao from '../components/UI/Botao.vue';
import Campo from '../components/UI/Campo.vue';
import * as utils from "../utils/main.js";

const oProdutoStore       = useProdutoStore();
const oClienteStore       = useClienteStore();
const oAtendimentoStore   = useAtendimentoStore();
const bFocado             = ref(false);
const bMostrarSugestoes   = ref(false);
const aSugestoesFiltradas = ref([]);
const aFormasPagamento    = ref([]);
let debounceTimeout       = null;
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
   iProduto: '',
   sNome: '',
   iQuantidade: '',
   fValorVenda: '',
   fDesconto: ''
});
const oVenda = ref({
   iFormaPagamento: '',
   fValorTotal: '',
   fDesconto: ''
});

onMounted(async () => {
   aFormasPagamento.value = await oAtendimentoStore.getFormasPagamento();
});

async function onChangeCodigoProduto() {
   if(!oProduto.value.iProduto) {
      resetarProduto();
   }

   try {
      oProduto.value = await oProdutoStore.getProdutoByCodigo(oProduto.value.iProduto);
   }
   catch(e) {
      oProduto.value.iProduto = null;
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
         iProduto      : oResultadoProduto.iProduto,
         sNome         : oResultadoProduto.sNome,
         iQuantidade   : oResultadoProduto.iQuantidade,
         fValorVenda   : oResultadoProduto.fValorVenda,
         fDesconto     : oResultadoProduto.fDesconto
      };
   }
   else {
      resetarProduto();
   }
};

function ocultarSugestoes() {
   if(!oProduto.value.sNome && oProduto.value.iProduto) {
      oProduto.value.sNome  = oProdutoSelecionado.sNome;
   }

   setTimeout(() => {
      bMostrarSugestoes.value = false;
   }, 100)
}

function adicionarProduto() {
   utils.validarCamposObrigatorios();
}

function finalizarVenda() {
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
      iProduto:       '',
      sNome:          '',
      iQuantidade:    '',
      fValorVenda:    '',
      fDesconto:      ''
   };
}
</script>