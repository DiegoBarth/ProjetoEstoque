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

   const sCorQuantidadeRealizado = '#42A5F5';
   const sCorValorRealizado      = '#FFA726';
   const sCorQuantidadePendente  = '#42A5F55E';
   const sCorValorPendente       = '#FFA7266E';

   const fQuantidadePercentual = percentual(oProps.dados.iQuantidadeTotal, oProps.dados.iQuantidadeMeta);
   const fValorPercentual      = percentual(oProps.dados.fValorTotal,      oProps.dados.fValorMeta);

   const oDadosChart = {
      labels: ['Valor', 'Quantidade'],
      datasets: [
         {
            label: 'Realizado Valor',
            data: [fValorPercentual, 0],
            backgroundColor: sCorValorRealizado,
            borderRadius: 4,
         },
         {
            label: 'Pendente Valor',
            data: [100 - fValorPercentual, 0],
            backgroundColor: sCorValorPendente,
            borderRadius: 4,
         },
         {
            label: 'Realizado Quantidade',
            data: [0, fQuantidadePercentual],
            backgroundColor: sCorQuantidadeRealizado,
            borderRadius: 4,
         },
         {
            label: 'Pendente Quantidade',
            data: [0, 100 - fQuantidadePercentual],
            backgroundColor: sCorQuantidadePendente,
            borderRadius: 4,
         }
      ]
   };

   const oChartOptions = {
      responsive: true,
      plugins: {
         legend: { position: 'top' },
         title:  { display: true, text: [
            `${oProps.dados.sDescricao} (Valor + Quantidade) - De ${oProps.dados.sDataInicial} à ${oProps.dados.sDataFinal}`,
            '',
            `${oProps.dados.sUsuario}`
         ]},
         tooltip: {
            callbacks: {
               label: (context) => {
                  const sLabel = context.dataset.label;

                  if(sLabel.includes('Quantidade')) {
                     if(sLabel.includes('Realizado')) {
                        return `Realizado Quantidade: ${oProps.dados.iQuantidadeTotal} / ${oProps.dados.iQuantidadeMeta}`;
                     }
                     return `Pendente Quantidade: ${oProps.dados.iQuantidadeMeta - oProps.dados.iQuantidadeTotal}`;
                  }

                  if(sLabel.includes('Valor')) {
                     if(sLabel.includes('Realizado')) {
                        return `Realizado Valor: ${oProps.dados.fValorTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })} / ${oProps.dados.fValorMeta.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
                     }

                     return `Pendente Valor: ${(oProps.dados.fValorMeta - oProps.dados.fValorTotal).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
                  }

                  return `${sLabel}: ${context.raw}`;
               }
            }
         }
      },
      scales: {
         x: { stacked: true },
         y: {
            grid: { display: false },
            stacked: true,
            beginAtZero: true,
            max: 100,
            ticks: {
               callback: v => v + '%'
            },
            title: { display: true, text: 'Percentual (%)' }
         }
      }
   };

</script>