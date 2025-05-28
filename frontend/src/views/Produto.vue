<template>
<div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
   <Consulta sTitulo='Produtos' @showModalCadastro="showModalCadastro(1)">
      <template #gridConsulta>
         <GridProdutos v-if="aProdutos" :aProdutos="aProdutos" @showModalCadastro="showModalCadastro"
            @showModalExclusao="showModalExclusao" />
      </template>
   </Consulta>
   <CadastroProdutos v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarProduto="adicionarProduto"
      @atualizarProduto="atualizarProduto" :oProduto="oProduto" :iAcaoAtual="iAcaoAtual" :aOpcoes="aFornecedores" />
   <ModalExclusao v-if="iProdutoExclusao" @fecharModal="() => {iProdutoExclusao = false; bShowModal = false}"
      @excluirRegistro="excluirProduto" />
</div>
</template>

<script setup>
//#region Componentes
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridProdutos from '../components/GridProdutos.vue';
import CadastroProdutos from '../components/CadastroProdutos.vue';
//#endregion

//#region Dependências
import { onMounted, ref } from 'vue';
import { useProdutoStore } from '../stores/produtoStore';
import { useFornecedorStore } from '../stores/fornecedorStore';
import * as utils from '../utils/main';
//#endregion

const oProduto = ref({
iProduto: '',
sNome: '',
sCodigoBarras: '',
iQuantidade: '',
fValorCompra: '',
fValorVenda: '',
fDesconto: '',
iFornecedor: '',
sFornecedor: ''
});

const oProdutoStore = useProdutoStore();
const oFornecedorStore = useFornecedorStore();
const iProdutoExclusao = ref(null);
const iAcaoAtual = ref(0);
const aProdutos = ref();
const aFornecedores = ref([]);
const bShowModal = ref(false);

onMounted(async () => {
   aProdutos.value = await oProdutoStore.getProdutos();
});

async function adicionarProduto(oDados) {
   console.log(parseFloat(oDados.fDesconto), parseFloat(oDados.fValorVenda))
   console.log(oDados.fDesconto, oDados.fValorVenda)
   if(utils.normalizarValor(oDados.fDesconto) > utils.normalizarValor(oDados.fValorVenda)) {
      return utils.alerta('O valor de desconto não pode ser maior que o valor de venda', 'error')
   }

   if(utils.validarCamposObrigatorios()) {
      await oProdutoStore.cadastrarProduto(tratarDadosProduto(oDados));
      utils.alerta('Produto cadastrado com sucesso');
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function atualizarProduto(oDados, iProduto) {
   if(utils.validarCamposObrigatorios()) {
      await oProdutoStore.atualizarProduto(iProduto, tratarDadosProduto(oDados));
      utils.alerta('Produto alterado com sucesso');
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function excluirProduto(iProduto) {
   await oProdutoStore.excluirProduto(iProdutoExclusao.value);
   utils.alerta('Produto excluído com sucesso!');
   recarregarGrid();
   iProdutoExclusao.value = null;
}

async function showModalCadastro(iAcao, oProdutoSelecionado) {
   bShowModal.value = true;
   iAcaoAtual.value = iAcao;

   if(iAcao != 1) {
      oProduto.value = {
            iProduto:      oProdutoSelecionado.iProduto,
            sNome:         oProdutoSelecionado.sNome,
            sCodigoBarras: oProdutoSelecionado.sCodigoBarras,
            iQuantidade:   oProdutoSelecionado.iQuantidade,
            fValorCompra:  oProdutoSelecionado.fValorCompra,
            fValorVenda:   oProdutoSelecionado.fValorVenda,
            fDesconto:     oProdutoSelecionado.fDesconto,
            iFornecedor:   oProdutoSelecionado.iFornecedor,
            sFornecedor:   oProdutoSelecionado.sFornecedor
      };
   }

   if(iAcao != 3) {
      oFornecedorStore.getFornecedores().then((oRetorno) => {
         aFornecedores.value = tratarFiltroFornecedores(oRetorno);
      });
}
}

function showModalExclusao(iCodigo) {
   iProdutoExclusao.value = iCodigo;
}

function tratarFiltroFornecedores(aFornecedores) {
   const aFiltro = [];

   if(aFornecedores.length) {
      for(const oFornecedor of aFornecedores) {
         aFiltro.push({
            iValor:     oFornecedor.forcodigo,
            sDescricao: oFornecedor.forrazao_social
         });
      }
   }

   return aFiltro;
}

function tratarDadosProduto(oDados) {   
   return {        
      sNome:          oDados.sNome,
      sCodigoBarras:  oDados.sCodigoBarras,
      iQuantidade:    oDados.iQuantidade,
      fValorCompra:   utils.normalizarValor(oDados.fValorCompra),
      fValorVenda:    utils.normalizarValor(oDados.fValorVenda),
      fDesconto:      utils.normalizarValor(oDados.fDesconto),
      iFornecedor:    oDados.iFornecedor
   };   
}

async function recarregarGrid() {
   aProdutos.value = null;
   aProdutos.value = await oProdutoStore.getProdutos();
}

</script>