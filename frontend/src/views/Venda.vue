<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">
      <Consulta sTitulo='Vendas' :bMostraBotao="false">
         <template #gridConsulta>
            <GridVendas v-if="aVendas" :aVendas="aVendas" @showModal="showModal" @showModalDevolucao="showModalDevolucao"/>
         </template>
      </Consulta>
      <Modal v-if="bShowModal">
         <h1 class="text-xl mb-4">{{ sTextoModal }}</h1>
         <div class="flex items-center gap-4 w-2/5">                        
            <button @click="iAcaoAtual == 1 ? finalizarVenda() : cancelarVenda()" class="text-white cursor-pointer p-2 bg-green-500 rounded-sm w-1/2">Sim</button>
            <button @click="() => bShowModal = false" class="text-white cursor-pointer p-2 bg-red-500 rounded-sm w-1/2">Não</button>
         </div>
      </Modal>
      <ModalDevolucao v-if="bExibirModalDevolucao" :aProdutos="aProdutos" @fecharModalDevolucao="() => {aProdutos.value = []; bExibirModalDevolucao = false}" />
   </div>
</template>

<script setup>
//#region Componentes
import Modal from '../components/UI/Modal.vue'
import Consulta from '../components/UI/Consulta.vue';
import GridVendas from '../components/GridVendas.vue';
//#endregion

//#region Dependências
import { onMounted, ref } from 'vue';
import api from '../api';
import { useAtendimentoStore } from '../stores/atendimentoStore';
import ModalDevolucao from '@/components/ModalDevolucao.vue';
//#endregion

const sTextoModal           = ref('');
const iVenda                = ref();
const bShowModal            = ref(false);
const bExibirModalDevolucao = ref(false);
const oVendaStore           = useAtendimentoStore();
const iVendaExclusao        = ref(null);
const iAcaoAtual            = ref(0);
const aVendas               = ref();
const aProdutos             = ref([]);

onMounted(async () => {
   aVendas.value = await oVendaStore.getVendas();
});

function showModal(iAcao, iCodigo) {
   iAcao == 1 ? 
      sTextoModal.value = 'Realmente deseja finalizar a venda?' :
      sTextoModal.value = 'Realmente deseja cancelar a venda?';
   iAcaoAtual.value = iAcao;
   iVenda.value = iCodigo;

   bShowModal.value = true;
}

function showModalDevolucao(oVenda) {
   bExibirModalDevolucao.value = true;
   aProdutos.value = oVenda.aProdutos;
}

async function finalizarVenda() {
   await oVendaStore.finalizarVenda(iVenda.value);
   bShowModal.value = false;
   aVendas.value = await oVendaStore.getVendas();
}

async function cancelarVenda() {
   await oVendaStore.cancelarVenda(iVenda.value);
   bShowModal.value = false;
   aVendas.value = await oVendaStore.getVendas();
}
</script>