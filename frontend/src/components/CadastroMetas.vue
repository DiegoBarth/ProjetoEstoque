<template>
   <ModalCadastro :bModalAberto="true" class="flex items-center justify-content-center" sTitulo="🎯 Cadastro de meta" :iAcao="iAcaoAtual" @fecharModal="$emit('fecharModal')" @incluir="$emit('adicionarMeta', oMeta)" @alterar="$emit('atualizarMeta', oMeta, oMeta.iMeta)">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">            
         <Campo :disabled="iAcaoAtual == 3" sTipo="text"   :bObrigatorio="true"  sTitulo="Descrição"       v-model="oMeta.sDescricao" />
         <Campo v-if="iAcaoAtual != 3"      sTipo="select" :bObrigatorio="true"  sTitulo="Tipo"            v-model="oMeta.iTipo" :aOpcoes="aOpcoes" />        
         <Campo v-else disabled             sTipo="text"   :bObrigatorio="true"  sTitulo="Tipo"            v-model="oMeta.sTipo" />
         <Campo :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoValorMetaHabilitado)"      sTipo="text" :bObrigatorio="bCampoValorMetaObrigatorio"     sTitulo="Valor Meta"      v-model="oMeta.fValorMeta" @input="() => oMeta.fValorMeta = converterParaMoeda(oMeta.fValorMeta)" />
         <Campo :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoQuantidadeMetaHabilitado)" sTipo="text" :bObrigatorio="bCampoQuantidadeMetaHabilitado" sTitulo="Quantidade Meta" v-model="oMeta.iQuantidadeMeta" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="date"   :bObrigatorio="true"  sTitulo="Data inicial"    v-model="oMeta.sDataInicial" />
         <Campo :disabled="iAcaoAtual == 3" sTipo="date"   :bObrigatorio="true"  sTitulo="Data final"      v-model="oMeta.sDataFinal" />                       
         <div class="flex gap-4 items-end">
            <Campo v-model="oMeta.iUsuario" sTipo="text" :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoUsuarioHabilitado)" :bObrigatorio="bCampoUsuarioObrigatorio" sTitulo="Usuário" @change="onChangeCodigoUsuario"/>
            <div class="relative">
               <Campo :class="'placeholder:text-sm'" sTipo="text" :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoUsuarioHabilitado)" :bObrigatorio="false" sTitulo="Usuário" :bLabel="false" v-model="oMeta.sUsuario" sPlaceholder="Digite o nome do usuário"  @input="filtrarSugestoes(oMeta.sUsuario, 1)" @focus="() => {bMostrarSugestoes = true, bFocado = true}" @blur="() => {ocultarSugestoes(); bFocado = false;}" class="w-full"/>
               <ul v-if="bMostrarSugestoes && aSugestoesFiltradas.length && iCampoSelecionado === 1" class="absolute bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-y-auto z-50 w-full">
                  <li v-for="(sSugestao, iIndice) in aSugestoesFiltradas" :key="iIndice" @mousedown.prevent="selecionarSugestao(sSugestao, 1)" class="px-4 py-2 hover:bg-blue-100 cursor-pointer truncate">
                     {{ sSugestao }}
                  </li>
               </ul>
            </div>
         </div>
         <div class="flex gap-4 items-end">
            <Campo v-model="oMeta.iProduto" sTipo="text" :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoProdutoHabilitado)" :bObrigatorio="bCampoProdutoObrigatorio" sTitulo="Produto" @change="onChangeCodigoProduto"/>
            <div class="relative">
               <Campo :class="'placeholder:text-sm'" sTipo="text" :disabled="iAcaoAtual == 3 || (iAcaoAtual != 3 && !bCampoProdutoHabilitado)" :bObrigatorio="false" sTitulo="Usuário" :bLabel="false" v-model="oMeta.sProduto" sPlaceholder="Digite o nome do produto"  @input="filtrarSugestoes(oMeta.sProduto, 2)" @focus="() => {bMostrarSugestoes = true, bFocado = true}" @blur="() => {ocultarSugestoes(); bFocado = false;}" class="w-full"/>
               <ul v-if="bMostrarSugestoes && aSugestoesFiltradas.length && iCampoSelecionado === 2" class="absolute bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-y-auto z-50 w-full">
                  <li v-for="(sSugestao, iIndice) in aSugestoesFiltradas" :key="iIndice" @mousedown.prevent="selecionarSugestao(sSugestao, 2)" class="px-4 py-2 hover:bg-blue-100 cursor-pointer truncate">
                     {{ sSugestao }}
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </ModalCadastro>
</template>
<script setup>
import ModalCadastro from '../components/UI/ModalCadastro.vue';
import Campo from '../components/UI/Campo.vue';
import { converterParaMoeda, limparCampos } from '../utils/main'
import { useUsuarioStore } from '../stores/usuarioStore';
import { useProdutoStore } from '../stores/produtoStore';
import { ref, onMounted, computed } from 'vue';
const oUsuarioStore       = useUsuarioStore();
const oProdutoStore       = useProdutoStore();


