<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aMetas"
         :aCabecalhos="['Meta', 'Descrição', 'Tipo', 'Valor', 'Quantidade', 'Data inicial', 'Data final', 'Usuário', 'Produto', 'Ações']"
         sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr">
         <tr v-for="(oMeta, iIndice) of aMetas" :key="iIndice">
            <td class="p-2">{{ oMeta.mecodigo }}</td>
            <td class="p-2">{{ oMeta.medescricao }}</td>
            <td class="p-2">{{ oMeta.metipo_descricao }}</td>
            <td class="p-2">{{ oMeta.mevalor_meta ? converterParaMoeda(oMeta.mevalor_meta) : null }}</td>
            <td class="p-2">{{ oMeta.mequantidade_meta }}</td>
            <td class="p-2">{{ formatarData(oMeta.medata_inicio) }}</td>
            <td class="p-2">{{ formatarData(oMeta.medata_fim) }}</td>
            <td class="p-2">{{ oMeta.usuario_descricao }}</td>
            <td class="p-2">{{ oMeta.produto_descricao }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oMeta)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white" title="Visualizar"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oMeta)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white" title="Alterar"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oMeta.mecodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white" title="Excluir"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';
import { converterParaMoeda, formatarData } from '../utils/main'

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aMetas']);
</script>