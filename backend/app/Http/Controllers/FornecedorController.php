<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller {

    /**
     * Valida os dados da requisição, e então insere um novo registro de fornecedor.
     * @param Request $oRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function salvar(Request $oRequest) {
        $aValidacao = $oRequest->validate([
            'forrazao_social'  => 'required|string|max:150',
            'fornome_fantasia' => 'string|max:150',
            'forcpfcnpj'       => 'required|string|max:14|min:14|unique:fornecedores,forcpfcnpj',
            'fortelefone'      => 'string|max:11',
            'foremail'         => 'string|max:30',
            'forendereco'      => 'string',
            'fordata_fundacao' => 'date|before_or_equal:today'
        ]);

        $oFornecedor = Fornecedor::create([
            'forrazao_social'  => $aValidacao['forrazao_social'], 
            'fornome_fantasia' => $aValidacao['fornome_fantasia'],
            'forcpfcnpj'       => $aValidacao['forcpfcnpj'],
            'fortelefone'      => $aValidacao['fortelefone'],
            'foremail'         => $aValidacao['foremail'],
            'forendereco'      => $aValidacao['forendereco'],
            'fordata_fundacao' => $aValidacao['fordata_fundacao']
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
     * @param int $iFornecedor
     * @return \Illuminate\Http\JsonResponse
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
     * @param int $iFornecedor
     * @return JsonResponse
     * @throws ValidationException
     */
    public function atualizar(Request $oRequest, $iFornecedor) {
        $oFornecedor = Fornecedor::find($iFornecedor);
        
        if(!$oFornecedor) {
            return response()->json(['sMensagem' => 'Fornecedor não encontrado.'], 404);
        }

        $aValidacao = $oRequest->validate([
            'forrazao_social'  => 'required|string|max:150',
            'fornome_fantasia' => 'string|max:150',
            'forcpfcnpj'       => 'required|string|max:14|min:14|unique:fornecedores,forcpfcnpj',
            'fortelefone'      => 'string|max:11',
            'foremail'         => 'string|max:30',
            'forendereco'      => 'string',
            'fordata_fundacao' => 'date|before_or_equal:today'
        ]);

        $oFornecedor = Fornecedor::update($aValidacao);

        return response()->json(['oFornecedor' => $oFornecedor], 201);
    }

    /**
     * Exclui um fornecedor com base no código
     * @param int $iFornecedor
     * @return JsonResponse
     * @throws ValidationException
     */
    public function excluir($iFornecedor) {
        $oFornecedor = Fornecedor::find($iFornecedor);

        if(!$oFornecedor) {
            return response()->json(['sMensagem' => 'Fornecedor não encontrado.'], 404);
        }

        $oFornecedor->delete();

        return response()->json(['sMensagem' => "Fornecedor {$iFornecedor} - {$oFornecedor['forrazao_social']} excluído com sucesso."], 200);
    }
    
}