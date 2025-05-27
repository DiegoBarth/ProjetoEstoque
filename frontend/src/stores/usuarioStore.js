import api from "@/api";
import { defineStore } from "pinia";

export const useUsuarioStore = defineStore('Usuario', {
   state: () => ({

   }),
   actions: {
      _formatarUsuario(oUsuario) {
         return {
            iUsuario    : oUsuario.usucodigo,
            sNome       : oUsuario.usunome,
            sNomeUsuario: oUsuario.usunome,
            iNivel      : oUsuario.usunivel,
            iAtivo      : oUsuario.usuativo
         };
      },
      async getToken() {
         return await api.get('/sanctum/csrf-cookie');
      },
      async login(sUsuario, sSsenha) {
         const {data, status} = await api.post('/api/login', {
            usunome_usuario: sUsuario,
            password       : sSsenha
         });

         data = data.aProdutos.map(this._formatarProduto);
         
         return {data, status};
      },
      async getUsuarios() {
         try {
            const { data } = await api.get('/api/usuario');

            return data.aUsuarios;
         }
         catch (error) {
            throw (error);
         }
      },
      async getNiveisUsuario() {
         try {
            const { data } = await api.get('/api/usuario/nivel');

            return data.aNiveis;
         }
         catch(error) {
            throw(error);
         }
      },
      async cadastrarUsuario(payload) {
         try {
            const { data } = await api.post('/api/usuario', {
               ...payload
            });

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async atualizarUsuario(iUsuario, oDados) {
         try {
            const { data } = await api.put(`/api/usuario/${iUsuario}`, {
               ...oDados
            });

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async excluirUsuario(iUsuario) {
         try {
            const { data } = await api.delete(`/api/usuario/${iUsuario}`);

            return data;
         }
         catch (error) {
            throw (error);
         }
      }
   }
});