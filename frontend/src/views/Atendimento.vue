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
            <Campo v-model="oProduto.iQuantidade" sTipo="number" :bObrigatorio="true"  sTitulo="Quantidade"     sStyle="width:40%" :max="iEstoque" min="1" @change="validarQuantidadeInformada"/>
            <Campo v-model="oProduto.fValorVenda" sTipo="text"   :bObrigatorio="true"  sTitulo="Valor unitário" maxlength="12" sStyle="width:40%"  @input="() => oProduto.fValorVenda = utils.converterParaMoeda(oProduto.fValorVenda)"/>
            <Campo v-model="oProduto.fDesconto"   sTipo="text"   :bObrigatorio="false" sTitulo="Valor desconto" maxlength="12" sStyle="width:40%"  @input="() => oProduto.fDesconto   = utils.converterParaMoeda(oProduto.fDesconto)"/>
            <Botao sTexto="Adicionar" sTipo="text" sId="botao_adicionar_produto" :bVisivel=bBotaoVisivel  sLargura="w-fit" @click="adicionarProdutoGrid"/>
            <div class="flex space-x-2">
               <Botao sTexto="Alterar"  sCor="botaoAzul"     sTipo="text" sId="botao_alterar_produto"   :bVisivel=!bBotaoVisivel sLargura="w-fit" @click="confirmarAlteracaoProdutoGrid"/>
               <Botao sTexto="Cancelar" sCor="botaoVermelho" sTipo="text" sId="botao_cancelar_produto"  :bVisivel=!bBotaoVisivel sLargura="w-fit" @click="cancelarAlteracaoProdutoGrid"/>
            </div>
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
                  <tr v-for="(produto, index) in aProdutos" :key="index">
                     <td class="p-2">{{ produto.iProduto }}</td>
                     <td class="p-2">{{ produto.sNome }}</td>
                     <td class="p-2">{{ produto.iQuantidade }}</td>
                     <td class="p-2">{{ utils.converterParaMoeda(parseFloat(produto.sValor).toFixed(2)) }}</td>
                     <td class="p-2">{{ utils.converterParaMoeda(parseFloat(produto.sDesconto).toFixed(2)) }}</td>
                     <td class="p-2">{{ utils.converterParaMoeda(parseFloat(produto.sValorTotal).toFixed(2)) }}</td>
                     <td class="p-2 flex gap-2">
                        <span v-if="!bGridBloqueado" class="cursor-pointer"><i
                              class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white" @click="alterarProdutoGrid(produto)"></i></span>
                        <span v-if="!bGridBloqueado" class="cursor-pointer"><i
                           class="fa fa-trash p-2 bg-red-500 rounded-sm text-white" @click="excluirProdutoGrid(produto)"></i></span>
                     </td>
                  </tr>
                  <tr v-if="aProdutos.length === 0">
                     <td colspan="7" class="p-4 text-center text-gray-500">Nenhum produto adicionado.</td>
                  </tr>
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
               <Campo v-model="oVenda.iFormaPagamento" sTipo="select" :bObrigatorio="true" sTitulo="Forma de Pagamento" :aOpcoes="aFormasPagamento" />
            </div>
            <div class="mb-4" v-if="bIsPagamentoCredito">
               <Campo v-model="oVenda.iNumeroParcelas" sTipo="select" :bObrigatorio="true" sTitulo="Número de Parcelas" :aOpcoes="aParcelas"/>
            </div>
            <div class="mb-4">
               <Campo v-model="oVenda.fDesconto" sTipo="text" :bObrigatorio="false" sTitulo="Desconto" @input="() => oVenda.fDesconto = utils.converterParaMoeda(oVenda.fDesconto)" @change="onChangeCampoDescontoVenda"/>
            </div>
            <div class="mb-4">
               <Campo v-model="oVenda.fValorFinal" sTipo="text" :bObrigatorio="false" :bDesabilitado="true" sTitulo="Valor total"/>
            </div>
            <Botao v-if="!oDadosAtendimento" @click="finalizarVenda" sTexto="Finalizar Venda" sTipo="text" sId="botao_finalizar_venda" sLargura="w-full" sClasses="py-2 px-4 rounded mt-auto"/>
         </div>
      </div>
   </div>
</template>

<script setup>
import { ref, watch, onMounted, computed, onUnmounted  } from 'vue'
import { useClienteStore } from '../stores/clienteStore';
import { useProdutoStore } from '../stores/produtoStore';
import { useAtendimentoStore } from '../stores/atendimentoStore';
import Botao from '../components/UI/Botao.vue';
import Campo from '../components/UI/Campo.vue';
import * as utils from "../utils/main.js";
import Cookies from 'js-cookie';

const oProdutoStore       = useProdutoStore();
const oClienteStore       = useClienteStore();
const oAtendimentoStore   = useAtendimentoStore();
const bFocado             = ref(false);
const bMostrarSugestoes   = ref(false);
const aSugestoesFiltradas = ref([]);
const aFormasPagamento    = ref([]);
const aProdutos           = ref([]);
let bGridBloqueado        = ref(false)
let bBotaoVisivel         = ref(true);
let debounceTimeout       = null;
let oProdutoSelecionado   = null;
let iEstoque              = 1;
let aProdutosFiltrados    = [];
let bIsPagamentoCredito   = computed(() => oVenda.value.iFormaPagamento == 1);

