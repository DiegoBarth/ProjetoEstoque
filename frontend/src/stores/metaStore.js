import api from "@/api";
import { defineStore } from "pinia";

export const useMetaStore = defineStore('Meta', {
   state: () => ({
      aMetas: []
   }),
   actions: {
      async getMetas() {
         try {
            const { data } = await api.get('/api/meta')
            return data.aMetas;
         }
         catch(error) {
            throw(error);
         }
      },
      async getMetaByCodigo(iMeta) {
         try {
            const { data } = await api.get(`/api/meta/${iMeta}`);

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async cadastrarMeta(oDados) {
         try {
            const { data } = await api.post('/api/meta', oDados)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async atualizarMeta(iMeta, oDados) {
         try {
            const { data } = await api.put(`/api/meta/${iMeta}`, oDados)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      },
      async excluirMeta(iMeta) {
         try {
            const { data } = await api.delete(`/api/meta/${iMeta}`)

            return !!data;
         }
         catch(error) {
            throw(error);
         }
      }
   }
})