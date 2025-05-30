import api from "@/api";
import { defineStore } from "pinia";

export const useClienteStore = defineStore('Cliente', {
   state: () => ({

   }),
   actions: {
      _formatarCliente(oCliente) {
         return {
            iCliente       : oCliente.clicodigo,
            sNome          : oCliente.clinome,
            sCpf           : utils.formatarCPF(oCliente.clicpf),
            sDataNascimento: utils.formatarData(oCliente.clidata_nascimento),
            sEndereco      : oCliente.cliendereco,
            sTelefone      : utils.formatarTelefone(oCliente.clitelefone)
         };
      },
      async getClientes() {
         try {
            const { data } = await api.get('/api/cliente');
            
            return data.aClientes;
         }
         catch (error) {
            throw (error);
         }
      },
      async getClienteByCPF(sValorCpf) {
         try {
            const { data } = await api.get(`/api/cliente/busca/cpf?cpf=${encodeURIComponent(sValorCpf)}`);

            return this._formatarCliente(data.oCliente);
         }
         catch (error) {
            throw (error);
         }
      },
      async cadastrarCliente(oDados) {
         try {
            const { data } = await api.post(`/api/cliente`, {
               ...oDados
            });

            return !!data;
         }
         catch(error) {
            throw(error)
         }
      },
      async atualizarCliente(iCliente, oDados) {
         try {
            const { data } = await api.put(`/api/cliente/${iCliente}`, {
               ...oDados
            });

            return !!data;
         }
         catch(error) {
            throw(error)
         }
      },
      async excluirCliente(iCliente) {
         try {
            const { data } = await api.delete(`/api/cliente/${iCliente}`);

            return !!data;
         }
         catch(error) {
            throw(error)
         }
      }
   }
}); 