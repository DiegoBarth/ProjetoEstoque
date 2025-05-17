<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response('Hello Bro', 200);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
