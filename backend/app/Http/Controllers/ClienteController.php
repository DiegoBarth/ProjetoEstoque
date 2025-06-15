<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\ClienteAnexo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller {

   /**
    * Busca todos os Clientes
    * @return JsonResponse
    * @throws ValidationException
    */
   public function getClientes() {
      $aClientes = Cliente::orderBy('clicodigo')->get();

      if(!$aClientes) {
         return response()->json(['Nenhum cliente encontrado.'], 404);
      }

      return response()->json(['aClientes' => $aClientes], 200);
   }

   /**
    * Busca um Cliente com base no código informado, ou informa que Cliente não foi encontrado.
    * @param int $iCliente
    * @return JsonResponse
    */
   public function getClienteByCodigo($iCliente) {
      $oCliente = Cliente::find($iCliente);

      if(!$oCliente) {
         return response()->json(['Cliente não encontrado.'], 404);
      }

      return response()->json(['oCliente' => $oCliente], 200);
   }

   /**
    * Busca um Cliente com base no CPF informado, ou informa que Cliente não foi encontrado.
    * @param Request $oRequest
    * @return JsonResponse
    */
   public function getClienteByCPF(Request $oRequest) {
      $sCPF     = $oRequest->query('cpf');

      $oCliente = Cliente::where('clicpf', $sCPF)->first();

      if(!$oCliente) {
         return response()->json('Cliente não encontrado.', 404);
      }

      return response()->json(['oCliente' => $oCliente], 200);
   }

   /**
    * Valida os dados da requisição, e então insere um novo registro de Cliente.
    * @param Request $oRequest
    * @return JsonResponse
    * @throws ValidationException
    */
   public function salvar(Request $oRequest) {
      $aValidacao = $oRequest->validate([
         'clinome'            => 'required|string|max:100',
         'clicpf'             => 'required|string|max:11|unique:clientes,clicpf',
         'clidata_nascimento' => 'string|max:150',
         'clitelefone'        => 'string|max:11',
         'cliendereco'        => 'string|nullable'
      ]);

      $oCliente = Cliente::create([
         'clinome'              => $aValidacao['clinome'],
         'clicpf'               => $aValidacao['clicpf'],
         'clidata_nascimento'   => $aValidacao['clidata_nascimento'],
         'clitelefone'          => $aValidacao['clitelefone'],
         'cliendereco'          => $aValidacao['cliendereco'],
         'clidata_hora_criacao' => now()->format('d/m/Y H:i:s')
      ]);

      return response()->json(['oCliente' => $oCliente, 'sMensagem' => 'Cliente cadastrado com sucesso.'], 201);
   }

   /**
    * Atualiza as informações de um Cliente
    * @param Request $oRequest
    * @param integer $iCliente
    * @return JsonResponse
    * @throws ValidationException
    */
   public function atualizar(Request $oRequest, $iCliente) {
      $oCliente = Cliente::find($iCliente);

      if(!$oCliente) {
         return response()->json(['sMensagem' => 'Cliente não encontrado.'], 404);
      }

      $aValidacao = $oRequest->validate([
         'clinome'            => 'required|string|max:100',
         'clicpf'             => "required|string|max:11|unique:clientes,clicpf,$iCliente,clicodigo",
         'clidata_nascimento' => 'string|max:150',
         'clitelefone'        => 'string|max:11',
         'cliendereco'        => 'nullable|string'
      ]);

      $oCliente->update($aValidacao);

      return response()->json(['oCliente' => $oCliente, 'sMensagem' => "Cliente {$iCliente} - {$oCliente->clinome} alterado com sucesso."], 201);
   }

   /**
    * Exclui um Cliente com base no código
    * @param integer $iCliente
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluir($iCliente) {
      $oCliente = Cliente::find($iCliente);

      if(!$oCliente) {
         return response()->json(['sMensagem' => 'Cliente não encontrado.'], 404);
      }

      $sNomeCliente = $oCliente->clinome;

      $xRetorno = $this->validaVendasPendentes($iCliente);

      if($xRetorno) {
         return response()->json(['sMensagem' => $xRetorno], 404);   
      }

      $oCliente->anexos()->delete();
      $oCliente->delete();

      return response()->json(['sMensagem' => "Cliente {$iCliente} - $sNomeCliente excluído com sucesso."], 200);
   }

   /**
    * Verifica se o cliente possui vendas em aberto. Caso possua, não permitirá excluir o mesmo
    * @param integer $iCliente
    * @return boolean|string
    */
   private function validaVendasPendentes($iCliente) {
      $aVendas = Venda::where([
         "clicodigo"  => $iCliente,
         "vesituacao" => 1
      ])
      ->count();

      if($aVendas) {
         return "O cliente não pode ser excluído, pois tem vendas pendentes";
      }

      return false;
   }

   /**
    * Busca os anexos do cliente
    * @param Request $oRequest
    * @return JsonResponse
    */
   public function getAnexos(Request $oRequest) {
      $iCliente = $oRequest->query('cliente');
      $oCliente = Cliente::with('anexos')->find($iCliente);

      if(!$oCliente) {  
         return response()->json(['sMensagem' => 'Cliente não encontrado'], 404);
      }

      return response()->json($oCliente->anexos, 200);
   }

   /**
    * Salva o anexo do cliente
    * @param \Illuminate\Http\Request $oRequest
    * @return JsonResponse|mixed
    */
   public function salvarAnexo(Request $oRequest) {
      $oRequest->validate([
         'arquivo'    => 'required|file|mimes:pdf,jpg,jpeg,png,bmp,txt|max:10240', // max 10MB
         'nome'       => 'required|string|max:255',
         'tipo'       => 'required|string',
         'clicodigo'  => 'required|integer',
         'observacao' => 'nullable|string|max:500',
      ]);

      $oFile = $oRequest->file('arquivo');
      $sPath = $oFile->store('anexos', 'public');

      $oAnexo = new ClienteAnexo();
      $oAnexo->anenome_arquivo = $oRequest->nome;
      $oAnexo->anetipo = $oRequest->tipo;
      $oAnexo->aneobservacao = $oRequest->observacao;
      $oAnexo->anearquivo = $sPath;
      $oAnexo->clicodigo = $oRequest->clicodigo;
      $oAnexo->anedata_hora = now();
      $oAnexo->save();

      return response()->json($oAnexo, 200);
   }

   /**
    * Retorna o anexo para visualização
    * @param mixed $iAnexo
    */
   public function visualizarAnexo($iAnexo) {
      $oAnexo = ClienteAnexo::find($iAnexo);

      $sCaminho = $oAnexo->anearquivo;

      if(!Storage::disk('public')->exists($sCaminho)) {
         return response()->json(['mensagem' => 'Arquivo não encontrado.'], 404);
      }

      $oConteudo = Storage::disk('public')->get($sCaminho);
      $sTipo     = $oAnexo->anetipo ?: 'application/octet-stream';
      $sNome     = $oAnexo->anenome_arquivo ?: 'arquivo';

      return response($oConteudo)
         ->header('Content-Type', $sTipo)
         ->header('Content-Disposition', "inline; filename=\"$sNome\"");
   }

   /**
    * Exclui um anexo com base no código
    * @param integer $iCliente
    * @return JsonResponse
    * @throws ValidationException
    */
   public function excluirAnexo($iAnexo) {
      $oAnexo = ClienteAnexo::find($iAnexo);

      if(!$oAnexo) {
         return response()->json(['sMensagem' => 'Anexo não encontrado.'], 404);
      }

      $sAnexo = $oAnexo->anenome_arquivo;

      $oAnexo->delete();

      return response()->json(['sMensagem' => "Anexo {$iAnexo} - $sAnexo excluído com sucesso."], 200);
   }

}