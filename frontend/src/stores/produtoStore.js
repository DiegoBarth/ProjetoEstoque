import api from "@/api";
import { defineStore } from "pinia";

export const useProdutoStore = defineStore('Produto', {
    state: () => ({

    }),
    actions: {
        cadatrarProduto: async (payload) => {            
            try {
                const { data } = await api.post('/api/produto', {
                    ...payload
                });

                return data;
            }
            catch(error) {
                throw(error);
            }
        },
        getProdutos: async () => {
            try {
                const { data } = await api.get('/api/produto');

                return data.aProdutos;
            }
            catch(error) {
                throw(error);
            }
        }
    }
})