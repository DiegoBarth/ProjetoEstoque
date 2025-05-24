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
            sDataNascimento: utils.formatarDataBR(oCliente.clidata_nascimento),
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
      }
   }
}); 