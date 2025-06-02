<template>
<div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden"> 
   <Consulta sTitulo='Fornecedores' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
         <GridFornecedores v-if="aFornecedores" :aFornecedores="aFornecedores"   @showModalCadastro="showModalCadastro" @showModalExclusao="showModalExclusao"/>
      </template>
   </Consulta>
   <CadastroFornecedores v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarFornecedor="adicionarFornecedor" @atualizarFornecedor="atualizarFornecedor" :oFornecedor="oFornecedor" :iAcaoAtual="iAcaoAtual" />
   <ModalExclusao v-if="iFornecedorExclusao" @fecharModal="() => iFornecedorExclusao = false" @excluirRegistro="excluirFornecedor" />   
</div>
</template>

<script setup>
// #region Componentes
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridFornecedores from '../components/GridFornecedores.vue';
import CadastroFornecedores from '../components/CadastroFornecedores.vue';
// #endregion

// #region Dependências
import { onMounted, ref } from 'vue';
import { useFornecedorStore } from '../stores/fornecedorStore';
import { format, parseISO } from 'date-fns';
import { formatarCPFCNPJ, formatarTelefone, formatarData, isDataMaiorAtual } from '../utils/main';
// #endregion

const oFornecedorStore = useFornecedorStore();

const iFornecedorExclusao = ref(0);
const iAcaoAtual = ref(0);
const bShowModal = ref(false);
const aFornecedores = ref();
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

onMounted(async () => {
   aFornecedores.value = await oFornecedorStore.getFornecedores();
})

async function adicionarFornecedor(oDados) {
   if(isDataMaiorAtual(oDados.sDataFundacao)) {
      return utils.alerta('A data de fundação não pode ser maior que a data atual.', 'error')
   }

   if(utils.validarCamposObrigatorios()) {
      await oFornecedorStore.cadastrarFornecedor(tratarDadosFornecedor(oDados));
      utils.alerta('Fornecedor cadastrado com sucesso');
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function atualizarFornecedor(oDados, iFornecedor) {
   if(isDataMaiorAtual(oDados.sDataFundacao)) {
      return utils.alerta('A data de fundação não pode ser maior que a data atual.', 'error')
   }

   if(utils.validarCamposObrigatorios()) {
      await oFornecedorStore.atualizarFornecedor(iFornecedor, tratarDadosFornecedor(oDados));
      utils.alerta('Fornecedor alterado com sucesso');
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function excluirFornecedor(iFornecedor) {
   await oFornecedorStore.excluirFornecedor(iFornecedorExclusao.value);
   utils.alerta('Fornecedor excluído com sucesso!');
   recarregarGrid();
   iFornecedorExclusao.value = null;
}

function showModalCadastro(iAcao, oFornecedorSelecionado) {
   bShowModal.value = true;
   iAcaoAtual.value = iAcao;

   if(iAcao != 1) {        
      oFornecedor.value = {            
         iFornecedor:   oFornecedorSelecionado.forcodigo,
         sRazaoSocial:  oFornecedorSelecionado.forrazao_social,
         sNomeFantasia: oFornecedorSelecionado.fornome_fantasia,
         sCpfCnpj:      formatarCPFCNPJ(oFornecedorSelecionado.forcpfcnpj),
         sTelefone:     formatarTelefone(oFornecedorSelecionado.fortelefone),
         sEmail:        oFornecedorSelecionado.foremail,
         sEndereco:     oFornecedorSelecionado.forendereco,
         sDataFundacao: formatarData(oFornecedorSelecionado.fordata_fundacao, false)
      };            
   }  
}

function showModalExclusao(iFornecedor) {
   iFornecedorExclusao.value = iFornecedor; 
}

async function recarregarGrid() {
   aFornecedores.value = null;
   aFornecedores.value = await oFornecedorStore.getFornecedores();
}

function tratarDadosFornecedor(oDados) {
   return {
      iFornecedor:   oDados.iFornecedor,
      sRazaoSocial:  oDados.sRazaoSocial,
      sNomeFantasia: oDados.sNomeFantasia,
      sCpfCnpj:      oDados.sCpfCnpj.replace(/\D/g, ''),
      sTelefone:     oDados.sTelefone.replace(/\D/g, ''),
      sEmail:        oDados.sEmail,
      sEndereco:     oDados.sEndereco,
      sDataFundacao: oDados.sDataFundacao
   }
}


</script>