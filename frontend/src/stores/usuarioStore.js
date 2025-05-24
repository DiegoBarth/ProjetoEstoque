import api from "@/api";
import { defineStore } from "pinia";

export const useUsuarioStore = defineStore('Usuario', {
   state: () => ({

   }),
   actions: {
      async getToken() {
         return await api.get('/sanctum/csrf-cookie');
      },
      async login(sUsuario, sSsenha) {
         const {data, status} = await api.post('/api/login', {
            usunome_usuario: sUsuario,
            password       : sSsenha
         });

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
      }
   }
});