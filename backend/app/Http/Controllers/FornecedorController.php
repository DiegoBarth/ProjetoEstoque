<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FornecedorController extends Controller {

   /**
    * Valida os dados da requisição, e então insere um novo registro de fornecedor.
    * @param Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'sRazaoSocial'  => 'required|string|max:150',
         'sNomeFantasia' => 'string|max:150',
         'sCpfCnpj'      => 'required|string|max:14|min:11|unique:fornecedores,forcpfcnpj',
         'sTelefone'     => 'string|max:11',
         'sEmail'        => 'nullable|string|max:30',
         'sEndereco'     => 'nullable|string',
         'sDataFundacao' => 'nullable|date|before_or_equal:today'
      ]);

      $oFornecedor = Fornecedor::create([
         'forrazao_social'      => $aValidacao['sRazaoSocial'],
         'fornome_fantasia'     => $aValidacao['sNomeFantasia'],
         'forcpfcnpj'           => $aValidacao['sCpfCnpj'],
         'fortelefone'          => $aValidacao['sTelefone'],
         'foremail'             => $aValidacao['sEmail'],
         'forendereco'          => $aValidacao['sEndereco'],
         'fordata_fundacao'     => $aValidacao['sDataFundacao'],
         'fordata_hora_criacao' => now()->format('d/m/Y H:i:s')
      ]);

      return response()->json(['oFornecedor' => $oFornecedor], 201);
   }

   /**
    * Busca todos os fornecedores
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getFornecedores() {
      $aFornecedores = Fornecedor::all();

      if(!$aFornecedores) {
         return response()->json(['Nenhum fornecedor encontrado.'], 404);
      }

      return response()->json(['aFornecedores' => $aFornecedores], 200);
   }

   /**
    * Busca um fornecedor com base no código informado, ou informa que fornecedor não foi encontrado.
    * @param integer $iFornecedor
    * @return JsonResponse
    */
   public function getFornecedorByCodigo($iFornecedor) {
      $oFornecedor = Fornecedor::find($iFornecedor);

      if(!$oFornecedor) {
         return response()->json(['Fornecedor não encontrado.'], 404);
      }

      return response()->json(['oFornecedor' => $oFornecedor], 200);
   }

   /**
    * Atualiza as informações de um fornecedor
    * @param Request $oRequest
    * @param integer $iFornecedor
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizar(Request $oRequest, $iFornecedor) {
      $oFornecedor = Fornecedor::find($iFornecedor);

      if(!$oFornecedor) {
         return response()->json(['sMensagem' => 'Fornecedor não encontrado.'], 404);
      }

      $aValidacao = $oRequest->validate([
         'sRazaoSocial'  => 'required|string|max:150',
         'sNomeFantasia' => 'string|max:150',
         'sCpfCnpj'      => "required|string|max:14|min:11|unique:fornecedores,forcpfcnpj,$iFornecedor,forcodigo",
         'sTelefone'     => 'string|max:11',
         'sEmail'        => 'nullable|string|max:30',
         'sEndereco'     => 'nullable|string',
         'sDataFundacao' => 'nullable|date|before_or_equal:today'
      ]);

      $oFornecedor->update([
         'forrazao_social'  => $aValidacao['sRazaoSocial'],
         'fornome_fantasia' => $aValidacao['sNomeFantasia'],
         'forcpfcnpj'       => $aValidacao['sCpfCnpj'],
         'fortelefone'      => $aValidacao['sTelefone'],
         'foremail'         => $aValidacao['sEmail'],
         'forendereco'      => $aValidacao['sEndereco'],
         'fordata_fundacao' => $aValidacao['sDataFundacao']
      ]);      

      return response()->json(['oFornecedor' => $oFornecedor], 201);
   }

   /**
    * Exclui um fornecedor com base no código
    * @param integer $iFornecedor
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluir($iFornecedor) {
      $oFornecedor = Fornecedor::find($iFornecedor);

      if(!$oFornecedor) {
         return response()->json(['sMensagem' => 'Fornecedor não encontrado.'], 404);
      }

      $sRazaoSocial = $oFornecedor->forrazao_social;

      $oFornecedor->delete();

      return response()->json(['sMensagem' => "Fornecedor {$iFornecedor} - {$sRazaoSocial} excluído com sucesso."], 200);
   }

}