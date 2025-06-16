<template>
   <ModalCadastro
      :bModalAberto="true"
      class="flex items-center justify-center"
      sTitulo="📎 Gerenciar Anexos"
      :iAcao="iAcaoAtual"
      @fecharModal="$emit('fecharModal')"
      @incluir="incluirAnexo"
   >
   <div class="mb-4">
      <label
         for="inputArquivo"
         class="inline-block cursor-pointer rounded border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
         :class="{ 'opacity-50 cursor-not-allowed': iAcaoAtual == 3 }">
         Escolher arquivo
      </label>
      <input
         id="inputArquivo"
         type="file"
         :disabled="iAcaoAtual == 3"
         @change="onChangeFile"
         accept=".pdf,.jpg,.jpeg,.png,.bmp"
         class="sr-only"
      />
      <span class="ml-2 text-sm text-gray-600">
         {{ oAnexo.anenome_arquivo || 'Nenhum arquivo selecionado' }}
      </span>
   </div>
   <div class="grid grid-cols-1 md:grid-cols-2 gap-4">     
      <Campo
         :bObrigatorio="true"
         sTipo="text"
         maxlength="255"
         sTitulo="Nome do arquivo"
         v-model="oAnexo.anenome_arquivo"
         :disabled="true"
      />
      <Campo
         :bObrigatorio="true"
         sTipo="text"
         sTitulo="Tipo"
         v-model="oAnexo.anetipo"
         :disabled="true"
      />
      <Campo class="col-span-2" :disabled="iAcaoAtual == 3" sTipo="text" :bObrigatorio="false" sTitulo="Observações"    v-model="oAnexo.aneobservacao"         />
   </div>
   <div class="mt-6">
      <p class="font-semibold mb-2">Anexos já incluídos</p>
         <ul class="max-h-96 overflow-y-auto border rounded p-2 bg-gray-50">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
               <div
                  v-for="(anexo, idx) in anexos"
                  :key="anexo.anecodigo || idx"
                  class="flex flex-col items-center p-2 border rounded bg-white shadow-sm h-[200px]"
               >
                  <div class="w-[85%] h-30 mb-2 flex items-center justify-center border rounded overflow-hidden bg-gray-50 relative cursor-pointer"
                     @click.stop="visualizarAnexo(anexo)"
                     @mousedown.middle="visualizarAnexo(anexo)"
                     :title="anexo.aneobservacao || anexo.anenome_arquivo"
                  >
                     <img
                        v-if="isImagem(anexo.anetipo)"
                        :src="getPreview(anexo)"
                        class="object-cover w-full h-full"
                        alt="Pré-visualização"
                     />
                     <div
                        v-else-if="anexo.anetipo === 'application/pdf'"
                        class="w-full h-full overflow-hidden"
                     >
                        <iframe 
                           :src="getPreview(anexo)"
                           class="w-full h-full"
                           style="border:none; overflow:hidden;"
                           title="Pré-visualização PDF"
                        ></iframe>
                     </div>
                     <i
                        v-else-if="anexo.anetipo === 'text/plain'"
                        class="fa fa-file-text-o text-gray-500 text-3xl"
                        title="Texto"
                     ></i>
                     <i
                        v-else
                        class="fa fa-file text-gray-400 text-3xl"
                        title="Arquivo"
                     ></i>
                  </div>
                  <div class="flex-1 flex flex-col justify-between w-full">
                     <span
                        class="text-center text-sm hover:underline cursor-pointer break-all"
                        @click="visualizarAnexo(anexo)"
                        :title="anexo.aneobservacao || anexo.anenome_arquivo"
                     >
                        {{ anexo.anenome_arquivo }}
                     </span>
                     <button
                        type="button"
                        class="mt-2 text-red-600 hover:text-red-800 text-sm cursor-pointer"
                        @click="removerAnexo(anexo.anecodigo)"
                        :disabled="iAcaoAtual == 3"
                        title="Remover anexo"
                     >
                        <i class="fa fa-trash mr-1"></i> Remover
                     </button>
                  </div>
               </div>
               <div v-if="anexos.length === 0" class="text-gray-400 italic text-center col-span-full">
                  Nenhum anexo incluído
               </div>
            </div>
            <li v-if="anexos.length === 0" class="text-gray-400 italic text-center">
               Nenhum anexo incluído
            </li>
         </ul>
      </div>
   </ModalCadastro>
</template>

<script setup>
   import { reactive, ref, watch } from 'vue';
   import { alerta, formatarDataHora } from '@/utils/main';
   import ModalCadastro from '../components/UI/ModalCadastro.vue';
   import Campo from '../components/UI/Campo.vue';

   const oProps = defineProps({
      iAcaoAtual: Number,
      oAnexo: Object,
      clicodigo: Number,
      aAnexosExistentes: { type: Array, default: () => [] }
   });

   const emit = defineEmits(['adicionarAnexo', 'fecharModal', 'showModalExclusao']);

   const oAnexo = reactive({
      anenome_arquivo: '',
      anetipo: '',
      anearquivo: null,
      anedata_hora: null,
      clicodigo: oProps.clicodigo || null,
      anecodigo: null,
      aneobservacao: ''
   });

   const anexos = ref([...oProps.aAnexosExistentes]);

   const aTiposPermitidos = [
      'application/pdf',
      'image/jpeg',
      'image/jpg',
      'image/png',
      'image/bmp'
   ];

   watch(() => oProps.clicodigo, (iNovoCodigo) => {
      oAnexo.clicodigo = iNovoCodigo;
   });

   watch(() => oProps.aAnexosExistentes, (novosAnexos) => {
      anexos.value = [...novosAnexos];
   });

   function isImagem(tipo) {
      return ['image/jpeg', 'image/jpg', 'image/png', 'image/bmp'].includes(tipo);
   }

   function getPreview(anexo) {
      return `${import.meta.env.VITE_BACKEND_URL}/api/cliente/anexo/visualizar/${anexo.anecodigo}`;
   }

   function onChangeFile(event) {
      const oArquivo = event.target.files[0];

      if(!oArquivo) {
         return;
      }

      oAnexo.anenome_arquivo = oArquivo.name;
      oAnexo.anetipo         = oArquivo.type;
      oAnexo.anearquivo      = oArquivo;
      oAnexo.anedata_hora    = formatarDataHora(oAnexo.anedata_hora);
   }

   function incluirAnexo() {
      if(!oAnexo.anearquivo) {
         alerta('Selecione um arquivo antes de incluir.', 'error');
         return;
      }

      if(!aTiposPermitidos.includes(oAnexo.anetipo)) {
         alerta(`Tipo de arquivo não permitido: ${oAnexo.anetipo}`, 'error');
         return;
      }

      emit('adicionarAnexo', { ...oAnexo });

      limparCampos();
   }

   function limparCampos() {
      oAnexo.anenome_arquivo = '';
      oAnexo.anetipo         = '';
      oAnexo.anearquivo      = null;
      oAnexo.anedata_hora    = null;
      oAnexo.aneobservacao   = null;
   }

   function visualizarAnexo(anexo) {
      const sBaseUrl = import.meta.env.VITE_BACKEND_URL;
      const sUrl     = `${sBaseUrl}/api/cliente/anexo/visualizar/${anexo.anecodigo}`;

      window.open(sUrl, '_blank');
   }

   function removerAnexo(iAnexo) {
      emit('showModalExclusao', 'anexo', iAnexo);
   }

</script>