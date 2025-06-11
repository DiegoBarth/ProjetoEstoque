<template>
   <div class="div-principal-grid w-full px-30">
      <Grid v-if="aFornecedores"
         :aCabecalhos="['Fornecedor', 'Nome Fantasia', 'CPF/CNPJ', 'Razão Social', 'Inscrição Estadual', 'Telefone', 'Ações']"
         sLayout="0.5fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 0.6fr">
         <tr v-for="(oFornecedor, iIndice) of aFornecedores" :key="iIndice">
            <td class="p-2">{{ oFornecedor.forcodigo }}</td>
            <td class="p-2">{{ oFornecedor.fornome_fantasia }}</td>
            <td class="p-2">{{ formatarCPFCNPJ(oFornecedor.forcpfcnpj) }}</td>
            <td class="p-2">{{ oFornecedor.forrazao_social }}</td>
            <td class="p-2">{{ oFornecedor.forinscricao_estadual }}</td>
            <td class="p-2">{{ formatarTelefone(oFornecedor.fortelefone) }}</td>            
            <td class="p-2 flex gap-2">
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 3, oFornecedor)"><i
                     class="fa fa-search p-2 bg-blue-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalCadastro', 2, oFornecedor)"><i
                     class="fa fa-pencil p-2 bg-yellow-500 rounded-sm text-white"></i></span>
               <span class="cursor-pointer" @click="$emit('showModalExclusao', oFornecedor.forcodigo)"><i
                     class="fa fa-trash p-2 bg-red-500 rounded-sm text-white"></i></span>
            </td>
         </tr>
      </Grid>
   </div>
</template>
<script setup>
import Grid from './UI/Grid.vue';
import { formatarCPFCNPJ, formatarTelefone } from '../utils/main'

defineEmits(['showModalCadastro', 'showModalExclusao']);
defineProps(['aFornecedores']);
</script>