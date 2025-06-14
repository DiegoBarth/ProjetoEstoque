<template>
   <Bar :data="oDadosChart" :options="oChartOptions" />
</template>

<script setup>
   import { Bar } from 'vue-chartjs';
   import {
      Chart as ChartJS,
      Title,
      Tooltip,
      Legend,
      BarElement,
      CategoryScale,
      LinearScale
   } from 'chart.js';

   ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

   const oProps      = defineProps({ dados: Object })
   const oDadosChart = {
      labels: [''],
      datasets: [
         {
            label: 'Meta',
            backgroundColor: '#42A5F5',
            borderRadius: 7,
            data: [oProps.dados.fValorMeta]
         },
         {
            label: 'Valor Atual',
            backgroundColor: oProps.dados.fValorTotal < oProps.dados.fValorMeta ? '#EF5350' : '#66BB6A',
            borderRadius: 7,
            data: [oProps.dados.fValorTotal]
         }
      ]
   }

   const oChartOptions = {
      responsive: true,
      plugins: {
         legend:  { position: 'top' },
         title:   { display: true, text: [
            `${oProps.dados.sDescricao} (Valor total por usuário) - De ${oProps.dados.sDataInicial} à ${oProps.dados.sDataFinal}`,
            '',
            `${oProps.dados.sUsuario}`
         ]},
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
   }
</script>