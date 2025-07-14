import api from "@/api";
import { defineStore } from "pinia";

export const useRelatorioStore = defineStore('Relatorio', {   
   actions: {
      async emitirRelatorioVendas(oFiltros) {
         try {
            const {data} = await api.post('/api/relatorio/vendas', oFiltros, {
               responseType: 'blob'
            })
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);
            
            window.open(url, '_blank');
         } catch (error) {
            console.error('Erro ao baixar o PDF: ', error);
            throw error;
         }
      },   
      
      async emitirRelatorioProdutos(oFiltros) {
         try {
            const {data} = await api.post('/api/relatorio/produtos', oFiltros, {
               responseType: 'blob'
            })
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);

            window.open(url, '_blank');
         } catch (error) {
            console.error('Erro ao baixar o PDF: ', error);
            throw error;
         }
      },  

      async emitirRelatorioVendasPorProduto(oFiltros) {         
         try {
            const {data} = await api.post('/api/relatorio/vendasProdutos', oFiltros, {
               responseType: 'blob'
            })
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);

            window.open(url, '_blank');
         }
         catch(error) {
            console.error('Erro ao baixar o PDF: ', error);
            throw error;
         }
      },

      async emitirRelatorioVendasPorCliente(oFiltros) {
         try {
            const {data} = await api.post('/api/relatorio/vendasClientes', oFiltros, {
               responseType: 'blob'
            })
            const blob = new Blob([data], { type: 'application/pdf' });
            const url = window.URL.createObjectURL(blob);

            window.open(url, '_blank');
         }
         catch(error) {
            console.error('Erro ao baixar o PDF: ', error);
            throw error;
         }
      }

   }
})