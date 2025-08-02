<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoUsuarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/tipousuario', [TipoUsuarioController::class, 'index'])->name('homeTipoUsuario');
Route::get('/admin/tipousuario/show', [TipoUsuarioController::class, 'show'])->name('showTipoUsuario');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
