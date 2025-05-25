<template>
   <div class="w-full px-30 ">
      <Grid v-if="aClientes" class="mt-10 text-left"
         :aCabecalhos="['Cliente', 'Nome do Cliente', 'CPF', 'Data de Nascimento', 'Telefone', 'Endereço', 'Ações']"
         sLayout="0.5fr 1fr 0.6fr 1fr 0.6fr 1.8fr 0.6fr">
         <tr v-for="(oCliente, iIndice) of aClientes" :key="iIndice">
            <td class="p-2">{{ oCliente.clicodigo }}</td>
            <td class="p-2">{{ oCliente.clinome }}</td>
            <td class="p-2">{{ formatarCPFCNPJ(oCliente.clicpf) }}</td>
            <td class="p-2">{{ formatarData(oCliente.clidata_nascimento) }}</td>
            <td class="p-2">{{ formatarTelefone(oCliente.clitelefone) }}</td>
            <td class="p-2" :style="{ 'font-style': oCliente.cliendereco ? '' : 'italic' }">{{ oCliente.cliendereco ??
               'Sem informação' }}</td>
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oCliente)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oCliente)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oCliente.clicodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import { format, parseISO } from 'date-fns';
import Grid from './UI/Grid.vue';

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aClientes']);

function formatarData(sValor) {
   return format(parseISO(sValor), 'dd/MM/yyyy');
}

function formatarCPFCNPJ(sValor) {
   if(sValor.length > 11) {
      return sValor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
   }

   return sValor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

function formatarTelefone(sValor) {
   if(sValor.length < 11) {
      if(sValor.length == 10) {
         return sValor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
      }

      if(sValor.length == 8) {
         return sValor.replace(/(\d{4})(\d{4})/, '(47) $1-$2');
      }

      return sValor.replace(/(\d{5})(\d{4})/, '(47) $1-$2');
   }

   return sValor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}

</script>