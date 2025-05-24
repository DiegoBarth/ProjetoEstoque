<template>
  <div class="w-full h-full">        
    <Consulta sTitulo='Usuários' @showModalCadastro="showModalCadastro(1)">      
      <template #gridConsulta>
        <Grid v-if="aUsuarios" class="mt-10 text-left" :aCabecalhos="['Usuário', 'Nome', 'Nome de Usuário', 'Nível de acesso', 'Situação', 'Ações']" sLayout="0.5fr 1fr 1fr 1fr 1fr 0.6fr">
          <tr class="grid" v-for="(oUsuario, iIndice) of aUsuarios" :key="iIndice" style="grid-template-columns: 0.5fr 1fr 1fr 1fr 1fr 0.6fr;">
            <td class="p-2">{{ oUsuario.usucodigo          }}</td>
            <td class="p-2">{{ oUsuario.usunome            }}</td>
            <td class="p-2">{{ oUsuario.usunome_usuario             }}</td>
            <td class="p-2">{{ oUsuario.usunivel == 1 ? 'Admin' : 'Caixa' }}</td>
            <td class="p-2">{{ oUsuario.usuativo == 1 ? 'Ativo' : 'Inativo' }}</td>            
            <td class="p-2 flex gap-2">
              <span class="cursor-pointer" @click="showModalCadastro(3, oUsuario)"><i class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="showModalCadastro(2, oUsuario)"><i class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
              <span class="cursor-pointer" @click="() => iUsuarioExclusao = oUsuario.procodigo"><i class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
          </tr>
        </Grid>
      </template>      
    </Consulta>           
    <ModalCadastro :bModalAberto="bShowModal" class="flex items-center justify-content-center" sTitulo="🧑🏻 Cadastro de Usuário" :iAcao="iAcaoAtual" @fecharModal="() => {bShowModal = false;}" @incluir="adicionarUsuario" @alterar="atualizarUsuario">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Nome"    v-model="oUsuario.sNome"         />
        <Campo :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="true" sTitulo="Nome de Usuário"                v-model="oUsuario.sNomeUsuario" />
        <Campo :disabled="iAcaoAtual == 3" sTipo="select" :bObrigatorio="true" sTitulo="Nível de acesso" v-model="oUsuario.iNivel" :aOpcoes="[{iValor: 1, sDescricao: 'Admin'}, {iValor: 2, sDescricao: 'Caixa'}]"  />
        <Campo :disabled="iAcaoAtual == 3" sTipo="select" :bObrigatorio="true" sTitulo="Situação"           v-model="oUsuario.iAtivo"  :aOpcoes="[{iValor: 1, sDescricao: 'Sim'}, {iValor: 0, sDescricao: 'Não'}]"/>
      </div>            
    </ModalCadastro>
    <Modal v-if="iUsuarioExclusao">
      <h1>Confirma a exclusão do registro?</h1>
      <div class="flex items-center gap-4">
        <button @click="excluirCliente(iUsuarioExclusao)" class="p-2 bg-yellow-400 rounded-sm">Sim</button>
        <button @click="() => iUsuarioExclusao = null" class="p-2 bg-red-400 rounded-sm">Não</button>
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
import { useUsuarioStore } from '../stores/usuarioStore';
import { format } from 'date-fns';

const oUsuario = ref({
  sNome: '',
  sNomeUsuario: '',
  iNivel: '',
  iAtivo: '',  
})

const oUsuarioStore = useUsuarioStore();
const iUsuarioExclusao = ref(null);
const iAcaoAtual = ref(0);
const aUsuarios = ref([]);
const bShowModal = ref(false);

onMounted(async () => {  
  aUsuarios.value = await oUsuarioStore .getUsuarios();   
});

function adicionarUsuario() {

}

function atualizarUsuario() {
    
}

function showModalCadastro(iAcao, oUsuarioSelecionado) {
    bShowModal.value = true;
    iAcaoAtual.value = iAcao;

    if(iAcao != 1) {        
        oUsuario.value = {            
            sNome:        oUsuarioSelecionado.usunome,
            sNomeUsuario: oUsuarioSelecionado.usunome_usuario,
            iNivel:       oUsuarioSelecionado.usunivel,
            iAtivo:       Number(oUsuarioSelecionado.usuativo)
        };            
    }  
}

</script>