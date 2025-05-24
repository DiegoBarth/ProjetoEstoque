<template>
  <div class="w-full h-full">        
    <Consulta sTitulo='Produtos' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
        <Grid v-if="aProdutos" class="mt-10 text-left" :aCabecalhos="['Produto', 'Nome do Produto', 'Valor de Custo', 'Valor de Venda', 'Quantidade', 'Valor de Desconto', 'Fornecedor', 'Ações']" sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr">
          <tr class="grid" v-for="(oProduto, iIndice) of aProdutos" :key="iIndice" style="grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr;">
            <td class="p-2">{{ oProduto.procodigo                              }}</td>
            <td class="p-2">{{ oProduto.pronome                                }}</td>
            <td class="p-2">R$ {{ oProduto.procusto.replace('.', ',')          }}</td>
            <td class="p-2">R$ {{ oProduto.provalor.replace('.', ',')          }}</td>
            <td class="p-2">{{ oProduto.proestoque                             }}</td>
            <td class="p-2">R$ {{ oProduto.provalor_desconto.replace('.',',')  }}</td>
            <td class="p-2">{{ oProduto.forrazao_social                        }}</td>
            <td class="p-2 flex gap-2">
              <span class="cursor-pointer" @click="showModalCadastro(3, oProduto)"><i class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="showModalCadastro(2, oProduto)"><i class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="() => iProdutoExclusao = oProduto.procodigo"><i class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
          </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro :bModalAberto="bShowModal" class="flex items-center justify-content-center" sTitulo="📦 Cadastro de produto" :iAcao="iAcaoAtual" @fecharModal="() => {bShowModal = false;}" @incluir="adicionarProduto" @alterar="atualizarProduto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Produto"          v-model="oProduto.sNome"         maxlength="100" placeholder="Informe uma descrição"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Código de barras" v-model="oProduto.sCodigoBarras" maxlength="20"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Quantidade"       v-model="oProduto.iQuantidade"   maxlength="4"  />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Valor compra"     v-model="oProduto.fValorCompra"  maxlength="12" @change="converterParaMoeda('fValorCompra')"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Valor venda"      v-model="oProduto.fValorVenda"   maxlength="12" @change="converterParaMoeda('fValorVenda')"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Valor desconto"   v-model="oProduto.fDesconto"     maxlength="12" @change="converterParaMoeda('fDesconto')"/>                                
        <Campo v-if="iAcaoAtual != 3" sTipo="select" :bObrigatorio="true"  sTitulo="Fornecedor" v-model="oProduto.iFornecedor"   :aOpcoes="aFornecedores"/>        
        <Campo v-else disabled sTipo="text" :bObrigatorio="true"  sTitulo="Fornecedor" v-model="oProduto.sFornecedor" />        
      </div>            
    </ModalCadastro>
    <Modal v-if="iProdutoExclusao">
      <h1>Confirma a exclusão do registro?</h1>
      <div class="flex items-center gap-4">
        <button @click="excluirProduto(iProdutoExclusao)" class="p-2 bg-yellow-400 rounded-sm">Sim</button>
        <button @click="() => iProdutoExclusao = null" class="p-2 bg-red-400 rounded-sm">Não</button>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import 'datatables.net-dt';
import { nextTick, onMounted, onUnmounted, onUpdated, reactive, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Modal from '../components/UI/Modal.vue';
import Grid from '../components/UI/Grid.vue';
import Consulta from '../components/UI/Consulta.vue';
import Campo from '../components/UI/Campo.vue';
import { useProdutoStore } from '../stores/produtoStore';
import { useFornecedorStore } from '../stores/fornecedorStore';

const bShowModal = ref(false);
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
})

const iProdutoExclusao = ref(null);
const iAcaoAtual = ref(0);
const aProdutos = ref([]);
const oProdutoStore = useProdutoStore();
const oFornecedorStore = useFornecedorStore();
const aFornecedores = ref([]);

onMounted(async () => {  
  aProdutos.value = await oProdutoStore.getProdutos();   
});

