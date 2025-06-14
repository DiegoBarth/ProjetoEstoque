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
      fValorMeta: Number,
      fValorTotal: Number
   });

   const dadosChart = {
      labels: [''],
      datasets: [
         {
            label: 'Meta',
            backgroundColor: '#42A5F5',
            borderRadius: 7,
            data: [props.fValorMeta]
         },
         {
            label: 'Valor Atual',
            backgroundColor: props.fValorTotal < props.fValorMeta ? '#EF5350' : '#66BB6A',
            borderRadius: 7,
            data: [props.fValorTotal]
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
         },
         tooltip: {
            callbacks: {
               label(context) {
                  return context.parsed.y.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
               }
            }
         }
      },
      scales: {
         y: {
            grid: {
               display: false
            },
            ticks: {
               callback(value) {
                  return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
               }
            }
         }
      }
   };
</script>