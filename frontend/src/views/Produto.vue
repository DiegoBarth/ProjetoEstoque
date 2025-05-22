<template>
  <div class="w-full h-full">        
    <Consulta sTitulo='Produtos' @showModalCadastro="showModalCadastro">
      <template #gridConsulta>
        <Grid v-if="aProdutos" class="mt-10 text-left" :aCabecalhos="['Produto', 'Nome do Produto', 'Valor de Custo', 'Valor de Venda', 'Quantidade', 'Valor de Desconto', 'Fornecedor', 'Ações']" sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr">
          <tr class="grid" v-for="(oProduto, iIndice) of aProdutos" :key="iIndice" style="grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;">
            <td class="p-2">{{ oProduto.procodigo                              }}</td>
            <td class="p-2">{{ oProduto.pronome                                }}</td>
            <td class="p-2">R$ {{ oProduto.procusto.replace('.', ',')          }}</td>
            <td class="p-2">R$ {{ oProduto.provalor.replace('.', ',')          }}</td>
            <td class="p-2">{{ oProduto.proestoque                             }}</td>
            <td class="p-2">R$ {{ oProduto.provalor_desconto.replace('.',',')  }}</td>
            <td class="p-2">{{ oProduto.forrazao_social                        }}</td>
            <td class="p-2 flex gap-2">
              <span><i class="cursor-pointer fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
              <span><i class="cursor-pointer fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white" @click="showModalCadastro(oProduto.procodigo)"></i></span>
              <span><i class="cursor-pointer fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
          </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro class="flex items-center justify-content-center" sTitulo="📦 Cadastro de produto" :bAlterar="oProduto.iProduto ? true : false" @incluir="adicionarProduto" @alterar="atualizarProduto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
        <Campo sTipo="text" :bObrigatorio="true" sTitulo="Produto" v-model="oProduto.sNome" maxlength="100" placeholder="Informe uma descrição"/>
        <Campo sTipo="text" :bObrigatorio="true" sTitulo="Código de barras" v-model="oProduto.sCodigoBarras" maxlength="20"/>
        <Campo sTipo="text" :bObrigatorio="true" sTitulo="Quantidade" v-model="oProduto.iQuantidade" maxlength="4" @input="onlyNumbers('iQuantidade')"/>
        <Campo sTipo="text" :bObrigatorio="true" sTitulo="Valor compra" v-model="oProduto.fValorCompra" maxlength="12" @input="onlyNumbers('fValorCompra')" @change="formatCurrency('fValorCompra')"/>
        <Campo sTipo="text" :bObrigatorio="true" sTitulo="Valor venda" v-model="oProduto.fValorVenda" maxlength="12" @input="onlyNumbers('fValorVenda')" @change="formatCurrency('fValorVenda')"/>
        <Campo sTipo="text" :bObrigatorio="false" sTitulo="Valor desconto" v-model="oProduto.fDesconto" maxlength="12" @change="formatCurrency('fDesconto')"/>                                
        <Campo sTipo="select" :bObrigatorio="true" sTitulo="Fornecedor" v-model="oProduto.iFornecedor" :aOpcoes="aFornecedores"/>        
      </div>            
    </ModalCadastro>
  </div>
</template>

<script setup>
import 'datatables.net-dt';
import { nextTick, onMounted, onUnmounted, reactive, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Grid from '../components/UI/Grid.vue';
import Consulta from '../components/UI/Consulta.vue';
import Campo from '../components/UI/Campo.vue';
import { useProdutoStore } from '../stores/produtoStore';
import { useFornecedorStore } from '../stores/fornecedorStore';

const oProduto = ref({
  iProduto: '',
  sNome: '',
  sCodigoBarras: '',
  iQuantidade: '',
  fValorCompra: '',
  fValorVenda: '',
  fDesconto: '',
  iFornecedor: ''
})

const aProdutos = ref([]);
const oProdutoStore = useProdutoStore();
const oFornecedorStore = useFornecedorStore();
const aFornecedores = ref([]);

onMounted(async () => {  
  aProdutos.value = await oProdutoStore.getProdutos();   
});

async function showModalCadastro(iProduto) {    
  const oModal = $('#modalCadastro');

  if(iProduto) {    
    oProduto.value = await oProdutoStore.getProdutoByCodigo(iProduto);    
  }

  oFornecedorStore.getFornecedores().then((oRetorno) => {    
    aFornecedores.value = tratarFiltroFornecedores(oRetorno.aFornecedores);
  });  

  oModal.css('display', 'flex');
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
  const oModal = $('#modalCadastro');
  const aMensagens = validarProduto();

  if(aMensagens.length > 0) {    
    return utils.alerta(aMensagens, 'error');
  }  

  await oProdutoStore.cadatrarProduto(tratarDadosProduto(false));  
  oModal.css('display', 'none');
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
    fValorVenda: normalizarValor(oProduto.value.fValorVenda),
    fDesconto: normalizarValor(oProduto.value.fDesconto),
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
  const oModal = $('#modalCadastro');
  const aMensagens = validarProduto();

  if(aMensagens.length > 0) {    
    return utils.alerta(aMensagens, 'error');
  }  

  await oProdutoStore.atualizarProduto(oProduto.value.iProduto, tratarDadosProduto(true));  
  oModal.css('display', 'none');
  utils.alerta('Produto alterado com sucesso');
  aProdutos.value = await oProdutoStore.getProdutos();
}

function normalizarValor(valor) {
  if(!valor) {
    return;
  }

  if (typeof valor === 'string') {
    return parseFloat(valor.replace(/\D/g, '')) / 100;
  }
  return parseFloat(valor) / 100;
}

function onlyNumbers(field) {
  oProduto[field] = oProduto[field].replace(/\D/g, '')
}

function formatCurrency(field) {
  const valor = parseFloat(oProduto[field]) / 100

  if(isNaN(valor)) {
    return;
  }

  oProduto[field] = valor.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  })
}
</script>