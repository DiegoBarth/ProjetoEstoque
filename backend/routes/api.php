<?php

use App\Http\Controllers\FornecedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

#region Rotas Usuário

Route::get('/usuario/{codigo}', [UsuarioController::class, 'getUsuarioByCodigo']);

Route::post('/autenticar', [UsuarioController::class, 'autenticar']);

Route::post('/usuario', [UsuarioController::class, 'salvar']);

#endregion

#region Rotas Produto

Route::get('/produto/{iProduto}', [ProdutoController::class, 'getProdutoByCodigo']);

#endregion

#region Rotas Fornecedor

Route::post('/fornecedor', [FornecedorController::class, 'salvar']);
Route::get('/fornecedor', [FornecedorController::class, 'getFornecedores']);
Route::get('/fornecedor/{iFornecedor}', [FornecedorController::class, 'getFornecedorByCodigo']);
Route::put('/fornecedor/{iFornecedor}', [FornecedorController::class, 'atualizar']);
Route::delete('/fornecedor/{iFornecedor}', [FornecedorController::class, 'excluir']);


#endregion