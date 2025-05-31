<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Venda;
use App\Models\ItemVenda;
use Illuminate\Http\JsonResponse;
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

   public function getVendas() {
      $aVendas = Venda::with([
         'usuario:usucodigo,usunome',
         'cliente:clicodigo,clinome,clicpf',
         'formaPagamento:fpcodigo,fpnome',
      ])->get(['vecodigo', 'usucodigo', 'clicodigo', 'fpcodigo', 'venumero_parcelas', 'vedesconto', 'vevalor_total', 'vesituacao', 'vedata_venda']);

      foreach ($aVendas as $venda) {
         $venda->vesituacao_nome = ($venda->vesituacao == 1) ? 'Aberto' : (($venda->vesituacao == 2) ? 'Finalizada' : 'Cancelada');
      }


      return response()->json(['aVendas' => $aVendas], 200);
   }

   public function finalizarVenda($iVenda) {
      $oVenda = Venda::find($iVenda);

      $oVenda->update(['vesituacao' => 2]);

      return response()->json([
         'sMensagem' => 'A venda foi finalizada com sucesso.',
         'oVenda'  => $oVenda
      ], 200);
   }

   public function cancelarVenda($iVenda) {
      $oVenda = Venda::find($iVenda);

      $oVenda->update(['vesituacao' => 3]);

      return response()->json([
         'sMensagem' => 'A venda foi cancelada com sucesso.',
         'oVenda'  => $oVenda
      ], 200);
   }
   
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