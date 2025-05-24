import api from "@/api";
import { defineStore } from "pinia";

export const useUsuarioStore = defineStore('Usuario', {
    state: () => ({

    }),
    actions: {
        getUsuarios: async () => {
            try {
                const { data } = await api.get('/api/usuario');

                return data.aUsuarios;
            }
            catch(error) {
                throw(error)
            }
        }
    }
})