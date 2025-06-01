<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Venda;
use App\Models\ItemVenda;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendaController extends Controller {
 
   /**
    * Retorna todas as formas de pagamento
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getFormasPagamento() {
      $aFormasPagamento = FormaPagamento::all();

      return response()->json(['aFormasPagamento' => $aFormasPagamento], 200);
   }

   /**
    * Retorna todas as vendas
    * @return JsonResponse|mixed
    */
   public function getVendas() {
      $aVendas = Venda::with([
         'usuario:usucodigo,usunome',
         'cliente:clicodigo,clinome,clicpf',
         'formaPagamento:fpcodigo,fpnome',
      ])->get([
         'vecodigo',
         'usucodigo',
         'clicodigo',
         'fpcodigo',
         'venumero_parcelas',
         'vedesconto',
         'vevalor_total',
         'vesituacao',
         'vedata_hora_venda'
      ]);

      foreach ($aVendas as $venda) {
         $venda->vesituacao_nome = ($venda->vesituacao == 1) ? 'Aberto' : (($venda->vesituacao == 2) ? 'Finalizada' : 'Cancelada');
      }

      return response()->json(['aVendas' => $aVendas], 200);
   }

   /**
    * Realiza a inserção de uma venda juntamente com os itens da venda
    * @param \Illuminate\Http\Request $oRequest
    * @return JsonResponse|mixed
    */
   public function cadastrarVenda(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'iCodigoCliente'     => 'nullable|exists:clientes,clicodigo',
         'iCodigoUsuario'     => 'required|exists:usuarios,usucodigo',
         'iFormaPagamento'    => 'required|exists:formas_pagamento,fpcodigo',
         'iNumeroParcelas'    => 'nullable|integer|min:1',
         'fDesconto'          => 'nullable|numeric|min:0',
         'fValorTotal'        => 'required|numeric|min:0',
         'iSituacao'          => 'required|in:1,2,3',
         'aProdutos'          => 'required|array|min:1',
         'aProdutos.*.iCodigoProduto' => 'required|exists:produtos,procodigo',
         'aProdutos.*.iQuantidade'    => 'required|integer|min:1',
         'aProdutos.*.fValorVenda'    => 'required|numeric|min:0',
         'aProdutos.*.fValorTotal'    => 'required|numeric|min:0',
      ]);

      DB::beginTransaction();

      try {
         $oVenda = Venda::create([
            'clicodigo'         => $aValidacao['iCodigoCliente'] ?? null,
            'usucodigo'         => $aValidacao['iCodigoUsuario'],
            'fpcodigo'          => $aValidacao['iFormaPagamento'],
            'venumero_parcelas' => $aValidacao['iNumeroParcelas'],
            'vedesconto'        => $aValidacao['fDesconto'],
            'vevalor_total'     => $aValidacao['fValorTotal'],
            'vesituacao'        => $aValidacao['iSituacao'],
            'vedata_hora_venda' => now()->format('d/m/Y H:i:s')
         ]);

         foreach($aValidacao['aProdutos'] as $oProduto) {
            $oVenda->itens()->create([
               'procodigo'       => $oProduto['iCodigoProduto'],
               'ivquantidade'    => $oProduto['iQuantidade'],
               'ivpreco_unitario'=> $oProduto['fValorVenda'],
               'ivsubtotal'      => $oProduto['fValorTotal'],
            ]);
         }

         DB::commit();
         return response()->json(['sMensagem' => 'Venda finalizada com sucesso.'], 200);
      }
      catch(\Exception $e) {
         DB::rollBack();
         return response()->json(['sMensagem' => 'Erro ao salvar a venda: ' . $e->getMessage()], 500);
      }
   }

   /**
    * Atualiza o status da venda como concluída
    * @param mixed $iVenda
    * @return JsonResponse|mixed
    */
   public function finalizarVenda($iVenda) {
      $oVenda = Venda::find($iVenda);

      $oVenda->update(['vesituacao' => 2]);

      return response()->json([
         'sMensagem' => 'A venda foi finalizada com sucesso.',
         'oVenda'  => $oVenda
      ], 200);
   }

   /**
    * Realiza o cancelamento da venda
    * @param mixed $iVenda
    * @return JsonResponse|mixed
    */
   public function cancelarVenda($iVenda) {
      $oVenda = Venda::find($iVenda);

      $oVenda->update(['vesituacao' => 3]);

      return response()->json([
         'sMensagem' => 'A venda foi cancelada com sucesso.',
         'oVenda'  => $oVenda
      ], 200);
   }
   
   /**
    * 
    * @param mixed $iVenda
    * @return JsonResponse|mixed
    */
   public function getItensVenda($iVenda) {
      $aItensVenda = ItemVenda::where('vecodigo', '=', $iVenda)->with('produto', 'venda')->get();

      if($aItensVenda->isEmpty()) {
         return response()->json([
            'sMensagem'   => 'Não foram encontrados itens para a venda informada.',
            'aItensVenda' => $aItensVenda
         ], 404);
      }

      $aItensTratados = $aItensVenda->map(function ($item) {
        return [
            'iProduto'     => $item->procodigo ?? '',
            'sNome'        => $item->produto->pronome ?? '',         // Supondo relacionamento com Produto
            'iQuantidade'  => $item->ivquantidade ?? '',
            'sValor'       => $item->produto->provalor ?? '',      // Supondo relacionamento com Produto
            'sDesconto'    => $item->produto->provalor_desconto ?? '',
            'sValorTotal'  => ($item->produto->provalor * $item->ivquantidade) - $item->produto->provalor_desconto ?? ''
        ];
    });

      return response()->json([
         'aItensVenda' => $aItensTratados
      ], 200); 
   }
}