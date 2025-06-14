<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\NivelUsuario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsuarioController extends Controller {
 
   /**
    * 
    * @param Request $oRequest
    * @return mixed|JsonResponse
    */
   public function login(Request $oRequest) {
      $aCredenciais = $oRequest->validate([
         'usunome_usuario' => 'required|string',
         'password'        => 'required|string',
      ]);

      if(!Auth::attempt($aCredenciais)) {
         return response()->json(['sMensagem' => 'Usuário ou senha incorretos'], 401);
      }

      $oRequest->session()->regenerate();

      $oUsuario = Usuario::where('usunome_usuario', '=', $oRequest->usunome_usuario)->first();

      return response()->json(['oUsuario' => $oUsuario], 200);
   }

   /**
    * 
    * @param Request $oRequest
    * @return mixed|JsonResponse
    */
   public function logout(Request $oRequest) {
      Auth::guard('web')->logout();

      $oRequest->session()->invalidate();
      $oRequest->session()->regenerateToken();

      return response()->json(['sMensagem' => 'Logout bem-sucedido']);
   }

   /**
    * Retorna todos os usuários
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getUsuarios() {
      $aUsuarios = Usuario::with('nivel')
                     ->orderBy('usucodigo')
                     ->get();

      $aUsuarios->transform(function ($oUsuario) {
         $oUsuario->sSituacao = $oUsuario->usuativo ? 'Ativo' : 'Inativo';
         $oUsuario->sNivel    = $oUsuario->nivel->nunome;

         return $oUsuario;
      });

      return response()->json(['aUsuarios' => $aUsuarios], 200);
   }

   /**
    * Retorna o usuário baseado no código informado
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getUsuarioByCodigo($iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo);

      return response()->json(['oUsuario' => $oUsuario], 200);
   }

   /**
    * Busca usuários com base na descrição informada, ou informa que não foram encontrados usuários.
    * @param Request $oRequest
    * @return JsonResponse
    */
   public function getUsuarioByNome(Request $oRequest) {
      $sUsuario  = $oRequest->query('nome_usuario');

      $aUsuarios = Usuario::where('usunome', 'ILIKE', "%{$sUsuario}%")->get();
      
      if(!count($aUsuarios)) {
         abort(response()->json(['sMensagem' => 'Nenhum usuário encontrado'], 404));
      }

      return response()->json(['aUsuarios' => $aUsuarios], 200);
   }


   /**
    * Retorna todos os níveis de usuário
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getNiveisUsuario() {
      $aNiveis = NivelUsuario::all();

      return response()->json(['aNiveis' => $aNiveis], 200);
   }

   /**
    * Após validar os dados recebidos na requisição, insere um novo usuário no sistema.
    * @param  Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'usunome'         => 'required|string|max:100',
         'usunome_usuario' => 'required|string|max:20|unique:usuarios,usunome_usuario',
         'usunivel'        => 'required|integer|exists:niveis_usuarios,nucodigo',
         'ususenha'        => 'required|string|min:6',
         'usuativo'        => 'boolean'
      ]);

      $oUsuario = Usuario::create([
         'usunome'              => $aValidacao['usunome'],
         'usunome_usuario'      => $aValidacao['usunome_usuario'],
         'usunivel'             => $aValidacao['usunivel'],
         'ususenha'             => bcrypt($aValidacao['ususenha']),
         'usuativo'             => $aValidacao['usuativo'] ?? true,
         'usudata_hora_criacao' => now()->format('d/m/Y H:i:s')
      ]);

      return response()->json(['oUsuario' => $oUsuario, 'sMensagem' => 'Usuário cadastrado com sucesso.'], 201);
   }

   /**
    * Após realizar as validações dos dados recebidos, atualiza as informações do usuário
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizar(Request $oRequest, $iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo, false);

      $aValidacao = $oRequest->validate([
         'usunome'         => 'sometimes|string|max:100',
         'usunome_usuario' => "sometimes|string|max:20|unique:usuarios,usunome_usuario,$iCodigo,usucodigo",
         'usunivel'        => 'sometimes|integer|exists:niveis_usuarios,nucodigo',
         'ususenha'        => 'sometimes|string|min:6',
         'usuativo'        => 'sometimes|boolean'
      ]);

      if($aValidacao['usunivel'] != 1 || $aValidacao['usuativo'] != 1) {
         $xRetorno = $this->validaUsuarioAdmin($iCodigo);

         if($xRetorno) {
            return response()->json(['sMensagem' => $xRetorno], 500);      
         }
      }
      
      if(isset($aValidacao['ususenha'])) {
         $aValidacao['ususenha'] = bcrypt($aValidacao['ususenha']);
      }

      $oUsuario->update($aValidacao);

      return response()->json(['oUsuario' => $oUsuario, 'sMensagem' => "Usuário {$iCodigo} - {$oUsuario->usunome} alterado com sucesso."], 201);
   }

   /**
    * Exclui o usuário
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluir($iCodigo) {
      $oUsuario     = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo);
      $sNomeUsuario = $oUsuario->usunome_usuario;

      $xRetorno = $this->validaUsuarioAdmin($iCodigo);

      if($xRetorno) {
         return response()->json(['sMensagem' => $xRetorno], 500);
      }

      $oUsuario->delete();

      return response()->json(['sMensagem' => "Usuário {$iCodigo} - {$sNomeUsuario} excluído com sucesso"], 200);
   }

   private function validaUsuarioAdmin($iCodigo) {      
      $oUsuario = Usuario::where('usucodigo', '<>', $iCodigo)
         ->where('usunivel', 1)
         ->get();

      if($oUsuario->isEmpty()) {
         return "Não é possível realizar a ação, pois este é o único usuário 'Administrador'";
      }      

      return false;
   }

   /**
    * Ativa ou inativa o usuário
    * @param integer $iCodigo
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizarSituacao($iCodigo) {
      $oUsuario = $this->getUsuarioOuRetornaMensagemUsuarioNaoEncontrado($iCodigo, false);

      $oUsuario->usuativo = !$oUsuario->usuativo;
      $oUsuario->save();

      return response()->json([
         'sMensagem' => 'Status do usuário atualizado com sucesso.',
         'oUsuario'  => $oUsuario
      ], 200);
   }

   /**
    * Retorna o usuário pelo código informado. Caso não encontre, aborta a requisição com erro HTTP 404 
    * @param integer $iCodigo
    * @param boolean $bFiltrarSituacao
    * @return Usuario
    * @throws HttpException
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