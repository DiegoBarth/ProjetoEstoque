<template>
<div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden"> 
   <Consulta sTitulo='Metas' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
         <GridMetas v-if="aMetas" :aMetas="aMetas"   @showModalCadastro="showModalCadastro" @showModalExclusao="showModalExclusao"/>
      </template>
   </Consulta>
   <CadastroMetas v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarMeta="adicionarMeta" @atualizarMeta="atualizarMeta" :oMeta="oMeta" :iAcaoAtual="iAcaoAtual" :aOpcoes="aTiposMeta" />
   <ModalExclusao v-if="iMetaExclusao" @fecharModal="() => iMetaExclusao = false" @excluirRegistro="excluirMeta" />   
</div>
</template>

<script setup>
// #region Componentes
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridMetas from '../components/GridMetas.vue';
import CadastroMetas from '@/components/CadastroMetas.vue';
// #endregion

// #region Dependências
import { onMounted, ref } from 'vue';
import { useMetaStore } from '../stores/metaStore';
import { formatarData, limparCampos, converterParaMoeda, normalizarValor, validarCamposObrigatorios } from '../utils/main';
// #endregion

const oMetaStore = useMetaStore();''

const iMetaExclusao = ref(0);
const iAcaoAtual    = ref(0);
const bShowModal    = ref(false);
const aMetas        = ref();
const oMeta         = ref({
   iMeta:           '',
   sDescricao:      '',
   iTipo:           '',
   sTipo:           '',
   fValorMeta:      '',
   iQuantidadeMeta: '',
   sDataInicial:    '',
   sDataFinal:      '',
   iUsuario:        '',
   sUsuario:        '',
   iProduto:        '',
   sProduto:        ''
});
const aTiposMeta = [
   { iValor: 1, sDescricao: 'Valor' },
   { iValor: 2, sDescricao: 'Quantidade' },
   { iValor: 3, sDescricao: 'Valor + Quantidade' },
   { iValor: 4, sDescricao: 'Valor por Usuário' },
   { iValor: 5, sDescricao: 'Quantidade por usuário' },
   { iValor: 6, sDescricao: 'Valor + Quantidade por usuário' },
   { iValor: 7, sDescricao: 'Valor por produto' },
   { iValor: 8, sDescricao: 'Quantidade por produto' },
   { iValor: 9, sDescricao: 'Valor + Quantidade por produto' }
];

onMounted(async () => {
   aMetas.value = await oMetaStore.getMetas();
})

async function adicionarMeta(oDados) {
   if(validarCamposObrigatorios()) {
      await oMetaStore.cadastrarMeta(tratarDadosMeta(oDados));
      recarregarGrid();
      limparCampos();      
   }
}

async function atualizarMeta(oDados, iMeta) {
   if(validarCamposObrigatorios()) {
      await oMetaStore.atualizarMeta(iMeta, tratarDadosMeta(oDados));
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function excluirMeta() {
   await oMetaStore.excluirMeta(iMetaExclusao.value);
   recarregarGrid();
   iMetaExclusao.value = null;
}

function showModalCadastro(iAcao, oMetaSelecionado) {
   bShowModal.value = true;
   iAcaoAtual.value = iAcao;

   if(iAcao != 1) {        
      oMeta.value = {            
         iMeta:           oMetaSelecionado.mecodigo,
         sDescricao:      oMetaSelecionado.medescricao,
         iTipo:           oMetaSelecionado.metipo,
         sTipo:           oMetaSelecionado.metipo_descricao,
         fValorMeta:      oMetaSelecionado.mevalor_meta ? converterParaMoeda(oMetaSelecionado.mevalor_meta) : null,
         iQuantidadeMeta: oMetaSelecionado.mequantidade_meta ?? null,
         sDataInicial:    formatarData(oMetaSelecionado.medata_inicio, false),
         sDataFinal:      formatarData(oMetaSelecionado.medata_fim, false),
         iUsuario:        oMetaSelecionado.usucodigo,
         sUsuario:        oMetaSelecionado.usuario_descricao,
         iProduto:        oMetaSelecionado.procodigo,
         sProduto:        oMetaSelecionado.produto_descricao
      };

      console.log(oMeta.value);
   }  
}

function showModalExclusao(iMeta) {
   iMetaExclusao.value = iMeta; 
}

async function recarregarGrid() {
   aMetas.value = null;
   aMetas.value = await oMetaStore.getMetas();
}

function tratarDadosMeta(oDados) {
   return {
      iMeta:           Number(oDados.iMeta),
      sDescricao:      oDados.sDescricao,
      iTipo:           Number(oDados.iTipo),
      sTipo:           oDados.metipo_descricao,
      fValorMeta:      normalizarValor(oDados.fValorMeta),
      iQuantidadeMeta: oDados.iQuantidadeMeta,
      sDataInicial:    formatarData(oDados.sDataInicial),
      sDataFinal:      formatarData(oDados.sDataFinal),
      iUsuario:        oDados.iUsuario,
      sUsuario:        oDados.sUsuario,
      iProduto:        oDados.iProduto,
      sProduto:        oDados.sProduto
   }
}


</script>