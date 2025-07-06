import api from "@/api";
import { defineStore } from "pinia";

export const useAtendimentoStore = defineStore('Atendimento', {
   state: () => ({
      oDadosVenda: null
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
      },
      async getVendas() {
         try {
            const { data } = await api.get('/api/venda');
            
            return data.aVendas;
         }
         catch (error) {
            throw (error);
         }
      },
      async cadastrarVenda(payload) {
         try {
            const { data } = await api.post(`/api/venda`, {
               ...payload
            });

            return data;
         }
         catch(error) {
            throw(error);
         }
      },
      async finalizarVenda(iVenda) {
         try {
            const { data } = await api.post(`/api/venda/finalizar/${iVenda}`);

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async cancelarVenda(iVenda) {
         try {
            const { data } = await api.post(`/api/venda/cancelar/${iVenda}`);

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async realizarDevolucao(iVenda, aProdutos) {
         try {
            const { data } = await api.post(`/api/venda/${iVenda}/devolucao`, {
               produtos: aProdutos
            });

            return !!data;
         }
         catch(error) {
            throw error;
         }
      },
      setDadosVenda(oDados) {
         this.oDadosVenda = oDados;
      },
      getDadosVenda() {
         return this.oDadosVenda;
      },
      async getItensVenda(iVenda) {
         try {
            const { data } = await api.post(`/api/venda/itens/${iVenda}`);

            return data.aItensVenda;
         }
         catch(error) {
            throw(error)
         }
      }

   }
}); 