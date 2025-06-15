<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aClientes"
         :aCabecalhos="['Cliente', 'Nome do Cliente', 'CPF', 'Data de Nascimento', 'Telefone', 'Endereço', 'Ações']">
         <tr v-for="(oCliente, iIndice) of aClientes" :key="iIndice">
            <td class="p-2">{{ oCliente.clicodigo }}</td>
            <td class="p-2">{{ oCliente.clinome }}</td>
            <td class="p-2">{{ formatarCPF(oCliente.clicpf) }}</td>
            <td class="p-2">{{ formatarData(oCliente.clidata_nascimento) }}</td>
            <td class="p-2">{{ formatarTelefone(oCliente.clitelefone)        }}</td>
            <td class="p-2" :style="{'font-style': oCliente.cliendereco ?? 'italic'}">{{ oCliente.cliendereco ?? 'Sem informação' }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oCliente)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white" title="Visualizar"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oCliente)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white" title="Alterar"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oCliente.clicodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white" title="Excluir"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';
import { formatarCPF, formatarTelefone, formatarData } from '../utils/main';

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aClientes']);

</script>