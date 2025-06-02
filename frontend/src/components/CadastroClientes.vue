<template>
    <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🧑🏻 Cadastro de Cliente" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarCliente', oCliente)" @alterar="$emit('atualizarCliente', oCliente, oCliente.iCliente)">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" maxlength="45" :bObrigatorio="true" sTitulo="Nome do Cliente"    v-model="oCliente.sNome"             />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" maxlength="14" :bObrigatorio="true" sTitulo="CPF"                v-model="oCliente.sCpf"              @input="formatarCPF($event.target)"/>
            <Campo :disabled="iAcaoAtual == 3" sTipo="date" maxlength="10" :bObrigatorio="true" sTitulo="Data de Nascimento" v-model="oCliente.sDataNascimento"   />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" maxlength="15" :bObrigatorio="true" sTitulo="Telefone"           v-model="oCliente.sTelefone"         @input="oCliente.sTelefone = formatarTelefone(oCliente.sTelefone)"/>
            <Campo class="col-span-2" :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="false" sTitulo="Endereço"    v-model="oCliente.sEndereco"         />
        </div>            
    </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { formatarCPF, formatarTelefone, limparCampos } from '../utils/main'
import { onBeforeMount, onMounted } from 'vue';

const oProps = defineProps(['iAcaoAtual', 'oCliente']);
defineEmits(['adicionarCliente', 'atualizarCliente', 'fecharModal']);

onMounted(() => {
    if(oProps.iAcaoAtual == 1) {
        limparCampos();
    }
})
</script>