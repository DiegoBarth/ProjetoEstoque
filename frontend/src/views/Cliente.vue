<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo='Clientes' @showModalCadastro="showModalCadastro(1)">
         <template #gridConsulta>
            <GridClientes v-if="aClientes" :aClientes="aClientes" @showModalCadastro="showModalCadastro"
               @showModalExclusao="showModalExclusao" />
         </template>
      </Consulta>
      <CadastroClientes v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarCliente="adicionarCliente"
         @atualizarCliente="atualizarCliente" :oCliente="oCliente" :iAcaoAtual="iAcaoAtual" />
      <ModalExclusao v-if="iClienteExclusao" @fecharModal="() => {iClienteExclusao = false; bShowModal = false}" @excluirRegistro="excluirCliente"/>
   </div>
</template>

<script setup>
//#region Componentes
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridClientes from '../components/GridClientes.vue';
import CadastroClientes from '../components/CadastroClientes.vue';
//#endregion

//#region Dependências
import { onMounted, ref } from 'vue';
import api from '../api';
import { useClienteStore } from '../stores/clienteStore';
import { format, parseISO } from 'date-fns';
import { formatarCPF, formatarData, formatarTelefone, isDataMaiorAtual, limparCampos } from '../utils/main';
//#endregion

const oCliente = ref({
   iCliente:        '',
   sNome:           '',
   sCpf:            '',
   sDataNascimento: '',
   sTelefone:       '',
   sEndereco:       ''
});

const oClienteStore = useClienteStore();
const iClienteExclusao = ref(null);
const iAcaoAtual = ref(0);
const aClientes = ref();
const bShowModal = ref(false);

onMounted(async () => {
   aClientes.value = await oClienteStore.getClientes();
});

async function adicionarCliente(oDados) {
   if(isDataMaiorAtual(oDados.sDataNascimento)) {
      return utils.alerta('A data de nascimento não pode ser maior que a data atual', 'error');
   }

   if(utils.validarCamposObrigatorios()) {
      await oClienteStore.cadastrarCliente(formatarDadosCliente(oDados));
      utils.alerta('Cliente cadastrado com sucesso!');      
      recarregarGrid();
      limparCampos();
   }
}

async function atualizarCliente(oDados, iCliente) {
   if(isDataMaiorAtual(oDados.sDataNascimento)) {
      return utils.alerta('A data de nascimento não pode ser maior que a data atual', 'error');
   }

   if(utils.validarCamposObrigatorios()) {
      await oClienteStore.atualizarCliente(iCliente, formatarDadosCliente(oDados));      
      bShowModal.value = false;
      recarregarGrid();
   }
}

async function excluirCliente() {
   await oClienteStore.excluirCliente(iClienteExclusao.value);   
   iClienteExclusao.value = null;
   recarregarGrid();
}

function showModalCadastro(iAcao, oClienteSelecionado) {
   bShowModal.value = true;
   iAcaoAtual.value = iAcao;

   if(iAcao != 1) {
      oCliente.value = {
         iCliente:        oClienteSelecionado.clicodigo,
         sNome:           oClienteSelecionado.clinome,
         sCpf:            formatarCPF(oClienteSelecionado.clicpf),
         sDataNascimento: format(parseISO(oClienteSelecionado.clidata_nascimento), 'yyyy-MM-dd'),
         sTelefone:       formatarTelefone(oClienteSelecionado.clitelefone),
         sEndereco:       oClienteSelecionado.cliendereco
      };
   }
}

function showModalExclusao(iCodigo) {
   iClienteExclusao.value = iCodigo;
}

function formatarDadosCliente(oDados) {
   return {
      clinome:            oDados.sNome,
      clicpf:             oDados.sCpf.replace(/\D/g, ''),
      clidata_nascimento: oDados.sDataNascimento,
      clitelefone:        oDados.sTelefone.replace(/\D/g, ''),
      cliendereco:        oDados.sEndereco
   };
}

async function recarregarGrid() {
    aClientes.value = null;
    aClientes.value = await oClienteStore.getClientes();
}

</script>