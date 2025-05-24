<template>
  <div class="w-full h-full">        
    <Consulta sTitulo='Clientes' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
        <Grid v-if="aClientes" class="mt-10 text-left" :aCabecalhos="['Cliente', 'Nome do Cliente', 'CPF', 'Data de Nascimento', 'Telefone', 'Endereço', 'Ações']" sLayout="0.5fr 1fr 0.6fr 1fr 0.6fr 1.8fr 0.6fr">
          <tr class="grid" v-for="(oCliente, iIndice) of aClientes" :key="iIndice" style="grid-template-columns: 0.5fr 1fr 0.6fr 1fr 0.6fr 1.8fr 0.6fr;">
            <td class="p-2">{{ oCliente.clicodigo          }}</td>
            <td class="p-2">{{ oCliente.clinome            }}</td>
            <td class="p-2">{{ formatarCPFCNPJ(oCliente.clicpf)             }}</td>
            <td class="p-2">{{ formatarData(oCliente.clidata_nascimento) }}</td>
            <td class="p-2">{{ formatarTelefone(oCliente.clitelefone)        }}</td>
            <td class="p-2">{{ oCliente.cliendereco        }}</td>            
            <td class="p-2 flex gap-2">
              <span class="cursor-pointer" @click="showModalCadastro(3, oCliente)"><i class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="showModalCadastro(2, oCliente)"><i class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="() => iClienteExclusao = oCliente.procodigo"><i class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
          </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro :bModalAberto="bShowModal" class="flex items-center justify-content-center" sTitulo="🧑🏻 Cadastro de Cliente" :iAcao="iAcaoAtual" @fecharModal="() => {bShowModal = false;}" @incluir="adicionarCliente" @alterar="atualizarCliente">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Nome do Cliente"    v-model="oCliente.sNome"         />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="CPF"                v-model="oCliente.sCpf" />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Data de Nascimento" v-model="oCliente.sDataNascimento"   />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Telefone"           v-model="oCliente.sTelefone"  />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Endereço"           v-model="oCliente.sEndereco"   />
      </div>            
    </ModalCadastro>
    <Modal v-if="iClienteExclusao">
      <h1>Confirma a exclusão do registro?</h1>
      <div class="flex items-center gap-4">
        <button @click="excluirCliente(iClienteExclusao)" class="p-2 bg-yellow-400 rounded-sm">Sim</button>
        <button @click="() => iClienteExclusao = null" class="p-2 bg-red-400 rounded-sm">Não</button>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Modal from '../components/UI/Modal.vue';
import Grid from '../components/UI/Grid.vue';
import Consulta from '../components/UI/Consulta.vue';
import Campo from '../components/UI/Campo.vue';
import { useClienteStore } from '../stores/clienteStore';
import { format } from 'date-fns';

const oCliente = ref({
  iCliente: '',
  sNome: '',
  sCpf: '',
  sDataNascimento: '',
  sTelefone: '',
  sEndereco: ''  
})

const oClienteStore = useClienteStore();
const iClienteExclusao = ref(null);
const iAcaoAtual = ref(0);
const aClientes = ref([]);
const bShowModal = ref(false);

onMounted(async () => {  
  aClientes.value = await oClienteStore .getClientes();   
});

function adicionarCliente() {

}

function atualizarCliente() {
    
}

function showModalCadastro(iAcao, oClienteSelecionado) {
    bShowModal.value = true;
    iAcaoAtual.value = iAcao;

    if(iAcao != 1) {        
        oCliente.value = {            
            sNome:           oClienteSelecionado.clinome,
            sCpf:            formatarCPFCNPJ(oClienteSelecionado.clicpf),
            sDataNascimento: formatarData(oClienteSelecionado.clidata_nascimento),
            sTelefone:       formatarTelefone(oClienteSelecionado.clitelefone),
            sEndereco:       oClienteSelecionado.cliendereco
        };            
    }  
}

function formatarData(sValor) {
    return format(sValor, 'dd/MM/yyyy');
}

function formatarCPFCNPJ(sValor) {
  if(sValor.length > 11) {
    return sValor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
  }

  return sValor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

function formatarTelefone(sValor) {
  if(sValor.length < 11) {
    if(sValor.length == 10) {
      return sValor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3')
    }

    if(sValor.length == 8) {
      return sValor.replace(/(\d{4})(\d{4})/, '(47) $1-$2')
    }
    
    return sValor.replace(/(\d{5})(\d{4})/, '(47) $1-$2')
  }

  return sValor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

</script>