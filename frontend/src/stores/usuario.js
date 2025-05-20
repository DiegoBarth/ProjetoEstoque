import { defineStore } from 'pinia';

export const useUsuarioStore = defineStore('oUsuario',  {
   state: () => ({
      oUsuario: {}
   }),

   actions: {
      setUsuario(oUsuario) {
         this.oUsuario = oUsuario;
      },
      getUsuario() {
         return this.oUsuario;
      }
   }
})