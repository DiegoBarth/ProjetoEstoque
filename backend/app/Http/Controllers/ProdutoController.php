<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProdutoController extends Controller {

   /**
    * Retorna todos os produtos
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getProdutos() {
      $aProdutos = Produto::all();

      return response()->json(['aProdutos' => $aProdutos], 200);
   }

   /**
    * Busca um produto com base no código informado, ou informa que o produto não foi encontrado.
    * @param $iProduto Código do produto
    * @return Produto
    */
   public function getProdutoByCodigo($iProduto) {
      $oProduto = $this->getProdutoOuRetornaMensagemProdutoNaoEncontrado($iProduto);
      $oProduto = Produto::find($iProduto);

      if(!$oProduto) {
         return response()->json(['sMensagem' => 'Produto não encontrado.'], 404);
      }

      return response()->json(['oProduto' => $oProduto], 200);
   }

   /**
    * Busca produtos com base na descrição informada, ou informa que não foram encontrados produtos.
    * @param string $sProduto
    * @return JsonResponse
    */
   public function getProdutoByNome($sProduto) {
      $aProdutos = Produto::find('*')
         ->Where('pronome', 'ILIKE', "%{$sProduto}%")
         ->get();
      
      if(!count($aProdutos)) {
         abort(response()->json(['sMensagem' => 'Nenhum produto encontrado'], 404));
      }

      $aNomes = [];
        
      foreach($aProdutos as $oProduto) {
         $aNomes[] = $oProduto->pronome;
      }

      return response()->json((object) [
         'aNomes'    => $aNomes,
         'aProdutos' => $aProdutos
      ], 200);
   }

   /**
    * Após validar os dados recebidos na requisição, insere um novo produto no sistema.
    * @param  Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'pronome'    => 'required|string|max:50',
         'forcodigo'  => 'required|integer|exists:fornecedores,forcodigo',
         'procusto'   => 'required|numeric|min:0',
         'provalor'   => 'required|numeric|min:0',
         'proestoque' => 'required|integer'
      ]);

      $oProduto = Produto::create([
         'pronome'    => $aValidacao['pronome'],
         'forcodigo'  => $aValidacao['forcodigo'],
         'procusto'   => $aValidacao['procusto'],
         'provalor'   => $aValidacao['provalor'],
         'proestoque' => $aValidacao['proestoque']
      ]);

      return response()->json(['oProduto' => $oProduto], 201);
   }

   /**
    * Após realizar as validações dos dados recebidos, atualiza as informações do produto
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizar(Request $oRequest, $iCodigo) {
      $oProduto = $this->getProdutoOuRetornaMensagemProdutoNaoEncontrado($iCodigo);

      $aValidacao = $oRequest->validate([
         'pronome'    => 'sometimes|required|string|max:50',
         'forcodigo'  => 'sometimes|required|integer|exists:fornecedores,forcodigo',
         'procusto'   => 'sometimes|required|numeric|min:0',
         'provalor'   => 'sometimes|required|numeric|min:0',
         'proestoque' => 'sometimes|required|integer'
      ]);

      $oProduto->update($aValidacao);

      return response()->json(['oProduto' => $oProduto], 200);
   }

   /**
    * Exclui o produto
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluir($iCodigo) {
      $oProduto     = $this->getProdutoOuRetornaMensagemProdutoNaoEncontrado($iCodigo);
      $sNomeProduto = $oProduto->pronome;

      $oProduto->delete();

      return response()->json(['sMensagem' => "Produto {$iCodigo} - {$sNomeProduto} excluído com sucesso"], 200);
   }

   /**
    * Retorna o produto pelo código informado. Caso não encontre, aborta a requisição com erro HTTP 404 
    * @param integer $iCodigo
    * @return Produto
    * @throws HttpException
    */
   private function getProdutoOuRetornaMensagemProdutoNaoEncontrado($iCodigo) {
      $oProduto = Produto::where('procodigo', $iCodigo)->first();
      
      if(!$oProduto) {
         abort(response()->json(['sMensagem' => 'Produto não encontrado'], 404));
      }

      return $oProduto;
   }

}