const aParcelas = Array.from({ length: 12 }, (_, i) => ({
  iValor: String(i + 1),
  sDescricao: `${i + 1}`
}));


const sCpf     = ref('');
const oCliente = ref({
   iCodigo:         '',
   sNome:           '',
   sEndereco:       '',
   sTelefone:       '',
   sDataNascimento: ''
});
const oProduto = ref({
   iProduto:    '',
   sNome:       '',
   iQuantidade: '',
   iEstoque:    '',
   fValorVenda: '',
   fDesconto:   ''
});
const oVenda = ref({
   iFormaPagamento: '',
   fValorTotal:     '',
   fValorFinal:     '',
   fDesconto:       '',
   iNumeroParcelas: ''
});
const oDadosAtendimento = ref(null);

onMounted(async () => {
   aFormasPagamento.value = await oAtendimentoStore.getFormasPagamento();
   oDadosAtendimento.value = await oAtendimentoStore.getDadosVenda(); 

   if(oDadosAtendimento.value) {
      oVenda.value = {
         iFormaPagamento: oDadosAtendimento.value.forma_pagamento.fpcodigo,
         fValorTotal:     utils.converterParaMoeda(oDadosAtendimento.value.vevalor_total),
         fDesconto:       utils.converterParaMoeda(oDadosAtendimento.value.vedesconto),
         fValorFinal:     utils.converterParaMoeda(parseFloat(oDadosAtendimento.value.vevalor_total - oDadosAtendimento.value.vedesconto).toFixed(2)),
         iNumeroParcelas: oDadosAtendimento.value.venumero_parcelas
      };
      aProdutos.value = oDadosAtendimento.value.aProdutos;
      sCpf.value = utils.formatarCPF(oDadosAtendimento.value.cliente.clicpf);
      onChangeCPF();
      bGridBloqueado.value = true;
      desabilitarCampos();
   }
});

onUnmounted(() => {
   oAtendimentoStore.setDadosVenda(null);
})

function desabilitarCampos() {
   setTimeout(() => {
      $('input, select, button').attr('disabled', true);
   }, 1000)
}

