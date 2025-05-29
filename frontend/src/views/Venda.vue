<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo='Vendas' :bMostraBotao="false">
         <template #gridConsulta>
            <GridVendas v-if="aVendas" :aVendas="aVendas" />
         </template>
      </Consulta>      
      <!-- <ModalExclusao v-if="iVendaExclusao" @fecharModal="() => {iVendaExclusao = false; bShowModal = false}" @excluirRegistro="excluirVenda"/> -->
   </div>
</template>

<script setup>
//#region Componentes
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridVendas from '../components/GridVendas.vue';
//#endregion

//#region Dependências
import { onMounted, ref } from 'vue';
import api from '../api';
import { useAtendimentoStore } from '../stores/atendimentoStore';
//#endregion

const oVenda = ref({
   iVenda:        '',
   sNome:           '',
   sCpf:            '',
   sDataNascimento: '',
   sTelefone:       '',
   sEndereco:       ''
});

const oVendaStore = useAtendimentoStore();
const iVendaExclusao = ref(null);
const iAcaoAtual = ref(0);
const aVendas = ref();
const bShowModal = ref(false);

onMounted(async () => {
   aVendas.value = await oVendaStore.getVendas();
});

</script>