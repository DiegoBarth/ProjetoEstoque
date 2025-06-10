<template>
   <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🚚 Cadastro de meta" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarMeta', oMeta)" @alterar="$emit('atualizarMeta', oMeta, oMeta.iMeta)">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Descrição"       v-model="oMeta.sDescricao" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Tipo"            v-model="oMeta.iTipo" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Valor Meta"      v-model="oMeta.fValorMeta"  />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Quantidade Meta" v-model="oMeta.iQuantidadeMeta" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="date"   :bObrigatorio="true"  sTitulo="Data inicial"    v-model="oMeta.sDataInicial" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="date"   :bObrigatorio="true"  sTitulo="Data final"      v-model="oMeta.sDataFinal" />                       
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Usuário"         v-model="oMeta.iUsuario" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Produto"         v-model="oMeta.iProduto" />
      </div>            
   </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { limparCampos } from '../utils/main'
import { onMounted } from 'vue';

const oProps = defineProps(['iAcaoAtual', 'oMeta', 'aOpcoes']);
defineEmits(['adicionarMeta', 'atualizarMeta', 'fecharModal']);

onMounted(() => {
   if(oProps.iAcaoAtual == 1) {
      limparCampos();
   }
})
</script>