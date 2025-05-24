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
      $aProdutos = Produto::join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo')
         ->select('produtos.*', 'fornecedores.forrazao_social')
         ->get();

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
    * @param Request $oRequest
    * @return JsonResponse
    */
   public function getProdutoByNome(Request $oRequest) {
      $sProduto  = $oRequest->query('nome_produto');

      $aProdutos = Produto::join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo')
         ->where('pronome', 'ILIKE', "%{$sProduto}%")
         ->select('produtos.*', 'fornecedores.forrazao_social')
         ->get();
      
      if(!count($aProdutos)) {
         abort(response()->json(['sMensagem' => 'Nenhum produto encontrado'], 404));
      }

      return response()->json(['aProdutos' => $aProdutos], 200);
   }

   /**
    * Após validar os dados recebidos na requisição, insere um novo produto no sistema.
    * @param  Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {      
      $aValidacao = $oRequest->validate([
         "sNome"         => 'required|string|max:50',
         "sCodigoBarras" => 'string|max:20',
         "iFornecedor"   => 'required|integer|exists:fornecedores,forcodigo',
         "fValorCompra"  => 'required|numeric|min:0',
         "fValorVenda"   => 'required|numeric|min:0',
         "fDesconto"     => 'nullable|numeric|min:0',
         "iQuantidade"   => 'required|integer'
      ]);            

      $oProduto = Produto::create([
         'pronome'           => $aValidacao['sNome'],
         'procodigo_barras'  => $aValidacao['sCodigoBarras'],
         'forcodigo'         => $aValidacao['iFornecedor'],
         'procusto'          => $aValidacao['fValorCompra'],
         'provalor'          => $aValidacao['fValorVenda'],
         'provalor_desconto' => $aValidacao['fDesconto'] ?: null,
         'proestoque'        => $aValidacao['iQuantidade']
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
         'sNome'         => 'sometimes|required|string|max:50',
         'sCodigoBarras' => 'sometimes|string|max:20',
         'iFornecedor'   => 'sometimes|required|integer|exists:fornecedores,forcodigo',
         'fValorCompra'  => 'sometimes|required|numeric|min:0',
         'fValorVenda'   => 'sometimes|required|numeric|min:0',
         'fDesconto'     => 'sometimes|required|numeric|min:0',
         'iQuantidade'   => 'sometimes|required|integer'
      ]);

      $oProduto->update([
         'pronome'           => $aValidacao['sNome'],
         'procodigo_barras'  => $aValidacao['sCodigoBarras'],
         'forcodigo'         => $aValidacao['iFornecedor'],
         'procusto'          => $aValidacao['fValorCompra'],
         'provalor'          => $aValidacao['fValorVenda'],
         'provalor_desconto' => $aValidacao['fDesconto'],
         'proestoque'        => $aValidacao['iQuantidade']
      ]);

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
      $oProduto = Produto::where('procodigo', $iCodigo)
         ->join('fornecedores', 'fornecedores.forcodigo', '=', 'produtos.forcodigo')
         ->first();
      
      if(!$oProduto) {
         abort(response()->json(['sMensagem' => 'Produto não encontrado'], 404));
      }

      return $oProduto;
   }

}