const oProps = defineProps(['iAcaoAtual', 'oMeta', 'aOpcoes']);
defineEmits(['adicionarMeta', 'atualizarMeta', 'fecharModal']);

const iTipoMeta = computed(() => Number(oProps.oMeta.iTipo));

const bCampoValorMetaObrigatorio      = computed(() => [1, 4, 7, 3, 6, 9].includes(iTipoMeta.value));
const bCampoQuantidadeMetaObrigatorio = computed(() => [2, 5, 8, 3, 6, 9].includes(iTipoMeta.value));
const bCampoUsuarioObrigatorio        = computed(() => [4, 5, 6].includes(iTipoMeta.value));
const bCampoProdutoObrigatorio        = computed(() => [7, 8, 9].includes(iTipoMeta.value));


const bCampoValorMetaHabilitado      = computed(() => oProps.iAcaoAtual.value !== 3 && bCampoValorMetaObrigatorio.value);
const bCampoQuantidadeMetaHabilitado = computed(() => oProps.iAcaoAtual.value !== 3 && bCampoQuantidadeMetaObrigatorio.value);
const bCampoUsuarioHabilitado        = computed(() => oProps.iAcaoAtual.value !== 3 && bCampoUsuarioObrigatorio.value);
const bCampoProdutoHabilitado        = computed(() => oProps.iAcaoAtual.value !== 3 && bCampoProdutoObrigatorio.value);


const bFocado             = ref(false);
const bMostrarSugestoes   = ref(false);
const aSugestoesFiltradas = ref([]);
let debounceTimeout       = null;
let oRegistroSelecionado  = null;
let aRegistrosFiltrados   = [];
let iCampoSelecionado     = ref(null);

onMounted(() => {
   if(oProps.iAcaoAtual == 1) {
      limparCampos();
   }
});

async function onChangeCodigoUsuario() {
   if(!oProps.oMeta.iUsuario) {
      return resetarUsuario();
   }

   try {
      let oUsuario = await oUsuarioStore.getUsuarioByCodigo(oProps.oMeta.iUsuario);

      oProps.oMeta.iUsuario = oUsuario.iUsuario;
      oProps.oMeta.sUsuario = oUsuario.sNome;
   }
   catch(e) {
      resetarUsuario();
   }
}

async function onChangeCodigoProduto() {
   if(!oProps.oMeta.iProduto) {
      return resetarProduto();
   }

   try {
      let oProduto = await oProdutoStore.getProdutoByCodigo(oProps.oMeta.iProduto);

      oProps.oMeta.iProduto = oProduto.iProduto;
      oProps.oMeta.sProduto = oProduto.sNome;
   }
   catch(e) {
      resetarProduto();
   }
}

function filtrarSugestoes(sValor, iCampo) {
   if(debounceTimeout) {
      clearTimeout(debounceTimeout);
   }

   debounceTimeout = setTimeout(async () => {
      if(!bFocado.value) {
         return;
      }

      const search = sValor.trim();

      if(!search) {
         aSugestoesFiltradas.value = [];

         return;
      }

      try {
         let aValores = [];
         
         if(iCampo === 1) {
            aValores = await oUsuarioStore.getUsuarioByNome(search);
            
            aSugestoesFiltradas.value = aValores.map(oUsuario => {
               return oUsuario.sNome;
            });

            iCampoSelecionado.value = 1;
         }
         else {
            aValores = await oProdutoStore.getProdutoByNome(search);
            
            aSugestoesFiltradas.value = aValores.map(oProduto => {
               return oProduto.sNome;
            });

            iCampoSelecionado.value = 2;
         }

         aRegistrosFiltrados = aValores;
      }
      catch(error) {
         aSugestoesFiltradas.value = [];
      }
   }, 1500);
}

function selecionarSugestao(sSugestao, iCampo) {
   if(iCampo === 1) {
      oProps.oMeta.sUsuario = sSugestao;
   }
   else {
      oProps.oMeta.sProduto = sSugestao;
   }

   bMostrarSugestoes.value   = false;
   aSugestoesFiltradas.value = [];

   const aResultados = aRegistrosFiltrados.filter(oRegistro =>
      oRegistro.sNome.toLowerCase() === sSugestao.toLowerCase()
   );

   if(aResultados.length > 0) {
      const oResultadoRegistro = aResultados[0];
      oRegistroSelecionado     = oResultadoRegistro;

      if(iCampo === 1) {
         oProps.oMeta.iUsuario = oRegistroSelecionado.iUsuario;
         oProps.oMeta.sUsuario = oRegistroSelecionado.sNome;
      }
      else {
         oProps.oMeta.iProduto = oRegistroSelecionado.iProduto;
         oProps.oMeta.sProduto = oRegistroSelecionado.sNome;
      }
      
   }
   else {
      resetarProduto();
   }
};

function ocultarSugestoes() {
   setTimeout(() => {
      bMostrarSugestoes.value = false;
   }, 100)
}

function resetarUsuario() {
   oProps.oMeta.iUsuario = null;
   oProps.oMeta.sUsuario = null;
}

function resetarProduto() {
   oProps.oMeta.iProduto = null;
   oProps.oMeta.sProduto = null;
}

</script>