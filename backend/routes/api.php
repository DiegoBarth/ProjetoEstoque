<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\VendaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\RelatoriosController;

#region Rotas Usuário

Route::post('/login',  [UsuarioController::class, 'login']);
Route::post('/logout', [UsuarioController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/usuario',                  [UsuarioController::class, 'getUsuarios']);
   Route::get('/usuario/{iCodigo}',        [UsuarioController::class, 'getUsuarioByCodigo']);
   Route::get('/usuario/busca/nome',       [UsuarioController::class, 'getUsuarioByNome']);
   Route::get('/usuario/busca/nivel',      [UsuarioController::class, 'getNiveisUsuario']);
   Route::post('/usuario',                 [UsuarioController::class, 'salvar']);
   Route::put('/usuario/{iCodigo}',        [UsuarioController::class, 'atualizar']);
   Route::delete('/usuario/{iCodigo}',     [UsuarioController::class, 'excluir']);
   Route::put('/usuario/{iCodigo}/status', [UsuarioController::class, 'atualizarSituacao']);
});

#endregion

#region Rotas Cliente

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/cliente',                       [ClienteController::class, 'getClientes']);
   Route::get('/cliente/{iCliente}',            [ClienteController::class, 'getClienteByCodigo']);
   Route::get('/cliente/busca/cpf',             [ClienteController::class, 'getClienteByCPF']);
   Route::post('/cliente',                      [ClienteController::class, 'salvar']);
   Route::put('/cliente/{iCliente}',            [ClienteController::class, 'atualizar']);
   Route::delete('/cliente/{iCliente}',         [ClienteController::class, 'excluir']);
   Route::get('/cliente/busca/anexo',           [ClienteController::class, 'getAnexos']);
   Route::post('/cliente/anexo',                [ClienteController::class, 'salvarAnexo']);
   Route::get('/cliente/anexo/visualizar/{id}', [ClienteController::class, 'visualizarAnexo']);
   Route::delete('/cliente/anexo/{iAnexo}',     [ClienteController::class, 'excluirAnexo']);

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

#region Rotas Metas

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/meta',            [MetaController::class, 'getMetas']);
   Route::get('/meta/consulta',   [MetaController::class, 'consultarMeta']);
   Route::get('/meta/{iMeta}',    [MetaController::class, 'getMetaByCodigo']);
   Route::post('/meta',           [MetaController::class, 'salvar']);
   Route::put('/meta/{iMeta}',    [MetaController::class, 'atualizar']);
   Route::delete('/meta/{iMeta}', [MetaController::class, 'excluir']);
});

#endregion

#region Rotas Venda

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/forma_pagamento',           [VendaController::class, 'getFormasPagamento']);
   Route::get('/venda',                     [VendaController::class, 'getVendas']);
   Route::post('/venda',                    [VendaController::class, 'cadastrarVenda']);
   Route::post('/venda/finalizar/{iVenda}', [VendaController::class, 'finalizarVenda']);
   Route::post('/venda/cancelar/{iVenda}',  [VendaController::class, 'cancelarVenda']);
   Route::post('/venda/itens/{iVenda}',     [VendaController::class, 'getItensVenda']);
   Route::post('/venda/{id}/devolucao',     [VendaController::class, 'realizarDevolucao']);
   Route::get('/venda/{id}/devolucao',      [VendaController::class, 'buscarDevolucao']);
});

#endregion

#region Rotas Relatórios
Route::middleware('auth:sanctum')->group(function () {
   Route::post('/relatorio/vendas', [RelatoriosController::class, 'gerarRelatorioVendas']);
   Route::post('/relatorio/produtos', [RelatoriosController::class, 'gerarRelatorioProdutos']);
   Route::post('/relatorio/vendasProdutos', [RelatoriosController::class, 'gerarRelatorioVendasPorProdutos']);
   Route::post('/relatorio/vendasClientes', [RelatoriosController::class, 'gerarRelatorioVendasPorCliente']);
});
#endregion