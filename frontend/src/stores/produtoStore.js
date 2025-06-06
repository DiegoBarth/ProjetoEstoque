import api from "@/api";
import { defineStore } from "pinia";
import * as utils from '../utils/main';

export const useProdutoStore = defineStore('Produto', {
   state: () => ({

   }),
   actions: {
      _formatarProduto(oProduto) {
         return {
            iProduto           : oProduto.procodigo,
            sNome              : oProduto.pronome,
            sCodigoBarras      : oProduto.procodigo_barras,
            iFornecedor        : oProduto.forcodigo,
            fValorCompra       : utils.converterParaMoeda(oProduto.procusto),
            fValorVenda        : utils.converterParaMoeda(oProduto.provalor),
            fDesconto          : utils.converterParaMoeda(oProduto.provalor_desconto),
            iQuantidade        : oProduto.proestoque,
            iEstoqueMinimoIdeal: oProduto.proestoque_minimo_ideal,
            sFornecedor        : oProduto.forrazao_social
         };
      },
      async getProdutos() {
         try {
            const { data } = await api.get('/api/produto');

            return data.aProdutos.map(this._formatarProduto);
         }
         catch (error) {
            throw (error);
         }
      },
      async getProdutoByCodigo(iProduto) {
         try {
            const { data } = await api.get(`/api/produto/${iProduto}`);

            return this._formatarProduto(data.oProduto);
         }
         catch (error) {
            throw (error);
         }
      },
      async getProdutoByNome(sProduto) {
         try {
            const { data } = await api.get(`/api/produto/busca/nome?nome_produto=${encodeURIComponent(sProduto)}`);

            return data.aProdutos.map(this._formatarProduto);
         }
         catch (error) {
            throw (error);
         }
      },
      async cadastrarProduto(payload) {
         try {
            const { data } = await api.post('/api/produto', {
               ...payload
            });

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async atualizarProduto(iProduto, oDados) {
         try {
            const { data } = await api.put(`/api/produto/${iProduto}`, {
               ...oDados
            });

            return data;
         }
         catch (error) {
            throw (error);
         }
      },
      async excluirProduto(iProduto) {
         try {
            const { data } = await api.delete(`/api/produto/${iProduto}`);

            return data;
         }
         catch (error) {
            throw (error);
         }
      }
   }
});