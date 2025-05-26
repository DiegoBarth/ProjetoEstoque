<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
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
   
}