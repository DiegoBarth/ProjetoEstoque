import api from "@/api";
import { defineStore } from "pinia";

export const useFornecedorStore = defineStore('Fornecedor', {
   state: () => ({
      aFornecedores: []
   }),
   actions: {
      async getFornecedores() {
         try {
            const { data } = await api.get('/api/fornecedor')
            return data.aFornecedores;
         }
         catch(error) {
            throw(error);
         }
      },
      async getFornecedorByCodigo(iFornecedor) {
         try {
            const { data } = await api.get(`/api/fornecedor/${iFornecedor}`);

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async cadastrarFornecedor(oDados) {
         try {
            const { data } = await api.post('/api/fornecedor', oDados)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async atualizarFornecedor(iFornecedor, oDados) {
         try {
            const { data } = await api.put(`/api/fornecedor/${iFornecedor}`, oDados)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async excluirFornecedor(iFornecedor) {
         try {
            const { data } = await api.delete(`/api/fornecedor/${iFornecedor}`)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      }
   }
})