async function showModalCadastro(iAcao, oProdutoSelecionado) {    
  bShowModal.value = true;
  iAcaoAtual.value = iAcao;

  if(iAcao != 1) {        
    oProduto.value = {
      sNome:         oProdutoSelecionado.pronome,
      sCodigoBarras: oProdutoSelecionado.procodigo_barras,
      iQuantidade:   oProdutoSelecionado.proestoque,
      fValorCompra:  oProdutoSelecionado.procusto,
      fValorVenda:   oProdutoSelecionado.provalor,
      fDesconto:     oProdutoSelecionado.provalor_desconto,
      iFornecedor:   oProdutoSelecionado.forcodigo,
      sFornecedor:   oProdutoSelecionado.forrazao_social
    };            
  }

  if(iAcao != 3) {
    oFornecedorStore.getFornecedores().then((oRetorno) => {    
      aFornecedores.value = tratarFiltroFornecedores(oRetorno.aFornecedores);
    });
  }
}

function tratarFiltroFornecedores(aFornecedores) {
  const aFiltro = []
  for(const oFornecedor of aFornecedores) {
    aFiltro.push({
      iValor:     oFornecedor.forcodigo,
      sDescricao: oFornecedor.forrazao_social
    })
  }

  return aFiltro;
}

async function adicionarProduto() {  
  const aMensagens = validarProduto();

  if(aMensagens.length > 0) {    
    return utils.alerta(aMensagens, 'error');
  }    

  await oProdutoStore.cadatrarProduto(tratarDadosProduto(false));
  utils.alerta('Produto cadastrado com sucesso');
  aProdutos.value = await oProdutoStore.getProdutos();
}

function tratarDadosProduto(bAlterar) {
  if(bAlterar) {
    return {
      iProduto: oProduto.value.iProduto,
      sNome: oProduto.value.sNome,
      sCodigoBarras: oProduto.value.sCodigoBarras,
      iQuantidade: oProduto.value.iQuantidade,
      fValorCompra: normalizarValor(oProduto.value.fValorCompra),
      fValorVenda: normalizarValor(oProduto.value.fValorVenda),
      fDesconto: normalizarValor(oProduto.value.fDesconto),
      iFornecedor: oProduto.value.iFornecedor
    }
  }

  return {
    sNome: oProduto.value.sNome,
    sCodigoBarras: oProduto.value.sCodigoBarras,
    iQuantidade: oProduto.value.iQuantidade,
    fValorCompra: normalizarValor(oProduto.value.fValorCompra),
    fValorVenda:  normalizarValor(oProduto.value.fValorVenda),
    fDesconto:    normalizarValor(oProduto.value.fDesconto),
    iFornecedor: oProduto.value.iFornecedor
  }
}

function validarProduto() {
  const aMensagens = [];  

  if(!oProduto.value.sNome) {
    aMensagens.push('O campo Produto é obrigatório.')
  }

  if(!oProduto.value.sCodigoBarras) {
    aMensagens.push('O campo Código de barras é obrigatório.')
  }

  if(!oProduto.value.iQuantidade) {
    aMensagens.push('O campo Quantidade é obrigatório.')
  }

  if(!oProduto.value.fValorCompra) {
    aMensagens.push('O campo Valor compra é obrigatório.')
  }

  if(!oProduto.value.fValorVenda) {
    aMensagens.push('O campo Valor venda é obrigatório.')
  }  

  if(!oProduto.value.iFornecedor) {
    aMensagens.push('O campo Fornecedor é obrigatório.')
  }

  return aMensagens;
}

async function atualizarProduto() {
  const aMensagens = validarProduto();

  if(aMensagens.length > 0) {    
    return utils.alerta(aMensagens, 'error');
  }  

  await oProdutoStore.atualizarProduto(oProduto.value.iProduto, tratarDadosProduto(true));    
  utils.alerta('Produto alterado com sucesso');
  aProdutos.value = await oProdutoStore.getProdutos();
}

async function excluirProduto(iProduto) {
  await oProdutoStore.excluirProduto(iProduto);
  aProdutos.value = await oProdutoStore.getProdutos();
  iProdutoExclusao.value = null;
}

function normalizarValor(sValor) {
  return sValor.replace(',', '.').replace(/[^\d.]/g, '')
}

function converterParaMoeda(sCampo) {
  const iValor = parseFloat(oProduto.value[sCampo]);

  if(isNaN(iValor)) {
    return iValor;
  }    

  oProduto.value[sCampo] = 'R$ '+oProduto.value[sCampo]+`${oProduto.value[sCampo].match(',') ? '' : ',00'}`;
}

</script>