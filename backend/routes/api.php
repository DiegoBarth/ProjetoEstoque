<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FornecedorController;

#region Rotas Usuário

Route::post('/login',  [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/usuario',                  [UsuarioController::class, 'getUsuarios']);
    Route::get('/usuario/{iCodigo}',        [UsuarioController::class, 'getUsuarioByCodigo']);
    Route::post('/usuario',                 [UsuarioController::class, 'salvar']);
    Route::put('/usuario/{iCodigo}',        [UsuarioController::class, 'atualizar']);
    Route::delete('/usuario/{iCodigo}',     [UsuarioController::class, 'excluir']);
    Route::put('/usuario/{iCodigo}/status', [UsuarioController::class, 'atualizarSituacao']);
});

#endregion

#region Rotas Cliente

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cliente',               [ClienteController::class, 'getClientes']);
    Route::post('/cliente',              [ClienteController::class, 'salvar']);
    Route::get('/cliente/{iCliente}',    [ClienteController::class, 'getClienteByCodigo']);
    Route::put('/cliente/{iCliente}',    [ClienteController::class, 'atualizar']);
    Route::delete('/cliente/{iCliente}', [ClienteController::class, 'excluir']);
});

#endregion

#region Rotas Fornecedor

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fornecedor',                  [FornecedorController::class, 'getFornecedores']);
    Route::post('/fornecedor',                 [FornecedorController::class, 'salvar']);
    Route::get('/fornecedor/{iFornecedor}',    [FornecedorController::class, 'getFornecedorByCodigo']);
    Route::put('/fornecedor/{iFornecedor}',    [FornecedorController::class, 'atualizar']);
    Route::delete('/fornecedor/{iFornecedor}', [FornecedorController::class, 'excluir']);
});

#endregion

#region Rotas Produto

Route::get('/produto/{iProduto}', [ProdutoController::class, 'getProdutoByCodigo']);

#endregion