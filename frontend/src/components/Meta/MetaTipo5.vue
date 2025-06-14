<template>
  <Bar :data="oDadosChart" :options="oChartOptions" />
</template>

<script setup>
   import { Bar } from 'vue-chartjs';
   import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

   ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

   const oProps      = defineProps({ dados: Object })
   const oDadosChart = {
      labels: [''],
      datasets: [
         {
            label: 'Meta',
            backgroundColor: '#FFA726',
            borderRadius: 7,
            data: [oProps.dados.iQuantidadeMeta]
         },
         {
            label: 'Quantidade Atual',
            backgroundColor: oProps.dados.iQuantidadeTotal < oProps.dados.iQuantidadeMeta ? '#EF5350' : '#66BB6A',
            borderRadius: 7,
            data: [oProps.dados.iQuantidadeTotal]
         }
      ]
   }
   const oChartOptions = {
      responsive: true,
      plugins: {
         legend: { position: 'top' },
         title:  { display: true, text: [
            `${oProps.dados.sDescricao} (Quantidade total por usuário) - De ${oProps.dados.sDataInicial} à ${oProps.dados.sDataFinal}` ,
            '',
            `${oProps.dados.sUsuario}`
         ]}
      },
      scales: {
         y: {
            grid: {
               display: false
            }
         }
      }
   }
</script>