<template>
  <div class="w-full h-full">        
    <Consulta sTitulo='Fornecedores' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
        <Grid v-if="aFornecedores" class="mt-10 text-left" :aCabecalhos="['Fornecedor', 'Razão Social', 'Nome Fantasia', 'CPF/CNPJ', 'Telefone', 'Ações']" sLayout="0.5fr 1fr 1fr 1fr 1fr 0.6fr">
            <tr class="grid" v-for="(oFornecedor, iIndice) of aFornecedores" :key="iIndice" style="grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 0.6fr;">
                <td class="p-2">{{ oFornecedor.forcodigo                     }}</td>
                <td class="p-2">{{ oFornecedor.forrazao_social               }}</td>
                <td class="p-2">{{ oFornecedor.fornome_fantasia              }}</td>
                <td class="p-2">{{ formatarCPFCNPJ(oFornecedor.forcpfcnpj)   }}</td>
                <td class="p-2">{{ formatarTelefone(oFornecedor.fortelefone) }}</td>            
                <td class="p-2 flex gap-2">
                    <span class="cursor-pointer" @click="showModalCadastro(3, oFornecedor)"><i class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
                    <span class="cursor-pointer" @click="showModalCadastro(2, oFornecedor)"><i class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
                    <span class="cursor-pointer" @click="() => iFornecedorExclusao = oFornecedor.forcodigo"><i class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
                </td>
            </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro :bModalAberto="bShowModal" class="flex items-center justify-content-center" sTitulo="🚚 Cadastro de fornecedor" :iAcao="iAcaoAtual" @fecharModal="() => {bShowModal = false;}" @incluir="adicionarFornecedor" @alterar="atualizarFornecedor">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">                        
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Razão Social"     v-model="oFornecedor.sRazaoSocial"  />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Nome Fantasia"    v-model="oFornecedor.sNomeFantasia" />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="CPF/CNPJ"         v-model="oFornecedor.sCpfCnpj"      />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Telefone"         v-model="oFornecedor.sTelefone"     />
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Email"            v-model="oFornecedor.sEmail"        />                                
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Endereço"         v-model="oFornecedor.sEndereco"     />                                
            <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="false" sTitulo="Data de Fundação" v-model="oFornecedor.sDataFundacao" />                                        
        </div>            
    </ModalCadastro>
    <Modal v-if="iFornecedorExclusao">
      <h1>Confirma a exclusão do registro?</h1>
      <div class="flex items-center gap-4">
        <button @click="excluirProduto(iFornecedorExclusao)" class="p-2 bg-yellow-400 rounded-sm">Sim</button>
        <button @click="() => iFornecedorExclusao = null" class="p-2 bg-red-400 rounded-sm">Não</button>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import api from '../api';
import ModalCadastro from '../components/ModalCadastro.vue';
import Modal from '../components/UI/Modal.vue';
import Grid from '../components/UI/Grid.vue';
import Consulta from '../components/UI/Consulta.vue';
import Campo from '../components/UI/Campo.vue';
import { useFornecedorStore } from '../stores/fornecedorStore';
import { format } from 'date-fns'

const oFornecedorStore = useFornecedorStore();

const iFornecedorExclusao = ref(0);
const iAcaoAtual = ref(0);
const bShowModal = ref(false);
const aFornecedores = ref([]);
const oFornecedor = ref({
    iFornecedor:   '',
    sRazaoSocial:  '',
    sNomeFantasia: '',
    sCpfCnpj:      '',
    sTelefone:     '',
    sEmail:        '',
    sEndereco:     '',
    sDataFundacao: ''
});

onBeforeMount(async () => {
    aFornecedores.value = await oFornecedorStore.getFornecedores();
})

function adicionarFornecedor() {

}

function atualizarFornecedor() {
    
}

function showModalCadastro(iAcao, oFornecedorSelecionado) {
    bShowModal.value = true;
    iAcaoAtual.value = iAcao;

    if(iAcao != 1) {        
        oFornecedor.value = {            
            sRazaoSocial:  oFornecedorSelecionado.forrazao_social,
            sNomeFantasia: oFornecedorSelecionado.fornome_fantasia,
            sCpfCnpj:      formatarCPFCNPJ(oFornecedorSelecionado.forcpfcnpj),
            sTelefone:     formatarTelefone(oFornecedorSelecionado.fortelefone),
            sEmail:        oFornecedorSelecionado.foremail,
            sEndereco:     oFornecedorSelecionado.forendereco,
            sDataFundacao: format(oFornecedorSelecionado.fordata_fundacao, 'dd/MM/yyyy')
        };            
    }  
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