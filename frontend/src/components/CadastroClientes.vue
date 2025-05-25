<template>
    <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🧑🏻 Cadastro de Cliente" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarCliente', oCliente)" @alterar="$emit('atualizarCliente', oCliente, oCliente.iCliente)">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Nome do Cliente"    v-model="oCliente.sNome"             />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="CPF"                v-model="oCliente.sCpf"              @blur="() => oCliente.sCpf = formatarCPFCNPJ(oCliente.sCpf)"/>
            <Campo :disabled="iAcaoAtual == 3" sTipo="date" :bObrigatorio="true" sTitulo="Data de Nascimento" v-model="oCliente.sDataNascimento"   />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Telefone"           v-model="oCliente.sTelefone"         @blur="() => oCliente.sTelefone = formatarTelefone(oCliente.sTelefone)"/>
            <Campo class="col-span-2" :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="false" sTitulo="Endereço"          v-model="oCliente.sEndereco"         />
        </div>            
    </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { formatarCPFCNPJ, formatarTelefone } from '../utils/main'

defineProps(['iAcaoAtual', 'oCliente']);
defineEmits(['adicionarProduto', 'atualizarProduto', 'fecharModal']);
</script>