<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/tipousuario', [TipoUsuarioController::class, 'index'])->name('homeTipoUsuario');
Route::get('/admin/tipousuario/show', [TipoUsuarioController::class, 'show'])->name('showTipoUsuario');

Route::get('/admin/usuario/index', [UsuarioController::class, 'index'])->name('homeUsuario');
Route::get('/admin/usuario/create', [UsuarioController::class, 'create'])->name('createUsuario');
Route::post('/admin/usuario/store', [UsuarioController::class, 'store'])->name('storeUsuario');

Route::get('/admin/empresa/index', [EmpresaController::class, 'index'])->name('homeEmpresa');
Route::get('/admin/empresa/create', [EmpresaController::class, 'create'])->name('createEmpresa');
Route::post('/admin/empresa/store', [EmpresaController::class, 'store'])->name('storeEmpresa');

Route::get('/admin/estudiante/index', [EstudianteController::class, 'index'])->name('homeEstudiante');
Route::get('/admin/estudiante/create', [EstudianteController::class, 'create'])->name('createEstudiante');
Route::post('/admin/estudiante/store', [EstudianteController::class, 'store'])->name('storeEstudiante');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
