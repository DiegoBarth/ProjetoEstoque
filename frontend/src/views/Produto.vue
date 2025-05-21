<template>
  <div class="w-full h-full">        
    <div class="flex items-center justify-between w-full py-10 px-30">
      <h1 class="text-3xl">Produtos</h1>
      <button @click="showModalCadastro" class="cursor-pointer bg-green-400 rounded-sm p-2">Cadastrar produto</button>      
    </div>
    <div class="w-full grid grid-cols-2 gap-5 px-30">
      <div>
          <label class="block text-sm font-medium">Produto</label>
          <input
            v-model="oProduto.sNome"
            type="text"
            maxlength="100"
            placeholder="Informe uma descrição"
            class="mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Código de barras</label>
          <input
            v-model="oProduto.sCodigoBarras"
            type="text"
            maxlength="20"            
            class="mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Quantidade</label>
          <input
            v-model="oProduto.iQuantidade"
            type="text"
            maxlength="4"
            class="mt-1 p-2 border rounded"
            @input="onlyNumbers('iQuantidade')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor compra</label>
          <input
            v-model="oProduto.fValorCompra"
            type="text"
            maxlength="12"
            class="mt-1 p-2 border rounded"
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
            class="mt-1 p-2 border rounded"
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
            class="mt-1 p-2 border rounded"            
            @change="formatCurrency('fDesconto')"
          />
        </div>
        <div>
          <label class="block text-sm font-medium">Fornecedor</label>
          <input
            v-model="oProduto.iFornecedor"
            type="text"
            maxlength="3"
            class="mt-1 p-2 border rounded"            
          />
        </div>
    </div>
    <Grid v-if="aProdutos" class="mt-10" :aCabecalhos="['Produto', 'Nome do Produto', 'Valor de Custo', 'Valor de Venda', 'Quantidade', 'Valor de Desconto', 'Fornecedor', 'Ações']" sLayout="1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr">
      <tr class="grid" v-for="(oProduto, iIndice) of aProdutos" :key="iIndice" style="grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;">
        <td class="p-2">{{ oProduto.procodigo          }}</td>
        <td class="p-2">{{ oProduto.pronome            }}</td>
        <td class="p-2">{{ oProduto.procusto           }}</td>
        <td class="p-2">{{ oProduto.provalor           }}</td>
        <td class="p-2">{{ oProduto.proestoque         }}</td>
        <td class="p-2">{{ oProduto.provalor_desconto  }}</td>
        <td class="p-2">{{ oProduto.forcodigo          }}</td>
        <td class="p-2"></td>        
      </tr>
    </Grid>   
    <ModalCadastro class="flex items-center justify-content-center" @adicionarProduto="adicionarProduto" @atualizarProduto="atualizarProduto">       
      <div class="flex items-center mb-4">
        <p class="text-2xl mr-2">📦</p>
        <h2 class="text-xl font-semibold">Cadastro de produto</h2>
      </div>

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
          <input
            v-model="oProduto.iFornecedor"
            type="text"
            maxlength="3"
            class="w-full mt-1 p-2 border rounded"            
          />
        </div>
      </div>            
    </ModalCadastro>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Grid from '../components/UI/Grid.vue';

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

onMounted(async () => {
  const { data } = await api.get('/api/produto');  

  aProdutos.value = data.aProdutos;

  console.log(aProdutos)  
});

function showModalCadastro() {
  const oModal = $('#modalCadastro');

  oModal.css('display', 'flex');
}

async function adicionarProduto() {    
  oProduto.fDesconto    = typeof oProduto.fDesconto == 'string' ? parseFloat(oProduto.fDesconto.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;
  oProduto.fValorCompra = typeof oProduto.fValorCompra == 'string' ? parseFloat(oProduto.fValorCompra.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;
  oProduto.fValorVenda  = typeof oProduto.fValorVenda == 'string' ? parseFloat(oProduto.fValorVenda.replace(/\D/g, '')) / 100 : parseFloat(oProduto.fDesconto) / 100;

  console.log(parseFloat(oProduto.fDesconto) / 100,parseFloat(oProduto.fValorCompra) / 100,parseFloat(oProduto.fValorVenda) / 100)

  const { data } = await api.post('api/produto', {
    ...oProduto
  });
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