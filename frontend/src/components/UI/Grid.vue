<template>
   <table class="divide-y divide-gray-200 shadow-md mx-auto rounded-sm" id="tabela" style="width: 86%;">
      <thead class="bg-black rounded-t-sm">
         <tr>
            <th v-for="(sCabecalho, iIndice) of aCabecalhos" class="p-2 text-left text-white" :key="iIndice">{{
               sCabecalho }}</th>
         </tr>
      </thead>
      <tbody>
         <slot></slot>
      </tbody>
   </table>
</template>
<script setup>
import { nextTick, onMounted } from "vue";

const oPropriedades = defineProps(['aCabecalhos', 'sLayout']);

onMounted(() => {
   nextTick(() => {
      $('#tabela').DataTable({
         pageLength: 10,
         responsive: false,
         order: [[0, 'asc']],
         language: {
            url: "/js/datatable/pt-BR.json"
         }
      });
   });
});
</script>
<style lang="scss" scoped></style>