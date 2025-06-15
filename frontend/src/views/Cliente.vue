<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo='Clientes' @showModalCadastro="showModalCadastro(1)">
         <template #gridConsulta>
            <GridClientes v-if="aClientes" :aClientes="aClientes" @showModalCadastro="showModalCadastro"
               @showModalExclusao="showModalExclusao" @showModalAnexo="showModalAnexo"/>
         </template>
      </Consulta>
      <CadastroClientes v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarCliente="adicionarCliente"
         @atualizarCliente="atualizarCliente" :oCliente="oCliente" :iAcaoAtual="iAcaoAtual" />
      <ModalExclusao 
         v-if="bShowModalExclusao"
         @fecharModal="() => { bShowModalExclusao = false; iExclusaoId = null; sExclusaoTipo = ''; }"
         @excluirRegistro="confirmarExclusao"
         style="z-index: 9999"
      />
      <CadastroClientesAnexos
         v-if="bShowModalAnexos"
         :iAcaoAtual="1"
         :clicodigo="iClienteAnexos"
         :aAnexosExistentes="aAnexos"
         @fecharModal="() => bShowModalAnexos = false"
         @adicionarAnexo="adicionarAnexo"
         @showModalExclusao="showModalExclusao"
      />
   </div>
</template>

<script setup>
   //#region Componentes
   import ModalExclusao from '../components/UI/ModalExclusao.vue';
   import Consulta from '../components/UI/Consulta.vue';
   import GridClientes from '../components/GridClientes.vue';
   import CadastroClientes from '../components/CadastroClientes.vue';
   import CadastroClientesAnexos from '@/components/CadastroClientesAnexos.vue';
   //#endregion

   //#region Dependências
   import { onMounted, ref } from 'vue';
   import { useClienteStore } from '../stores/clienteStore';
   import { format, parseISO } from 'date-fns';
   import { formatarCPF, formatarTelefone, isDataMaiorAtual, limparCampos } from '../utils/main';
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
   const iAcaoAtual = ref(0);
   const aClientes = ref();
   const bShowModal = ref(false);

   const bShowModalAnexos = ref(false);
   const iClienteAnexos = ref(null);
   const aAnexos = ref([]); 

   const bShowModalExclusao = ref(false);
   const iExclusaoId = ref(null);
   const sExclusaoTipo = ref('');

   onMounted(async () => {
      aClientes.value = await oClienteStore.getClientes();
   });

   async function adicionarCliente(oDados) {
      if(isDataMaiorAtual(oDados.sDataNascimento)) {
         return utils.alerta('A data de nascimento não pode ser maior que a data atual', 'error');
      }

      if(utils.validarCamposObrigatorios()) {
         await oClienteStore.cadastrarCliente(formatarDadosCliente(oDados));
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

   function showModalExclusao(sTipo, iCodigo) {
      sExclusaoTipo.value      = sTipo;
      iExclusaoId.value        = iCodigo;
      bShowModalExclusao.value = true;
   }

   function confirmarExclusao() {
      if(sExclusaoTipo.value === 'cliente') {
         excluirCliente();
      }
      else if(sExclusaoTipo.value === 'anexo') {
         excluirAnexo();
      }

      bShowModalExclusao.value = false;
      iExclusaoId.value        = null;
      sExclusaoTipo.value      = '';
   }

   async function excluirCliente() {
      await oClienteStore.excluirCliente(iExclusaoId.value);   

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

   async function showModalAnexo(codigoCliente) {
      iClienteAnexos.value = codigoCliente;
      
      aAnexos.value = await oClienteStore.getAnexosCliente(codigoCliente); // cria essa função na store

      bShowModalAnexos.value = true;
   }

   async function adicionarAnexo(anexo) {
      await oClienteStore.cadastrarAnexo(anexo);

      aAnexos.value = await oClienteStore.getAnexosCliente(iClienteAnexos.value); // atualiza lista
   }

   async function excluirAnexo() {
      await oClienteStore.removerAnexo(iExclusaoId.value);

      aAnexos.value = await oClienteStore.getAnexosCliente(iClienteAnexos.value);
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