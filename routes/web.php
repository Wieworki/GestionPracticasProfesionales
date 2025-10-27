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
use App\Http\Controllers\Empresa\OfertaController as EmpresaOferta;
use App\Http\Controllers\Estudiante\PerfilController as EstudiantePerfil;
use App\Http\Controllers\Estudiante\EmpresaController as EmpresaEstudianteController;
use App\Http\Controllers\Administrativo\PerfilController as AdministrativoPerfil;
use App\Http\Controllers\Administrativo\EmpresaController as EmpresaAdministrativoController;
use App\Http\Controllers\Administrativo\EstudianteController as EstudianteAdministrativoController;
use App\Http\Controllers\Administrativo\OfertaController as OfertaAdministrativoController;
use App\Http\Controllers\Auth\PasswordController;

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
    Route::get('/password/cambiar', [PasswordController::class, 'edit'])->name('password.cambiar');
    Route::post('/password/guardar', [PasswordController::class, 'update'])->name('password.guardar');
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');    // This calls the functin "__invoke" of the class

    Route::middleware('checkUserType:empresa')->group(function () {
        Route::get('/empresa/perfil', [EmpresaPerfil::class, 'index'])->name('empresa.perfil');
        Route::get('/empresa/dashboard', [EmpresaDashboardController::class, 'index'])->name('empresa.dashboard');
        Route::get('/empresa/perfil/edit', [EmpresaController::class, 'edit'])->name('empresa.edit');
        Route::patch('/empresa/perfil', [EmpresaController::class, 'update'])->name('empresa.update');

        Route::get('/empresa/ofertas/nueva', [EmpresaOferta::class, 'create'])
            ->name('empresa.ofertas.create');

        Route::post('/empresa/ofertas', [EmpresaOferta::class, 'store'])
            ->name('empresa.ofertas.store');

        Route::get('/empresa/ofertas/index', [EmpresaOferta::class, 'index'])
            ->name('empresa.ofertas.index');

        Route::get('/empresa/ofertas/show/{id}', [EmpresaOferta::class, 'show'])
            ->name('empresa.ofertas.show');

        Route::get('/empresa/ofertas/edit/{id}', [EmpresaOferta::class, 'edit'])
            ->name('empresa.ofertas.edit');

        Route::patch('/empresa/ofertas/edit/{id}', [EmpresaOferta::class, 'update'])
            ->name('empresa.ofertas.update');

        Route::get('/empresa/ofertas/postulantes/{id}', [EmpresaOferta::class, 'show'])
            ->name('empresa.ofertas.postulantes'); // REVISAR

        Route::patch('/empresa/ofertas/eliminar/{id}', [EmpresaOferta::class, 'eliminar'])
            ->name('empresa.ofertas.eliminar');
    });

    Route::middleware('checkUserType:estudiante')->group(function () {
        Route::get('/estudiante/perfil', [EstudiantePerfil::class, 'index'])->name('estudiante.perfil');
        Route::get('/estudiante/dashboard', [EstudianteDashboardController::class, 'index'])->name('estudiante.dashboard');
        Route::get('/estudiante/perfil/edit', [EstudianteController::class, 'edit'])->name('estudiante.edit');
        Route::patch('/estudiante/perfil', [EstudianteController::class, 'update'])->name('estudiante.update');

        Route::get('/estudiante/empresas', [EmpresaController::class, 'indexEstudiante'])
            ->name('estudiante.empresas.index');
        Route::get('/estudiante/empresas/show/{id}', [EmpresaEstudianteController::class, 'show'])
            ->name('estudiante.empresas.show');
    });

    Route::middleware('checkUserType:administrativo')->group(function () {
        Route::get('/administrativo/perfil', [AdministrativoPerfil::class, 'index'])->name('administrativo.perfil');
        Route::get('/administrativo/dashboard', [AdministrativoDashboardController::class, 'index'])->name('administrativo.dashboard');
        Route::get('/administrativo/perfil/edit', [AdministrativoController::class, 'edit'])->name('administrativo.edit');
        Route::patch('/administrativo/perfil', [AdministrativoController::class, 'update'])->name('administrativo.update');

        Route::get('/administrativo/empresas', [EmpresaAdministrativoController::class, 'index'])
            ->name('administrativo.empresas.index');
        Route::get('/administrativo/empresas/show/{id}', [EmpresaAdministrativoController::class, 'show'])
            ->name('administrativo.empresas.show');
        Route::patch('/administrativo/empresas/convenio', [EmpresaAdministrativoController::class, 'confirmarConvenio'])
            ->name('administrativo.empresas.convenio');
        Route::get('/administrativo/empresas/create', [EmpresaAdministrativoController::class, 'create'])
            ->name('administrativo.empresas.create');
        Route::post('/administrativo/empresas/store', [EmpresaAdministrativoController::class, 'store'])
            ->name('administrativo.empresas.store');

        Route::get('/administrativo/ofertas/index', [OfertaAdministrativoController::class, 'index'])
            ->name('administrativo.ofertas.index');
        Route::get('/administrativo/ofertas/show/{id}', [OfertaAdministrativoController::class, 'show'])
            ->name('administrativo.oferta.show');
        Route::patch('/administrativo/ofertas/confirmar/{id}', [OfertaAdministrativoController::class, 'confirmarVisibilidad'])
            ->name('administrativo.oferta.confirmarVisibilidad');

        Route::get('/administrativo/estudiantes', [EstudianteAdministrativoController::class, 'index'])
            ->name('administrativo.estudiantes.index');
        Route::get('/administrativo/estudiantes/show/{id}', [EstudianteAdministrativoController::class, 'show'])
            ->name('administrativo.estudiantes.show');
        Route::get('/administrativo/estudiantes/edit/{id}', [EstudianteAdministrativoController::class, 'edit'])
            ->name('administrativo.estudiantes.edit');
        Route::patch('/administrativo/estudiantes/edit/{id}', [EstudianteAdministrativoController::class, 'update'])
            ->name('administrativo.estudiantes.update');
        Route::get('/administrativo/estudiantes/create', [EstudianteAdministrativoController::class, 'create'])
            ->name('administrativo.estudiantes.create');
        Route::post('/administrativo/estudiantes/store', [EstudianteAdministrativoController::class, 'store'])
            ->name('administrativo.estudiantes.store');
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
