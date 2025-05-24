import api from "@/api";
import { defineStore } from "pinia";

export const useClienteStore = defineStore('Cliente', {
    state: () => ({

    }),
    actions: {
        getClientes: async () => {
            try {
                const { data } = await api.get('/api/cliente');

                return data.aClientes;
            }
            catch(error) {
                throw(error);
            }
        }
    }
}) 