<template>
   <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="📦 Cadastro de produto" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarProduto', oProduto)" @alterar="$emit('atualizarProduto', oProduto, oProduto.iProduto)">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Produto"          v-model="oProduto.sNome"         maxlength="100" placeholder="Informe uma descrição"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Código de barras" v-model="oProduto.sCodigoBarras" maxlength="20"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Quantidade"       v-model="oProduto.iQuantidade"   maxlength="6"  @input="() => oProduto.iQuantidade = oProduto.iQuantidade.replace(/\D/g, '')"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Valor compra"     v-model="oProduto.fValorCompra"  maxlength="13" @input="() => oProduto.fValorCompra = converterParaMoeda(oProduto.fValorCompra)"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Valor venda"      v-model="oProduto.fValorVenda"   maxlength="13" @input="() => oProduto.fValorVenda = converterParaMoeda(oProduto.fValorVenda)"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Valor desconto"   v-model="oProduto.fDesconto"     maxlength="13" @input="() => oProduto.fDesconto = converterParaMoeda(oProduto.fDesconto)"/>                                
         <Campo v-if="iAcaoAtual != 3" sTipo="select" :bObrigatorio="true"  sTitulo="Fornecedor" v-model="oProduto.iFornecedor" :aOpcoes="aOpcoes"/>        
         <Campo v-else disabled sTipo="text" :bObrigatorio="true"  sTitulo="Fornecedor" v-model="oProduto.sFornecedor" />        
      </div>            
   </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { converterParaMoeda, limparCampos } from '../utils/main'
import { onMounted } from 'vue';

const oProps = defineProps(['iAcaoAtual', 'oProduto', 'aOpcoes']);
defineEmits(['adicionarProduto', 'atualizarProduto', 'fecharModal']);

onMounted(() => {
    if(oProps.iAcaoAtual == 1) {
        limparCampos();
    }
})
</script>