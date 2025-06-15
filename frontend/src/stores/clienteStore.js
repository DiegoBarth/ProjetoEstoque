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

            return data;
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
      },
      async getAnexosCliente(iCliente) {
         const response = await api.get(`/api/cliente/busca/anexo?cliente=${encodeURIComponent(iCliente)}`);

         return response.data;
      },
      async cadastrarAnexo(oAnexo) {
         try {
            const formData = new FormData();
            formData.append('arquivo', oAnexo.anearquivo);
            formData.append('nome', oAnexo.anenome_arquivo);
            formData.append('tipo', oAnexo.anetipo);
            formData.append('observacao', oAnexo.aneobservacao);
            formData.append('clicodigo', oAnexo.clicodigo);
            
            const response = await api.post('/api/cliente/anexo', formData, {
               headers: { 'Content-Type': 'multipart/form-data' }
            });

            return response.data;
         }
         catch (error) {
            console.error('Erro ao enviar anexo:', error);
            throw error;
         }
      },
      async removerAnexo(iAnexo) {
         try {
            const { data } = await api.delete(`/api/cliente/anexo/${iAnexo}`);

            return !!data;
         }
         catch(error) {
            throw(error)
         }
      }
   }
}); 