<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller {
    
    /**
     * Valida os dados da requisição, e então insere um novo registro de Cliente.
     * @param Request $oRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function salvar(Request $oRequest) {
        $aValidacao = $oRequest->validate([
            'clinome'            => 'required|string|max:100',
            'clicpf'             => 'required|string|max:11|unique:clientes,clicpf',
            'clidata_nascimento' => 'string|max:150',
            'clitelefone'        => 'string|max:11',
            'cliendereco'        => 'string'                        
        ]);

        $oCliente = Cliente::create([
            'clinome'            => $aValidacao['clinome'], 
            'clicpf'             => $aValidacao['clicpf'],
            'clidata_nascimento' => $aValidacao['clidata_nascimento'],
            'clitelefone'        => $aValidacao['clitelefone'],
            'cliendereco'        => $aValidacao['cliendereco'],            
        ]);

        return response()->json(['oCliente' => $oCliente], 201);
    }    

    /**
     * Busca todos os Clientes
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getClientes() {
        $aClientes = Cliente::all();

        if(!$aClientes) {
            return response()->json(['Nenhum cliente encontrado.'], 404);
        }

        return response()->json(['aClientes' => $aClientes], 200);
    }

    /**
     * Busca um Cliente com base no código informado, ou informa que Cliente não foi encontrado.
     * @param int $iCliente
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClienteByCodigo($iCliente) {
        $oCliente = Cliente::find($iCliente);

        if(!$oCliente) {
            return response()->json(['Cliente não encontrado.'], 404);
        }

        return response()->json(['oCliente' => $oCliente], 200);
    }

    /**
     * Atualiza as informações de um Cliente
     * @param Request $oRequest
     * @param int $iCliente
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
            'cliendereco'        => 'string'                        
        ]);

        $oCliente->update($aValidacao);

        return response()->json(['oCliente' => $oCliente], 201);
    }

    /**
     * Exclui um Cliente com base no código
     * @param int $iCliente
     * @return JsonResponse
     * @throws ValidationException
     */
    public function excluir($iCliente) {
        $oCliente = Cliente::find($iCliente);

        if(!$oCliente) {
            return response()->json(['sMensagem' => 'Cliente não encontrado.'], 404);
        }

        $oCliente->delete();

        return response()->json(['sMensagem' => "Cliente {$iCliente} - {$oCliente['forrazao_social']} excluído com sucesso."], 200);
    }
    
}