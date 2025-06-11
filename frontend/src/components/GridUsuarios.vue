<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aUsuarios"
         :aCabecalhos="['Usuário', 'Nome do Usuário', 'Nível', 'Situação', 'Ações']">
         <tr v-for="(oUsuario, iIndice) of aUsuarios" :key="iIndice">
            <td class="p-2">{{ oUsuario.usucodigo }}</td>
            <td class="p-2">{{ oUsuario.usunome }}</td>
            <td class="p-2">{{ oUsuario.sNivel }}</td>
            <td class="p-2">{{ oUsuario.sSituacao }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oUsuario)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oUsuario)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oUsuario.usucodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';
import { converterParaMoeda } from '../utils/main';

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aUsuarios']);
</script>