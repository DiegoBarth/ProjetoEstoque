import api from "@/api";
import { defineStore } from "pinia";

export const useVendaStore = defineStore('Venda', {
   state: () => ({

   }),
   actions: {
      async getFormasPagamento() {
         try {
            const { data } = await api.get('/api/forma_pagamento');
            
            return data.aFormasPagamento.map((oFormaPagamento) => {
               return {
                  iValor    : oFormaPagamento.fpcodigo,
                  sDescricao: oFormaPagamento.fpnome
               }
            });
         }
         catch (error) {
            throw (error);
         }
      }
   }
}); 