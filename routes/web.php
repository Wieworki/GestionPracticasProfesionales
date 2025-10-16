<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\Dashboard\DashboardRedirectController;
use App\Http\Controllers\Dashboard\EmpresaDashboardController;
use App\Http\Controllers\Dashboard\AdministrativoDashboardController;
use App\Http\Controllers\Dashboard\EstudianteDashboardController;
use App\Http\Controllers\Empresa\PerfilController as EmpresaPerfil;
use App\Http\Controllers\Estudiante\PerfilController as EstudiantePerfil;
use App\Http\Controllers\Administrativo\PerfilController as AdministrativoPerfil;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/usuario/index', [UsuarioController::class, 'index'])->name('homeUsuario');
Route::get('/admin/usuario/create', [UsuarioController::class, 'create'])->name('createUsuario');
Route::post('/admin/usuario/store', [UsuarioController::class, 'store'])->name('storeUsuario');

Route::get('/admin/empresa/index', [EmpresaController::class, 'index'])->name('homeEmpresa');
Route::get('/admin/empresa/create', [EmpresaController::class, 'create'])->name('createEmpresa');
Route::post('/admin/empresa/store', [EmpresaController::class, 'store'])->name('storeEmpresa');

Route::get('/admin/estudiante/index', [EstudianteController::class, 'index'])->name('homeEstudiante');
Route::get('/admin/estudiante/create', [EstudianteController::class, 'create'])->name('createEstudiante');
Route::post('/admin/estudiante/store', [EstudianteController::class, 'store'])->name('storeEstudiante');

Route::get('/admin/administrativo/index', [AdministrativoController::class, 'index'])->name('homeAdministrativo');
Route::get('/admin/administrativo/create', [AdministrativoController::class, 'create'])->name('createAdministrativo');
Route::post('/admin/administrativo/store', [AdministrativoController::class, 'store'])->name('storeAdministrativo');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');    // This calls the functin "__invoke" of the class

    Route::middleware('checkUserType:empresa')->group(function () {
        Route::get('/empresa/perfil', [EmpresaPerfil::class, 'index'])->name('empresa.perfil');
        Route::get('/empresa/dashboard', [EmpresaDashboardController::class, 'index'])->name('empresa.dashboard');
        Route::get('/empresa/perfil/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::patch('/empresa/perfil', [EmpresaController::class, 'update'])->name('empresa.update');
    });

    Route::middleware('checkUserType:estudiante')->group(function () {
        Route::get('/estudiante/perfil', [EstudiantePerfil::class, 'index'])->name('estudiante.perfil');
        Route::get('/estudiante/dashboard', [EstudianteDashboardController::class, 'index'])->name('estudiante.dashboard');
        Route::get('/estudiante/perfil/edit', [EstudianteController::class, 'edit'])->name('estudiante.edit');
        Route::patch('/estudiante/perfil', [EstudianteController::class, 'update'])->name('estudiante.update');
    });

    Route::middleware('checkUserType:administrativo')->group(function () {
        Route::get('/administrativo/perfil', [AdministrativoPerfil::class, 'index'])->name('administrativo.perfil');
        Route::get('/administrativo/dashboard', [AdministrativoDashboardController::class, 'index'])->name('administrativo.dashboard');
        Route::get('/administrativo/perfil/edit', [AdministrativoController::class, 'edit'])->name('administrativo.edit');
        Route::patch('/administrativo/perfil', [AdministrativoController::class, 'update'])->name('administrativo.update');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
