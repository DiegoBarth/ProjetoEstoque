<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MetaController extends Controller {

   /**
    * Busca todas as metas
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getMetas() {
      $aMetas = DB::table('metas')
         ->leftJoin('metas_usuarios', 'metas_usuarios.mecodigo', '=', 'metas.mecodigo')
         ->leftJoin('metas_produtos', 'metas_produtos.mecodigo', '=', 'metas.mecodigo')
         ->leftJoin('usuarios', 'usuarios.usucodigo', '=', 'metas_usuarios.usucodigo')
         ->leftJoin('produtos', 'produtos.procodigo', '=', 'metas_produtos.procodigo')
         ->select([
            'metas.*',
            'usuarios.usucodigo',
            'produtos.procodigo',
            'usuarios.usunome as usuario_descricao',
            'produtos.pronome as produto_descricao',
            DB::raw("
               CASE metas.metipo
                  WHEN 1 THEN 'Valor'
                  WHEN 2 THEN 'Quantidade'
                  WHEN 3 THEN 'Valor + Quantidade'
                  WHEN 4 THEN 'Valor por Usuário'
                  WHEN 5 THEN 'Quantidade por usuário'
                  WHEN 6 THEN 'Valor + Quantidade por usuário'
                  WHEN 7 THEN 'Valor por produto'
                  WHEN 8 THEN 'Quantidade por produto'
                  WHEN 9 THEN 'Valor + Quantidade por produto'
                  ELSE 'Desconhecido'
               END AS metipo_descricao
            "),
         ])
      ->get();

      if(!$aMetas) {
         return response()->json(['Nenhuma meta encontrada.'], 404);
      }

      return response()->json(['aMetas' => $aMetas], 200);
   }

   /**
    * Busca uma meta com base no código informado, ou informa que meta não foi encontrada.
    * @param integer $iMeta
    * @return JsonResponse
    */
   public function getMetaByCodigo($iMeta) {
      $oMeta = Meta::find($iMeta);

      if(!$oMeta) {
         return response()->json(['Meta não encontrada.'], 404);
      }

      return response()->json(['oMeta' => $oMeta], 200);
   }

   /**
    * Valida os dados da requisição, e então insere um novo registro de meta.
    * @param Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'sDescricao'      => 'nullable|string|max:50',
         'iTipo'           => 'required|integer|between:1,9',
         'fValorMeta'      => 'nullable|numeric|min:0',
         'iQuantidadeMeta' => 'nullable|integer|min:0',
         'sDataInicial'    => 'required',
         'sDataFinal'      => 'required',
         'iUsuario'        => 'nullable|integer|exists:usuarios,usucodigo',
         'iProduto'        => 'nullable|integer|exists:produtos,procodigo'
      ]);

      DB::beginTransaction();

      try {
         $oMeta = Meta::create([
            'medescricao'       => $aValidacao['sDescricao'],
            'metipo'            => $aValidacao['iTipo'],
            'mevalor_meta'      => $aValidacao['fValorMeta'],
            'mequantidade_meta' => $aValidacao['iQuantidadeMeta'],
            'medata_inicio'     => $aValidacao['sDataInicial'],
            'medata_fim'        => $aValidacao['sDataFinal']
         ]);

         if(isset($aValidacao['iUsuario'])) {
            $oMeta->usuarios()->create([
               'mecodigo'  => $oMeta->mecodigo,
               'usucodigo' => $aValidacao['iUsuario']
            ]);
         }

         if(isset($aValidacao['iProduto'])) {
            $oMeta->produtos()->create([
               'mecodigo'  => $oMeta->mecodigo,
               'procodigo' => $aValidacao['iProduto']
            ]);
         }

         DB::commit();
         return response()->json(['oMeta' => $oMeta, 'sMensagem' => 'Meta cadastrada com sucesso.'], 201);
      }
      catch(\Exception $e) {
         DB::rollBack();
         return response()->json(['sMensagem' => 'Erro ao salvar a meta: ' . $e->getMessage()], 500);
      }
   }

   /**
    * Atualiza as informações de uma meta
    * @param Request $oRequest
    * @param integer $iMeta
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizar(Request $oRequest, $iMeta) {
      $oMeta = Meta::find($iMeta);

      if(!$oMeta) {
         return response()->json(['sMensagem' => 'Meta não encontrada.'], 404);
      }

      $aValidacao = $oRequest->validate([
         'sDescricao'      => 'nullable|string|max:50',
         'iTipo'           => 'required|integer|between:1,9',
         'fValorMeta'      => 'nullable|numeric|min:0',
         'iQuantidadeMeta' => 'nullable|integer|min:0',
         'sDataInicial'    => 'required',
         'sDataFinal'      => 'required',
         'iUsuario'        => 'nullable|integer|exists:usuarios,usucodigo',
         'iProduto'        => 'nullable|integer|exists:produtos,procodigo'
      ]);

      try {
         $oMeta->update([
            'medescricao'       => $aValidacao['sDescricao'],
            'metipo'            => $aValidacao['iTipo'],
            'mevalor_meta'      => $aValidacao['fValorMeta'],
            'mequantidade_meta' => $aValidacao['iQuantidadeMeta'],
            'medata_inicio'     => $aValidacao['sDataInicial'],
            'medata_fim'        => $aValidacao['sDataFinal']
         ]);    

         $oMeta->usuarios()->delete();

         if(isset($aValidacao['iUsuario'])) {
            $oMeta->usuarios()->create([
               'mecodigo'  => $oMeta->mecodigo,
               'usucodigo' => $aValidacao['iUsuario']
            ]);
         }

         $oMeta->produtos()->delete();

         if(isset($aValidacao['iProduto'])) {
            $oMeta->produtos()->create([
               'mecodigo'  => $oMeta->mecodigo,
               'procodigo' => $aValidacao['iProduto']
            ]);
         }

         DB::commit();

         return response()->json(['oMeta' => $oMeta, 'sMensagem' => "Meta {$iMeta} - {$oMeta->medescricao} alterada com sucesso."], 201);
      }
      catch(\Exception $e) {
         DB::rollBack();
         return response()->json(['sMensagem' => 'Erro ao atualizar a meta: ' . $e->getMessage()], 500);
      }
   }

   /**
    * Exclui uma meta com base no código
    * @param integer $iMeta
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluir($iMeta) {
      $oMeta = Meta::find($iMeta);

      if(!$oMeta) {
         return response()->json(['sMensagem' => 'Meta não encontrada.'], 404);
      }

      $oMeta->usuarios()->delete();
      $oMeta->produtos()->delete();

      $sDescricao = $oMeta->medescricao;

      $oMeta->delete();

      return response()->json(['sMensagem' => "Meta {$iMeta} - {$sDescricao} excluída com sucesso."], 200);
   }

}