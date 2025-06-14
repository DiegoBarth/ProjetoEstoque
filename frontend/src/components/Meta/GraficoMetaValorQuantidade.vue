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
      iQuantidadeAtual: Number,
      fValorMeta: Number,
      fValorAtual: Number
   });

   function percentual(fRealizado, fMeta) {
      if(!fMeta || fMeta === 0) {
         return 0;
      }

      const fPercentual = (fRealizado / fMeta) * 100;

      return fPercentual > 100 ? 100 : fPercentual;
   }

   const sCorQuantidadeRealizado = '#42A5F5';
   const sCorValorRealizado      = '#FFA726';
   const sCorQuantidadePendente  = '#42A5F55E';
   const sCorValorPendente       = '#FFA7266E';

   const fPercentualQuantidade = percentual(props.iQuantidadeAtual, props.iQuantidadeMeta);
   const fPercentualValor      = percentual(props.fValorAtual,      props.fValorMeta);

   const dadosChart = {
      labels: ['Valor', 'Quantidade'],
      datasets: [
         {
            label: 'Realizado Valor',
            data: [fPercentualValor, 0],
            backgroundColor: sCorValorRealizado,
            borderRadius: 4
         },
         {
            label: 'Pendente Valor',
            data: [100 - fPercentualValor, 0],
            backgroundColor: sCorValorPendente,
            borderRadius: 4
         },
         {
            label: 'Realizado Quantidade',
            data: [0, fPercentualQuantidade],
            backgroundColor: sCorQuantidadeRealizado,
            borderRadius: 4
         },
         {
            label: 'Pendente Quantidade',
            data: [0, 100 - fPercentualQuantidade],
            backgroundColor: sCorQuantidadePendente,
            borderRadius: 4
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
            label: (context) => {
               const sLabel = context.dataset.label;

               if(sLabel.includes('Quantidade')) {
                  if (sLabel.includes('Realizado')) {
                  return `Realizado Quantidade: ${props.iQuantidadeAtual} / ${props.iQuantidadeMeta}`;
                  }
                  return `Pendente Quantidade: ${props.iQuantidadeMeta - props.iQuantidadeAtual}`;
               }

               if(sLabel.includes('Valor')) {
                  if(sLabel.includes('Realizado')) {
                     return `Realizado Valor: ${props.fValorAtual.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })} / ${props.fValorMeta.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
                  }

                  return `Pendente Valor: ${(props.fValorMeta - props.fValorAtual).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}`;
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
               callback: v => `${v}%`
            },
            title: {
               display: true,
               text: 'Percentual (%)'
            }
         }
      }
   };
</script>