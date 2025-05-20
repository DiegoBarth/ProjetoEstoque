<template>
  <div class="w-full p-4">
    <div class="bg-gray-100 p-6 rounded-lg shadow-md">
      <div class="flex items-center mb-4">
        <i class="text-2xl mr-2">📦</i>
        <h2 class="text-xl font-semibold">Cadastro de produto</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Produto</label>
          <input
            v-model="product.product_name"
            type="text"
            maxlength="100"
            placeholder="Informe uma descrição"
            class="w-full mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Código de barras</label>
          <input
            v-model="product.product_barcode"
            type="text"
            maxlength="100"
            class="w-full mt-1 p-2 border rounded"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Quantidade</label>
          <input
            v-model="product.quantity"
            type="text"
            maxlength="4"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('quantity')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor compra</label>
          <input
            v-model="product.purchase_price"
            type="text"
            maxlength="7"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('purchase_price')"
            @change="formatCurrency('purchase_price')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Valor venda</label>
          <input
            v-model="product.sale_value"
            type="text"
            maxlength="7"
            class="w-full mt-1 p-2 border rounded"
            @input="onlyNumbers('sale_value')"
            @change="formatCurrency('sale_value')"
          />
        </div>

        <div>
          <label class="block text-sm font-medium">Desconto</label>
          <input
            v-model="product.discount"
            type="text"
            maxlength="3"
            class="w-full mt-1 p-2 border rounded"
            @input="handleDiscount()"
          />
        </div>
      </div>

      <div class="mt-6 flex gap-4">
        <button
          v-if="!product.code"
          @click="addProduct"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        >
          Incluir
        </button>
        <button
          v-else
          @click="updateProduct"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Atualizar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'

const product = reactive({
  code: '',
  product_name: '',
  product_barcode: '',
  quantity: '',
  purchase_price: '',
  sale_value: '',
  discount: ''
})

function addProduct() {
  console.log('Produto incluído:', product)
}

function updateProduct() {
  console.log('Produto atualizado:', product)
}

function onlyNumbers(field) {
  product[field] = product[field].replace(/\D/g, '')
}

function formatCurrency(field) {
  const valor = parseFloat(product[field]) / 100
  product[field] = valor.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  })
}

function handleDiscount() {
  product.discount = product.discount.replace(/\D/g, '')
  if (product.discount && !product.discount.includes('%')) {
    product.discount += '%'
  }
}
</script>