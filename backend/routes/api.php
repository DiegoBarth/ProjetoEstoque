<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/', function (Request $request) {
    return response('Hello bro', 200);
});

Route::get('/usuario/{codigo}', [UsuarioController::class, 'getUsuarioByCodigo']);