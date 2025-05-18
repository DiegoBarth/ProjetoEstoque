<?php

namespace App\Http\Controllers;

use App\Models\Produto;

class ProdutoController extends Controller {


   public function getProdutos() {

   }

   /**
    * @param $iProduto Código do produto
    * @return Produto
    */
   public function getProdutoByCodigo($iProduto) {
      $oProduto = Produto::find($iProduto);

      if(!$oProduto) {
         return response()->json(['sMensagem' => 'Produto não encontrado.'], 404);
      }

      return response()->json(['oProduto' => $oProduto], 200);
   }

}