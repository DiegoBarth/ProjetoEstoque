<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Venda;
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
         'cliente:clicodigo,clinome',
         'formaPagamento:fpcodigo,fpnome',
      ])->get(['vecodigo', 'usucodigo', 'clicodigo', 'fpcodigo', 'venumero_parcelas', 'vedesconto', 'vevalor_total', 'vesituacao', 'vedata_venda']);

      foreach ($aVendas as $venda) {
         $venda->vesituacao_nome = ($venda->vesituacao == 1) ? 'Aberto' : (($venda->vesituacao == 2) ? 'Finalizada' : 'Cancelada');
      }


      return response()->json(['aVendas' => $aVendas], 200);
   }
   
}