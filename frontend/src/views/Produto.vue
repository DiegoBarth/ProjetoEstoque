<template>
  <div>
    <div>
      <button @click="showModalCadastro" >Abrir modal</button>
    </div>
    <ModalCadastro class="flex items-center justify-content-center" @adicionarProduto="adicionarProduto" @atualizarProduto="atualizarProduto">       
      <div class="flex items-center mb-4">
        <i class="text-2xl mr-2">📦</i>
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
            v-model="oProduto.iCodigoBarras"
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
            @input="handleDiscount()"
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
import { reactive } from 'vue'
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue'

const oProduto = reactive({
  iProduto: '',
  sNome: '',
  iCodigoBarras: '',
  iQuantidade: '',
  fValorCompra: '',
  fValorVenda: '',
  fDesconto: '',
  iFornecedor: ''
})

function showModalCadastro() {
  const oModal = $('#modalCadastro');

  oModal.css('display', 'flex');
}

async function adicionarProduto() {
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