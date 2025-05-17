<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller {
 
   /**
    * Retorna todos os usuários
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function getUsuarios() {
      $aUsuarios = Usuario::all();

      return response()->json(['aUsuarios' => $aUsuarios]);
   }

   /**
    * Retorna o usuário baseado no código informado
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function getUsuarioByCodigo($iCodigo) {
      $oUsuario = Usuario::find($iCodigo);

      if(!$oUsuario) {
         return response()->json(['sMensagem' => 'Usuário não encontrado'], 404);
      }

      return response()->json(['oUsuario' => $oUsuario]);
   }

   /**
    * Após validar os dados recebidos na requisição, insere um novo usuário no sistema.
    * @param  \Illuminate\Http\Request $oRequest
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'usunome'         => 'required|string|max:100',
         'usunome_usuario' => 'required|string|max:20|unique:usuarios,usunome_usuario',
         'usunivel'        => 'required|integer|exists:nivel_usuarios,nucodigo',
         'ususenha'        => 'required|string|min:6',
         'usuativo'        => 'boolean'
      ]);

      $oUsuario = Usuario::create([
         'usunome'         => $aValidacao['usunome'],
         'usunome_usuario' => $aValidacao['usunome_usuario'],
         'usunivel'        => $aValidacao['usunivel'],
         'ususenha'        => bcrypt($aValidacao['ususenha']),
         'usuativo'        => $aValidacao['usuativo'] ?? true
      ]);

      return response()->json(['oUsuario' => $oUsuario], 201);
   }

   /**
    * Após realizar as validações dos dados recebidos, atualiza as informações do usuário
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function atualizar(Request $oRequest, $iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo);

      $aValidacao = $oRequest->validate([
         'usunome'         => 'sometimes|string|max:100',
         'usunome_usuario' => "sometimes|string|max:20|unique:usuarios,usunome_usuario,$iCodigo,usucodigo",
         'usunivel'        => 'sometimes|integer|exists:nivel_usuarios,nucodigo',
         'ususenha'        => 'sometimes|string|min:6',
         'usuativo'        => 'sometimes|boolean'
      ]);

      if(isset($aValidacao['ususenha'])) {
         $aValidacao['ususenha'] = bcrypt($aValidacao['ususenha']);
      }

      $oUsuario->update($aValidacao);

      return response()->json(['oUsuario' => $oUsuario]);
   }

   /**
    * Exclui o usuário
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function excluir($iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo);

      $oUsuario->delete();

      return response()->json(['sMensagem' => 'Usuário excluído com sucesso']);
   }

   /**
    * Ativa ou inativa o usuário
    * @param integer $iCodigo
    * @return \Illuminate\Http\JsonResponse
    * @throws \Illuminate\Validation\ValidationException
    */
   public function atualizarSituacao($iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo, false);

      $oUsuario->usuativo = !$oUsuario->usuativo;
      $oUsuario->save();

      return response()->json([
         'sMensagem' => 'Status do usuário atualizado com sucesso.',
         'oUsuario'  => $oUsuario
      ]);
   }

   /**
    * Retorna o usuário pelo código informado. Caso não encontre, aborta a requisição com erro HTTP 404 
    * @param integer $iCodigo
    * @param boolean $bFiltrarSituacao
    * @return \App\Models\Usuario
    * @throws \Symfony\Component\HttpKernel\Exception\HttpException
    */
   private function getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo, $bFiltrarSituacao = true) {
      $oQuery = Usuario::where('usucodigo', $iCodigo);
      
      if($bFiltrarSituacao) {
         $oQuery->where('usuativo', true);
      }

      $oUsuario = $oQuery->first();

      if(!$oUsuario) {
         abort(response()->json(['sMensagem' => 'Usuário não encontrado'], 404));
      }

      return $oUsuario;
   }

}