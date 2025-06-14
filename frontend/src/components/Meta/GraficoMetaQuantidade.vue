<template>
  <Bar :data="dadosChart" :options="chartOptions" />
</template>

<script setup>
   import { Bar } from 'vue-chartjs';
   import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

   ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

   const props = defineProps({
      sTitulo: String,
      sSubtitulo: String,
      sDescricaoExtra: String,
      iQuantidadeMeta: Number,
      iQuantidadeAtual: Number
   });

   const dadosChart = {
      labels: [''],
      datasets: [
         {
            label: 'Meta',
            backgroundColor: '#FFA726',
            borderRadius: 7,
            data: [props.iQuantidadeMeta]
         },
         {
            label: 'Quantidade Atual',
            backgroundColor: props.iQuantidadeAtual < props.iQuantidadeMeta ? '#EF5350' : '#66BB6A',
            borderRadius: 7,
            data: [props.iQuantidadeAtual]
         }
      ]
   };

   const chartOptions = {
      responsive: true,
      plugins: {
         legend: { position: 'top' },
         title: {
            display: true,
            text: [props.sTitulo, props.sSubtitulo, props.sDescricaoExtra].filter(Boolean)
         }
      },
      scales: {
         y: {
            grid: {
            display: false
            }
         }
      }
   };
</script>