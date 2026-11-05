<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AtivoController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\VendedorMiddleware;
use Illuminate\Support\Facades\Route;

// Páginas de ilustração (prontas)
Route::view('/', 'home')->name('home');
Route::view('/sobre', 'sobre')->name('sobre');

// Autenticação
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ações
Route::get('/ativos', [AtivoController::class, 'index'])->name('ativos.index');

Route::middleware(AuthMiddleware::class)->group(function (){
    Route::middleware(VendedorMiddleware::class)->group(function (){
        Route::get('/ativos/create', [AtivoController::class, 'create'])->name('ativos.create');
        Route::post('/ativos', [AtivoController::class, 'store'])->name('ativos.store');
    });
    // Compras
    Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
    Route::get('/compras/create', [CompraController::class, 'create'])->name('compras.create');
    Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');        
});
