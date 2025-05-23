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
        },
        getProdutoByCodigo: async (iProduto) => {
            try {
                const { data } = await api.get(`/api/produto/${iProduto}`);

                return {
                    iProduto: data.oProduto.procodigo,
                    sNome: data.oProduto.pronome,
                    sCodigoBarras: data.oProduto.procodigo_barras,
                    iFornecedor: data.oProduto.forcodigo,
                    fValorCompra: data.oProduto.procusto,
                    fValorVenda: data.oProduto.provalor,
                    fDesconto: data.oProduto.prodesconto,
                    iQuantidade: data.oProduto.proestoque
                };
            }
            catch(error) {
                throw(error);
            }
        },
        atualizarProduto: async (iProduto, oDados) => {
            try {
                const { data } = await api.put(`/api/produto/${iProduto}`, {
                    ...oDados
                });

                return data;
            }
            catch(error) {
                throw(error);
            }
        },
        excluirProduto: async (iProduto) => {
            try {
                const { data } = await api.delete(`/api/produto/${iProduto}`);

                return data;
            }
            catch(error) {
                throw(error);
            }
        }
    }
})