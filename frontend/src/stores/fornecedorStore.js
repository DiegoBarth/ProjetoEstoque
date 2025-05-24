import api from "@/api";
import { defineStore } from "pinia";

export const useFornecedorStore = defineStore('Fornecedor', {
    state: () => ({
        aFornecedores: []
    }),
    actions: {
        getFornecedores: async () => {
            try {
                const { data } = await api.get('/api/fornecedor')

                return data.aFornecedores;
            }
            catch(error) {
                throw(error);
            }
        }
    }
})