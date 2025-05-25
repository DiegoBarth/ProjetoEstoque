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

defineProps(['iAcaoAtual', 'oCliente']);
defineEmits(['adicionarProduto', 'atualizarProduto', 'fecharModal']);

function formatarData(sValor) {
    if(sValor.length < 10) {
        utils.alerta('Data inválida', 'error');
        return '';
    }

    return format(parseISO(sValor), 'dd/MM/yyyy');
}

function formatarCPFCNPJ(sValor) {
    sValor = sValor.replace(/\D/g, '');

    if(sValor.length < 11 || sValor.length > 14) {
        utils.alerta('O valor informado é inválido', 'error');
        return '';
    }

    if(sValor.length > 11) {
        return sValor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
    }

    return sValor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

function formatarTelefone(sValor) {
    if(sValor.length < 8) {
        utils.alerta('O valor informado é inválido', 'error');
        return '';
    }

    if(sValor.length < 11) {
        if(sValor.length == 10) {
            return sValor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        }

        if(sValor.length == 8) {
            return sValor.replace(/(\d{4})(\d{4})/, '(47) $1-$2');
        }

        return sValor.replace(/(\d{5})(\d{4})/, '(47) $1-$2');
    }

    return sValor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}
</script>