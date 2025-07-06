<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Venda;
use App\Models\Devolucao;
use App\Models\ItemVenda;
use App\Models\ItemDevolucao;
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
         'formaPagamento:fpcodigo,fpnome'
      ])->get();

      foreach($aVendas as $oVenda) {
         $oVenda->vesituacao_nome = ($oVenda->vesituacao == 1) ? 'Aberto' : (($oVenda->vesituacao == 2) ? 'Finalizada' : 'Cancelada');
         $oVenda->aProdutos = $this->getItensVenda($oVenda->vecodigo, false);
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
               'vecodigo'        => $oVenda->vecodigo,
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
    * Realiza a devolução de produto(s) de uma venda
    * @param \Illuminate\Http\Request $oRequest
    * @param mixed $iVenda
    * @return JsonResponse|mixed
    */
   public function realizarDevolucao(Request $oRequest, $iVenda) {
      $aDados = $oRequest->validate([
         'produtos'                        => 'required|array',
         'produtos.*.iProduto'             => 'required|integer|exists:produtos,procodigo',
         'produtos.*.iQuantidadeDevolvida' => 'required|integer|min:1',
         'motivo'                          => 'nullable|string|max:100'
      ]);

      $aProdutosDevolucao = $aDados['produtos'];
      $sMotivo            = $aDados['motivo'] ?? null;
      $iUsuario           = auth()->id();

      DB::beginTransaction();

      try {
         $oDevolucao = Devolucao::create([
            'vecodigo'              => $iVenda,
            'usucodigo'             => $iUsuario,
            'demotivo'              => $sMotivo,
            'dedata_hora_devolucao' => now()->format('d/m/Y H:i:s'),
            'devalor_total'         => 0,
         ]);

         $fTotalDevolucao = 0;

         $aItensVenda = DB::table('itens_vendas')
            ->where('vecodigo', $iVenda)
            ->whereIn('procodigo', collect($aProdutosDevolucao)->pluck('iProduto'))
            ->get()
            ->keyBy('procodigo');

         foreach($aProdutosDevolucao as $aProduto) {
            $iProduto             = $aProduto['iProduto'];
            $iQuantidadeDevolvida = $aProduto['iQuantidadeDevolvida'];

            if(!isset($aItensVenda[$iProduto])) {
               DB::rollBack();
               return response()->json(['sMensagem' => "Produto $iProduto não pertence à venda"], 422);
            }

            $oItemVenda = $aItensVenda[$iProduto];
            $fDesconto  = ($oItemVenda->ivdesconto ?? 0) / 1;
            $fSubtotal  = $iQuantidadeDevolvida * ($oItemVenda->ivpreco_unitario - $fDesconto);

            ItemDevolucao::create([
               'decodigo'     => $oDevolucao->decodigo,
               'ivcodigo'     => $oItemVenda->ivcodigo,
               'idquantidade' => $iQuantidadeDevolvida,
               'idsubtotal'   => $fSubtotal
            ]);

            $fTotalDevolucao += $fSubtotal;
         }

         $oDevolucao->devalor_total = $fTotalDevolucao;
         $oDevolucao->save();

         DB::commit();

         return response()->json(['sMensagem' => 'Devolução realizada com sucesso.'], 200);
      }
      catch (\Exception $e) {
         DB::rollBack();
         return response()->json(['sMensagem' => 'Erro ao salvar a devolução: ' . $e->getMessage()], 500);
      }
   }

   /**
    * Busca uma devolução já existente
    * @param mixed $iVenda
    * @return JsonResponse|mixed
    */
   public function buscarDevolucao($iVenda) {
      $oDevolucao = Devolucao::where('vecodigo', $iVenda)->first();

      if(!$oDevolucao) {
         return response()->json(null);
      }

      $aItens = $oDevolucao->itens()
         ->join('itens_vendas', 'itens_devolucoes.ivcodigo', '=', 'itens_vendas.ivcodigo')
         ->select(
            'itens_vendas.procodigo as iProduto',
            'itens_devolucoes.idquantidade as iQuantidadeDevolvida'
         )
         ->get();

      return response()->json([
         'sMotivo'   => $oDevolucao->demotivo,
         'aProdutos' => $aItens
      ]);
   }
   
   /**
    * 
    * @param mixed $iVenda
    * @param boolean $bRetornaJson
    * @return JsonResponse|mixed
    */
   public function getItensVenda($iVenda, $bRetornaJson = true) {
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
            'sNome'        => $item->produto->pronome ?? '',
            'iQuantidade'  => $item->ivquantidade ?? '',
            'sValor'       => $item->ivpreco_unitario ?? '',
            'sDesconto'    => ($item->ivquantidade * $item->ivpreco_unitario - $item->ivsubtotal) / $item->ivquantidade  ?? '',
            'sValorTotal'  => $item->ivsubtotal
         ];
      });

      if($bRetornaJson) {
         return response()->json([
            'aItensVenda' => $aItensTratados
         ], 200); 
      }

      return $aItensTratados;
   }
}