async function onChangeCodigoProduto() {
   if(!oProduto.value.iProduto) {
      return resetarProduto();
   }

   try {
      oProduto.value = await oProdutoStore.getProdutoByCodigo(oProduto.value.iProduto);
      iEstoque                   = oProduto.value.iQuantidade;
      oProduto.value.iEstoque    = oProduto.value.iQuantidade;
      oProduto.value.iQuantidade = 1;
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
            iCodigo        : oDados.iCliente,
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

function onChangeCampoDescontoVenda() {
   let fDesconto   = utils.normalizarValor(oVenda.value.fDesconto);
   let fValorVenda = (utils.normalizarValor(oVenda.value.fValorTotal) - fDesconto).toFixed(2);

   if(!fDesconto) {
      oVenda.value.fValorFinal = oVenda.value.fValorTotal;   
   }

   oVenda.value.fValorFinal = utils.converterParaMoeda(fValorVenda);
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
         iQuantidade   : 1,
         iEstoque      : oResultadoProduto.iQuantidade,
         fValorVenda   : oResultadoProduto.fValorVenda,
         fDesconto     : oResultadoProduto.fDesconto
      };

      iEstoque = oResultadoProduto.iQuantidade;
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

async function finalizarVenda() {
   if(utils.validarCamposObrigatorios()) {
      if(!aProdutos.value.length) {
         return utils.alerta('Para finalizar a venda é necessário inserir ao menos um produto.', 'error');
      }

      try {
         await oAtendimentoStore.cadastrarVenda(tratarDadosVenda());
         resetarElementos();
      }
      catch(error) {
         console.error(error);
      }

   }
}

function tratarDadosVenda() {
   let oUsuario = JSON.parse(atob(Cookies.get('oUsuario')));

   return {
      iCodigoCliente : Number(oCliente.value.iCodigo),
      iCodigoUsuario : Number(oUsuario.iUsuario),
      iFormaPagamento: Number(oVenda.value.iFormaPagamento),
      iNumeroParcelas: oVenda.value.iNumeroParcelas ? Number(oVenda.value.iNumeroParcelas) : null,
      fDesconto      : utils.normalizarValor(oVenda.value.fDesconto) ? parseFloat(utils.normalizarValor(oVenda.value.fDesconto)) : null,
      fValorTotal    : parseFloat(utils.normalizarValor(oVenda.value.fValorFinal)),
      iSituacao      : 1,
      aProdutos      : aProdutos.value.map(function(oProduto) {return tratarProdutoGrid(oProduto);})
   }
}

function tratarProdutoGrid(oProduto) {
   return {
      iCodigoProduto: oProduto.iProduto,
      iQuantidade   : oProduto.iQuantidade,
      fValorVenda   : parseFloat(utils.normalizarValor(oProduto.fValorVenda)),
      fValorTotal   : parseFloat(utils.normalizarValor(oProduto.sValorTotal))
   }
}

function resetarElementos() {
   resetarCliente();
   resetarProduto();
   resetarGridProdutos();
   resetarVenda();
}

function resetarCliente() {
   sCpf.value = '';

   oCliente.value = {
      iCodigo:         '',
      sNome:           '',
      sEndereco:       '',
      sTelefone:       '',
      sDataNascimento: ''
   };
}

function resetarProduto() {
   iEstoque = 1;

   oProduto.value = {
      iProduto:       '',
      sNome:          '',
      iQuantidade:    '',
      iEstoque:       '',
      fValorVenda:    '',
      fDesconto:      ''
   };
}

function resetarGridProdutos() {
   aProdutos.value = [];
}

function resetarVenda() {
   oVenda.value = {
      iFormaPagamento: '',
      fValorTotal:     '',
      fValorFinal:     '',
      fDesconto:       '',
      iNumeroParcelas: ''
   };
}

function validarQuantidadeInformada() {
   if(oProduto.value.iQuantidade > iEstoque) {
      oProduto.value.iQuantidade = 1;
      utils.alerta('A quantidade informada é maior do que a disponível em estoque', 'error');
   }
}

function valorDescontoValido() {
   if(utils.normalizarValor(oProduto.value.fDesconto) > utils.normalizarValor(oProduto.value.fValorVenda)) {
      utils.alerta('O desconto não pode ser maior do que o valor de venda', 'error');
      return false;
   }

   return true;
}

function adicionarProdutoGrid() {
   if(utils.validarCamposObrigatorios() && valorDescontoValido()) {
      adicionarProduto();
      resetarProduto();
   }
}

function adicionarProduto() {
   let sValor      = (utils.normalizarValor(oProduto.value.fValorVenda) * oProduto.value.iQuantidade).toFixed(2);
   let sDesconto   = ((oProduto.value.fDesconto ? utils.normalizarValor(oProduto.value.fDesconto) : 0) * oProduto.value.iQuantidade).toFixed(2);
   let sValorTotal = (sValor - sDesconto).toFixed(2);
   let idRegistro  = Date.now() + Math.random();

   sValor      = utils.converterParaMoeda(sValor);
   sDesconto   = utils.converterParaMoeda(sDesconto);
   sValorTotal = utils.converterParaMoeda(sValorTotal);

   const produto = {
      ...oProduto.value,
      sValor,
      sDesconto,
      sValorTotal,
      idRegistro
   }

   aProdutos.value.push(produto);

   atualizarValorTotal();
}

function alterarProdutoGrid(produto) {
   oProduto.value = {...produto};
   bGridBloqueado = true;
   bBotaoVisivel  = false;
   iEstoque = oProduto.value.iEstoque;
}

function confirmarAlteracaoProdutoGrid() {
   const iIndice = aProdutos.value.findIndex(p => p.idRegistro === oProduto.value.idRegistro);

   if(iIndice !== -1) {
      let sValor      = (utils.normalizarValor(oProduto.value.fValorVenda) * oProduto.value.iQuantidade).toFixed(2);
      let sDesconto   = ((oProduto.value.fDesconto ? utils.normalizarValor(oProduto.value.fDesconto) : 0) * oProduto.value.iQuantidade).toFixed(2);
      let sValorTotal = (sValor - sDesconto).toFixed(2);

      sValor      = utils.converterParaMoeda(sValor);
      sDesconto   = utils.converterParaMoeda(sDesconto);
      sValorTotal = utils.converterParaMoeda(sValorTotal);

      aProdutos.value[iIndice] = {
         ...oProduto.value,
         sValor,
         sDesconto,
         sValorTotal
      };

      resetarProduto();
      bGridBloqueado = false;
      bBotaoVisivel  = true;

      atualizarValorTotal();
   }
}

function cancelarAlteracaoProdutoGrid() {
   resetarProduto();
   bGridBloqueado = false;
   bBotaoVisivel  = true;
}

function excluirProdutoGrid(produto) {
  const iIndice = aProdutos.value.findIndex(oProduto => oProduto.idRegistro === produto.idRegistro)

   if(iIndice !== -1) {
      aProdutos.value.splice(iIndice, 1)
      atualizarValorTotal();
   }
}

function atualizarValorTotal() {
   const fTotal = aProdutos.value.reduce((fSoma, oProduto) => {
      return (parseFloat(fSoma) + parseFloat(utils.normalizarValor(oProduto.sValorTotal))).toFixed(2);
   }, 0);

   let fDesconto   = utils.normalizarValor(oVenda.value.fDesconto);

   oVenda.value.fValorTotal = utils.converterParaMoeda(fTotal);

   if(!fDesconto) {
      oVenda.value.fValorFinal = utils.converterParaMoeda(fTotal);
      return;
   }

   if(!fTotal) {
      return oVenda.value.fValorFinal = null;
   }

   oVenda.value.fValorFinal = utils.converterParaMoeda((parseFloat(fTotal) - parseFloat(fDesconto)).toFixed(2));
}

</script>