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

   const oProps = defineProps({ dados: Object });

   function percentual(fRealizado, fMeta) {
      if(!fMeta || fMeta === 0) {
         return 0;
      }

      let fPercentual = (fRealizado / fMeta) * 100;

      return fPercentual > 100 ? 100 : fPercentual;
   }

   const fQuantidadeRealizado = percentual(oProps.dados.iQuantidadeTotal, oProps.dados.iQuantidadeMeta);
   const fValorRealizado      = percentual(oProps.dados.fValorTotal,      oProps.dados.fValorMeta);

   const sCorQuantidadeRealizado = '#42A5F5';
   const sCorValorRealizado      = '#FFA726';

   const sCorQuantidadePendente = '#42A5F55E';
   const sCorValorPendente      = '#FFA7266E';

   const oDadosChart = {
      labels: ['Quantidade', 'Valor'],
      datasets: [
         {
            label: 'Realizado',
            data: [fQuantidadeRealizado, fValorRealizado],
            backgroundColor: [sCorQuantidadeRealizado, sCorValorRealizado],
            stack: 'Stack 0',
            borderRadius: 4
         },
         {
            label: 'Pendente',
            data: [100 - fQuantidadeRealizado, 100 - fValorRealizado],
            backgroundColor: [sCorQuantidadePendente, sCorValorPendente],
            stack: 'Stack 0',
            borderRadius: 4
         }
      ]
   };

   const oChartOptions = {
      responsive: true,
      plugins: {
         legend: { position: 'top' },
         title:  { display: true, text: `${oProps.dados.sDescricao} (Valor + Quantidade)`},
         tooltip: {
            callbacks: {
               label: (context) => {
                  const sLabel = context.dataset.label;
                  const iIndex = context.dataIndex;
                  if (sLabel === 'Realizado') {
                     if (iIndex === 0) {
                     return `${sLabel} Quantidade: ${oProps.dados.iQuantidadeTotal} / ${oProps.dados.iQuantidadeMeta}`;
                     } else {
                     return `${sLabel} Valor: ${oProps.dados.fValorTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })} / ${oProps.dados.fValorMeta.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
                     }
                  }
                  if (sLabel === 'Pendente') {
                     return `Pendente: ${(context.raw).toFixed(2)}%`;
                  }
                  return `${sLabel}: ${context.raw}`;
               }
            }
         }
      },
      scales: {
         x: { stacked: true },
         y: {
            stacked: true,
            max: 100,
            beginAtZero: true,
            ticks: {
               callback: fValor => `${fValor}%`
            },
            title: { display: true, text: 'Percentual (%)' }
         }
      }
   };
</script>