<template>
   <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🚚 Cadastro de fornecedor" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarFornecedor', oFornecedor)" @alterar="$emit('atualizarFornecedor', oFornecedor, oFornecedor.iFornecedor)">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"   sTitulo="Nome Fantasia"    v-model="oFornecedor.sNomeFantasia" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false"  sTitulo="Razão Social"     v-model="oFornecedor.sRazaoSocial"  />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"   sTitulo="CPF/CNPJ"         maxlength="18" v-model="oFornecedor.sCpfCnpj" @input="formatarCPFCNPJ($event.target)"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"   sTitulo="Telefone"         maxlength="15" v-model="oFornecedor.sTelefone"     @input="formatarTelefone($event.target)"/>
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false"  sTitulo="Email"            v-model="oFornecedor.sEmail"        />                                
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false"  sTitulo="Endereço"         v-model="oFornecedor.sEndereco"     />                                
         <Campo :disabled="iAcaoAtual == 3" sTipo="date"   :bObrigatorio="false"  sTitulo="Data de Fundação" v-model="oFornecedor.sDataFundacao" />
      </div>            
   </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { formatarTelefone, limparCampos, formatarCPFCNPJ } from '../utils/main'
import { onMounted } from 'vue';

const oProps = defineProps(['iAcaoAtual', 'oFornecedor', 'aOpcoes']);
defineEmits(['adicionarFornecedor', 'atualizarFornecedor', 'fecharModal']);

onMounted(() => {
   if(oProps.iAcaoAtual == 1) {
      limparCampos();
   }
})
</script>