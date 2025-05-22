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
            <td class="p-2"></td>        
          </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro class="flex items-center justify-content-center" sTitulo="📦 Cadastro de produto" @adicionarProduto="adicionarProduto" @atualizarProduto="atualizarProduto">      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Produto</label>
          <input
            v-model="oProduto.sNome"
            type="text"
            maxlength="100"
            placeholder="Informe uma descrição"
            class="w-full mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Código de barras</label>
          <input
            v-model="oProduto.sCodigoBarras"
            type="text"
            maxlength="20"            
            class="w-full mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Quantidade</label>
          <input
            v-model="oProduto.iQuantidade"
            type="text"
            maxlength="4"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('iQuantidade')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor compra</label>
          <input
            v-model="oProduto.fValorCompra"
            type="text"
            maxlength="12"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('fValorCompra')"
            @change="formatCurrency('fValorCompra')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor venda</label>
          <input
            v-model="oProduto.fValorVenda"
            type="text"
            maxlength="12"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('fValorVenda')"
            @change="formatCurrency('fValorVenda')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor desconto</label>
          <input
            v-model="oProduto.fDesconto"
            type="text"
            maxlength="12"
            class="w-full mt-1 p-2 border rounded"            
            @change="formatCurrency('fDesconto')"
          />
        </div>
        <div>
          <label class="block text-sm font-medium">Fornecedor</label>
          <select v-if="aFornecedores" v-model="oProduto.iFornecedor" class="mt-1 p-2 border rounded">
            <option value=""  >Selecione</option>
            <option v-for="(oFornecedor, iIndice) of aFornecedores" :key="iIndice" :value="oFornecedor.forcodigo">{{ oFornecedor.forrazao_social}}</option>
          </select>
        </div>
      </div>            
    </ModalCadastro>
  </div>
</template>

<script setup>
import 'datatables.net-dt';
import { nextTick, onMounted, reactive, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Grid from '../components/UI/Grid.vue';
import Consulta from '../components/UI/Consulta.vue';
import { useProdutoStore } from '../stores/produtoStore';
import { useFornecedorStore } from '../stores/fornecedorStore';

const oProduto = reactive({
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

function showModalCadastro() {
  const oModal = $('#modalCadastro');
  oFornecedorStore.getFornecedores().then((oRetorno) => {    
    aFornecedores.value = oRetorno.aFornecedores;
  });  

  oModal.css('display', 'flex');
}

async function adicionarProduto() {    
  oProduto.fDesconto    = typeof oProduto.fDesconto == 'string' ? parseFloat(oProduto.fDesconto.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;
  oProduto.fValorCompra = typeof oProduto.fValorCompra == 'string' ? parseFloat(oProduto.fValorCompra.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;
  oProduto.fValorVenda  = typeof oProduto.fValorVenda == 'string' ? parseFloat(oProduto.fValorVenda.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;  

  const oDados = tratarDadosProduto();
  
  await oProdutoStore.cadatrarProduto(oProduto);
  await oProdutoStore.getProdutos();
  oModal.css('display', 'none');
}

function tratarDadosProduto() {
  return {
      pronome:           oProduto.sNome,
      procodigo_barras:  oProduto.sCodigoBarras,
      forcodigo:         oProduto.iFornecedor,
      procusto:          oProduto.fValorCompra,
      provalor:          oProduto.fValorVenda,
      provalor_desconto: oProduto.fDesconto,
      proestoque:        oProduto.iQuantidade
  }
}

async function atualizarProduto(iProduto) {
  const { data } = await api.put(`api/produto/${iProduto}`, {

  });
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