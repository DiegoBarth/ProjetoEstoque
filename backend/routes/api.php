<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendaController;
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
   Route::get('/usuario/busca/nivel',      [UsuarioController::class, 'getNiveisUsuario']);
   Route::post('/usuario',                 [UsuarioController::class, 'salvar']);
   Route::put('/usuario/{iCodigo}',        [UsuarioController::class, 'atualizar']);
   Route::delete('/usuario/{iCodigo}',     [UsuarioController::class, 'excluir']);
   Route::put('/usuario/{iCodigo}/status', [UsuarioController::class, 'atualizarSituacao']);
});

#endregion

#region Rotas Cliente

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/cliente',               [ClienteController::class, 'getClientes']);
   Route::get('/cliente/{iCliente}',    [ClienteController::class, 'getClienteByCodigo']);
   Route::get('/cliente/busca/cpf',     [ClienteController::class, 'getClienteByCPF']);
   Route::post('/cliente',              [ClienteController::class, 'salvar']);
   Route::put('/cliente/{iCliente}',    [ClienteController::class, 'atualizar']);
   Route::delete('/cliente/{iCliente}', [ClienteController::class, 'excluir']);
});

#endregion

#region Rotas Fornecedor

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/fornecedor',                  [FornecedorController::class, 'getFornecedores']);
   Route::get('/fornecedor/{iFornecedor}',    [FornecedorController::class, 'getFornecedorByCodigo']);
   Route::post('/fornecedor',                 [FornecedorController::class, 'salvar']);
   Route::put('/fornecedor/{iFornecedor}',    [FornecedorController::class, 'atualizar']);
   Route::delete('/fornecedor/{iFornecedor}', [FornecedorController::class, 'excluir']);
});

#endregion

#region Rotas Produto

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/produto',               [ProdutoController::class, 'getProdutos']);
   Route::get('/produto/{iProduto}',    [ProdutoController::class, 'getProdutoByCodigo']);
   Route::get('/produto/busca/nome',    [ProdutoController::class, 'getProdutoByNome']);
   Route::post('/produto',              [ProdutoController::class, 'salvar']);
   Route::put('/produto/{iProduto}',    [ProdutoController::class, 'atualizar']);
   Route::delete('/produto/{iProduto}', [ProdutoController::class, 'excluir']);
});

#endregion

#region Rotas Venda

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/forma_pagamento',  [VendaController::class, 'getFormasPagamento']);
   Route::get('/venda',            [VendaController::class, 'getVendas']);
});

#endregion