<template>
   <table class="shadow-md mx-auto rounded-sm" id="tabela" style="width: 86%;">
      <thead class="bg-black rounded-t-sm sticky top-0">
         <tr>
            <th v-for="(sCabecalho, iIndice) of aCabecalhos" class="p-2 text-left text-white" :key="iIndice">{{
               sCabecalho }}</th>
         </tr>
      </thead>
      <tbody v-bind="$attrs">
         <slot></slot>
      </tbody>
   </table>
</template>
<script setup>
import { nextTick, onMounted } from "vue";
import 'datatables.net-dt';

const oPropriedades = defineProps(['aCabecalhos', 'sLayout']);

onMounted(() => {
   nextTick(() => {
      $('#tabela').DataTable({
         pageLength: 10,
         responsive: false,
         order: [[0, 'asc']],
         columnDefs: [{
            orderable: false, targets: -1
         }],
         language: {
            url: "/js/datatable/pt-BR.json"
         }
      });
   });
});
</script>
<style lang="scss" scoped></style>