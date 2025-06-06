<template>
   <div class="card-principal w-[calc(100vw-50px)] h-[calc(100vh-50px)] m-[25px] rounded-xl overflow-hidden">        
      <Consulta sTitulo='Usuários' @showModalCadastro="showModalCadastro(1)">      
         <template #gridConsulta>
            <GridUsuarios v-if="aUsuarios" :aUsuarios="aUsuarios" @showModalCadastro="showModalCadastro"
               @showModalExclusao="showModalExclusao" />
         </template>      
      </Consulta>           
      <CadastroUsuarios v-if="bShowModal" @fecharModal="() => bShowModal = false" @adicionarUsuario="adicionarUsuario"
         @atualizarUsuario="atualizarUsuario" :oUsuario="oUsuario" :iAcaoAtual="iAcaoAtual" :aOpcoes="aNiveis" />
      <ModalExclusao v-if="iUsuarioExclusao" @fecharModal="() => {iUsuarioExclusao = false; bShowModal = false}"
         @excluirRegistro="excluirUsuario" />
   </div>
</template>

<script setup>

//#region Componentes
import ModalExclusao from '../components/UI/ModalExclusao.vue';
import Consulta from '../components/UI/Consulta.vue';
import GridUsuarios from '../components/GridUsuarios.vue';
import CadastroUsuarios from '../components/CadastroUsuarios.vue';
//#endregion

//#region Dependências
import { onMounted, ref } from 'vue';
import { useUsuarioStore } from '../stores/usuarioStore';
import * as utils from '../utils/main';
//#endregion

const oUsuario = ref({
   iUsuario: '',
   sNome: '',
   sNomeUsuario: '',
   iNivel: '',
   iAtivo: '', 
   sSenha: ''
});

const oUsuarioStore    = useUsuarioStore();
const iUsuarioExclusao = ref(null);
const iAcaoAtual       = ref(0);
const aUsuarios        = ref();
const aNiveis          = ref([]);
const bShowModal       = ref(false);

onMounted(async () => {  
   aUsuarios.value = await oUsuarioStore.getUsuarios();   
});

async function adicionarUsuario(oDados) {
   if(utils.validarCamposObrigatorios()) {
      await oUsuarioStore.cadastrarUsuario(tratarDadosUsuario(oDados));
      recarregarGrid();
      utils.limparCampos();
   }
}

async function atualizarUsuario(oDados, iUsuario) {
   if(utils.validarCamposObrigatorios()) {      
      await oUsuarioStore.atualizarUsuario(iUsuario, tratarDadosUsuario(oDados));
      recarregarGrid();
      bShowModal.value = false;
   }
}

async function excluirUsuario(iUsuario) {
   await oUsuarioStore.excluirUsuario(iUsuarioExclusao.value);
   recarregarGrid();
   iUsuarioExclusao.value = null;
}

function showModalCadastro(iAcao, oUsuarioSelecionado) {
   bShowModal.value = true;
   iAcaoAtual.value = iAcao;

   if(iAcao != 1) {        
      oUsuario.value = {       
         iUsuario:     oUsuarioSelecionado.usucodigo,     
         sNome:        oUsuarioSelecionado.usunome,
         sNomeUsuario: oUsuarioSelecionado.usunome_usuario,
         iNivel:       oUsuarioSelecionado.usunivel,
         sNivel:       oUsuarioSelecionado.sNivel,
         sSituacao:    oUsuarioSelecionado.sSituacao,
         iAtivo:       Number(oUsuarioSelecionado.usuativo)
      };
   }  

   if(iAcao != 3) {
      oUsuarioStore.getNiveisUsuario().then((oRetorno) => {
         aNiveis.value = tratarFiltroNiveis(oRetorno);
      });
   }
}

function showModalExclusao(iCodigo) { 
   iUsuarioExclusao.value = iCodigo;
}

function tratarFiltroNiveis(aNiveis) {
   const aFiltro = [];

   if(aNiveis.length) {
      for(const oNivel of aNiveis) {
         aFiltro.push({
            iValor:     oNivel.nucodigo,
            sDescricao: oNivel.nunome
         });
      }
   }

   return aFiltro;
}

function tratarDadosUsuario(oDados) {
   return {
      usunome: oDados.sNome,
      usunome_usuario: oDados.sNomeUsuario,
      usunivel: oDados.iNivel,
      ususenha: oDados.sSenha,
      usuativo: oDados.iAtivo
   }
}

async function recarregarGrid() {
   aUsuarios.value = null;
   aUsuarios.value = await oUsuarioStore.getUsuarios();
}

</script>