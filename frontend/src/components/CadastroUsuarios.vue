<template>
   <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🧑🏻 Cadastro de Usuário" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarUsuario', oUsuario)" @alterar="$emit('atualizarUsuario', oUsuario, oUsuario.iUsuario)">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true" sTitulo="Nome"            v-model="oUsuario.sNome"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true" sTitulo="Nome de Usuário" v-model="oUsuario.sNomeUsuario"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="select" :bObrigatorio="true" sTitulo="Nível de acesso" v-model="oUsuario.iNivel" :aOpcoes="aOpcoes"/>
        <Campo :disabled="iAcaoAtual == 3" sTipo="select" :bObrigatorio="true" sTitulo="Situação"        v-model="oUsuario.iAtivo" :aOpcoes="[{iValor: 1, sDescricao: 'Ativo'}, {iValor: 0, sDescricao: 'Inativo'}]"/>
        <Campo v-if="iAcaoAtual != 3" sTipo="text"   :bObrigatorio="true" sTitulo="Senha"           v-model="oUsuario.sSenha"/>
      </div>            
   </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { formatarCPF, formatarTelefone, limparCampos } from '../utils/main'
import { onBeforeMount, onMounted } from 'vue';

const oProps = defineProps(['iAcaoAtual', 'oUsuario', 'aOpcoes']);
defineEmits(['adicionarUsuario', 'atualizarUsuario', 'fecharModal']);

onMounted(() => {
   if(oProps.iAcaoAtual == 1) {
      limparCampos();
   }
});
</script>