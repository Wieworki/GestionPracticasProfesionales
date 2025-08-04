<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\UsuarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/tipousuario', [TipoUsuarioController::class, 'index'])->name('homeTipoUsuario');
Route::get('/admin/tipousuario/show', [TipoUsuarioController::class, 'show'])->name('showTipoUsuario');

Route::get('/admin/usuario/index', [UsuarioController::class, 'index'])->name('homeUsuario');
Route::get('/admin/usuario/create', [UsuarioController::class, 'create'])->name('createUsuario');
Route::post('/admin/usuario/store', [UsuarioController::class, 'store'])->name('storeUsuario');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
